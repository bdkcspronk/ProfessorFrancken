@extends('profile.layout')

@section('content')
    <section class="profile-panel profile-form">
        <header class="profile-panel__header">
            <h2 class="profile-panel__title">
                <i class="fas fa-money-check-alt" aria-hidden="true"></i>
                Change payment details
            </h2>
        </header>

        {!!
               Form::model(
                   $member, [
                       'url' => action([\Francken\Association\Members\Http\PaymentDetailsController::class, 'update']),
               ])
        !!}
            @method('PUT')

            <x-forms.text
                name="iban"
                label="International Bank Account Number (IBAN)"
                help="We will use your IBAN to automatically withdraw your expenses."
                :value="$member->payment_details->iban()"
            />

            <x-forms.checkbox
                name="deduct_additional_costs"
                label="Allow deducting additional costs for buying food & drinks and attending activities"
                :value="$member->payment_details->deductAdditionalCosts()"
            />

            <div class="profile-form__actions">
                <x-forms.submit class="btn home-btn home-btn--crimson">Save</x-forms.submit>
                <a href="/profile" class="profile-form__cancel">Cancel</a>
            </div>
        {!! Form::close() !!}
    </section>
@endsection
