{{--
    The title of the page in a navy hero, like the hero of the homepage. Pages set it with
    @section('page-title'); otherwise the title of the browser tab is used, without the
    name of the association
--}}
@php
    $pageTitle = trim($__env->yieldContent('page-title'));

    if ($pageTitle === '') {
        $tabTitle = html_entity_decode($__env->yieldContent('title'), ENT_QUOTES);
        $tabTitle = trim(preg_replace("/\s*-\s*T\.F\.V\..*$/u", '', $tabTitle));
        $pageTitle = e($tabTitle !== '' ? $tabTitle : "T.F.V. ‘Professor Francken’");
    }
@endphp
<section class="page-hero" data-fcc-lattice>
    <div class="container">
        <h1 class="page-hero__title">{!! $pageTitle !!}</h1>
    </div>
</section>
