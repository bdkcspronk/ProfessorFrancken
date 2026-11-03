@extends('profile.layout')

@section('content')
    <section class="profile-panel profile-form">
        <header class="profile-panel__header">
            <h2 class="profile-panel__title">
                <i class="fas fa-address-card" aria-hidden="true"></i>
                Change contact details
            </h2>
        </header>

        {!!
               Form::model(
                   $member, [
                   'url' => action([\Francken\Association\Members\Http\ContactDetailsController::class, 'update']),
               ])
        !!}
            @method('PUT')

            <x-forms.email name="email" label="Email" :value="$member->email->toString()" />

            <x-forms.text name="phone_number" label="Phone number" placeholder="+31 50 363 4978" />

            <x-forms.checkbox
                name="newsletter"
                label="Receive the bi-weekly newsletter"
                :value="$member->mailinglist_email"
            />

            <h3 class="profile-form__heading">Address</h3>

            <x-forms.text
                name="address"
                label="Street and number"
                placeholder="Nijenborgh 9"
                :value="$member->address->address()"
            />

            <div class="row">
                <div class="col-sm-6">
                    <x-forms.text
                        name="postal_code"
                        label="Postal code"
                        placeholder="9742 AG"
                        :value="$member->address->postalCode()"
                    />
                </div>

                <div class="col-sm-6">
                    <x-forms.text
                        name="city"
                        label="City"
                        placeholder="Groningen"
                        :value="$member->address->city()"
                    />
                </div>
            </div>

            <x-forms.text
                name="country"
                label="Country"
                placeholder="Netherlands"
                :value="$member->address->country()"
            />

            <div class="profile-form__actions">
                <x-forms.submit class="btn home-btn home-btn--crimson">Save</x-forms.submit>
                <a href="/profile" class="profile-form__cancel">Cancel</a>
            </div>
        {!! Form::close() !!}
    </section>
@endsection
