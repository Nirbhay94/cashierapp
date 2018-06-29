@extends('layouts.master')
@section('page_title', __('Google Plus'))
@section('content')
    <div class="block-header">
        <h2>
            {{__('SERVICES')}}
            <small>{{__('Google')}}</small>
        </h2>
    </div>
    <!-- Main content -->
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="card">
                <div class="header bg-brown">
                    <h2>
                        <i class="fa fa-map-marker"></i> <b>{{__('Google Maps API')}}</b>
                        <small>{{__('You can configure your google maps api details from here and enable services you would like.')}}</small>
                    </h2>
                </div>
                <form class="form-horizontal" action="{{route('services.google.google-maps')}}" method="post">
                    <div class="body">
                        {{csrf_field()}}
                        <div class="form-group">
                            {!! Form::label('google_maps_key', __('Google Maps Key'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9">
                                <div class="form-line {{ $errors->has('google_maps_key') ? ' error ' : '' }}">
                                    {!! Form::text('google_maps_key', $keys['GOOGLEMAPS_API_KEY']['value'], array('id' => 'google_maps_key', 'class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>
                        <h2 class="card-inside-title">{{__('Configuration')}}</h2>
                        <div class="form-group">
                            {!! Form::label('enable_googlemaps', __('Enable Google Maps'), array('class' => 'col-xs-9')); !!}
                            <div class="col-xs-3">
                                <div class="switch">
                                    <label><input type="checkbox"  name="enable_googlemaps" value="yes" {{($keys['ENABLE_GOOGLEMAPS']['value'] == 'true')? 'checked': ''}}><span class="lever switch-col-brown"></span></label>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-9 col-md-offset-3">
                                <button type="submit" class="btn bg-brown btn-flat">{{__('Submit')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="card">
                <div class="header bg-indigo">
                    <h2>
                        <i class="fa fa-lock"></i> <b>{{__('Invisible reCAPTCHA API')}}</b>
                        <small>{{__('You can configure your invisible recaptcha api details from here and enable services you would like.')}}</small>
                    </h2>
                </div>
                <form class="form-horizontal" action="{{route('services.google.google-recaptcha')}}" method="post">
                    <div class="body">
                        {{csrf_field()}}
                        <div class="form-group">
                            {!! Form::label('recaptcha_key', __('reCAPTCHA Key'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9 form-float">
                                <div class="form-line {{ $errors->has('recaptcha_key') ? ' error ' : '' }}">
                                    {!! Form::text('recaptcha_key', $keys['RECAPTCHA_KEY']['value'], array('id' => 'recaptcha_key', 'class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('recaptcha_secret', __('reCAPTCHA Secret'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9 form-float">
                                <div class="form-line {{ $errors->has('recaptcha_secret') ? ' error ' : '' }}">
                                    {!! Form::text('recaptcha_secret', $keys['RECAPTCHA_SECRET']['value'], array('id' => 'recaptcha_secret', 'class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>
                        <h2 class="card-inside-title">{{__('Configuration')}}</h2>
                        <div class="form-group">
                            {!! Form::label('enable_recaptcha', __('Enable reCAPTCHA'), array('class' => 'col-xs-9')); !!}
                            <div class="col-xs-3">
                                <div class="switch">
                                    <label><input type="checkbox"  name="enable_recaptcha" value="yes" {{($keys['ENABLE_RECAPTCHA']['value'] == 'true')? 'checked': ''}}><span class="lever switch-col-indigo"></span></label>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-9 col-md-offset-3">
                                <button type="submit" class="btn bg-indigo btn-flat">{{__('Submit')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection