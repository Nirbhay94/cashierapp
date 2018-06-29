@extends('layouts.master')
@section('page_title', __('Braintree'))
@section('content')
	<div class="block-header">
		<h2>
			{{__('SERVICES')}}
			<small>{{__('Braintree')}}</small>
		</h2>
	</div>
	
	<div class="callout-block callout-info">
		<div class="icon-holder">
			<i class="fa fa-life-ring"></i>
		</div>
		<div class="content">
			<h4 class="callout-title">{{__('Braintree Processor')}}</h4>
			<p>{{__('A braintree API details is required')}} {{HTML::link('https://signups.braintreepayments.com/')}}</p>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<!-- BEGIN SAMPLE FORM PORTLET-->
			<div class="card">
				<div class="header bg-blue">
					<h2>
						<i class="fa fa-braintree"></i> <b>{{__('Braintree API')}}</b>
						<small>{{__('You can configure your braintree api details from here and enable services you would like.')}}</small>
					</h2>
				</div>
				<form class="form-horizontal" method="post">
					<div class="body">
						{{csrf_field()}}
						<div class="form-group">
							{!! Form::label('braintree_env', __('Braintree Env'), array('class' => 'col-md-3')); !!}
							<div class="col-md-9 form-float">
								{!! Form::select('braintree_env', ['production' => 'Production', 'sandbox' => 'Sandbox'], $keys['BRAINTREE_ENV']['value'], array('id' => 'braintree_env', 'class' => 'form-control')) !!}
							</div>
						</div>
						
						<div class="form-group">
							{!! Form::label('braintree_merchant_id', __('Braintree Merchant ID'), array('class' => 'col-md-3')); !!}
							<div class="col-md-9 form-float">
								<div class="form-line {{ $errors->has('braintree_merchant_id') ? ' error ' : '' }}">
									{!! Form::text('braintree_merchant_id', $keys['BRAINTREE_MERCHANT_ID']['value'], array('id' => 'braintree_merchant_id', 'class' => 'form-control')) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							{!! Form::label('braintree_public_key', __('Braintree Public Key'), array('class' => 'col-md-3')); !!}
							<div class="col-md-9 form-float">
								<div class="form-line {{ $errors->has('braintree_public_key') ? ' error ' : '' }}">
									{!! Form::text('braintree_public_key', $keys['BRAINTREE_PUBLIC_KEY']['value'], array('id' => 'braintree_public_key', 'class' => 'form-control')) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							{!! Form::label('braintree_private_key', __('Braintree Private Key'), array('class' => 'col-md-3')); !!}
							<div class="col-md-9 form-float">
								<div class="form-line {{ $errors->has('braintree_private_key') ? ' error ' : '' }}">
									{!! Form::text('braintree_private_key', $keys['BRAINTREE_PRIVATE_KEY']['value'], array('id' => 'braintree_private_key', 'class' => 'form-control')) !!}
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