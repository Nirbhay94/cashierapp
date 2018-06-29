@extends('layouts.master')
@section('page_title', __('Edit Plan'))
@section('content')
    <div class="block-header">
        <h2>{{__('EDIT PLAN')}}</h2>
    </div>
    <!-- Main content -->
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                {!!Form::open(['route' => ['administration.plans.update', 'id' => $plan->id], 'class' => 'form-horizontal', 'method' => 'PUT'])!!}
                <div class="header">
                    <h2>
                        {{__('Plan Details')}}
                        <small>{{__('You can edit your plan details here, as such to be displayed to the user.')}}</small>
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="{{route('administration.plans.index')}}">
                                <i class="material-icons">reply</i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    {{csrf_field()}}
                    <div class="form-group">
                        {!! Form::label('name', __('Name'), array('class' => 'col-md-3')); !!}
                        <div class="col-md-9 form-float">
                            <div class="form-line {{ $errors->has('name') ? ' error ' : '' }}">
                                {!! Form::text('name', $plan->name, array('id' => 'name', 'class' => 'form-control', 'placeholder' => __('Plan Name'))) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('description', __('Description'), array('class' => 'col-md-3')); !!}
                        <div class="col-md-9 form-float">
                            <div class="form-line {{ $errors->has('description') ? ' error ' : '' }}">
                                {!! Form::text('description', $plan->description, array('id' => 'description', 'class' => 'form-control', 'placeholder' => __('Plan Description'), 'max' => '100')) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('price', __('Price'), array('class' => 'col-md-3')); !!}
                        <div class="col-md-9">
                            <div class="row clearfix">
                                <div class="col-md-6 form-float">
                                    <div class="form-line {{ $errors->has('price') ? ' error ' : '' }}">
                                        {!! Form::text('price', $plan->price, array('id' => 'price', 'class' => 'form-control', 'placeholder' => __('Price'))) !!}
                                     </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('interval', __('Interval'), array('class' => 'col-md-3')); !!}
                        <div class="col-md-9">
                            <div class="row clearfix">
                                <div class="col-md-6 form-float">
                                    <div class="form-line {{ $errors->has('interval') ? ' error ' : '' }}">
                                        <select class="form-control" name="interval" id="interval">
                                            <option>{{ __('Select Interval') }}</option>
                                            <option value="day" {{($plan->interval == 'day')? 'selected' : ''}}>{{__('Day')}}</option>
                                            <option value="week" {{($plan->interval == 'week')? 'selected' : ''}}>{{__('Week')}}</option>
                                            <option value="month" {{($plan->interval == 'month')? 'selected' : ''}}>{{__('Month')}}</option>
                                            <option value="year" {{($plan->interval == 'year')? 'selected' : ''}}>{{__('Year')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 form-float">
                                    <div class="form-line {{ $errors->has('interval_count') ? ' error ' : '' }}">
                                        {!! Form::number('interval_count', $plan->interval_count, array('id' => 'interval_count', 'class' => 'form-control', 'placeholder' => __('Interval Count'))) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        {!! Form::label('min_quantity', __('Quantity Range'), array('class' => 'col-md-3')); !!}
                        <div class="col-md-9">
                            <div class="row clearfix">
                                <div class="col-md-6 form-float">
                                    <div class="form-line {{ $errors->has('min_quantity') ? ' error ' : '' }}">
                                        {!! Form::number('min_quantity', $plan->min_quantity, array('id' => 'min_quantity', 'class' => 'form-control', 'placeholder' => __('Min Quantity'))) !!}
                                    </div>
                                    <span class="help-block">{{__('The least quantity a user can purchase')}}</span>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-line {{ $errors->has('max_quantity') ? ' error ' : '' }}">
                                        {!! Form::number('max_quantity', $plan->max_quantity, array('id' => 'max_quantity', 'class' => 'form-control', 'placeholder' => __('Max Quantity'))) !!}
                                    </div>
                                    <span class="help-block">{{__('The most quantity a user can purchase')}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('trial_period_days', __('Trial Period'), array('class' => 'col-md-3')); !!}
                        <div class="col-md-9">
                            <div class="row clearfix">
                                <div class="col-md-6 form-float">
                                    <div class="form-line {{ $errors->has('trial_period_days') ? ' error ' : '' }}">
                                        {!! Form::number('trial_period_days', $plan->trial_period_days, array('id' => 'trial_period_days', 'class' => 'form-control', 'placeholder' => __('Trial Period (in days)'))) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        {!! Form::label('sort_order', __('Sort Order'), array('class' => 'col-md-3')); !!}
                        <div class="col-md-9">
                            <div class="row clearfix">
                                <div class="col-md-6 form-float">
                                    <div class="form-line {{ $errors->has('sort_order') ? ' error ' : '' }}">
                                        {!! Form::number('sort_order', $plan->sort_order, array('id' => 'sort_order', 'class' => 'form-control', 'placeholder' => __('Sort Order (Ascending)'))) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 class="card-inside-title">{{__('Features')}}</h2>
                    <div class="alert alert-info">
                        <h4><i class="icon fa fa-warning"></i> {{__('Important!')}}</h4>
                        {{__('Setting a features as a negative value will make the feature unlimited for subscribers to this plan.')}}
                    </div>
                    @foreach($plan->features()->get() as $feature)
                        <div class="form-group ">
                            {!! Form::label($feature['code'], $feature['label'], array('class' => 'col-md-3')); !!}
                            <div class="col-md-9">
                                <div class="row clearfix">
                                    @if($feature['type'] == 'boolean')
                                        <div class="col-md-6 form-float">
                                            <div class="form-line {{$errors->has($feature['code']) ? ' error ' : ''}}">
                                                {!! Form::select($feature['code'], ['true' => 'Yes', 'false' => 'No'], $feature['value'], array('id' => $feature['code'], 'class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-6 form-float">
                                            <div class="form-line {{$errors->has($feature['code']) ? ' error ' : ''}}">
                                                {!! Form::number($feature['code'], $feature['value'], array('id' => $feature['code'], 'class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="form-group">
                        <div class="col-md-9 col-md-offset-3">
                            {!! Form::button(__('Save'), array('class' => 'btn btn-success btn-flat margin-bottom-1 pull-right','type' => 'submit')) !!}
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection