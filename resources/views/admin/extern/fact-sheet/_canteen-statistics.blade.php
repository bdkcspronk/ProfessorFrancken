<div class="card my-3">
    <div class="card-body">
        <h4 class="card-title">Active member statistics per {{ $period }}</h4>

        <p class="card-text">
            The number of members that bought one or more products from our "streep system" in each {{ $period === 'week' ? 'day' : 'week' }} of the {{ $period }}, and in the whole {{ $period }}.
        </p>

    </div>
    <table class="table table-hover mb-0">
        <thead class="thead-inverse">
            <tr>
                <th>{{ ucfirst($period) }}</th>
                @foreach ($weeklyStats[0]['stats'] as $statsOfDay)
                    @if ($loop->last)
                    <th class="text-right">
                    @else
                    <th class="text-left">
                    @endif
                        {{ $statsOfDay["day"] }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($weeklyStats as $stats)
                <tr>
                    <th>
                        {{ $stats['week'] }}
                    </th>
                    @foreach ($stats['stats'] as $statsOfDay)
                        @if ($loop->last)
                        <td class="text-right bg-primary text-white">
                        @else
                        <td class="text-left">
                        @endif
                            {{ $statsOfDay['members'] ?? '–' }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
