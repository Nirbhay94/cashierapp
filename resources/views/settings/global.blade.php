@extends('layouts.master')
@section('page_title', __('Global Settings'))
@section('content')
    <div class="block-header">
        <h2>{{__('SETTINGS')}}</h2>
    </div>
    <!-- Main content -->
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2>
                        <b>{{__('Global Settings')}}</b>
                        <small>{{__('This is where you can modify your basic site settings (including name & description).')}}</small>
                    </h2>
                </div>
                <form class="form-horizontal" method="POST" id="settings-form" enctype="multipart/form-data">
                    <div class="body">
                        {{csrf_field()}}
                        <div class="form-group">
                            {!! Form::label('site_name', __('Site Name'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9">
                                <div class="row clearfix">
                                    <div class="col-md-9 form-float">
                                        <div class="form-line {{ $errors->has('site_name') ? ' error ' : '' }}">
                                            {!! Form::text('site_name', $settings->site_name, array('id' => 'name', 'class' => 'form-control', 'maxlength' => '200')) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3 form-float">
                                        <div class="form-line {{ $errors->has('site_name_abbr') ? ' error ' : '' }}">
                                            {!! Form::text('site_name_abbr', $settings->site_name_abbr, array('id' => 'name', 'class' => 'form-control', 'maxlength' => '200')) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('site_title', __('Site Title'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9 form-float">
                                <div class="form-line {{ $errors->has('site_title') ? ' error ' : '' }}">
                                    {!! Form::text('site_title', $settings->site_title, array('id' => 'site_title', 'class' => 'form-control', 'placeholder' => __('Site Title'), 'maxlength' => '200')) !!}
                                </div>
                            </div>
                        </div>
    
                        <div class="form-group">
                            {!! Form::label('logo', __('Logo'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9">
                                <div class="row clearfix">
                                    <div class="col-md-6">
                                        <div class="clearfix">
                                            <input type="file" id="file-upload" name="logo">
                                        </div>
                                        <div class="clearfix">
                                            <span class="label label-danger">{{__('NOTE!')}}</span>
                                            <span><b>{{__('Requirement!')}}</b> 200 * 47, {{__('Max size')}} 50kb</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        @if($settings && $settings->logo)
                                            <img src="{{url($settings->logo)}}" class="img-bordered bg-purple img-responsive">
                                        @else
                                            <img src="{{url('images/logo.png')}}" class="img-bordered bg-purple img-responsive">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            {!! Form::label('site_desc', __('Description'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9">
                                <div class="row clearfix">
                                    <div class="col-md-12 form-float">
                                        <div class="form-line  {{ $errors->has('site_desc') ? ' error ' : '' }}">
                                            {!! Form::textarea('site_desc', $settings->site_desc, array('id' => 'site_desc', 'class' => 'form-control', 'rows' => 4, 'placeholder' => __('Site Description'))) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('facebook_url', __('Facebook Url'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9">
                                <div class="row clearfix">
                                    <div class="col-md-12 form-float">
                                        <div class="form-line {{ $errors->has('facebook_url') ? ' error ' : '' }}">
                                            {!! Form::text('facebook_url', $settings->facebook_url, array('id' => 'facebook_url', 'class' => 'form-control', 'placeholder' => __('Facebook Url'))) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('twitter_url', __('Twitter Url'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9">
                                <div class="row clearfix">
                                    <div class="col-md-12 form-float">
                                        <div class="form-line {{ $errors->has('twitter_url') ? ' error ' : '' }}">
                                            {!! Form::text('twitter_url', $settings->twitter_url, array('id' => 'twitter_url', 'class' => 'form-control', 'placeholder' => __('Twitter Url'))) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('instagram_url', __('Instagram Url'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9">
                                <div class="row clearfix">
                                    <div class="col-md-12 form-float">
                                        <div class="form-line {{ $errors->has('instagram_url') ? ' error ' : '' }}">
                                            {!! Form::text('instagram_url', $settings->instagram_url, array('id' => 'instagram_url', 'class' => 'form-control', 'placeholder' => __('Instagram Url'))) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('google_plus_url', __('Google Plus Url'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9">
                                <div class="row clearfix">
                                    <div class="col-md-12 form-float">
                                        <div class="form-line  {{ $errors->has('google_plus_url') ? ' error ' : '' }}">
                                            {!! Form::text('google_plus_url', $settings->google_plus_url, array('id' => 'google_plus_url', 'class' => 'form-control', 'placeholder' => __('Google Pus Url'))) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-9 col-md-offset-3">
                                {!! Form::button(__('Save Changes'), array('class' => 'btn btn-success btn-flat margin-bottom-1 pull-right','type' => 'submit', )) !!}
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        var Page = function(){
            var handleFileUpload = function(){
                $('#file-upload').fileselect({
                    language: "{{config('app.locale')}}",
                });
            };
            
            var handleFormValidation = function() {
                $('#settings-form').validate({
                    highlight: function (input) {
                        $(input).parents('.form-line').addClass('error');
                    },
                    unhighlight: function (input) {
                        $(input).parents('.form-line').removeClass('error');
                    },
                    errorPlacement: function (error, element) {
                        $(element).parents('.form-float').append(error);
                    }
                });
            };
            
            return {
                init: function(){
                    handleFileUpload();
                    handleFormValidation();
                }
            }
        }();
        
        $(document).ready(function(){
            Page.init();
        });
    </script>
@endpush