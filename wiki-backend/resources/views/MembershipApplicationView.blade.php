@component('mail::message')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
@endcomponent
@endslot

{{-- Body --}}
# Nouvelle demande d'adhésion à WikiMyHd

Bonjour Admin,

Une nouvelle demande d'adhésion a été soumise par l'utilisateur suivant :

- **Nom**: {{ $userName }}
- **Email**: {{ $userEmail }}
- **Poste**: {{ $userPoste }}

Veuillez examiner la demande et prendre les mesures appropriées.

@component('mail::button', ['url' => $demandesUrl, 'color' => 'primary'])
Accéder à la page des demandes
@endcomponent

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
