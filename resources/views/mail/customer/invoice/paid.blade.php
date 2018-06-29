@extends('mail.layouts.master')
@section('body')
# {{trans('mail.customer.invoice.paid.greeting', ['name' => $name])}}

{{trans('mail.customer.invoice.paid.headnote')}}

@component('mail::panel')
	{{$note}}
@endcomponent

@if($product_items = json_decode($items))
@component('mail::table')
	| {{__('Name')}} | {{__('Price')}} | {{__('Quantity')}} | {{__('Total')}} |
	| -------------- | --------------- | ------------------ | --------------- |
	@foreach($product_items as $item)
	| {{$item->name}} | {{$item->price}} | {{$item->amount}} | {{bcmul($item->price, $item->amount)}}|
	@endforeach
@endcomponent
@endif

{{__('Discount: :discount', compact('discount'))}}<br>
{{__('Tax: :tax', compact('tax'))}}<br>
# {{__('Total: :total', compact('total'))}}

@component('mail::button', ['url' => $url])
	{{trans('mail.customer.invoice.paid.button')}}
@endcomponent

@component('mail::panel')
	{{trans('mail.customer.invoice.paid.footnote')}}
@endcomponent

{{trans('mail.customer.invoice.paid.regards')}}
@endsection