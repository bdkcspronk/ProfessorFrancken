<?php

declare(strict_types=1);

namespace Francken\Shared\ViewComponents\Admin;

use Francken\Auth\Account;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\View\Component;
use Illuminate\View\View;
use Webmozart\Assert\Assert;

/**
 * The pages of the admin that the account may see, grouped by section (see
 * config/francken/navigation.php). The variant chooses how they are shown:
 *  - 'sections': a tab per section in the header
 *  - 'section-pages': a tab per page of the current section, below the header
 *  - 'page': all pages as cards on the dashboard
 */
final class Navigation extends Component
{
    public array $groups;
    public ?array $currentGroup;

    public function __construct(Guard $auth, Request $request, public string $variant = 'page')
    {
        $account = $auth->user();
        Assert::isInstanceOf($account, Account::class);

        /** @var array<int, array{name: string, url: string, items: array<int, array<string, mixed>>}> $menu */
        $menu = config('francken.navigation.admin-menu');

        $groups = array_map(
            function (array $group) use ($account, $request) : array {
                $items = array_filter(
                    $group['items'],
                    // Pages that don't work yet are only shown to super admins
                    fn (array $item) : bool => isset($item['works']) && ! $item['works']
                        ? $account->can('super-admin-read')
                        : $account->can(is_string($item['can'] ?? null) ? $item['can'] : 'can-access-dashboard')
                );

                $group['items'] = array_map(function (array $item) use ($group, $request) : array {
                    $path = 'admin/' . $group['url'] . '/' . $item['url'];
                    $item['href'] = '/' . $path;
                    /** @var string[] $also */
                    $also = $item['also'] ?? [];
                    $item['match'] = $this->match($request, [$path, ...$also]);

                    return $item;
                }, array_values($items));

                return $group;
            },
            $menu
        );

        // The page is the one whose url matches best, as the pages of committees are also
        // pages of a board
        $best = 0;
        foreach ($groups as $group) {
            foreach ($group['items'] as $item) {
                $best = max($best, $item['match']);
            }
        }

        $groups = array_map(function (array $group) use ($best) : array {
            $group['items'] = array_map(function (array $item) use ($best) : array {
                $item['active'] = $best > 0 && $item['match'] === $best;

                return $item;
            }, $group['items']);

            // A section goes to its first page and is active on any of its pages
            $group['href'] = $group['items'][0]['href'] ?? null;
            $group['active'] = in_array(true, array_column($group['items'], 'active'), true);

            return $group;
        }, $groups);

        $this->groups = array_values(array_filter(
            $groups,
            fn (array $group) : bool => count($group['items']) > 0
        ));

        // On narrow screens the sections on the dashboard are in this order, not in columns
        foreach ($this->groups as $position => &$group) {
            $group['position'] = $position;
        }
        unset($group);

        $this->currentGroup = collect($this->groups)->firstWhere('active', true);
    }

    /**
     * The sections divided over three columns: the first three sections (see
     * config/francken/navigation.php) are at the top of the columns, and the others are
     * below them, keeping their order, such that the longest column is as short as possible
     * (a section with its heading counts as its pages plus one)
     */
    public function columns() : array
    {
        $tops = array_slice($this->groups, 0, 3);
        $others = array_slice($this->groups, 3);
        if ($tops === []) {
            return [];
        }
        $weight = fn (array $group) : int => count($group['items']) + 1;

        // Try every way of putting the other sections in the columns, there are only a few
        $best = [];
        $shortest = PHP_INT_MAX;
        for ($way = 0; $way < 3 ** count($others); $way++) {
            $columns = array_map(fn (array $group) : array => [$group], $tops);
            $code = $way;
            foreach ($others as $group) {
                $columns[$code % 3][] = $group;
                $code = intdiv($code, 3);
            }

            $longest = max(array_map(
                fn (array $column) : int => array_sum(array_map($weight, $column)),
                $columns
            ));
            if ($longest < $shortest) {
                $shortest = $longest;
                $best = $columns;
            }
        }

        return $best;
    }

    public function render() : View
    {
        return view('admin.components.navigation');
    }

    /**
     * How well the url of the request matches one of the paths (the length of the longest
     * path it is on or below), or 0 when it matches none
     */
    private function match(Request $request, array $paths) : int
    {
        $lengths = array_map(
            fn (string $path) : int => $request->is($path, $path . '/*') ? strlen($path) : 0,
            $paths
        );

        return max([0, ...$lengths]);
    }
}
