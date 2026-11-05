{{-- An activity in the list of the admin, of which the whole row opens the activity --}}
<tr>
    <td class="text-nowrap">
        <div>{{ $activity->start_date->format('D j M Y') }}</div>
        <small class="text-muted">{{ $activity->start_date->format('H:i') }}</small>
    </td>
    <td>
        <a class="admin-row-link font-weight-bold" href="{{ action([\Francken\Association\Activities\Http\AdminActivitiesController::class, 'show'], ['activity' => $activity]) }}">
            {{ $activity->name }}
        </a>
        @if ($activity->location)
            <div class="text-muted small">
                <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                {{ $activity->location }}
            </div>
        @endif
    </td>
    <td class="text-right text-nowrap">
        @if ($activity->signUpSettings)
            <div>
                {{ $activity->total_sign_ups }}
                @if ($activity->signUpSettings->max_sign_ups)
                    / {{ $activity->signUpSettings->max_sign_ups }}
                @endif
                signed up
            </div>
            @if ($activity->waiting_list_sign_ups->isNotEmpty())
                <div class="text-muted">
                    + {{ $activity->waiting_list_sign_ups->count() }} on the waiting list
                </div>
            @endif
            <small class="text-muted">
                @if ($activity->signUpSettings->deadline_at->isFuture())
                    Closes {{ $activity->signUpSettings->deadline_at->diffForHumans() }}
                @else
                    Closed
                @endif
            </small>
        @else
            <span class="text-muted small">No sign-ups</span>
        @endif
    </td>
</tr>
