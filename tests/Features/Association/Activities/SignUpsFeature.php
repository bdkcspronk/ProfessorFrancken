<?php

declare(strict_types=1);

namespace Francken\Features\Association\Activities;

use DateTimeImmutable;
use Francken\Association\Activities\Activity;
use Francken\Association\Activities\Http\ActivitiesController;
use Francken\Association\Activities\Http\SignUpsController;
use Francken\Association\Activities\SignUp;
use Francken\Association\Activities\SignUpSettings;
use Francken\Auth\Account;
use Francken\Features\TestCase;

class SignUpsFeature extends TestCase
{
    /** @test */
    public function it_allows_a_member_to_sign_up_to_an_activity() : void
    {
        $tomorrow = new DateTimeImmutable('tomorrow +1day');

        $activity = factory(Activity::class)->create();
        factory(SignUpSettings::class)->create([
            'activity_id' => $activity->id,
            'deadline_at' => $tomorrow
        ]);

        $account = factory(Account::class)->create();
        $this->actingAs($account)
            ->visit(action([ActivitiesController::class, 'show'], ['activity' => $activity]))
            ->press('Sign up');
        $this->assertCount(1, $activity->signUps);
    }

    /** @test */
    public function it_does_not_allow_a_member_to_sign_up_twice_to_an_activity() : void
    {
        $activity = factory(Activity::class)->create();
        factory(SignUpSettings::class)->create([
            'activity_id' => $activity->id,
            'ask_for_dietary_wishes' => true,
            'ask_for_drivers_license' => true,
        ]);

        $account = factory(Account::class)->create();
        factory(SignUp::class)->create([
            'activity_id' => $activity->id,
            'member_id' => $account->member_id,
        ]);

        $this->actingAs($account)
             ->visit(action([ActivitiesController::class, 'show'], ['activity' => $activity]))
            ->dontSee('Sign up for');
    }

    /** @test */
    public function it_does_not_allow_a_member_to_sign_up_after_the_signup_deadline() : void
    {
        $today = new DateTimeImmutable();
        $activity = factory(Activity::class)->create([
            'end_date' => $today->modify("-1 day")->format('Y-m-d')
        ]);
        factory(SignUpSettings::class)->create([
            'activity_id' => $activity->id,
            'deadline_at' => $activity->end_date,
            'max_sign_ups' => 33,
        ]);

        $account = factory(Account::class)->create();

        $this->actingAs($account)
             ->visit(action([ActivitiesController::class, 'show'], ['activity' => $activity]))
             ->dontSee('Sign up for');
    }

    /** @test */
    public function it_has_a_limit_to_the_amount_of_plus_ones_a_member_can_bring() : void
    {
        $today = new DateTimeImmutable();
        $activity = factory(Activity::class)->create([
            'end_date' => $today->modify("+1 day")->format('Y-m-d')
        ]);
        factory(SignUpSettings::class)->create([
            'activity_id' => $activity->id,
            'deadline_at' => $activity->end_date,
            'max_sign_ups' => 33,
            'max_plus_ones_per_member' => null,
        ]);
        $account = factory(Account::class)->create();
        $this->actingAs($account)
            ->visit(action([ActivitiesController::class, 'show'], ['activity' => $activity]))
            ->type(33, 'plus_ones')
            ->press('Sign up');

        $this->assertCount(0, $activity->signUps);
    }

    /** @test */
    public function it_limits_the_plus_ones_to_the_maximum_per_member() : void
    {
        $activity = factory(Activity::class)->create();
        factory(SignUpSettings::class)->create([
            'activity_id' => $activity->id,
            'deadline_at' => new DateTimeImmutable('tomorrow +1day'),
            'max_sign_ups' => 33,
            'max_plus_ones_per_member' => 2,
        ]);
        $account = factory(Account::class)->create();

        $this->actingAs($account)
            ->visit(action([ActivitiesController::class, 'show'], ['activity' => $activity]))
            ->see('You can bring up to 2 people.')
            ->type(3, 'plus_ones')
            ->press('Sign up')
            ->see("You can't bring more than 2 people");
        $this->assertCount(0, $activity->signUps);

        $this->type(2, 'plus_ones')
            ->press('Sign up');
        $this->assertCount(1, $activity->fresh()->signUps);
    }

