@extends('profile.layout')

@section('content')
    <section class="profile-panel profile-form">
        <header class="profile-panel__header">
            <h2 class="profile-panel__title">
                <i class="fas fa-book-open" aria-hidden="true"></i>
                Francken Vrij subscription
            </h2>
        </header>

        {!!
               Form::model(
                   $member, [
                   'url' => action([\Francken\Association\Members\Http\FranckenVrijSubscriptionController::class, 'update']),
               ])
        !!}
            @method('PUT')

            @if ($subscription->subscription_ends_at !== null)
                <p>
                    Your subscription to the printed Francken Vrij ends {{ (new \Carbon\Carbon($subscription->subscription_ends_at))->diffForHumans() }} ({{ $subscription->subscription_ends_at->format('j F Y') }}).
                </p>

                <x-forms.select
                    name="subsription_ends_at"
                    label="Receive new printed editions of the Francken Vrij until"
                    placeholder=""
                    :options="$extensionOptions"
                    :value="'September ' . $subscription->subscription_ends_at->format('Y')"
                />
            @else
                <p>
                    You don't have a subscription to the <strong>printed</strong> Francken Vrij at the moment.
                </p>

                <x-forms.select
                    name="subsription_ends_at"
                    label="Receive new printed editions of the Francken Vrij until"
                    placeholder=""
                    :options="$extensionOptions"
                />
            @endif

            <p class="mb-2">
                @if ($subscription->subscription_ends_at !== null)
                    We send the Francken Vrij to this address:
                @else
                    Once you subscribe, we will send new editions of the Francken Vrij to this address:
                @endif
            </p>
            <div class="profile-form__address">
                <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                {{ $member->address->toString() }}
                <a href="{{ action([\Francken\Association\Members\Http\ContactDetailsController::class, 'index']) }}">Change</a>
            </div>

            <x-forms.checkbox
                name="send_expiration_notification"
                label="Email me when my subscription ends"
                :value="$subscription->send_expiration_notification"
            />

            <div class="profile-form__actions">
                @if ($subscription->subscription_ends_at !== null)
                    <x-forms.submit class="btn home-btn home-btn--crimson">Update subscription</x-forms.submit>
                @else
                    <x-forms.submit class="btn home-btn home-btn--crimson">Subscribe</x-forms.submit>
                @endif
            </div>
        {!! Form::close() !!}
    </section>

    <section class="profile-panel profile-panel--note">
        <h3 class="profile-form__heading mt-0">Why does my subscription end?</h3>
        <p class="mb-0">
            We want everyone to be able to enjoy the Francken Vrij, but we also want to use less paper.
            By renewing your subscription now and then, we make sure we don't send printed copies to old addresses or to members who no longer want them.
            Every edition can also be read online on the <a href="/association/francken-vrij">Francken Vrij page</a>.
        </p>
    </section>
@endsection
