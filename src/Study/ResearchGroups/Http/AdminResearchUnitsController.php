<?php

declare(strict_types=1);

namespace Francken\Study\ResearchGroups\Http;

use Francken\Study\ResearchGroups\Http\Requests\AdminResearchUnitRequest;
use Francken\Study\ResearchGroups\PhotoUploader;
use Francken\Study\ResearchGroups\ResearchGroup;
use Francken\Study\ResearchGroups\ResearchUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class AdminResearchUnitsController
{
    public function __construct(private PhotoUploader $uploader)
    {
    }

    public function store(AdminResearchUnitRequest $request, ResearchGroup $researchGroup) : RedirectResponse
    {
        /** @var ResearchUnit $unit */
        $unit = $researchGroup->units()->create([
            'name' => $request->name(),
            'title' => $request->title(),
            'website_url' => $request->websiteUrl(),
            'contact_url' => $request->contactUrl(),
        ]);

        $this->uploader->uploadUnitPhoto($request->photo(), $unit);

        return redirect()->action([AdminResearchGroupsController::class, 'show'], ['researchGroup' => $researchGroup]);
    }

    public function edit(ResearchGroup $researchGroup, ResearchUnit $unit) : View
    {
        return view('admin.study.research-groups.units.edit', [
            'group' => $researchGroup,
            'unit' => $unit,
            'breadcrumbs' => [
                ['url' => action([AdminResearchGroupsController::class, 'index']), 'text' => 'Research groups'],
                ['url' => action([AdminResearchGroupsController::class, 'show'], ['researchGroup' => $researchGroup]), 'text' => $researchGroup->title],
                ['url' => action([self::class, 'edit'], ['researchGroup' => $researchGroup, 'unit' => $unit]), 'text' => $unit->name . ' group'],
            ]
        ]);
    }

    public function update(AdminResearchUnitRequest $request, ResearchGroup $researchGroup, ResearchUnit $unit) : RedirectResponse
    {
        $unit->update([
            'name' => $request->name(),
            'title' => $request->title(),
            'website_url' => $request->websiteUrl(),
            'contact_url' => $request->contactUrl(),
        ]);

        $this->uploader->uploadUnitPhoto($request->photo(), $unit);

        return redirect()->action([AdminResearchGroupsController::class, 'show'], ['researchGroup' => $researchGroup]);
    }

    public function destroy(ResearchGroup $researchGroup, ResearchUnit $unit) : RedirectResponse
    {
        $unit->delete();

        return redirect()->action([AdminResearchGroupsController::class, 'show'], ['researchGroup' => $researchGroup]);
    }
}
