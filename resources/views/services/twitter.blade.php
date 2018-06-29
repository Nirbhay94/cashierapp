@extends('layouts.master')
@section('page_title', __('Twitter'))
@section('content')
    <div class="block-header">
        <h2>
            {{__('SERVICES')}}
            <small>{{__('Twitter')}}</small>
        </h2>
    </div>
    <div class="bs-callout bs-callout-default">
        <h4>{{__('Twitter Service')}}</h4>
        <p>{{__('Ensure that you have an active')}} <a href="http://apps.twitter.com">Twitter App</a> {{__('and must have configured the required parameters as stated below.')}}</p>
        <ul>
            <li>{{__('Site Domain')}}: <code>{{url('/')}}</code></li>
            <li>{{__('Redirect Url')}}: <code>{{route('accounts.twitter.callback')}}</code></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="card">
                <div class="header bg-teal">
                    <h2>
                        <i class="fa fa-twitter"></i> <b>{{__('Twitter API')}}</b>
                        <small>{{__('You can configure your twitter app details from here and enable services you would like.')}}</small>
                    </h2>
                </div>
                <form class="form-horizontal" method="post">
                    <div class="body">
                        {{csrf_field()}}
                        <div class="form-group">
                            {!! Form::label('tw_key', __('Twitter Consumer Key'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9 form-float">
                                <div class="form-line {{ $errors->has('tw_key') ? ' error ' : '' }}">
                                    {!! Form::text('tw_key', $keys['TW_KEY']['value'], array('id' => 'tw_key', 'class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('tw_secret', __('Twitter Consumer Secret'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9 form-float">
                                <div class="form-line {{ $errors->has('tw_secret') ? ' error ' : '' }}">
                                    {!! Form::text('tw_secret', $keys['TW_SECRET']['value'], array('id' => 'tw_secret', 'class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-9 col-md-offset-3">
                                <button type="submit" class="btn bg-teal btn-flat">{{__('Submit')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection