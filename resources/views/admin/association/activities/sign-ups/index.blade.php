<ul class="list-unstyled mb-0">
    @foreach ($activity->placed_sign_ups as $signUp)
        @include('admin.association.activities.sign-ups._sign-up')
    @endforeach
</ul>

@if ($activity->waiting_list_sign_ups->isNotEmpty())
    <h5 class="mt-4 mb-1 font-weight-bold">
        Waiting list
        <small class="text-muted">({{ $activity->waiting_list_sign_ups->count() }})</small>
    </h5>
    <p class="text-muted mb-0">
        When someone cancels, or when you raise the max sign ups, the first ones on the waiting list are offered a place, which they (or you) have to accept. To give a place to someone right away, use “Replace with someone from the waiting list” on a member above.
    </p>
    <ol class="list-unstyled mb-0">
        @foreach ($activity->waiting_list_sign_ups as $signUp)
            @include('admin.association.activities.sign-ups._sign-up', ['position' => $loop->iteration])
        @endforeach
    </ol>
@endif

<div class="d-flex justify-content-between">
    <a class="btn btn-text text-primary"
        href="{{  action(
                      [\Francken\Association\Activities\Http\AdminSignUpsController::class, 'create'],
                      ['activity' => $activity]
                      )
              }}"
    >
        <i class="fas fa-plus"></i>
        Add sign up
    </a>
</div>