    /** @test */
    public function it_allows_a_member_to_change_their_sign_up() : void
    {
        $activity = factory(Activity::class)->create();
        factory(SignUpSettings::class)->create([
            'activity_id' => $activity->id,
            'max_sign_ups' => 34,
            'max_plus_ones_per_member' => null,
            'deadline_at' => new DateTimeImmutable('tomorrow +1day'),
        ]);

        $account = factory(Account::class)->create();
        $signUp = factory(SignUp::class)->create([
            'activity_id' => $activity->id,
            'member_id' => $account->member_id,
            'plus_ones' => 10
        ]);

        $this->actingAs($account)
            ->visit(action(
                [SignUpsController::class, 'edit'],
                ['activity' => $activity, 'sign_up' => $signUp]
            ))
            ->type(33, 'plus_ones')
            ->press('Save')
            ->seePageIs(
                action(
                    [ActivitiesController::class, 'show'],
                    ['activity' => $activity]
                )
            );

        $signUp->refresh();
        $this->assertEquals(33, $signUp->plus_ones);
    }

    /** @test */
    public function it_allows_a_member_to_cancel_their_sign_up() : void
    {
        $activity = factory(Activity::class)->create();
        factory(SignUpSettings::class)->create([
            'activity_id' => $activity->id,
            'max_sign_ups' => 34,
            'max_plus_ones_per_member' => null,
            'deadline_at' => new DateTimeImmutable('tomorrow +1day'),
        ]);

        $account = factory(Account::class)->create();
        $signUp = factory(SignUp::class)->create([
            'activity_id' => $activity->id,
            'member_id' => $account->member_id,
            'plus_ones' => 10
        ]);

        $this->actingAs($account)
            ->visit(action(
                [SignUpsController::class, 'edit'],
                ['activity' => $activity, 'sign_up' => $signUp]
            ))
            ->press('Cancel your sign up')
            ->seePageIs(
                action(
                    [ActivitiesController::class, 'show'],
                    ['activity' => $activity]
                )
            );

        $this->assertEquals(0, $activity->total_sign_ups);
    }

    /** @test */
    public function it_puts_members_on_the_waiting_list_once_an_activity_is_full() : void
    {
        $activity = factory(Activity::class)->create();
        factory(SignUpSettings::class)->create([
            'activity_id' => $activity->id,
            'max_sign_ups' => 2,
            'max_plus_ones_per_member' => null,
            'deadline_at' => new DateTimeImmutable('tomorrow +1day'),
        ]);
        factory(SignUp::class)->create([
            'activity_id' => $activity->id,
            'plus_ones' => 1,
        ]);

        $account = factory(Account::class)->create();
        $this->actingAs($account)
            ->visit(action([ActivitiesController::class, 'show'], ['activity' => $activity]))
            ->see('This activity is full')
            ->press('Sign up for the waiting list')
            ->see('Waiting list');

        $activity = $activity->fresh();
        $this->assertCount(2, $activity->signUps);
        $this->assertEquals(2, $activity->total_sign_ups);
        $this->assertCount(1, $activity->waiting_list_sign_ups);
        $this->assertEquals($account->member_id, $activity->waiting_list_sign_ups->first()->member_id);
        $this->assertEquals(SignUp::STATUS_WAITING, $activity->waiting_list_sign_ups->first()->status);
    }

    /** @test */
    public function it_offers_the_place_of_someone_who_cancels_to_the_first_on_the_waiting_list() : void
    {
        $activity = factory(Activity::class)->create();
        factory(SignUpSettings::class)->create([
            'activity_id' => $activity->id,
            'max_sign_ups' => 1,
            'deadline_at' => new DateTimeImmutable('tomorrow +1day'),
        ]);
        $account = factory(Account::class)->create();
        $first = factory(SignUp::class)->create([
            'activity_id' => $activity->id,
            'member_id' => $account->member_id,
            'plus_ones' => 0,
        ]);
        [$second, $third] = collect(range(1, 2))->map(fn () => factory(SignUp::class)->create([
            'activity_id' => $activity->id,
            'plus_ones' => 0,
            'status' => SignUp::STATUS_WAITING,
        ]));

        $this->actingAs($account)
            ->visit(action(
                [SignUpsController::class, 'edit'],
                ['activity' => $activity, 'sign_up' => $first]
            ))
            ->press('Cancel your sign up');

        $this->assertNull($first->fresh());
        $this->assertEquals(SignUp::STATUS_OFFERED, $second->fresh()->status);
        $this->assertEquals(SignUp::STATUS_WAITING, $third->fresh()->status);

        // The offered place is kept, so the activity is still full
        $activity = $activity->fresh();
        $this->assertEquals(1, $activity->total_sign_ups);
        $this->assertTrue($activity->is_full);
    }

