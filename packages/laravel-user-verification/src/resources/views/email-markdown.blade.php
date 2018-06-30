@component('mail::message')
{{trans('laravel-user-verification::user-verification.verification_email_greeting', ['name' => $user->name])}},

# {{trans('laravel-user-verification::user-verification.verification_email_headnote')}}

{{trans('laravel-user-verification::user-verification.verification_email_body')}}

@component('mail::button', ['url' => route('email-verification.check', ['token' => $user->verification_token]).'?email='.urlencode($user->email)])
{{trans('laravel-user-verification::user-verification.verification_email_button')}}
@endcomponent

@component('mail::panel')
{{trans('laravel-user-verification::user-verification.verification_email_footnote')}}
@endcomponent

{{trans('laravel-user-verification::user-verification.verification_email_regards')}},<br>
{{ config('app.name') }}
@endcomponent
