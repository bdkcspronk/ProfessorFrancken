<?php

declare(strict_types=1);

namespace Francken\Study\ResearchGroups\Http;

use Francken\Shared\Markdown\ContentCompiler;
use Francken\Shared\Page;
use Francken\Study\ResearchGroups\Http\Requests\AdminResearchGroupRequest;
use Francken\Study\ResearchGroups\PhotoUploader;
use Francken\Study\ResearchGroups\ResearchGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class AdminResearchGroupsController
{
    public function __construct(private PhotoUploader $uploader)
    {
    }

    public function index() : View
    {
        return view('admin.study.research-groups.index', [
            'introduction' => $this->introduction(),
            'groups' => ResearchGroup::with('photoMedia')->withCount('units')->orderBy('title')->get(),
            'breadcrumbs' => [
                ['url' => action([self::class, 'index']), 'text' => 'Research groups'],
            ]
        ]);
    }

    public function editIntroduction() : View
    {
        return view('admin.study.research-groups.introduction', [
            'introduction' => $this->introduction(),
            'breadcrumbs' => [
                ['url' => action([self::class, 'index']), 'text' => 'Research groups'],
                ['url' => action([self::class, 'editIntroduction']), 'text' => 'Introduction'],
            ]
        ]);
    }

    public function updateIntroduction(Request $request, ContentCompiler $compiler) : RedirectResponse
    {
        $request->validate(['source_content' => ['nullable', 'min:1']]);

        $markdown = $compiler->content($request->string('source_content')->toString());
        $this->introduction()->fill([
            'source_content' => $markdown->originalMarkdown(),
            'compiled_content' => $markdown->compiledContent(),
        ])->save();

        return redirect()->action([self::class, 'index'])
            ->with(['status' => 'Saved the introduction of the research groups page']);
    }

    public function show(ResearchGroup $researchGroup) : View
    {
        $researchGroup->load(['photoMedia', 'units.photoMedia']);

        return view('admin.study.research-groups.show', [
            'group' => $researchGroup,
            'breadcrumbs' => [
                ['url' => action([self::class, 'index']), 'text' => 'Research groups'],
                ['url' => action([self::class, 'show'], ['researchGroup' => $researchGroup]), 'text' => $researchGroup->title],
            ]
        ]);
    }

    public function create() : View
    {
        return view('admin.study.research-groups.create', [
            'group' => new ResearchGroup(),
            'breadcrumbs' => [
                ['url' => action([self::class, 'index']), 'text' => 'Research groups'],
                ['url' => action([self::class, 'create']), 'text' => 'Add research group'],
            ]
        ]);
    }

    public function store(AdminResearchGroupRequest $request, ContentCompiler $compiler) : RedirectResponse
    {
        $markdown = $compiler->content($request->content());

        $group = ResearchGroup::query()->create([
            'title' => $request->title(),
            'slug' => $request->slug(),
            'source_content' => $markdown->originalMarkdown(),
            'compiled_content' => $markdown->compiledContent(),
        ]);

        $this->uploader->uploadGroupPhoto($request->photo(), $group);

        return redirect()->action([self::class, 'show'], ['researchGroup' => $group]);
    }

    public function edit(ResearchGroup $researchGroup) : View
    {
        return view('admin.study.research-groups.edit', [
            'group' => $researchGroup,
            'breadcrumbs' => [
                ['url' => action([self::class, 'index']), 'text' => 'Research groups'],
                ['url' => action([self::class, 'show'], ['researchGroup' => $researchGroup]), 'text' => $researchGroup->title],
                ['url' => action([self::class, 'edit'], ['researchGroup' => $researchGroup]), 'text' => 'Edit'],
            ]
        ]);
    }

    public function update(AdminResearchGroupRequest $request, ResearchGroup $researchGroup, ContentCompiler $compiler) : RedirectResponse
    {
        $markdown = $compiler->content($request->content());

        $researchGroup->update([
            'title' => $request->title(),
            'slug' => $request->slug(),
            'source_content' => $markdown->originalMarkdown(),
            'compiled_content' => $markdown->compiledContent(),
        ]);

        $this->uploader->uploadGroupPhoto($request->photo(), $researchGroup);

        return redirect()->action([self::class, 'show'], ['researchGroup' => $researchGroup]);
    }

    public function destroy(ResearchGroup $researchGroup) : RedirectResponse
    {
        $researchGroup->units()->delete();
        $researchGroup->delete();

        return redirect()->action([self::class, 'index'])
            ->with(['status' => 'Removed the research group ' . $researchGroup->title]);
    }

    private function introduction() : Page
    {
        return Page::firstOrNew(
            ['slug' => ResearchGroupsController::INTRODUCTION_SLUG],
            ['title' => 'Research Groups', 'is_published' => false]
        );
    }
}
