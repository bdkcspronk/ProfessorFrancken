<?php

declare(strict_types=1);

namespace Francken\Study\ResearchGroups\Http;

use Francken\Shared\Page;
use Francken\Study\ResearchGroups\ResearchGroup;
use Illuminate\View\View;

final class ResearchGroupsController
{
    /**
     * The introduction of the research groups page is a page of its own, so that it can
     * be changed in the admin
     */
    public const INTRODUCTION_SLUG = 'study/research-groups';

    public function index() : View
    {
        return view('study.research-groups.index')
            ->with('introduction', Page::where('slug', self::INTRODUCTION_SLUG)->first())
            ->with('groups', ResearchGroup::with('photoMedia')->orderBy('title')->get())
            ->with('breadcrumbs', [
                ['url' => '/study', 'text' => 'Study'],
                ['url' => '/study/research-groups', 'text' => 'Research Groups'],
            ]);
    }

    public function show(ResearchGroup $group) : View
    {
        $group->load(['photoMedia', 'units.photoMedia']);

        return view('study.research-groups.show')
            ->with('groups', ResearchGroup::with('photoMedia')->orderBy('title')->get())
            ->with('group', $group)
            ->with('breadcrumbs', [
                ['url' => '/study', 'text' => 'Study'],
                ['url' => '/study/research-groups', 'text' => 'Research Groups'],
                ['text' => $group->title],
            ]);
    }
}
