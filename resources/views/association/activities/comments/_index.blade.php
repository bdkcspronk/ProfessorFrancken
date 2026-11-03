<div class="activity-comments">
    <h2 class="home-heading">
        Comments
    </h2>

    @if ($activity->comments->isEmpty())
        <p class="text-muted">No comments yet.</p>
    @endif

    <ul class="list-unstyled">
        @foreach ($activity->comments as $comment)
            <li class="activity-comment">
                <div class="d-flex justify-content-between">
                    <h3 class="activity-comment__author">
                        {{ $comment->member->fullname }}

                        <small class="text-muted mx-1">
                            •
                        </small>
                        <small class="text-muted">
                            {{ $comment->created_at->diffForHumans() }}
                        </small>
                    </h3>
                    @can('update', $comment)
                    <div>
                        <a
                            class="home-link"
                            href="{{ action(
                                         [\Francken\Association\Activities\Http\CommentsController::class, 'edit'],
                                         ['activity' => $activity, 'comment' => $comment])
                                  }}"
                        >
                            <i class="fas fa-edit"></i>
                            Edit
                        </a>
                    </div>
                    @endcan
                </div>
                <p class="mb-0">
                    {{ $comment->content }}
                </p>
            </li>
        @endforeach
    </ul>

    @include('association.activities.comments._create')
</div>
