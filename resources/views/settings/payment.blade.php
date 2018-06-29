@extends('layouts.master')
@section('page_title', __('Payment'))
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
                        <b>{{__('Payment Settings')}}</b>
                        <small>{{__('You may specify your payment settings including business details to be used for invoicing.')}}</small>
                    </h2>
                </div>
                <form class="form-horizontal" method="POST" enctype="multipart/form-data">
                    <div class="body">
                        {{csrf_field()}}
                        <div class="form-group">
                            {!! Form::label('amount_init', __('Initial Amount'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9">
                                <div class="row clearfix">
                                    <div class="col-md-12 form-float">
                                        <div class="form-line {{ $errors->has('amount_init') ? ' error ' : '' }}">
                                            {!! Form::number('amount_init', $payment_setting->amount_init, array('id' => 'amount_init', 'class' => 'form-control')) !!}
                                        </div>
                                        <span class="help-block">{{__('The minimum amount an end user can deposit into his/her balance')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('amount_inc', __('Amount Interval'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9">
                                <div class="row clearfix">
                                    <div class="col-md-12 form-float">
                                        <div class="form-line {{ $errors->has('amount_inc') ? ' error ' : '' }}">
                                            {!! Form::number('amount_inc', $payment_setting->amount_inc, array('id' => 'amount_inc', 'class' => 'form-control')) !!}
                                        </div>
                                        <span class="help-block">{{__('The interval between amounts an end user can deposit into his/her balance')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('amount_max', __('Maximum Amount'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9">
                                <div class="row clearfix">
                                    <div class="col-md-12 form-float">
                                        <div class="form-line {{ $errors->has('amount_max') ? ' error ' : '' }}">
                                            {!! Form::number('amount_max', $payment_setting->amount_max, array('id' => 'amount_max', 'class' => 'form-control')) !!}
                                        </div>
                                        <span class="help-block">{{__('The maximum amount an end user can deposit into his/her balance')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2 class="card-inside-title">{{__('Business Details')}}</h2>
                        <div class="form-group">
                            {!! Form::label('business_name', __('Business Name'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9">
                                <div class="row clearfix">
                                    <div class="col-md-8 form-float">
                                        <div class="form-line {{ $errors->has('business_name') ? ' error ' : '' }}">
                                            {!! Form::text('business_name', $payment_setting->business_name, array('id' => 'business_name', 'class' => 'form-control', 'placeholder' => __('Business Name'), 'max' => '150')) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-float">
                                        <div class="form-line {{ $errors->has('business_id') ? ' error ' : '' }}">
                                            {!! Form::text('business_id', $payment_setting->business_id, array('id' => 'business_id', 'class' => 'form-control', 'placeholder' => __('Identification Number'), 'max' => '50')) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('business_logo', __('Business Logo'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9">
                                <div class="row clearfix">
                                    <div class="col-md-6">
                                        <div class="clearfix">
                                            <input type="file" id="file-upload" name="business_logo">
                                        </div>
                                        <div class="clearfix">
                                            <span class="label label-danger">{{__('NOTE!')}}</span>
                                            <span><b>{{__('Requirement!')}}</b> 524 * 140, {{__('Max size')}} 50kb</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        @if($payment_setting && $payment_setting->business_logo)
                                            <img src="{{url($payment_setting->business_logo)}}" class="img-bordered img-responsive">
                                        @else
                                            <img src="{{url('images/logo_black.png')}}" class="img-bordered img-responsive">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('business_legal_terms', __('Business Legal Terms'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9">
                                <div class="form-line  {{ $errors->has('business_legal_terms') ? ' error ' : '' }}">
                                    {!! Form::textarea('business_legal_terms', $payment_setting->business_legal_terms, array('id' => 'business_legal_terms', 'class' => 'form-control', 'placeholder' => __('This will be used for invoices foot notes. Make it as brief as possible.'))) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('business_phone', __('Business Phone'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9">
                                <div class="form-line  {{ $errors->has('business_phone') ? ' error ' : '' }}">
                                    {!! Form::text('business_phone', $payment_setting->business_phone, array('id' => 'business_phone', 'class' => 'form-control', 'placeholder' => __('Business Phone'))) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('business_location', __('Business Location'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9 form-float">
                                <div class="form-line {{ $errors->has('business_location') ? ' error ' : '' }}">
                                    {!! Form::text('business_location', $payment_setting->business_location, array('id' => 'business_location', 'class' => 'form-control', 'placeholder' => __('Business Location'))) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('business_city', __('City'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9">
                                <div class="row clearfix">
                                    <div class="col-md-6 form-float">
                                        <div class="form-line {{ $errors->has('business_city') ? ' error ' : '' }}">
                                            {!! Form::text('business_city', $payment_setting->business_city, array('id' => 'business_city', 'class' => 'form-control', 'placeholder' => __('City'))) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-line {{ $errors->has('business_zip') ? ' error ' : '' }}">
                                            {!! Form::text('business_zip', $payment_setting->business_zip, array('id' => 'business_zip', 'class' => 'form-control', 'placeholder' => __('Zip Code'))) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('business_country', __('Country'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9">
                                <div class="form-line {{ $errors->has('business_country') ? ' error ' : '' }}">
                                    {!! Form::text('business_country', $payment_setting->business_country, array('id' => 'business_country', 'class' => 'form-control', 'placeholder' => __('Country'))) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-9 col-md-offset-3">
                                {!! Form::button(__('Save Changes'), array('class' => 'btn btn-success btn-flat margin-bottom-1','type' => 'submit', )) !!}
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
                    rules: {
                        amount_init: {digits: true},
                        amount_inc: {digits: true},
                        amount_max: {digits: true}
                    },

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