@extends('mail.layouts.master')
@section('body')
# {{trans('mail.preview.greeting')}}

{{trans('mail.preview.body')}}
Pol, danista! Est fortis diatria, cesaris. Lunas ortum in hortus! Cur lapsus mori?

@component('mail::button', ['url' => config('app.url')])
{{trans('mail.preview.button')}}
@endcomponent

Pol, danista! Est fortis diatria, cesaris. Lunas ortum in hortus! Cur lapsus mori?

@component('mail::panel')
{{trans('mail.preview.footnote')}}
@endcomponent
@endsection