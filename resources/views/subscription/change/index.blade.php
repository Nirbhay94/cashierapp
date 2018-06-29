@extends('layouts.master')
@section('page_title', __('Change Plan'))
@push('css')
    <link href="{{asset('/css/page_pricing.css')}}" type="text/css" rel="stylesheet"/>
@endpush
@section('content')
    <div class="block-header">
        <h2>{{__('SUBSCRIPTION')}}</h2>
    </div>
    @foreach($records as $plans)
        <div class="row">
            @foreach($plans as $plan)
                <div class="col-md-3 col-sm-6">
                    <div class="pricing hover-effect">
                        <div class="pricing-head">
                            <h3 class="bg-purple">
                                {{$plan->name}}
                                @if($plan->trial_period_days == 0)
                                    <span>{{__('No trial offer!')}}</span>
                                @else
                                    <span><i class="fa fa-asterisk"></i> <b>{{$plan->trial_period_days}}</b> {{__('days of trial')}}</span>
                                @endif
                            </h3>
                            @php list($whole, $decimal) = explode('.', $plan->price); @endphp
                            <h4><i>{{config('settings.currencySymbol')}}</i>{{$whole}}<i>.{{$decimal}}</i> <span>{{__('Per')}} {{($plan->interval_count)? $plan->interval_count: ''}} {{ucwords($plan->interval)}}</span></h4>
                        </div>
                        <ul class="pricing-content list-unstyled">
                            @foreach($plan->features()->orderBy('sort_order', 'ASC')->get() as $feature)
                                <li class="text-center">
                                    @if($feature->type == 'boolean')
                                        @if(in_array($feature->value, config('laraplans.positive_words')))
                                            <i class="fa fa-check"></i>
                                        @else
                                            <i class="fa fa-times"></i>
                                        @endif
                                    @else
                                        @if($feature->value >= 0 || in_array($feature->value, config('laraplans.positive_words')))
                                            <b>{{$feature->value}}</b>
                                        @else
                                            <b>{{__('Unlimited')}}</b>
                                        @endif
                                    @endif
                                    <span>{{$feature->label}}</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="pricing-footer">
                            <p>{{$plan->description}}</p>
                            @if(($subscription = Auth::user()->subscription('main')) && $subscription->plan->id == $plan->id)
                                <a class="btn btn-default btn-flat" disabled="disabled">
                                    <i class="fa fa-shopping-cart"></i> {{__('CURRENT')}}
                                </a>
                            @else
                                <form class="form-horizontal" method="GET" action="{{route('subscription.change.checkout')}}">
                                    <div class="input-group input-group-sm">
                                        <div class="form-line">
                                            <input type="hidden" name="plan" value="{{$plan->id}}" />
                                            <input type="text" class="form-control" name="quantity" placeholder="{{__('Quantity')}}"/>
                                        </div>
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-primary material-icons">shopping_cart</button>
                                        </span>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
    @if(!count($records))
        <h3 class="text-center">{{__('No Plans Found Yet!')}}</h3>
    @endif
@endsection
@push('js')
@endpush