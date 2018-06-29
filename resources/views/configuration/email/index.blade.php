@extends('layouts.master')
@section('page_title', __('Email Configuration'))
@section('content')
	<div class="block-header">
		<h2>
			{{__('CONFIGURATION')}}
			<a href="{{route('configuration.email.preview')}}" target="_blank" class="btn bg-purple pull-right">
				{{__('PREVIEW')}}
			</a>
			<small class="clearfix">
				{{__('Email')}}
			</small>
		</h2>
	</div>
	
	<div class="callout-block callout-info">
		<div class="icon-holder">
			<i class="fa fa-info-circle"></i>
		</div>
		<div class="content">
			<h4 class="callout-title">{{__('Email Configuration')}}</h4>
			<p>{{__('This will be used for customizing email notifications.')}}</p>
		</div>
	</div>
	
	<div class="card">
		<div class="header">
			<h2>
				{{__('CONFIGURATION')}}
				<small>{{__('Set your basic email information.')}}</small>
			</h2>
		</div>
		{!! Form::model($config, ['route' => 'configuration.email', 'method' => 'POST']) !!}
		<div class="body">
			<div class="row">
				{!! Form::label('from', __('From'), ['class' => 'col-md-3']) !!}
				<div class="col-md-9">
					<div class="form-group row">
						<div class="col-sm-6 form-float">
							<div class="form-line {{ $errors->has('from_address') ? ' error ' : '' }}">
								{!! Form::email('from_address', null, ['class' => 'form-control', 'maxlength' => 200, 'placeholder' => __('Email Address')]) !!}
							</div>
						</div>
						<div class="col-sm-6 form-float">
							<div class="form-line {{ $errors->has('from_name') ? ' error ' : '' }}">
								{!! Form::text('from_name', null, ['class' => 'form-control', 'maxlength' => 200, 'placeholder' => __('Name')]) !!}
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				{!! Form::label('reply_to', __('Reply To'), ['class' => 'col-md-3']) !!}
				<div class="col-md-9">
					<div class="form-group row">
						<div class="col-sm-6 form-float">
							<div class="form-line {{ $errors->has('reply_to_address') ? ' error ' : '' }}">
								{!! Form::email('reply_to_address', null, ['class' => 'form-control', 'maxlength' => 200, 'placeholder' => __('Email Address')]) !!}
							</div>
						</div>
						<div class="col-sm-6 form-float">
							<div class="form-line {{ $errors->has('reply_to_name') ? ' error ' : '' }}">
								{!! Form::text('reply_to_name', null, ['class' => 'form-control', 'maxlength' => 200, 'placeholder' => __('Name')]) !!}
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group row">
				{!! Form::label('header_url', __('Header Url'), array('class' => 'col-md-3')); !!}
				<div class="form-float col-md-9">
					<div class="form-line {{ $errors->has('header_url') ? ' error ' : '' }}">
						{!! Form::text('header_url', null, array('id' => 'header_url', 'class' => 'form-control', 'maxlength' => 200, 'placeholder' => __('Company Url'))) !!}
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 clearfix">
					{!! Form::label('header', __('Header')) !!}
					{!! Form::textarea('header', null, ['class' => 'tinymce']) !!}
				</div>
				
				<div class="col-md-12 clearfix">
					{!! Form::label('subcopy', __('Sub Copy')) !!}
					{!! Form::textarea('subcopy', null, ['class' => 'tinymce']) !!}
				</div>
				
				<div class="col-md-12 clearfix">
					{!! Form::label('footer', __('Footer')) !!}
					{!! Form::textarea('footer', null, ['class' => 'tinymce']) !!}
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<button type="submit" class="btn bg-purple">{{__('SUBMIT')}}</button>
				</div>
			</div>
		</div>
		{!! Form::close() !!}
	</div>
@endsection
@push('js')
	<script>
		var Page = function(){
		    var handleEditor = function(){
                tinymce.init({
                    selector: "textarea.tinymce",
                    theme: "modern",
                    height: 100,
                    plugins: [
                        'autolink link image preview hr anchor',
                        'searchreplace wordcount visualblocks visualchars code',
                        'insertdatetime nonbreaking table contextmenu directionality',
                        'paste textcolor colorpicker textpattern'
                    ],
                    toolbar1: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | preview | forecolor backcolor',
                    image_advtab: true,
	                menubar: false,
                    relative_urls : false,
                    remove_script_host : false,
                    convert_urls : true,
                });
		    };
		    
		    return {
		        init: function(){
		            handleEditor();
		        }
		    }
		}();
		
		$(document).ready(function(){
		    Page.init();
		});
	</script>
@endpush