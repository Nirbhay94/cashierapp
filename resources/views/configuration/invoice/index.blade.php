@extends('layouts.master')
@section('page_title', __('Invoice Configuration'))
@section('content')
	<div class="block-header">
		<h2>
			{{__('CONFIGURATION')}}
			<small class="clearfix">
				{{__('Invoice')}}
			</small>
		</h2>
	</div>
	
	<div class="row clearfix">
		<div class="col-md-12">
			<div class="card">
				<div class="header">
					<h2>
						{{__('INVOICE CONFIGURATION')}}
						<small>{{__('This will be used to customize your invoice.')}}</small>
					</h2>
				</div>
				{!! Form::model($config, ['method' => 'POST', 'files' => true]) !!}
					<div class="body">
						<h2 class="card-inside-title">{{__('Currency Setup')}}</h2>
						<div class="row clearfix">
							<div class="form-group">
								{!! Form::label('currency_locale', __('Currency Locale'), array('class' => 'col-md-3')); !!}
								<div class="col-md-9">
									<div class="form-line {{ $errors->has('currency_locale') ? ' error ' : '' }}">
										@php $options = get_currencies(); @endphp
										{!! Form::select('currency_locale', $options, null, array('id' => 'currency_locale', 'class' => 'form-control ms')) !!}
									</div>
								</div>
							</div>
						</div>
						
						<h2 class="card-inside-title">{{__('Business Details')}}</h2>
						<div class="row clearfix">
							<div class="form-group">
								{!! Form::label('business_name', __('Business Name'), array('class' => 'col-md-3')); !!}
								<div class="col-md-9">
									<div class="row clearfix">
										<div class="col-md-8 form-float">
											<div class="form-line {{ $errors->has('business_name') ? ' error ' : '' }}">
												{!! Form::text('business_name', null, array('id' => 'business_name', 'class' => 'form-control', 'placeholder' => __('Business Name'), 'max' => '150')) !!}
											</div>
										</div>
										<div class="col-md-4 form-float">
											<div class="form-line {{ $errors->has('business_id') ? ' error ' : '' }}">
												{!! Form::text('business_id', null, array('id' => 'business_id', 'class' => 'form-control', 'placeholder' => __('Identification Number'), 'max' => '50')) !!}
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
											@if($config && $config->business_logo)
												<img src="{{url($config->business_logo)}}" class="img-bordered img-responsive">
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
										{!! Form::textarea('business_legal_terms', null, array('id' => 'business_legal_terms', 'class' => 'form-control', 'placeholder' => __('This will be used for invoices foot notes. Make it as brief as possible.'))) !!}
									</div>
								</div>
							</div>
							<div class="form-group">
								{!! Form::label('business_phone', __('Business Phone'), array('class' => 'col-md-3')); !!}
								<div class="col-md-9">
									<div class="form-line  {{ $errors->has('business_phone') ? ' error ' : '' }}">
										{!! Form::text('business_phone', null, array('id' => 'business_phone', 'class' => 'form-control', 'placeholder' => __('Business Phone'))) !!}
									</div>
								</div>
							</div>
							<div class="form-group">
								{!! Form::label('business_location', __('Business Location'), array('class' => 'col-md-3')); !!}
								<div class="col-md-9 form-float">
									<div class="form-line {{ $errors->has('business_location') ? ' error ' : '' }}">
										{!! Form::text('business_location', null, array('id' => 'business_location', 'class' => 'form-control', 'placeholder' => __('Business Location'))) !!}
									</div>
								</div>
							</div>
							<div class="form-group">
								{!! Form::label('business_city', __('City'), array('class' => 'col-md-3')); !!}
								<div class="col-md-9">
									<div class="row clearfix">
										<div class="col-md-6 form-float">
											<div class="form-line {{ $errors->has('business_city') ? ' error ' : '' }}">
												{!! Form::text('business_city', null, array('id' => 'business_city', 'class' => 'form-control', 'placeholder' => __('City'))) !!}
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-line {{ $errors->has('business_zip') ? ' error ' : '' }}">
												{!! Form::text('business_zip', null, array('id' => 'business_zip', 'class' => 'form-control', 'placeholder' => __('Zip Code'))) !!}
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								{!! Form::label('business_country', __('Country'), array('class' => 'col-md-3')); !!}
								<div class="col-md-9">
									<div class="form-line {{ $errors->has('business_country') ? ' error ' : '' }}">
										{!! Form::text('business_country', null, array('id' => 'business_country', 'class' => 'form-control', 'placeholder' => __('Country'))) !!}
									</div>
								</div>
							</div>
						</div>
						
						<div class="row clearfix">
							<div class="col-md-9 col-md-offset-3">
								{!! Form::button(__('Save Changes'), array('class' => 'btn bg-purple btn-flat margin-bottom-1', 'type' => 'submit', )) !!}
							</div>
						</div>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
@endsection
@push('js')
	<script>
        var Page = function(){
            var handleFileUpload = function(){
                $('#file-upload').fileselect({
                    language: "{{App::getLocale()}}",
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