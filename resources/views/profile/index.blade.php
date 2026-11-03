@extends('profile.layout')

@section('content')
    <div class="profile-header" data-fcc-lattice>
        <span class="profile-header__avatar" aria-hidden="true">
            {{ mb_strtoupper(mb_substr($member->firstname, 0, 1) . mb_substr($member->achternaam ?? '', 0, 1)) }}
        </span>
        <div>
            <h2 class="profile-header__name">{{ $member->fullname }}</h2>
            <ul class="profile-header__facts">
                <li>{{ $member->initials }} {{ $member->surname }}</li>
                @if ($member->birthdate !== null)
                    <li>
                        <i class="fas fa-birthday-cake" aria-hidden="true"></i>
                        {{ $member->birthdate->format('j F Y') }}
                    </li>
                @endif
                @if ($member->gender === \Francken\Association\Members\Gender::FEMALE)
                    <li><i class="fas fa-venus" aria-hidden="true"></i> Female</li>
                @elseif ($member->gender === \Francken\Association\Members\Gender::MALE)
                    <li><i class="fas fa-mars" aria-hidden="true"></i> Male</li>
                @elseif ($member->gender)
                    <li>{{ $member->gender }}</li>
                @endif
            </ul>
        </div>
    </div>

    <section class="profile-panel">
        <header class="profile-panel__header">
            <h2 class="profile-panel__title">
                <i class="fas fa-address-card" aria-hidden="true"></i>
                Contact details
            </h2>
            <a href="{{ action([\Francken\Association\Members\Http\ContactDetailsController::class, 'index']) }}" class="profile-panel__edit">
                <i class="fas fa-edit" aria-hidden="true"></i>
                Edit
            </a>
        </header>
        <dl class="profile-details">
            <dt>Email</dt>
            <dd>{{ $member->email }}</dd>
            @if ($member->address)
                <dt>Address</dt>
                <dd>
                    <address class="mb-0">
                        {{ $member->address->address() }}<br/>
                        {{ $member->address->postalCode() }} {{ $member->address->city() }}<br/>
                        {{ $member->address->country() }}
                    </address>
                </dd>
            @endif
            @if ($member->phone_number !== '')
                <dt>Phone number</dt>
                <dd>{{ $member->phone_number }}</dd>
            @endif
        </dl>
    </section>

    <section class="profile-panel">
        <header class="profile-panel__header">
            <h2 class="profile-panel__title">
                <i class="fas fa-graduation-cap" aria-hidden="true"></i>
                Studies
            </h2>
        </header>
        <dl class="profile-details">
            <dt>Student number</dt>
            <dd>{{ $member->student_number }}</dd>
        </dl>
        <ul class="profile-list">
            @foreach ($member->student->studies() as $study)
                <li class="profile-list__item">
                    <strong>{{ $study }}</strong>
                    <span class="profile-list__meta">{{ $study->startYear() }} – {{ $study->endYear() }}</span>
                </li>
            @endforeach
        </ul>
    </section>

    <section class="profile-panel">
        <header class="profile-panel__header">
            <h2 class="profile-panel__title">
                <i class="fas fa-money-check-alt" aria-hidden="true"></i>
                Finances
            </h2>
            <a href="{{ action([\Francken\Association\Members\Http\PaymentDetailsController::class, 'index']) }}" class="profile-panel__edit">
                <i class="fas fa-edit" aria-hidden="true"></i>
                Edit
            </a>
        </header>
        <p>
            You have authorised T.F.V. ‘Professor Francken’ to deduct the following costs from {{ $member->payment_details->maskedIban() }}:
        </p>
        <ul class="profile-checks">
            @if (! $member->gratis_lidmaatschap)
                <li>
                    <i class="fas fa-check-square" aria-hidden="true"></i>
                    Membership (€5,- per year)
                </li>
            @endif
            <li @class(['is-unchecked' => ! $member->payment_details->deductAdditionalCosts()])>
                <i class="{{ $member->payment_details->deductAdditionalCosts() ? 'fas fa-check-square' : 'far fa-square' }}" aria-hidden="true"></i>
                Food, drinks and the costs of other activities of the association
            </li>
        </ul>
    </section>

    <section class="profile-panel">
        <header class="profile-panel__header">
            <h2 class="profile-panel__title">
                <i class="fas fa-key" aria-hidden="true"></i>
                Password
            </h2>
            <a href="{{ action([\Francken\Association\Members\Http\PasswordController::class, 'index']) }}" class="profile-panel__edit">
                <i class="fas fa-edit" aria-hidden="true"></i>
                Change
            </a>
        </header>
        <p class="mb-0">
            You can <a href="{{ action([\Francken\Association\Members\Http\PasswordController::class, 'index']) }}">change your password</a> at any time.
        </p>
    </section>

    @if ($committees->isNotEmpty())
        <section class="profile-panel">
            <header class="profile-panel__header">
                <h2 class="profile-panel__title">
                    <i class="fas fa-users" aria-hidden="true"></i>
                    Committees
                </h2>
            </header>
            <ul class="list-unstyled mb-0">
                @foreach ($committees as $committee)
                    <li>
                        <a
                            class="committee-nav__link"
                            href="{{ action(
                                [\Francken\Association\Committees\Http\CommitteesController::class, 'show'],
                                ['board' => $committee->board, 'committee' => $committee]
                            ) }}"
                        >
                            @if (! is_null($committee->logo))
                                <img
                                    class="committee-nav__logo"
                                    src="{{ image($committee->logo, ['height' => 75, 'width' => 75, 'background' => '255,255,255']) }}"
                                    alt=""
                                >
                            @else
                                <span class="committee-nav__logo committee-nav__logo--empty" aria-hidden="true">
                                    <i class="fas fa-users"></i>
                                </span>
                            @endif
                            <span>
                                {{ $committee->name }}
                                <span class="profile-list__meta d-block">
                                    {{ $committee->board->board_name->toString() }}
                                    ({{ $committee->board->board_year->toString() }})
                                </span>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif
@endsection
