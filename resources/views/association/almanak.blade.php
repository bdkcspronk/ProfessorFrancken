@extends('pages.association')

@section('page-title')
    Almanak
@endsection

@push('scripts')
<script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="https://professorfrancken.nl/almanak/turn.min.js"></script>
@endpush

@section('main-content')
    <div class="container my-4">
        <div class="page-intro">
            <p>
                The almanak of T.F.V. ‘Professor Francken’, made by the Almanakcie.
                Turn the pages with the buttons below the book or the arrow keys, or download the whole almanak as a pdf.
            </p>
        </div>

        {{-- The pages are turned by turn.js, which gets the size of the book from its stage --}}
        <div class="almanak">
            <div class="almanak__stage">
                <div id="almanak" class="almanak__book">
                    @foreach ($pages as $page)
                        <div class="almanak__page" style="background-image:url('{{ $page }}');"></div>
                    @endforeach
                </div>
            </div>

            <div class="almanak__controls">
                <button class="btn home-btn home-btn--crimson" id="previous">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    <span class="d-none d-sm-inline">Previous page</span>
                </button>
                <span class="almanak__count" id="almanak-count" aria-live="polite"></span>
                <button class="btn home-btn home-btn--crimson" id="next">
                    <span class="d-none d-sm-inline">Next page</span>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </button>
            </div>

            <p class="almanak__download">
                <a href="{{ $download }}" class="home-link">
                    <i class="fas fa-file-download" aria-hidden="true"></i>
                    Download the almanak as a pdf
                </a>
            </p>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(window).ready(function() {
            var book = $('#almanak');
            var stage = $('.almanak__stage');

            // A page is 692 by 983 pixels; on narrow screens the book shows one page at a time
            var PAGE_RATIO = 692 / 983;
            var single = stage.width() < 640;
            var width = Math.min(stage.width(), 1000);
            var height = single ? width / PAGE_RATIO : width / (2 * PAGE_RATIO);

            book.turn({
                width: width,
                height: height,
                display: single ? 'single' : 'double',
                autoCenter: true,
                acceleration: true,
                elevation: 50,
                gradients: !$.isTouch,
            });

            function showCount() {
                $('#almanak-count').text('Page ' + book.turn('page') + ' of ' + book.turn('pages'));
            }
            book.bind('turned', showCount);
            showCount();
        });

        $(window).bind('keydown', function(e) {
            if (e.keyCode == 37) {
                $('#almanak').turn('previous');
                return false;
            } else if (e.keyCode == 39) {
                $('#almanak').turn('next');
                return false;
            }
        });

        $('#previous').click(function() {
            $('#almanak').turn('previous');
            return false;
        });

        $('#next').click(function() {
            $('#almanak').turn('next');
            return false;
        });
    </script>
@endpush