    /** @test */
    public function it_allows_a_member_to_accept_a_place_that_opened_up() : void
    {
        $activity = factory(Activity::class)->create();
        factory(SignUpSettings::class)->create([
            'activity_id' => $activity->id,
            'max_sign_ups' => 1,
            'deadline_at' => new DateTimeImmutable('tomorrow +1day'),
        ]);
        $account = factory(Account::class)->create();
        $signUp = factory(SignUp::class)->create([
            'activity_id' => $activity->id,
            'member_id' => $account->member_id,
            'plus_ones' => 0,
            'status' => SignUp::STATUS_OFFERED,
        ]);

        $this->actingAs($account)
            ->visit(action([ActivitiesController::class, 'show'], ['activity' => $activity]))
            ->see('A place has opened up for you!')
            ->press('Accept my place')
            ->dontSee('A place has opened up for you!');

        $this->assertEquals(SignUp::STATUS_PLACED, $signUp->fresh()->status);
    }

    /** @test */
    public function it_offers_the_place_to_the_next_one_when_a_member_declines_it() : void
    {
        $activity = factory(Activity::class)->create();
        factory(SignUpSettings::class)->create([
            'activity_id' => $activity->id,
            'max_sign_ups' => 1,
            'deadline_at' => new DateTimeImmutable('tomorrow +1day'),
        ]);
        $account = factory(Account::class)->create();
        $signUp = factory(SignUp::class)->create([
            'activity_id' => $activity->id,
            'member_id' => $account->member_id,
            'plus_ones' => 0,
            'status' => SignUp::STATUS_OFFERED,
        ]);
        $next = factory(SignUp::class)->create([
            'activity_id' => $activity->id,
            'plus_ones' => 0,
            'status' => SignUp::STATUS_WAITING,
        ]);

        $this->actingAs($account)
            ->visit(action([ActivitiesController::class, 'show'], ['activity' => $activity]))
            ->press("I can't come anymore");

        $this->assertNull($signUp->fresh());
        $this->assertEquals(SignUp::STATUS_OFFERED, $next->fresh()->status);
    }

    /** @test */
    public function it_does_not_let_the_waiting_list_skip_a_group_that_does_not_fit() : void
    {
        $activity = factory(Activity::class)->create();
        factory(SignUpSettings::class)->create([
            'activity_id' => $activity->id,
            'max_sign_ups' => 2,
            'deadline_at' => new DateTimeImmutable('tomorrow +1day'),
        ]);
        $placed = factory(SignUp::class)->create(['activity_id' => $activity->id, 'plus_ones' => 0]);
        factory(SignUp::class)->create(['activity_id' => $activity->id, 'plus_ones' => 0]);
        $group = factory(SignUp::class)->create(['activity_id' => $activity->id, 'plus_ones' => 1, 'status' => SignUp::STATUS_WAITING]);
        $single = factory(SignUp::class)->create(['activity_id' => $activity->id, 'plus_ones' => 0, 'status' => SignUp::STATUS_WAITING]);

        $activity->cancelSignUp($placed);

        // One place is free, but the group of two is first in line
        $this->assertEquals(SignUp::STATUS_WAITING, $group->fresh()->status);
        $this->assertEquals(SignUp::STATUS_WAITING, $single->fresh()->status);
    }

    /** @test */
    public function it_does_not_let_a_member_with_a_place_bring_so_many_people_that_they_lose_it() : void
    {
        $activity = factory(Activity::class)->create();
        factory(SignUpSettings::class)->create([
            'activity_id' => $activity->id,
            'max_sign_ups' => 3,
            'max_plus_ones_per_member' => null,
            'deadline_at' => new DateTimeImmutable('tomorrow +1day'),
        ]);

        $account = factory(Account::class)->create();
        $signUp = factory(SignUp::class)->create([
            'activity_id' => $activity->id,
            'member_id' => $account->member_id,
            'plus_ones' => 0
        ]);
        factory(SignUp::class)->create([
            'activity_id' => $activity->id,
            'plus_ones' => 0
        ]);

        $this->actingAs($account)
            ->visit(action(
                [SignUpsController::class, 'edit'],
                ['activity' => $activity, 'sign_up' => $signUp]
            ))
            ->type(2, 'plus_ones')
            ->press('Save')
            ->see("You can't bring more than 1 people");

        $this->assertEquals(0, $signUp->fresh()->plus_ones);
    }
}
