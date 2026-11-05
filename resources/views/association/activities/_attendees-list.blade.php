{{-- The members that signed up for an activity, either those with a place or those on the waiting list --}}
<ul class="activity-attendees__list">
    @foreach ($signUps as $signUp)
        <li>
            <span>
                @if ($numbered ?? false)
                    <span class="activity-attendees__position">{{ $loop->iteration }}.</span>
                @else
                    <i class="fas fa-user" aria-hidden="true"></i>
                @endif
                {{ $signUp->member->fullname }}
                @if ($signUp->plus_ones > 0)
                    (+{{ $signUp->plus_ones }})
                @endif
                @if ($signUp->is_offered)
                    <small class="activity-attendees__pending" title="A place opened up for this member, which still has to be accepted">not yet accepted</small>
                @endif
            </span>
            @can('update', $signUp)
                <a class="home-link" href="{{ action([\Francken\Association\Activities\Http\SignUpsController::class, 'edit'], ['activity' => $activity, 'sign_up' => $signUp]) }}">
                    <i class="fas fa-edit" aria-hidden="true"></i> Edit
                </a>
            @endcan
        </li>
    @endforeach
</ul>
