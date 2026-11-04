<script type="text/javascript">
 @if (config('francken.general.google-analytics'))
 // Google Analytics
 (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
     (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
 })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

 ga('create', '{{ config('francken.general.google-analytics') }}', 'auto');
 ga('send', 'pageview');
 @endif

</script>

{{-- The lattice of the hero, the balls of the logo and the tabs --}}
@vite('resources/assets/js/admin.js')

@stack('scripts')
