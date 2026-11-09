@extends('admin.layout')
@section('page-title', 'Accounts')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <p>
                        Here you can see the currently activated accounts.
                        An account belongs to a member and can be used to do stuff on our website.
                        You can view an account to manually add permissions and roles, however it is unlikely that this is necessary as an account roles will be automatically get roles based on the committees its member is in.
                    </p>

                    <form
                        action="{{ action([\Francken\Auth\Http\Controllers\Admin\AccountsController::class, 'index']) }}"
                        method="GET"
                        class="d-flex align-items-center flex-wrap"
                        style="gap: 0.5rem"
                    >
                        <input type="hidden" name="sort" value="{{ $sort }}">
                        <input type="hidden" name="direction" value="{{ $direction }}">
                        <label for="search" class="sr-only">Search accounts</label>
                        <input
                            type="search"
                            name="search"
                            id="search"
                            value="{{ $search }}"
                            class="form-control"
                            style="max-width: 24rem"
                            placeholder="Email, name or member id"
                        >
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            Search
                        </button>
                        @if ($search !== '')
                            <a href="{{ action([\Francken\Auth\Http\Controllers\Admin\AccountsController::class, 'index']) }}" class="btn btn-text text-primary">
                                <i class="fas fa-times"></i>
                                Clear
                            </a>
                        @endif
                    </form>
                </div>

                <table class="table table-hover table-small">
                    <thead>
                        <tr>
                            @include('admin.compucie.accounts._sortable-header', ['column' => 'email', 'label' => 'Email'])
                            @include('admin.compucie.accounts._sortable-header', ['column' => 'member', 'label' => 'Member'])
                            @include('admin.compucie.accounts._sortable-header', ['column' => 'member_id', 'label' => 'Member id'])
                            @include('admin.compucie.accounts._sortable-header', ['column' => 'roles', 'label' => 'Nr. roles'])
                            @include('admin.compucie.accounts._sortable-header', ['column' => 'permissions', 'label' => 'Nr. permissions'])
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accounts as $account)
                            <tr>
                                <td>
                                    <a href="{{ action([\Francken\Auth\Http\Controllers\Admin\AccountsController::class, 'show'], $account->id)}}">
                                        {{ $account->email }}
                                    </a>
                                </td>
                                <td>
                                    {{ $account->member?->fullname }}
                                </td>
                                <td>
                                    {{ $account->member_id }}
                                </td>
                                <td>
                                    {{ $account->roles_count }}
                                </td>
                                <td>
                                    {{ $account->permissions_count }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No accounts found{{ $search !== '' ? ' for "' . $search . '"' : '' }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $accounts->links() }}
            </div>
        </div>
    </div>
@endsection

@section('actions')
    <div class="d-flex align-items-end">
        <a
            class="btn btn-primary btn-sm"
            href="{{ action([\Francken\Auth\Http\Controllers\Admin\AccountsController::class, 'create']) }}"
        >
            <i class="fas fa-plus fa-fw"></i>
            Activate a new account
        </a>
    </div>
@endsection
