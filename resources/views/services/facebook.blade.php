@extends('layouts.master')
@section('page_title', __('Facebook'))
@section('content')
    <div class="block-header">
        <h2>
            {{__('SERVICES')}}
            <small>{{__('Facebook')}}</small>
        </h2>
    </div>
    <!-- Main content -->
    <div class="bs-callout bs-callout-default">
        <h4>{{__('Facebook Service')}}</h4>
        <p>{{__('Ensure that you have an active')}} <a href="http://developers.facebook.com">Facebook App</a> {{__('and must have configured the required parameters as stated below.')}}</p>
        <ul>
            <li>{{__('Site Domain')}}: <code>{{url('/')}}</code></li>
            <li>{{__('Valid OAth Redirect Url')}}:
                <ol>
                    <li><code>{{route('accounts.facebook.callback')}}</code></li>
                    <li><code>{{route('services.facebook.callback')}}</code></li>
                </ol>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="card">
                <div class="header bg-blue">
                    <h2>
                        <i class="fa fa-facebook-square"></i> <b>{{__('Facebook API')}}</b>
                        <small>{{__('You can configure your facebook app details from here and enable services you would like.')}}</small>
                    </h2>
                </div>
                <form class="form-horizontal" method="POST">
                    <div class="body">
                        {{csrf_field()}}
                        <div class="form-group">
                            {!! Form::label('fb_id', __('Facebook API ID'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9 form-float">
                                <div class="form-line {{ $errors->has('fb_id') ? ' error ' : '' }}">
                                    {!! Form::text('fb_id', $keys['FB_ID']['value'], array('id' => 'fb_id', 'class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('fb_secret', __('Facebook API Secret'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9 form-float">
                                <div class="form-line {{ $errors->has('fb_secret') ? ' error ' : '' }}">
                                    {!! Form::text('fb_secret', $keys['FB_SECRET']['value'], array('id' => 'fb_secret', 'class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">{{__('Status')}}</label>
                            <div class="col-md-9 form-float">
                                <div class="row clearfix">
                                    <div class="col-xs-6">
                                        @if(isset($status))
                                            <span class="label label-{{$status['type']}}"> {{$status['message']}} </span>
                                        @endif
                                    </div>
                                    @if(isset($status) && $status['type'] != 'success')
                                        <div class="col-xs-6">
                                            <a href="{{route('services.facebook.login')}}" class="btn btn-info">{{__('Login as Administrator')}}</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-9 col-md-offset-3">
                                <button type="submit" class="btn bg-blue btn-flat">{{__('Submit')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection