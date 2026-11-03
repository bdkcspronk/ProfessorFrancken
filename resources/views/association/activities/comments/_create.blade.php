<div class='mt-3'>
    @can('create', \Francken\Association\Activities\Comment::class)
    <h3 class="activity-comment__author">Add a comment</h3>

    {!!
           Form::open([
               'url' => action(
                   [\Francken\Association\Activities\Http\CommentsController::class, 'store'],
                   ['activity' => $activity]
               ),
           ])
    !!}

    <x-forms.textarea name="content" label="Comment" rows="2" />

    <p class="mt-3">
        <button class="btn home-btn home-btn--crimson" type="submit">
            Comment
        </button>
    </p>

    {!! Form::close() !!}
    @endcan
</div>
