<?php

declare(strict_types=1);

namespace Francken\Features\Admin\Study;

use Francken\Features\LoggedInAsAdmin;
use Francken\Features\TestCase;
use Francken\Study\ResearchGroups\Http\AdminResearchGroupsController;
use Francken\Study\ResearchGroups\Http\AdminResearchUnitsController;
use Francken\Study\ResearchGroups\ResearchGroup;
use Francken\Study\ResearchGroups\ResearchUnit;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ResearchGroupsFeature extends TestCase
{
    use LoggedInAsAdmin;
    use DatabaseTransactions;

    /** @test */
    public function the_introduction_of_the_research_groups_page_can_be_changed() : void
    {
        $this->visit(action([AdminResearchGroupsController::class, 'index']))
            ->click('Edit introduction')
            ->type('Find a group for your **thesis**', 'source_content')
            ->press('Save')
            ->seePageIs(action([AdminResearchGroupsController::class, 'index']));

        $this->visit('/study/research-groups')
            ->seeInElement('.page-intro strong', 'thesis');
    }

    /** @test */
    public function a_research_group_can_be_added() : void
    {
        $this->visit(action([AdminResearchGroupsController::class, 'index']))
            ->click('Add research group')
            ->type('Theoretical Physics', 'title')
            ->type('We work on *theories*', 'source_content')
            ->press('Add research group')
            ->see('Theoretical Physics');

        $this->visit('/study/research-groups/theoretical-physics')
            ->seeInElement('.cms-page em', 'theories');
    }

    /** @test */
    public function a_research_group_can_be_changed() : void
    {
        $this->visit(action([AdminResearchGroupsController::class, 'edit'], ['researchGroup' => 'micromechanics']))
            ->type('Micromechanics of Materials', 'title')
            ->press('Save')
            ->seePageIs(action([AdminResearchGroupsController::class, 'show'], ['researchGroup' => 'micromechanics-of-materials']));

        $this->visit('/study/research-groups/micromechanics-of-materials')
            ->assertResponseOk();
    }

    /** @test */
    public function two_research_groups_cannot_have_the_same_title() : void
    {
        $this->visit(action([AdminResearchGroupsController::class, 'create']))
            ->type('Micromechanics', 'title')
            ->press('Add research group')
            ->see('There already is a research group with this title.');
    }

    /** @test */
    public function a_research_group_can_be_removed() : void
    {
        $this->visit(action([AdminResearchGroupsController::class, 'edit'], ['researchGroup' => 'micromechanics']))
            ->press('here')
            ->seePageIs(action([AdminResearchGroupsController::class, 'index']));

        $this->assertNull(ResearchGroup::where('slug', 'micromechanics')->first());
        $this->assertSame(0, ResearchUnit::where('name', 'Onck')->count());
    }

    /** @test */
    public function a_group_can_be_added_to_a_research_group() : void
    {
        $this->visit(action([AdminResearchGroupsController::class, 'show'], ['researchGroup' => 'micromechanics']))
            ->type('Kamperman', 'name')
            ->type('Polymer Science', 'title')
            ->type('https://www.rug.nl/staff/m.m.g.kamperman/', 'contact_url')
            ->press('Add group')
            ->see('Kamperman group');

        $this->visit('/study/research-groups/micromechanics')
            ->see('Kamperman group')
            ->see('Polymer Science');
    }

    /** @test */
    public function a_group_of_a_research_group_can_be_changed_and_removed() : void
    {
        $group = ResearchGroup::where('slug', 'micromechanics')->firstOrFail();
        $unit = $group->units()->where('name', 'Onck')->firstOrFail();

        $this->visit(action([AdminResearchUnitsController::class, 'edit'], ['researchGroup' => $group, 'unit' => $unit]))
            ->type('Active Matter', 'title')
            ->press('Save')
            ->seePageIs(action([AdminResearchGroupsController::class, 'show'], ['researchGroup' => $group]))
            ->see('Active Matter');

        $this->visit(action([AdminResearchUnitsController::class, 'edit'], ['researchGroup' => $group, 'unit' => $unit]))
            ->press('here')
            ->seePageIs(action([AdminResearchGroupsController::class, 'show'], ['researchGroup' => $group]));

        $this->assertNull(ResearchUnit::find($unit->id));
        $this->dontSee('Active Matter');
    }
}
