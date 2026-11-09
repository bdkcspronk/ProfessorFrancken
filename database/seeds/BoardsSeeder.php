<?php

declare(strict_types=1);

use Francken\Association\Boards\Board;
use Francken\Association\Boards\BoardMember;
use Francken\Association\Committees\Committee;
use Francken\Association\Committees\CommitteeMember;
use Francken\Association\LegacyMember;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploader;

/**
 * The boards and their committees as they are shown on the live site, with their photos and
 * logos, which are downloaded from there. The committees are in data/committees.json, and are
 * continued from one board to the next when they have the same slug.
 *
 * The board and committee members are members with the names of the live site, they are
 * looked up by name, or made when there is no member with that name yet. Except for the
 * president of the current board, who is the admin so that they may approve registration
 * requests
 */
class BoardsSeeder extends Seeder
{
    private const UPLOADS_URL = 'https://professorfrancken.nl/uploads/';

    private const PARTICLES = ['van', 'de', 'der', 'den', 'het', 't', "'t", 'ten', 'ter', 'te', 'in', 'op', 'von', 'du', 'da', 'la', 'le'];
    private const BOARDS = [
        [
            'year' => 2022,
            'name' => 'Fusion',
            'photo' => '2022-2023/fusion.jpg',
            'members' => [
                ['Sjoerd Buitjes', 'Commissioner of External Relations and Vice-President', '2022-2023/commissioner-of-external-relations-and-vice-president.jpg'],
                ['Annabelle van Berlo', 'Commissioner of Internal Relations', '2022-2023/commissioner-of-internal-relations.jpg'],
                ['Christian Berg', 'Treasurer', '2022-2023/treasurer.jpg'],
                ['Filippo Carretta', 'Secretary & Commissioner of Education', '2022-2023/secretary-commissioner-of-education.jpg'],
                ['Csilla Tijssen', 'President', '2022-2023/president.jpg'],
            ],
        ],
        [
            'year' => 2023,
            'name' => 'Freefall',
            'photo' => '2023-2024/freefall-2.jpg',
            'members' => [
                ['David Dijkman', 'Commissioner of Educational Affairs and Vice-President', '2023-2024/commissioner-of-educational-affairs-and-vice-president-6.jpg'],
                ['Liz Titz', 'Commissioner of Internal Relations', '2023-2024/commissioner-of-internal-relations-4.jpg'],
                ['Hannelys Posthumus', 'Secretary', '2023-2024/secretary-6.jpg'],
                ['Ciska van Elsberg', 'President', '2023-2024/president-6.jpg'],
                ['Siem Kuijpers', 'Treasurer', '2023-2024/treasurer-4.jpg'],
                ['Lilly-Anne Kalderen', 'Commissioner of External Relations', '2023-2024/commissioner-of-external-relations-4.jpg'],
            ],
        ],
        [
            'year' => 2024,
            'name' => 'Parity',
            'photo' => '2024-2025/parity.jpg',
            'members' => [
                ['Dennis Francke', 'Commissioner of Educational Affairs and Vice-President', '2024-2025/commissioner-of-educational-affairs-and-vice-president.jpg'],
                ['Eline Mijnlieff', 'Commissioner of External Relations', '2024-2025/commissioner-of-external-relations.jpg'],
                ['Rebecca Bult', 'Commissioner of Internal Relations', '2024-2025/commissioner-of-internal-relations.jpg'],
                ['Camiel Koning', 'Treasurer', '2024-2025/treasurer.jpg'],
                ['Gerrit Boonstra', 'Secretary', '2024-2025/secretary.jpg'],
                ['Adriana van Harten', 'President', '2024-2025/president.jpg'],
            ],
        ],
        [
            'year' => 2025,
            'name' => 'Flux',
            'photo' => '2025-2026/flux-1.jpeg',
            'members' => [
                ['Zoltán Hermann', 'Secretary and Commissioner of Internal Relations', '2025-2026/secretary-and-commissioner-of-internal-relations-2.jpg'],
                ['Emily Geekie', 'President and Commissioner of Educational Affairs', '2025-2026/president-and-commissioner-of-educational-affairs-2.jpg'],
                ['Emma Robertson', 'Treasurer', '2025-2026/treasurer-2.jpg'],
                ['Solène van der Schot', 'Commissioner of External Relations and Vice-President', '2025-2026/commissioner-of-external-relations-and-vice-president-2.jpg'],
            ],
        ],
        [
            'year' => 2026,
            'name' => 'Induction',
            'photo' => '2026-2027/induction-4.png',
            'members' => [
                ['Matko Majstorović', 'Commissioner of External Relations and Vice-President', '2026-2027/commissioner-of-external-relations-and-vice-president.png'],
                ['Pelle van der Woude', 'Treasurer', '2026-2027/treasurer.jpeg'],
                ['Jasmijn Kool', 'Secretary and Commissioner of Internal Relations', '2026-2027/secretary-and-commissioner-of-internal-relations.jpeg'],
                ['Mats den Haan', 'President and Commissioner of Educational Affairs', '2026-2027/president-and-commissioner-of-educational-affairs.jpeg'],
            ],
        ],
    ];

    /**
     * The media that were downloaded, by their path, as the same logo is used by a committee
     * in several years
     *
     * @var array<string, Media|null>
     */
    private array $media = [];

    /**
     * @var array<string, LegacyMember>
     */
    private array $members = [];

