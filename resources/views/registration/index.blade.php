@extends('layout.one-column-layout')

@section('page-title')
    Register to be a Francken member
@endsection

@section('content')
    <div class="page-intro">
        <p>
            Are you studying in Groningen and do you want to have a good time? Join Francken!
            For only <strong>€5,- per year</strong> you get free <strong>coffee and tea</strong> in our members room in the Feringa Building, and you can join all our activities: a great way to get to know your fellow students.
        </p>
        <p>
            Already a member? <a href="{{ route('login') }}">Log in</a>.
        </p>
    </div>

    @if ($errors->any())
        <div class="register-errors" role="alert">
            <h2 class="register-errors__title">
                <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                Some details are missing or not right
            </h2>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {!!
       Form::open(
           [
               'action' => [[\Francken\Association\Members\Http\Controllers\RegistrationController::class, 'store']],
               'class' => 'register-form',
           ]
       )
    !!}

    @foreach ([
        ['icon' => 'fas fa-address-book', 'title' => 'Personal details', 'partial' => 'registration._personal-details'],
        ['icon' => 'fas fa-home', 'title' => 'Contact details', 'partial' => 'registration._contact-details'],
        ['icon' => 'fas fa-graduation-cap', 'title' => 'Study details', 'partial' => 'registration._study-details'],
        ['icon' => 'fas fa-money-check-alt', 'title' => 'Billing details', 'partial' => 'registration._billing-details'],
    ] as $step)
        <fieldset class="register-step">
            <legend class="register-step__legend">
                <span class="register-step__number">{{ $loop->iteration }}</span>
                <span>
                    <span class="register-step__count">Step {{ $loop->iteration }} of 4</span>
                    {{ $step['title'] }}
                </span>
            </legend>

            @include($step['partial'])
        </fieldset>
    @endforeach

    <fieldset class="register-step register-step--extra">
        <legend class="register-step__legend">
            <span class="register-step__number"><i class="fas fa-users" aria-hidden="true"></i></span>
            <span>
                <span class="register-step__count">Optional</span>
                Become an active member
            </span>
        </legend>

        <h4>Would you like to join a committee?</h4>
        <p>
            Many of our activities are organized by members in committees.
            Joining a committee is a good way to get to know your fellow students, and it looks good on your resume too.
            You can read more about each committee on our
            <a href="{{ action([\Francken\Association\Committees\Http\RedirectToBoardCommitteesController::class, 'index']) }}">committees page</a>.
        </p>

        <x-forms.checkbox
            name="wants_to_join_a_committee"
            label="Yes, I would like to join a committee!"
        />

        <h4 class="mt-4">Do you have any comments or questions? Let us know!</h4>

        <x-forms.textarea
            name="comments"
            rows="4"
            placeholder="Your comments or questions"
        />
    </fieldset>

    <div class="register-submit">
        <p>
            By registering you agree to the <a href="/privacy-policy">privacy policy</a> of T.F.V. ‘Professor Francken’.
        </p>
        <x-forms.submit class="btn home-btn home-btn--crimson">Register</x-forms.submit>
    </div>

    {!! Form::close() !!}

<script>
 var addStudy = document.querySelector('#addAdditionalStudy');
 var studies = document.querySelector('.studies');

 function newStudyHtml()
 {
     return `<div class="form-group row">
                    <div class="col-sm-3">
                        <label for="study">Study</label>
                        <input placeholder="BSc Applied Physics" class="form-control" name="study_name[]" type="text" required>
                    </div>
                    <div class="col-sm-3">
                        <label for="starting-date-study">Starting date</label>
                        <input placeholder="yyyy-mm" class="form-control" name="study_starting_date[]" type="month" required>
                    </div>
                    <div class="col-sm-3">
                        <label for="starting-date-study">Graduation date (optional)</label>
                        <input placeholder="yyyy-mm" class="form-control" name="study_graduation_date[]" type="month">
                    </div>
                    <div class="col-sm-3 d-flex">
                        <button class="remove-study btn btn-secondary align-self-end">
                            <i class="fa fa-times" aria-hidden="true"></i>
                            Cancel
                        </button>
                    </div>
                </div>`;
 }

 addStudy.addEventListener('click', function(e) {
     e.preventDefault();
     e.stopPropagation();

     var li = document.createElement("li");
     li.innerHTML = newStudyHtml();
     var button = li.querySelector('.remove-study');
     button.addEventListener('click', function(btnEvent) {
         btnEvent.preventDefault();
         btnEvent.stopPropagation();
         li.parentNode.removeChild(li);
     });

     studies.appendChild(li);

     var study = li.querySelector('input[name="study_name[]"]');
     study.focus();
 });

</script>


@endsection
