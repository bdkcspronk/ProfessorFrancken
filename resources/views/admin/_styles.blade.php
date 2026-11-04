@unless(config('env') == 'testing')
    @vite(['resources/assets/sass/app.scss', 'resources/assets/css/vendor.css'])
@endunless

@stack('css')
