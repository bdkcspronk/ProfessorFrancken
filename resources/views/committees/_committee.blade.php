<div class="col-sm-6 col-lg-4 mb-4">
    <a href="{{ $link }}" class="committee-tile">
        <div class="committee-tile__logo">
            @if (trim($logo) != '')
                <img
                    alt=""
                    src="{{ image(trim($logo), ['height' => 200, 'width' => 300, 'resize' => true, 'background' => '255,255,255']) }}"
                />
            @else
                <i class="fas fa-users" aria-hidden="true"></i>
            @endif
        </div>
        <h2 class="committee-tile__name">
            {{ $name }}
        </h2>
    </a>
</div>
