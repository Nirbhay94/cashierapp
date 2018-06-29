@extends('invoice.payment.layout.master')
@section('page_title', __('Invoice #:invoice Payment', ['invoice' => $invoice->token]))
@section('content')
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="card">
				@if($configuration && $configuration->business_logo)
					<div class="header text-center">
						<img src="{{url($configuration->business_logo)}}"  class="img-responsive" style="margin: auto"/>
					</div>
				@endif
				<div class="body">
					<div class="list-group">
						<div class="list-group-item">
							<div class="row">
								<strong class="col-xs-4" style="margin: 0">{{__('Customer')}}</strong>
								<div class="col-xs-8" style="margin: 0">{{$invoice->customer->name}}</div>
							</div>
						</div>
						<div class="list-group-item">
							<div class="row">
								<strong class="col-xs-4" style="margin: 0">{{__('Items')}}</strong>
								<div class="col-xs-8" style="margin: 0">
									@foreach($items as $item)
										<div class="chip">
											<span>{{$item->name.' ('.money($item->price, $user).') x '.$item->amount}}</span>
										</div>
									@endforeach
								</div>
							</div>
						</div>
						<div class="list-group-item">
							<div class="row">
								<strong class="col-xs-4" style="margin: 0">{{__('Discount')}}</strong>
								<div class="col-xs-8" style="margin: 0">{!! money($invoice->discount, $user) !!}</div>
							</div>
						</div>
						<div class="list-group-item">
							<div class="row">
								<strong class="col-xs-4" style="margin: 0">{{__('Sub Total')}}</strong>
								<div class="col-xs-8" style="margin: 0">{!! money($invoice->sub_total, $user) !!}</div>
							</div>
						</div>
						<div class="list-group-item">
							<div class="row">
								<strong class="col-xs-4" style="margin: 0">{{__('Tax')}}</strong>
								<div class="col-xs-8" style="margin: 0">{!! money($invoice->tax, $user) !!}</div>
							</div>
						</div>
						<div class="list-group-item">
							<div class="row">
								<strong class="col-xs-4" style="margin: 0">{{__('Total')}}</strong>
								<h4 class="col-xs-8" style="margin: 0">{!! money($invoice->total, $user) !!}</h4>
							</div>
						</div>
						<div class="list-group-item">
							<div class="row">
								<strong class="col-xs-4" style="margin: 0">{{__('Status')}}</strong>
								<div class="col-xs-8" style="margin: 0">
									<span class="label label-{{invoice_label($invoice->status)}}">
										{{strtoupper($invoice->status)}}
									</span>
								</div>
							</div>
						</div>
						<div class="list-group-item">
							<div class="row">
								<strong class="col-xs-4" style="margin: 0">{{__('Amount Paid')}}</strong>
								<h4 class="col-xs-8" style="margin: 0">{!! money($invoice->amount_paid, $user) !!}</h4>
							</div>
						</div>
					</div>
					
					@if($invoice->status != 'paid')
					<ul class="nav nav-tabs" role="tablist">
						@if($processor['paypal'])
							<li role="presentation" class="active">
								<a href="#paypal" data-toggle="tab">
									<i class="fa fa-cc-paypal"></i> {{__('PAYPAL')}}
								</a>
							</li>
						@endif
						
						@if($processor['stripe'])
							<li role="presentation">
								<a href="#stripe" data-toggle="tab">
									<i class="fa fa-cc-stripe"></i> {{__('STRIPE')}}
								</a>
							</li>
						@endif
						
						@if($processor['bank'])
							<li role="presentation">
								<a href="#bank" data-toggle="tab">
									<i class="fa fa-money"></i> {{__('BANK TRANSFER')}}
								</a>
							</li>
						@endif
					</ul>
					
					<!-- Tab panes -->
					<div class="tab-content">
						@if($payment = $processor['paypal'])
							<div role="tabpanel" class="tab-pane fade in active" id="paypal">
								@if(strtolower(currency($user, true)) != $payment['currency'])
									<span>
										{{__('The equivalent of your total in :currency will be processed', [
											'currency' => strtoupper($payment['currency'])
										])}}: <b>{{currency_format($payment['total'], $payment['currency'])}}</b>
									</span><br/>
								@endif
								<i>{{__('Extra payment will be reflected on your balance.')}}</i>
								
								<div class="text-center m-t-20 m-b-20">
									<div id="paypal-button"></div>
								</div>
								
								<script src="https://www.paypalobjects.com/api/checkout.js"></script>
								
								<script type="text/javascript">
                                    var CREATE_PAYMENT_URL  = '{{$payment['create_url']}}';
                                    var EXECUTE_PAYMENT_URL = '{{$payment['execute_url']}}';

                                    paypal.Button.render({
                                        env: '{{isset($payment['mode'])? $payment['mode']: 'sandbox'}}', // Or 'sandbox'
                                        commit: true, // Show a 'Pay Now' button
	                                    
                                        payment: function() {
                                            return paypal.request.post(CREATE_PAYMENT_URL).then(function(data) {
                                                return data.id;
                                            });
                                        },
	                                    
                                        onAuthorize: function(data) {
                                            return paypal.request.post(EXECUTE_PAYMENT_URL, {
                                                paymentID: data.paymentID,
                                                payerID:   data.payerID
                                            }).then(function(message) {
                                                Global.notifySuccess(message);
                                                
                                                window.location.reload();
                                            }).catch(function(error){
                                                Global.notifyDanger(error);
                                            });
                                        }
                                    }, '#paypal-button');
								</script>
							</div>
						@endif
						
						@if($payment = $processor['stripe'])
							<div role="tabpanel" class="tab-pane fade" id="stripe">
								@if(strtolower(currency($user, true)) != $payment['currency'])
									<span>
										{{__('The equivalent of your total in :currency will be processed', [
											'currency' => strtoupper($payment['currency'])
										])}}: <b>{{currency_format($payment['total'] / 100, $payment['currency'])}}</b>
									</span><br/>
								@endif
								<i>{{__('Extra payment will be reflected on your balance.')}}</i>
								<div class="text-center m-t-20 m-b-20">
									<form action="{{$payment['charge_url']}}" method="POST">
										{{csrf_field()}}
										<script
											src="https://checkout.stripe.com/checkout.js" class="stripe-button"
											data-key="{{$payment['public_key']}}"
											data-amount="{{$payment['total']}}"
											data-description="{{$payment['note']}}"
											data-currency="{{$payment['currency']}}"
											@if(isset($payment['name']))
												data-name="{{$payment['name']}}"
											@endif
											data-locale="auto">
										</script>
									</form>
								</div>
							</div>
						@endif
						
						@if($payment = $processor['bank'])
							<div role="tabpanel" class="tab-pane fade" id="bank">
								@if($invoice->allow_partial == 'yes')
									<i>{{__('Partial payments are allowed on this invoice!')}}</i>
								@endif
								{!! $payment['details'] !!}
							</div>
						@endif
					</div>
					@endif
				</div>
			</div>
		</div>
	</div>
@endsection