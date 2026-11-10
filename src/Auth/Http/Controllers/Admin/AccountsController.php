<?php

declare(strict_types=1);

namespace Francken\Auth\Http\Controllers\Admin;

use Francken\Association\LegacyMember;
use Francken\Auth\Account;
use Francken\Auth\Permission;
use Francken\Auth\Role;
use Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Webmozart\Assert\Assert;

final class AccountsController
{
    /**
     * The columns by which the accounts can be sorted
     *
     * @var string[]
     */
    private const SORTABLE = ['email', 'member', 'member_id', 'roles', 'permissions'];

    public function index(Request $request) : View
    {
        $search = trim($request->string('search')->toString());
        $sort = in_array($request->input('sort'), self::SORTABLE, true) ? $request->string('sort')->toString() : 'email';
        $direction = $request->input('direction') === 'desc' ? 'desc' : 'asc';

        $accounts = Account::with('member')
            ->withCount(['roles', 'permissions'])
            ->when($search !== '', function (Builder $query) use ($search) : void {
                $query->where(function (Builder $query) use ($search) : void {
                    $query->where('email', 'like', '%' . $search . '%')
                        ->orWhereIn('member_id', $this->idsOfMembersNamed($search));

                    if (ctype_digit($search)) {
                        $query->orWhere('member_id', (int) $search);
                    }
                });
            })
            ->when(
                $sort === 'member',
                function (Builder $query) use ($direction) : void {
                    $this->orderByMemberName($query, $direction);
                },
                function (Builder $query) use ($sort, $direction) : void {
                    $query->orderBy(match ($sort) {
                        'roles' => 'roles_count',
                        'permissions' => 'permissions_count',
                        default => $sort,
                    }, $direction);
                }
            )
            ->orderBy('email')
            ->paginate(30)
            ->withQueryString();

        return view('admin.compucie.accounts.index', [
            'accounts' => $accounts,
            'search' => $search,
            'sort' => $sort,
            'direction' => $direction,
            'breadcrumbs' => [
                ['url' => action([static::class, 'index']), 'text' => 'Accounts'],
            ]
        ]);
    }

    public function show(Account $account) : View
    {
        $roles = Role::where('guard_name', 'web')->get();
        $permissions = Permission::where('guard_name', 'web')->get();

        return view('admin.compucie.accounts.show', [
            'account' => $account,
            'permissions' => $permissions,
            'roles' => $roles,
            'breadcrumbs' => [
                ['url' => action([static::class, 'index']), 'text' => 'Accounts'],
                ['url' => action([static::class, 'show'], $account->id), 'text' => $account->email],
            ]
        ]);
    }

    public function create() : View
    {
        return view('admin.compucie.accounts.create', [
            'account' => new Account(),
            'members' => LegacyMember::autocomplete(Account::pluck('member_id')),
        ]);
    }

    public function store(Request $request) : RedirectResponse
    {
        $request->validate([
            'member_id' => ['required', 'integer', 'exists:francken-legacy.leden,id'],
        ]);

        $memberId = (int)$request->input('member_id');

        $member = LegacyMember::query()
            ->where('is_lid', true)
            ->where('emailadres', '<>', '')
            ->findOrFail($memberId);

        Assert::notNull($member->emailadres);

        Account::activate($memberId, $member->emailadres, Hash::make(Str::random(32)));

        return redirect()->action([
            self::class,
            'index'
        ]);
    }

    /**
     * The ids of the members whose name contains every word of the search, as the members are
     * in another database than the accounts
     *
     * @return int[]
     */
    private function idsOfMembersNamed(string $search) : array
    {
        $words = preg_split('/\s+/', $search) ?: [];

        return (new LegacyMember())->newQuery()
            ->where(function (Builder $query) use ($words) : void {
                foreach ($words as $word) {
                    $query->where(function (Builder $query) use ($word) : void {
                        $query->where('voornaam', 'like', '%' . $word . '%')
                            ->orWhere('tussenvoegsel', 'like', '%' . $word . '%')
                            ->orWhere('achternaam', 'like', '%' . $word . '%');
                    });
                }
            })
            ->pluck('id')
            ->map(fn ($id) : int => is_numeric($id) ? (int) $id : 0)
            ->all();
    }

    /**
     * Order the accounts by the surname and first name of their members, which are in another
     * database than the accounts, so the members are put in order first
     */
    private function orderByMemberName(Builder $query, string $direction) : void
    {
        $memberIds = (new LegacyMember())->newQuery()
            ->whereIn('id', Account::query()->pluck('member_id'))
            ->orderBy('achternaam', $direction)
            ->orderBy('voornaam', $direction)
            ->pluck('id')
            ->map(fn ($id) : int => is_numeric($id) ? (int) $id : 0);

        if ($memberIds->isEmpty()) {
            return;
        }

        $cases = $memberIds->map(fn (int $id, int $position) : string => "WHEN {$id} THEN {$position}")->implode(' ');

        $query->orderByRaw("CASE member_id {$cases} ELSE {$memberIds->count()} END");
    }
}
