<?php

declare(strict_types=1);

use Francken\Association\AlumniActivity\Alumnus;
use Francken\Association\Boards\Board;
use Francken\Association\Boards\BoardMember;
use Francken\Association\Committees\Committee;
use Francken\Association\Committees\CommitteeMember;
use Francken\Association\LegacyMember;
use Francken\Association\Members\Registration\Registration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Plank\Mediable\MediaUploader;

/**
 * Fake data for the association pages of the admin, so that their buttons can be tried
 * out: the current board with the photos of the live site and the admin as board member
 * (only board members may approve registration requests), its committees with members, registration requests in each
 * stage and alumni that come to the alumni activity
 */
class AssociationSeeder extends Seeder
{
    // The current board as it is on the live site, from where its photos are taken
    private const PHOTOS_URL = 'https://professorfrancken.nl/uploads/images/boards/';
    private const BOARD = [
        'name' => 'Induction',
        'photo' => 'induction-4.png',
        'members' => [
            ['Matko Majstorović', 'Commissioner of External Relations and Vice-President', 'commissioner-of-external-relations-and-vice-president.png'],
            ['Pelle van der Woude', 'Treasurer', 'treasurer.jpeg'],
            ['Jasmijn Kool', 'Secretary and Commissioner of Internal Relations', 'secretary-and-commissioner-of-internal-relations.jpeg'],
            ['Mats den Haan', 'President and Commissioner of Educational Affairs', 'president-and-commissioner-of-educational-affairs.jpeg'],
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run() : void
    {
        $now = new DateTimeImmutable();
        $year = (int) $now->format('n') >= 6
            ? (int) $now->format('Y')
            : (int) $now->format('Y') - 1;

        $board = Board::where('board_year_slug', $year . '-' . ($year + 1))->first()
            ?? $this->installBoard($year);

        $this->installCommitteeMembers($board);
        $this->submitRegistrationRequests($now);
        $this->registerAlumni();
    }

    private function installBoard(int $year) : Board
    {
        $previousBoard = Board::current()->first();
        $boardYear = $year . '-' . ($year + 1);

        /** @var Board $board */
        $board = factory(Board::class)->create([
            'installed_at' => $year . '-06-06',
            'name' => self::BOARD['name'],
            'photo_position' => '',
        ]);
        $this->attachPhoto($board, 'board_photo', $boardYear, self::BOARD['photo']);

        // The admin is the president, so that they may approve registration requests
        $members = $this->randomMembers(count(self::BOARD['members']) - 1)
            ->push(LegacyMember::findOrFail(DatabaseSeeder::MEMBER_ID));

        foreach (self::BOARD['members'] as $index => [$name, $title, $photo]) {
            /** @var BoardMember $boardMember */
            $boardMember = factory(BoardMember::class)->create([
                'board_id' => $board->id,
                'member_id' => $members[$index]->id,
                'name' => $name,
                'title' => $title,
            ]);
            $this->attachPhoto($boardMember, 'board_member_photo', $boardYear, $photo);
        }

        // Using continueFrom requries guarding our committee model as otherwise
        // it tries to overwrite the id column
        Model::reguard();
        if ($previousBoard !== null) {
            foreach ($previousBoard->committees as $committee) {
                Committee::continueFrom($committee, $board);
            }
        }
        Model::unguard();

        return $board;
    }

    private function installCommitteeMembers(Board $board) : void
    {
        $functions = ['Chair', 'Secretary', 'Treasurer', '', '', ''];

        foreach ($board->committees as $index => $committee) {
            if (CommitteeMember::where('committee_id', $committee->id)->exists()) {
                continue;
            }

            $members = $this->randomMembers(rand(2, 6));

            // Put the admin in a few committees, so that they show up on their profile
            if ($index < 3) {
                $members = $members->prepend(LegacyMember::findOrFail(DatabaseSeeder::MEMBER_ID));
            }

            foreach ($members->values() as $position => $member) {
                CommitteeMember::create([
                    'committee_id' => $committee->id,
                    'member_id' => $member->id,
                    'function' => $functions[$position] ?? '',
                    'installed_at' => $board->installed_at,
                    'decharged_at' => null,
                ]);
            }
        }
    }

    private function submitRegistrationRequests(DateTimeImmutable $now) : void
    {
        if (Registration::query()->exists()) {
            return;
        }

        // Each stage of a request: [count, email verified, approved, form signed]
        $stages = [
            [3, false, false, false],
            [3, true, false, false],
            [2, true, true, false],
            [2, true, true, true],
        ];

        $studies = ['Applied Physics', 'Physics', 'Applied Mathematics', 'Astronomy'];
        $days = 1;
        foreach ($stages as [$count, $verified, $approved, $signed]) {
            foreach (range(1, $count) as $_) {
                $submittedAt = $now->modify("-{$days} days");
                $days += rand(1, 4);

                factory(Registration::class)->create([
                    'student_number' => 's' . rand(3000000, 5999999),
                    'studies' => json_encode([[
                        'study' => 'Bachelor ' . $studies[array_rand($studies)],
                        'start_date' => $now->modify('first day of september this year')->format('Y-m-d'),
                        'graduation_date' => null,
                    ]], JSON_THROW_ON_ERROR),
                    'created_at' => $submittedAt,
                    'updated_at' => $submittedAt,
                    'email_verified_at' => $verified ? $submittedAt->modify('+1 hour') : null,
                    'registration_accepted_at' => $approved ? $submittedAt->modify('+1 day') : null,
                    'registration_form_signed_at' => $signed ? $submittedAt->modify('+2 days') : null,
                ]);
            }
        }
    }

    private function registerAlumni() : void
    {
        if (Alumnus::query()->exists()) {
            return;
        }

        $studies = ['Applied Physics', 'Physics', 'Applied Mathematics', 'Astronomy'];

        foreach ($this->randomMembers(12) as $member) {
            Alumnus::create([
                'member_id' => $member->id,
                'fullname' => $member->fullname,
                'study' => $studies[array_rand($studies)],
                'graduation_year' => rand(1995, 2024),
            ]);
        }
    }

    /**
     * Download the photo from the live site, the board is shown without it when that fails
     */
    private function attachPhoto(Model $model, string $tag, string $boardYear, string $photo) : void
    {
        try {
            $media = app(MediaUploader::class)
                ->fromSource(self::PHOTOS_URL . $boardYear . '/' . $photo)
                ->toDirectory('images/boards/' . $boardYear . '/')
                ->useFilename(pathinfo($photo, PATHINFO_FILENAME))
                ->onDuplicateReplace()
                ->upload();
        } catch (Throwable $e) {
            $this->command->warn("Could not download {$photo}: {$e->getMessage()}");

            return;
        }

        $model->attachMedia($media, $tag);
        $model->update(['photo_media_id' => $media->id]);
    }

    private function randomMembers(int $count) : Collection
    {
        return LegacyMember::where('id', '!=', DatabaseSeeder::MEMBER_ID)
            ->inRandomOrder()
            ->limit($count)
            ->get();
    }
}
