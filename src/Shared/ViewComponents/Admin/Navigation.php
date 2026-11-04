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

        $groups = array_map(
            function (array $group) use ($account, $request) : array {
                $items = array_filter(
                    $group['items'],
                    // Pages that don't work yet are only shown to super admins
                    fn (array $item) : bool => isset($item['works']) && ! $item['works']
                        ? $account->can('super-admin-read')
                        : $account->can($item['can'] ?? 'can-access-dashboard')
                );

                $group['items'] = array_map(function (array $item) use ($group, $request) : array {
                    $path = 'admin/' . $group['url'] . '/' . $item['url'];
                    $item['href'] = '/' . $path;
                    $item['match'] = $this->match($request, [$path, ...($item['also'] ?? [])]);

                    return $item;
                }, array_values($items));

                return $group;
            },
            config('francken.navigation.admin-menu')
        );

        // The page is the one whose url matches best, as the pages of committees are also
        // pages of a board
        $best = max([0, ...array_merge(...array_map(
            fn (array $group) : array => array_column($group['items'], 'match'),
            $groups
        ))]);

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

        $this->currentGroup = collect($this->groups)->firstWhere('active', true);
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

    /**
     * The sections divided over three columns of about the same length (a section with
     * its heading counts as its pages plus one), keeping their order
     */
    public function columns() : array
    {
        $weights = array_map(fn (array $group) : int => count($group['items']) + 1, $this->groups);
        $target = (int) ceil(array_sum($weights) / 3);

        $columns = [[]];
        $length = 0;
        foreach ($this->groups as $index => $group) {
            $last = count($columns) - 1;
            if ($columns[$last] !== [] && $length + $weights[$index] > $target && count($columns) < 3) {
                $columns[] = [];
                $last++;
                $length = 0;
            }
            $columns[$last][] = $group;
            $length += $weights[$index];
        }

        return $columns;
    }

    public function render() : View
    {
        return view('admin.components.navigation');
    }
}
