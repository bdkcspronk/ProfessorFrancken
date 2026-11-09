<?php

declare(strict_types=1);

use Francken\Association\AlumniActivity\Alumnus;
use Francken\Association\LegacyMember;
use Francken\Association\Members\Registration\Registration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * Fake data for the association pages of the admin, so that their buttons can be tried
 * out: registration requests in each stage and alumni that come to the alumni activity. The
 * boards and committees with their members are in the BoardsSeeder
 */
class AssociationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() : void
    {
        $this->submitRegistrationRequests(new DateTimeImmutable());
        $this->registerAlumni();
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

    private function randomMembers(int $count) : Collection
    {
        return LegacyMember::where('id', '!=', DatabaseSeeder::MEMBER_ID)
            ->inRandomOrder()
            ->limit($count)
            ->get();
    }
}
