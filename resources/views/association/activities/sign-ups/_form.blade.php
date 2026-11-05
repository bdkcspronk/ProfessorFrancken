{{-- The edit page shows the activity and its costs elsewhere, so it leaves out this header --}}
@if ($showHeader ?? true)
    <h5 class="d-flex justify-content-between">
        <span>
            Sign up for {{ $activity->name }}
        </span>

        @if (! $activity->signUpSettings->is_free)
            <small class="text-muted font-weight-light mt-2">
                Costs: <i class="fas fa-euro-sign"></i>
                {{ number_format($activity->signUpSettings->costs_per_person / 100, 2) }}
                per person
            </small>
        @endif
    </h5>
@endif

@if ($activity->signUpSettings->allows_plus_ones)
    @php
        $plusOnesHelp = $activity->signUpSettings->max_plus_ones_per_member !== null
            ? 'You can bring up to ' . $activity->signUpSettings->max_plus_ones_per_member . ' ' . \Illuminate\Support\Str::plural('person', $activity->signUpSettings->max_plus_ones_per_member) . '.'
            : '';

        // Tell new sign ups when bringing people would put them on the waiting list
        $maxSignUps = $activity->signUpSettings->max_sign_ups;
        if (($isNewSignUp ?? false) && $maxSignUps !== null && ! $activity->is_full) {
            $placesLeft = $maxSignUps - $activity->total_sign_ups;
            $plusOnesHelp .= ' There ' . ($placesLeft === 1 ? 'is 1 place' : 'are ' . $placesLeft . ' places') . ' left, including your own; if you bring more people than that, you go on the waiting list together.';
        }
    @endphp
    <x-forms.number
        name="plus_ones"
        label="Are you bringing anyone?"
        :help="trim($plusOnesHelp)"
    />
@endif

@if ($activity->signUpSettings->ask_for_dietary_wishes)
    <x-forms.text name="dietary_wishes" label="Dietary wishes" placeholder="Flexitarian" />
@endif

@if ($activity->signUpSettings->ask_for_drivers_license)
    <x-forms.checkbox name="has_drivers_license" label="Do you have a driver's license?" />
@endif
