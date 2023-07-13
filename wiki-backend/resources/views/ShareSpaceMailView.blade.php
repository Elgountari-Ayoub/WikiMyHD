@component('mail::message')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
@endcomponent
@endslot

{{-- Body --}}
# WikiMyHd a partagé un espace avec vous

Cher utilisateur,

Nous sommes ravis de vous informer que WikiMyHd a partagé un espace passionnant avec vous. Vous pouvez désormais accéder à cet espace . Voici l'espace partagé :

@component('mail::panel')

* {{ $space['title'] }}

@endcomponent

Cordialement,
WikiMyHD


{{-- Logo --}}
<div style="background-color: #ddd; border-radius: 1rem; padding: 1rem; margin-bottom: 2rem;">
    <img src="https://havetdigital.fr/img/logo.png" alt="{{ config('app.name') }} Logo" style="height: 80px;">
</div>


{{-- Footer --}}
@slot('footer')
@component('mail::footer')
&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
@endcomponent
@endslot
@endcomponent
