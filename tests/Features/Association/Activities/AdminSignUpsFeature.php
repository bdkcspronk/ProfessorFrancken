<?php

declare(strict_types=1);

namespace Francken\Features\Association\Activities;

use Francken\Association\Activities\Activity;
use Francken\Association\Activities\Http\AdminActivitiesController;
use Francken\Association\Activities\Http\AdminSignUpsController;
use Francken\Association\Activities\SignUp;
use Francken\Association\Activities\SignUpSettings;
use Francken\Association\LegacyMember;
use Francken\Features\LoggedInAsAdmin;
use Francken\Features\TestCase;

class AdminSignUpsFeature extends TestCase
{
    use LoggedInAsAdmin;

    /** @test */
    public function it_allows_a_board_member_to_sign_up_a_member() : void
    {
        $activity = factory(Activity::class)->create();
        factory(SignUpSettings::class)->create([
            'activity_id' => $activity->id,
            'ask_for_dietary_wishes' => true,
            'ask_for_drivers_license' => true,
        ]);

        $member = factory(LegacyMember::class)->create();

        $this->visit(action([AdminActivitiesController::class, 'show'], ['activity' => $activity]))
            ->see($activity->name)
            ->click('Add sign up')
            ->type($member->id, 'member_id')
            ->type('Kip & Ketel', 'dietary_wishes')
            ->uncheck('has_drivers_license')
            ->type(1, 'plus_ones')
            ->type('Hoi', 'notes')
            ->press('Sign up')
            ->seePageIs(action([AdminActivitiesController::class, 'show'], ['activity' => $activity]));

        $this->assertResponseOk();
        $this->assertCount(1, $activity->signUps);
    }

    /** @test */
    public function it_allows_changing_a_sign_up() : void
    {
        $activity = factory(Activity::class)->create();
        factory(SignUpSettings::class)->create([
            'activity_id' => $activity->id,
            'ask_for_dietary_wishes' => true,
            'ask_for_drivers_license' => true,
        ]);
        $signUp = factory(SignUp::class)->create([
            'activity_id' => $activity->id,
            'has_drivers_license' => true
        ]);

        $this->visit(action([AdminSignUpsController::class, 'edit'], ['activity' => $activity, 'sign_up' => $signUp]))
            ->see($activity->name)
            ->type('Kip & Ketel', 'dietary_wishes')
            ->uncheck('has_drivers_license')
            ->type(1, 'plus_ones')
            ->type('Hoi', 'notes')
            ->press('Save')
            ->seePageIs(action([AdminActivitiesController::class, 'show'], ['activity' => $activity]));

        $this->assertResponseOk();
        $signUp->refresh();
        $this->assertEquals('Kip & Ketel', $signUp->dietary_wishes);
        $this->assertEquals(1, $signUp->plus_ones);
        $this->assertEquals('Hoi', $signUp->notes);
        $this->assertFalse($signUp->has_drivers_license);
    }

    /** @test */
    public function it_allows_removing_a_sign_up() : void
    {
        $activity = factory(Activity::class)->create();
        factory(SignUpSettings::class)->create([
            'activity_id' => $activity->id,
            'ask_for_dietary_wishes' => true,
            'ask_for_drivers_license' => true,
        ]);
        $signUp = factory(SignUp::class)->create([
            'activity_id' => $activity->id,
            'has_drivers_license' => true
        ]);

        $this->visit(action([AdminSignUpsController::class, 'edit'], ['activity' => $activity, 'sign_up' => $signUp]))
             ->press('here')
             ->seePageIs(action([AdminActivitiesController::class, 'show'], ['activity' => $activity]));

        $this->assertCount(0, $activity->signUps);
    }

    /** @test */
    public function it_allows_replacing_a_member_with_someone_from_the_waiting_list() : void
    {
        $activity = factory(Activity::class)->create();
        factory(SignUpSettings::class)->create([
            'activity_id' => $activity->id,
            'max_sign_ups' => 1,
        ]);
        $placed = factory(SignUp::class)->create(['activity_id' => $activity->id, 'plus_ones' => 0]);
        $first = factory(SignUp::class)->create(['activity_id' => $activity->id, 'plus_ones' => 0, 'status' => SignUp::STATUS_WAITING]);
        $second = factory(SignUp::class)->create(['activity_id' => $activity->id, 'plus_ones' => 0, 'status' => SignUp::STATUS_WAITING]);

        $this->visit(action([AdminActivitiesController::class, 'show'], ['activity' => $activity]))
            ->see('Replace with someone from the waiting list')
            ->select($second->id, 'replacement_id')
            ->press('Replace')
            ->seePageIs(action([AdminActivitiesController::class, 'show'], ['activity' => $activity]));

        $this->assertNull($placed->fresh());
        // The admin chose who gets the place, so it's official right away
        $this->assertEquals(SignUp::STATUS_PLACED, $second->fresh()->status);
        $this->assertEquals(SignUp::STATUS_WAITING, $first->fresh()->status);
    }

    /** @test */
    public function it_allows_approving_a_place_that_was_offered_to_a_member() : void
    {
        $activity = factory(Activity::class)->create();
        factory(SignUpSettings::class)->create([
            'activity_id' => $activity->id,
            'max_sign_ups' => 1,
        ]);
        $offered = factory(SignUp::class)->create(['activity_id' => $activity->id, 'plus_ones' => 0, 'status' => SignUp::STATUS_OFFERED]);

        $this->visit(action([AdminActivitiesController::class, 'show'], ['activity' => $activity]))
            ->see('Place offered, not yet accepted')
            ->press('Approve')
            ->seePageIs(action([AdminActivitiesController::class, 'show'], ['activity' => $activity]));

        $this->assertEquals(SignUp::STATUS_PLACED, $offered->fresh()->status);
    }

    /** @test */
    public function it_offers_places_to_the_waiting_list_when_removing_a_sign_up() : void
    {
        $activity = factory(Activity::class)->create();
        factory(SignUpSettings::class)->create([
            'activity_id' => $activity->id,
            'max_sign_ups' => 1,
        ]);
        $placed = factory(SignUp::class)->create(['activity_id' => $activity->id, 'plus_ones' => 0]);
        $waiting = factory(SignUp::class)->create(['activity_id' => $activity->id, 'plus_ones' => 0, 'status' => SignUp::STATUS_WAITING]);

        $this->delete(action([AdminSignUpsController::class, 'destroy'], ['activity' => $activity, 'sign_up' => $placed]));

        $this->assertNull($placed->fresh());
        $this->assertEquals(SignUp::STATUS_OFFERED, $waiting->fresh()->status);
    }
}
