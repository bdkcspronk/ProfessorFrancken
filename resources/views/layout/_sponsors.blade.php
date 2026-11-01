<div class="sponsors text-center">
    <h5>The main sponsors of T.F.V. ‘Professor Francken’ are</h5>

    {{--
        The homepage shows the logos in rows of at most 3, spread evenly over the rows
        (4 logos: 2 + 2, 5: 3 + 2, 7: 3 + 2 + 2), using --sponsors-per-row
    --}}
    @php($sponsorsPerRow = count($footer) > 0 ? (int) ceil(count($footer) / ceil(count($footer) / 3)) : 1)
    <div class="row" style="--sponsors-per-row: {{ $sponsorsPerRow }}">
        @foreach ($footer as $company)
            <div @class([
                'my-3 col-6 col-sm-4 col-md-3 col-lg-2 col-lx-1',
                'sponsors__row-start' => $loop->index % $sponsorsPerRow === 0,
                'sponsors__row-end' => $loop->index % $sponsorsPerRow === $sponsorsPerRow - 1 || $loop->last,
            ])>
                <a href="{{ $company['footer-link'] }}" class="h-100 d-flex align-items-center">
                    <img
                        class="img-fluid"
                        src="{{ $company['footer-logo'] }}"
                        alt="Logo of {{ $company['name'] }}"
                    >
                    {{--
                        The homepage colors the logo with a mask instead of the image above.
                        Browsers only use masks from the same domain, so only use the path of
                        the logo (the logos are uploaded to the website itself)
                    --}}
                    <span
                        class="sponsors__logo"
                        style="--logo: url('{{ parse_url($company['footer-logo'], PHP_URL_PATH) }}')"
                        role="img"
                        aria-label="Logo of {{ $company['name'] }}"
                    ></span>
                </a>
            </div>
        @endforeach
    </div>

    <p>
        &copy; Copyright {{ date('Y') }}
    </p>
</div>
