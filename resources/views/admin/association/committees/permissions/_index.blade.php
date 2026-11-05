<div class="card mt-4">
    <div class="card-body">
        <h4 class="font-weight-bold">
            <i class="fas fa-key"></i>
            Website Permissions
        </h4>
        @if ($committee->permissions->isEmpty())
            <p class="small text-muted mb-0">
                Members of this committee don't get any extra permissions on the website (role "{{ $committee->role->name }}").
            </p>
        @else
            <p>
                Members installed to this committee are given the following permissions, based on the role "{{ $committee->role->name }}".
            </p>
            <ul>
                @foreach ($committee->permissions as $permission)
                    <li>{{ $permission->name }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
