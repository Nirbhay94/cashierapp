@extends('layouts.master')
@section('page_title', __('Extend Subscription'))
@section('content')
    <div class="block-header">
        <h2>{{__('Subscription')}}</h2>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2>
                        <b>{{__('Extend Subscription')}}</b>
                        <small>{{__('This is necessary to avoid stoppage of service.')}}</small>
                    </h2>
                </div>
                @php
                if($subscription && $subscription->isActive()){
                    $now = \Carbon\Carbon::now();
                    $starts_at = new \Carbon\Carbon($subscription->starts_at);
                    
                    if($subscription->onStrictTrial()){
                        $ends_at = new \Carbon\Carbon($subscription->trial_ends_at);
                    }else{
                        $ends_at = new \Carbon\Carbon($subscription->ends_at);
                    }
                }
                @endphp
                <form method="GET" action="{{route('subscription.extend.checkout')}}">
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="row clearfix">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="info-box">
                                            <div class="icon bg-blue">
                                                <i class="material-icons">play_circle_filled</i>
                                            </div>
                                            <div class="content">
                                                <div class="text">{{__('CURRENT PLAN')}}</div>
                                                <div class="number">
                                                    @if($subscription)
                                                        {{$subscription->plan->name}}
                                                    @else
                                                        {{__('Not Available')}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="info-box">
                                            <div class="icon bg-orange">
                                                <i class="material-icons">date_range</i>
                                            </div>
                                            <div class="content">
                                                <div class="text">{{__('SUBSCRIBED DATE')}}</div>
                                                <div class="number">
                                                    @if($subscription)
                                                        {{\Carbon\Carbon::parse($subscription->plan->starts_at)->toFormattedDateString()}}
                                                    @else
                                                        {{__('Not Available')}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="form-group">
                                        {!! Form::label('price', __('Status'), array('class' => 'col-md-3')); !!}
                                        <div class="col-md-9">
                                            @if($subscription && $subscription->onStrictTrial())
                                                {{__('Your trial expires in :count days time', ['count' => $ends_at->diffInDays($now)])}}
                                            @elseif($subscription && $subscription->isActive())
                                                {{__('Expires in :count days time', ['count' => $ends_at->diffInDays($now)])}}
                                            @else
                                                {{__('Not Available')}}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('price', __('Price'), array('class' => 'col-md-3')); !!}
                                        <div class="col-md-9">
                                            {!! money(($subscription)? $subscription->plan->price: 0) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('quantity', __('Quantity'), array('class' => 'col-md-3')); !!}
                                        <div class="col-md-9">
                                            <div class="row clearfix">
                                                <div class="col-md-6 form-float">
                                                    <div class="form-line {{ $errors->has('interval') ? ' error ' : '' }}">
                                                        <select class="form-control" name="interval" id="interval" disabled readonly>
                                                            <option>{{ __('Select Interval') }}</option>
                                                            @if($subscription)
                                                                <option value="day" {{($subscription->plan->interval == 'day')? 'selected': ''}}>{{$subscription->plan->interval_count}} {{__('Day')}}</option>
                                                                <option value="week" {{($subscription->plan->interval == 'week')? 'selected': ''}}>{{$subscription->plan->interval_count}} {{__('Week')}}</option>
                                                                <option value="month" {{($subscription->plan->interval == 'month')? 'selected': ''}}>{{$subscription->plan->interval_count}} {{__('Month')}}</option>
                                                                <option value="year" {{($subscription->plan->interval == 'year')? 'selected': ''}}>{{$subscription->plan->interval_count}} {{__('Year')}}</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="input-group-addon" for="quantity">
                                                            <span class="material-icons">clear</span>
                                                        </label>
                                                        <div class="form-line {{ $errors->has('quantity') ? ' error ' : '' }}">
                                                            {!! Form::number('quantity', ($subscription)? $subscription->plan->min_quantity: NULL, array('id' => 'quantity', 'class' => 'form-control', 'placeholder' => __('Quantity'), (!$subscription)? 'readonly': '')) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-12">
                                @if($subscription)
                                    {!! Form::button(__('Proceed'), array('class' => 'btn btn-success btn-flat margin-bottom-1 pull-right','type' => 'submit' )) !!}
                                @else
                                    <a href="{{route('subscription.change')}}" class="btn btn-primary btn-flat margin-bottom-1">{{__('Choose A Plan ')}}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection