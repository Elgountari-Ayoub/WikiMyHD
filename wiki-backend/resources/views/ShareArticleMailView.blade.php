@component('mail::message')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
@endcomponent
@endslot

{{-- Body --}}
# WikiMyHd a partagé un article avec vous

Cher utilisateur,

Je t'envoie un article intéressant en pièce jointe. Bonne lecture !

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
