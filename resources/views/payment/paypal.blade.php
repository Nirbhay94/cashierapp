@extends('layouts.master')
@section('page_title', __('Setup Paypal Processor'))
@section('content')
	<div class="block-header">
		<h2>
			{{__('PAYMENT')}}
			<small class="clearfix">
				{{__('Paypal')}}
			</small>
		</h2>
	</div>
	
	<div class="callout-block callout-info">
		<div class="icon-holder">
			<i class="fa fa-life-ring"></i>
		</div>
		<div class="content">
			<h4 class="callout-title">{{__('Paypal Payment')}}</h4>
			<p>{{__('You are required to setup a PayPal REST API app if you wish to receive money via PayPal.')}} {{HTML::link('https://developer.paypal.com/developer/applications')}}</p>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<!-- BEGIN SAMPLE FORM PORTLET-->
			<div class="card">
				<div class="header">
					<h2>
						<i class="fa fa-paypal"></i> <b>{{__('PayPal API')}}</b>
						<small>{{__('You can configure your paypal api details from here and enable services you would like.')}}</small>
					</h2>
				</div>
				{!! Form::model($credential, ['method' => 'POST']) !!}
					<div class="body">
						{{csrf_field()}}
						<div class="form-group">
							{!! Form::label('mode', __('Mode'), array('class' => 'col-md-3')); !!}
							<div class="col-md-9 form-float">
								{!! Form::select('mode', ['sandbox' => 'Sandbox', 'production' => 'Production'], null, array('id' => 'mode', 'class' => 'form-control')) !!}
							</div>
						</div>
						
						<div class="form-group">
							{!! Form::label('client_id', __('Paypal Client ID'), array('class' => 'col-md-3')); !!}
							<div class="col-md-9 form-float">
								<div class="form-line {{ $errors->has('client_id') ? ' error ' : '' }}">
									{!! Form::text('client_id', null, array('id' => 'client_id', 'class' => 'form-control')) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							{!! Form::label('client_secret', __('Paypal Client Secret'), array('class' => 'col-md-3')); !!}
							<div class="col-md-9 form-float">
								<div class="form-line {{ $errors->has('client_secret') ? ' error ' : '' }}">
									{!! Form::text('client_secret', null, array('id' => 'client_secret', 'class' => 'form-control')) !!}
								</div>
							</div>
						</div>
						
						<h2 class="card-inside-title">{{__('Configuration')}}</h2>
						<div class="form-group">
							{!! Form::label('enable', __('Enable Payment'), array('class' => 'col-xs-9')); !!}
							<div class="col-xs-3">
								<div class="switch">
									<label>
										{!! Form::checkbox('enable', '1', null) !!}
										<span class="lever switch-col-brown"></span>
									</label>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							{!! Form::label('currency', __('Select Currency'), array('class' => 'col-md-3')); !!}
							<div class="col-md-9 form-float">
								{!! Form::select('currency', array_combine_single($currencies), null, array('id' => 'currency', 'class' => 'form-control')) !!}
								<span class="help-block">{{__('Your locale currency will be converted to its equivalent on the point of payment.')}}</span>
							</div>
						</div>
						
						<div class="row clearfix">
							<div class="col-md-9 col-md-offset-3">
								<button type="submit" class="btn bg-teal btn-flat">{{__('Submit')}}</button>
							</div>
						</div>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
@endsection