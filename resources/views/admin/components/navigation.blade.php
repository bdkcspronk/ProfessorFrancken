{{--
    The pages of the admin, grouped by section: as tabs in the header and below it, and as
    cards on the dashboard, see Francken\Shared\ViewComponents\Admin\Navigation
--}}
@switch ($variant)
    @case('sections')
        <nav class="admin-tabs" aria-label="Sections of the admin">
            <a href="/admin" @class(['admin-tabs__tab', 'is-active' => request()->is('admin', 'admin/dashboard/*')])>
                <i class="fas fa-th-large" aria-hidden="true"></i>
                Overview
            </a>
            @foreach ($groups as $group)
                <a
                    href="{{ $group['href'] }}"
                    @class(['admin-tabs__tab', 'is-active' => $group['active']])
                    @if ($group['active']) aria-current="true" @endif
                >{{ $group['name'] }}</a>
            @endforeach
        </nav>
        @break

    @case('section-pages')
        @if ($currentGroup !== null)
            <nav class="admin-subtabs" aria-label="Pages of {{ $currentGroup['name'] }}">
                <div class="admin-subtabs__list">
                    @foreach ($currentGroup['items'] as $item)
                        <a
                            href="{{ $item['href'] }}"
                            @class(['admin-subtabs__tab', 'is-active' => $item['active']])
                            @if ($item['active']) aria-current="page" @endif
                        >
                            <i class="{{ $item['icon'] ?? 'fas fa-circle' }} fa-fw" aria-hidden="true"></i>
                            {{ $item['name'] }}
                        </a>
                    @endforeach
                </div>
            </nav>
        @endif
        @break

    @default
        <div class="admin-pages">
            @foreach ($columns() as $column)
                <div class="admin-pages__column">
                    @foreach ($column as $group)
                        <section class="admin-pages__group" style="order: {{ $group['position'] }}">
                            <h2 class="admin-pages__heading">{{ $group['name'] }}</h2>
                            <ul class="admin-pages__list">
                                @foreach ($group['items'] as $item)
                                    <li>
                                        <a href="{{ $item['href'] }}" class="admin-pages__link">
                                            <i class="{{ $item['icon'] ?? 'fas fa-circle' }} fa-fw admin-pages__icon" aria-hidden="true"></i>
                                            <span>
                                                <span class="admin-pages__name">
                                                    {{ $item['name'] }}
                                                    @if (isset($item['works']) && ! $item['works'])
                                                        <small class="admin-pages__unfinished">(not finished)</small>
                                                    @endif
                                                </span>
                                                @if (($item['description'] ?? '') !== '')
                                                    <small class="admin-pages__description">{{ $item['description'] }}</small>
                                                @endif
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endforeach
                </div>
            @endforeach
        </div>
@endswitch
