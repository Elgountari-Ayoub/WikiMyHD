@component('mail::message')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
@endcomponent
@endslot

{{-- Body --}}
# Bienvenue **{{ $userName }}** sur WikiMyHD !

Votre nouveau compte a été créé par l'administrateur. Veuillez trouver ci-dessous vos informations de connexion :

Adresse e-mail : **{{ $email }}**

Mot de passe : **{{ $pass }}**

Vous avez été approuvé pour le poste suivant : {{ $post }}

Vous pouvez maintenant accéder aux espaces suivants:

@component('mail::panel')
@if (count($spaces) > 0)

@foreach($spaces as $space)
* {{ $space['title'] }}
@foreach($space['manuals'] as $manual)
    * {{ $manual['title'] }}
@endforeach

@endforeach

@else
Pas de espaces disponibles.
@endif
@endcomponent

Pour commencer, veuillez cliquer sur le bouton ci-dessous pour accéder à la page de connexion :

@component('mail::button', ['url' => $loginUrl, 'color' => 'primary'])
Accéder à la page de connexion
@endcomponent

{{-- Logo--}}
<div style="background-color: #ddd ;border-radius: 1rem ; padding: 1rem; margin-bottom: 2rem;">
    <!-- <img src="{{ $logoUrl }}" alt="{{ config('app.name') }} Logo" style="height: 80px;"> -->
    <img src="https://havetdigital.fr/img/logo.png" alt="{{ config('app.name') }} Logo" style="height: 80px;">

</div>

Si vous avez des questions ou avez besoin d'aide, n'hésitez pas à contacter notre équipe d'assistance.

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
@endcomponent
@endslot
@endcomponent