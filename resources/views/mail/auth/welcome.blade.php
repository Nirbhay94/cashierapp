@component('mail::message')
# {{trans('mail.auth.welcome.greeting', ['name' => $user->name])}}

{{trans('mail.auth.welcome.headnote')}}

@component('mail::button', ['url' => route('home')])
{{trans('mail.auth.welcome.button')}}
@endcomponent

@if($password != null)
@component('mail::panel')
{{trans('mail.auth.welcome.footnote', ['password' => $password])}}
@endcomponent
@endif

{{trans('mail.auth.welcome.regards')}}<br>
{{ config('app.name') }}
@endcomponent
