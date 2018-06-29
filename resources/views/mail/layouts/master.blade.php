@component('mail::layout')

@if(isset($content['header']) && $content['header'] != null)
	@slot('header')
		@php $url = $content['header_url'] ?: config('app.url'); @endphp
		@component('mail::header', ['url' => $url])
		{!! $content['header'] !!}
		@endcomponent
	@endslot
@endif

@yield('body')

@if(isset($content['subcopy']) && $content['subcopy'] != null)
	@slot('subcopy')
		@component('mail::subcopy')
		{!! $content['subcopy'] !!}
		@endcomponent
	@endslot
@endif

@if(isset($content['footer']) && $content['footer'] != null)
	@slot('footer')
		@component('mail::footer')
		{!! $content['footer'] !!}
		@endcomponent
	@endslot
@endif

@endcomponent