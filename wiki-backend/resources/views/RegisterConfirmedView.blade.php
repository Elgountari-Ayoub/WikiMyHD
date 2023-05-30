@component('mail::message')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
@endcomponent
@endslot

{{-- Body --}}
# Congratulations, your sign-up request has been approved!

Hello {{ $userName}},

We are delighted to inform you that your sign-up request has been approved. You can now access the following spaces:

@component('mail::panel')
@foreach ($spaces as $space)
- {{ $space['name'] }}
@endforeach
@endcomponent

To get started, please click the button below to visit the login page:

@component('mail::button', ['url' => $loginUrl, 'color' => 'primary'])
Go to Login Page
@endcomponent

{{-- Logo--}}
<div style="background-color: #ddd ;border-radius: 1rem ; padding: 1rem; margin-bottom: 2rem;">
    <!-- <img src="{{ $logoUrl }}" alt="{{ config('app.name') }} Logo" style="height: 80px;"> -->
    <img src="https://havetdigital.fr/img/logo.png" alt="{{ config('app.name') }} Logo" style="height: 80px;">

</div>


If you have any questions or need assistance, please feel free to contact our support team.

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
@endcomponent
@endslot
@endcomponent