    /**
     * Run the database seeds.
     */
    public function run() : void
    {
        /** @var array<string, array<int, array{slug: string, name: string, description: string, logo: ?string, photo: ?string, members: string[]}>> $committeesPerYear */
        $committeesPerYear = json_decode(
            (string) file_get_contents(__DIR__ . '/data/committees.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        /** @var array<string, Committee> $previousCommittees */
        $previousCommittees = [];

        foreach (self::BOARDS as $index => $boardData) {
            $isCurrentBoard = $index === count(self::BOARDS) - 1;
            $board = $this->installBoard($boardData, $isCurrentBoard);

            $committees = [];
            foreach ($committeesPerYear[$board->board_year_slug] ?? [] as $committeeData) {
                $committees[$committeeData['slug']] = $this->installCommittee(
                    $board,
                    $committeeData,
                    $previousCommittees[$committeeData['slug']] ?? null,
                    $isCurrentBoard
                );
            }
            $previousCommittees = $committees;
        }
    }

    private function installBoard(array $boardData, bool $isCurrentBoard) : Board
    {
        /** @var Board $board */
        $board = factory(Board::class)->create([
            'installed_at' => $boardData['year'] . '-06-06',
            'name' => $boardData['name'],
            'photo_position' => '',
        ]);
        $this->attachMedia($board, 'board_photo', 'photo_media_id', 'images/boards/' . $boardData['photo']);

        foreach ($boardData['members'] as [$name, $title, $photo]) {
            $isAdmin = $isCurrentBoard && str_starts_with($title, 'President');

            /** @var BoardMember $boardMember */
            $boardMember = factory(BoardMember::class)->create([
                'board_id' => $board->id,
                'member_id' => $isAdmin ? DatabaseSeeder::MEMBER_ID : $this->memberNamed($name)->id,
                'name' => $name,
                'title' => $title,
            ]);
            $this->attachMedia($boardMember, 'board_member_photo', 'photo_media_id', 'images/boards/' . $photo);
        }

        return $board;
    }

    private function installCommittee(Board $board, array $committeeData, ?Committee $parent, bool $isCurrentBoard) : Committee
    {
        $description = $committeeData['description'];

        /** @var Committee $committee */
        $committee = Committee::create([
            'board_id' => $board->id,
            'parent_committee_id' => $parent?->id,
            'name' => $committeeData['name'],
            'slug' => $committeeData['slug'],
            'goal' => $description !== '' ? Str::limit(trim(strip_tags($description)), 150) : null,
            'email' => null,
            'is_public' => true,
            // The live site only shows the compiled content, as Markdown may contain html
            // it is the source as well
            'source_content' => $description !== '' ? $description : null,
            'compiled_content' => $description !== '' ? $description : null,
        ]);

        if ($committeeData['logo'] !== null) {
            $this->attachMedia($committee, Committee::COMMITTEE_LOGO_TAG, 'logo_media_id', $committeeData['logo']);
        }
        if ($committeeData['photo'] !== null) {
            $this->attachMedia($committee, Committee::COMMITEE_PHOTO_TAG, 'photo_media_id', $committeeData['photo']);
        }

        foreach (array_unique($committeeData['members']) as $name) {
            CommitteeMember::create([
                'committee_id' => $committee->id,
                'member_id' => $this->memberNamed($name)->id,
                'function' => '',
                'installed_at' => $board->installed_at,
                'decharged_at' => $isCurrentBoard ? null : $board->demissioned_at,
            ]);
        }

        return $committee;
    }

    /**
     * The member with this name, or a new member with this name when there is none
     */
    private function memberNamed(string $fullname) : LegacyMember
    {
        if (isset($this->members[$fullname])) {
            return $this->members[$fullname];
        }

        // "Annabelle van Berlo": the first name, the particles and the last name
        $words = explode(' ', $fullname);
        $firstname = array_shift($words);
        $particles = [];
        while (count($words) > 1 && in_array($words[0], self::PARTICLES, true)) {
            $particles[] = array_shift($words);
        }
        $surname = implode(' ', $words);

        /** @var LegacyMember $member */
        // The admin when it has this name, as there may be several members with the same name
        $member = LegacyMember::where('voornaam', $firstname)
            ->where('achternaam', $surname)
            ->orderByRaw('id = ? desc', [DatabaseSeeder::MEMBER_ID])
            ->orderBy('id')
            ->first()
            ?? factory(LegacyMember::class)->create([
                'voornaam' => $firstname,
                'tussenvoegsel' => implode(' ', $particles),
                'achternaam' => $surname,
                'initialen' => mb_substr($firstname, 0, 1) . '.',
            ]);

        return $this->members[$fullname] = $member;
    }

    /**
     * Download the file from the live site and attach it, the model is shown without it
     * when that fails
     */
    private function attachMedia(Model $model, string $tag, string $column, string $path) : void
    {
        if ( ! array_key_exists($path, $this->media)) {
            try {
                $this->media[$path] = app(MediaUploader::class)
                    ->fromSource(self::UPLOADS_URL . $path)
                    ->toDirectory(dirname($path))
                    ->useFilename(pathinfo($path, PATHINFO_FILENAME))
                    ->onDuplicateReplace()
                    ->upload();
            } catch (Throwable $e) {
                $this->command->warn("Could not download {$path}: {$e->getMessage()}");
                $this->media[$path] = null;
            }
        }

        $media = $this->media[$path];
        if ($media === null) {
            return;
        }

        $model->attachMedia($media, $tag);
        $model->update([$column => $media->id]);
    }
}
