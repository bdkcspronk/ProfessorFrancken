@php
$menu = [
    ['url' => '/profile', 'icon' => 'fas fa-user', 'text' => 'Profile'],
    ['url' => '/profile/expenses', 'icon' => 'fas fa-chart-bar', 'text' => 'Expenses'],
    ['url' => action([\Francken\Association\Members\Http\ProfileActivitiesController::class, 'index']), 'icon' => 'fas fa-calendar', 'text' => 'Activities'],
    ['url' => action([\Francken\Association\Members\Http\FranckenVrijSubscriptionController::class, 'index']), 'icon' => 'fas fa-book-open', 'text' => 'Francken Vrij'],
];

if (Auth::user()->can('can-access-dashboard')) {
    $menu[] = ['url' => '/admin', 'icon' => 'fas fa-database', 'text' => 'Admin'];
}

$menu[] = ['url' => '/logout', 'icon' => 'fas fa-sign-out-alt', 'text' => 'Logout'];

// The profile is active on its own pages that are not in the menu, such as the password
$active = collect($menu)->pluck('url')
    ->map(fn ($url) => parse_url($url, PHP_URL_PATH))
    ->filter(fn ($path) => request()->is(ltrim($path, '/'), ltrim($path, '/') . '/*'))
    ->sortByDesc(fn ($path) => strlen($path))
    ->first();
@endphp
<nav class="committee-nav profile-nav">
    <h2 class="home-heading">{{ $member->fullname }}</h2>
    <ul class="list-unstyled mb-0">
        @foreach ($menu as $item)
            <li>
                <a
                    href="{{ $item['url'] }}"
                    @class(['committee-nav__link', 'is-active' => parse_url($item['url'], PHP_URL_PATH) === $active])
                >
                    <i class="{{ $item['icon'] }} fa-fw profile-nav__icon" aria-hidden="true"></i>
                    {{ $item['text'] }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
