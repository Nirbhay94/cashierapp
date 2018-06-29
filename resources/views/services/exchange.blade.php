@extends('layouts.master')
@section('page_title', __('Exchange'))
@section('content')
	<div class="block-header">
		<h2>
			{{__('SERVICES')}}
			<small>{{__('Exchange')}}</small>
		</h2>
	</div>
	
	<div class="callout-block callout-info">
		<div class="icon-holder">
			<i class="fa fa-life-ring"></i>
		</div>
		<div class="content">
			<h4 class="callout-title">{{__('Exchange Service')}}</h4>
			<p>{{__('Exchange rates are fetched from Google Finance API by default.')}}</p>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<!-- BEGIN SAMPLE FORM PORTLET-->
			<div class="card">
				<div class="header bg-teal">
					<h2>
						<i class="fa fa-money"></i> <b>OpenExchangeRates</b>
						<small>{{__('You need to get a free api key from OpenExchangeRates.org')}}</small>
					</h2>
				</div>
				<form class="form-horizontal" method="post">
					<div class="body">
						{{csrf_field()}}
						<div class="form-group">
							{!! Form::label('oer_key', __('API Key'), array('class' => 'col-md-3')); !!}
							<div class="col-md-9 form-float">
								<div class="form-line {{ $errors->has('oer_key') ? ' error ' : '' }}">
									{!! Form::text('oer_key', $keys['OER_KEY']['value'], array('id' => 'oer_key', 'class' => 'form-control')) !!}
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