@extends('layout.one-column-layout')

@section('page-title')
    Contact
@endsection
@section('title', "Contact - T.F.V. ‘Professor Francken’")
@section('description', "Contact information of the study association T.F.V. ‘Professor Francken’.")
@inject('settings', "Francken\Shared\Settings\Settings")

@section('content')
    <div class="page-intro">
        <p>Questions, ideas, or just want to say hi? Drop by the Francken room or send us an email.</p>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="contact-card">
                <span class="contact-card__icon"><i class="fas fa-map-marker-alt" aria-hidden="true"></i></span>
                <h2 class="contact-card__title">Visit us</h2>
                <address class="mb-0">
                    <strong>Francken room</strong><br>
                    Feringa Building, room 5614.0143<br>
                    <a href="https://maps.app.goo.gl/ZzqTA98zP9sSvZLA6">Nijenborgh 3, 9747 AG Groningen</a>
                </address>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="contact-card">
                <span class="contact-card__icon"><i class="fas fa-user-tie" aria-hidden="true"></i></span>
                <h2 class="contact-card__title">Board</h2>
                <dl class="contact-card__details">
                    <dt>Email</dt>
                    <dd><a href="mailto:board@professorfrancken.nl">board@professorfrancken.nl</a></dd>
                    @if ($settings->contactNumberOfChair() != '')
                        <dt>Phone</dt>
                        <dd><a href="tel:{{ str_replace(' ', '', $settings->contactNumberOfChair()) }}">{{ $settings->contactNumberOfChair() }}</a></dd>
                    @endif
                </dl>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="contact-card">
                <span class="contact-card__icon"><i class="fas fa-handshake" aria-hidden="true"></i></span>
                <h2 class="contact-card__title">External relations</h2>
                <dl class="contact-card__details">
                    <dt>Email</dt>
                    <dd><a href="mailto:extern@professorfrancken.nl">extern@professorfrancken.nl</a></dd>
                    @if ($settings->contactNumberOfExtern() != '')
                        <dt>Phone</dt>
                        <dd><a href="tel:{{ str_replace(' ', '', $settings->contactNumberOfExtern()) }}">{{ $settings->contactNumberOfExtern() }}</a></dd>
                    @endif
                </dl>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="contact-card">
                <span class="contact-card__icon"><i class="fas fa-envelope" aria-hidden="true"></i></span>
                <h2 class="contact-card__title">Post</h2>
                <address class="mb-0">
                    T.F.V. ‘Professor Francken’<br>
                    Nijenborgh 3<br>
                    9747 AG Groningen
                </address>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="contact-card">
                <span class="contact-card__icon"><i class="fas fa-file-invoice" aria-hidden="true"></i></span>
                <h2 class="contact-card__title">Association details</h2>
                <dl class="contact-card__details">
                    <dt>K.v.K.</dt>
                    <dd>400 252 71</dd>
                    <dt>IBAN</dt>
                    <dd>NL31 ABNA 0510 5771 56</dd>
                </dl>
            </div>
        </div>
    </div>
@endsection
