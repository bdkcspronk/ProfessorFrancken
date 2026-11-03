<h2 class="home-heading committee-members__heading">
    Committee members
</h2>

<ul class="committee-members">
    @foreach ($members as $member)
        <li class="committee-members__member">
            <i class="fas fa-user" aria-hidden="true"></i>
            {{ $member->member->fullname }}
        </li>
    @endforeach
</ul>
