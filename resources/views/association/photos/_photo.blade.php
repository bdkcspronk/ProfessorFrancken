<li class="photo-wall__item">
    <a href="{{ $photo->src() }}" class="photo-wall__link">
        <img src="{{ $photo->src() }}" srcset="{{ $photo->srcset() }}" class="photo-wall__img" alt="" loading="lazy">
    </a>
</li>
