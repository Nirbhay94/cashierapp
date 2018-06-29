@extends('layouts.master')
@section('page_title', __('Topup Balance'))
@section('content')
    <div class="block-header">
        <h2>{{__('SUBSCRIPTION')}}</h2>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2>
                        <b>{{__('Topup Balance')}}</b>
                        <small>{{__('Add funds to your account balance, in order to keep services running for longer period.')}}</small>
                    </h2>
                </div>
                <form method="POST" id="payment-form">
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-md-8 col-md-offset-2">
                                {{csrf_field()}}
                                <div class="row clearfix">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="info-box">
                                            <div class="icon bg-green">
                                                <i class="fa fa-money"></i>
                                            </div>
                                            <div class="content">
                                                <div class="text">{{__('CURRENT BALANCE')}}</div>
                                                <div class="number">
                                                    <b>{!! money(Auth::user()->currentPoints()) !!}</b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="info-box">
                                            <div class="icon bg-orange">
                                                <i class="material-icons">date_range</i>
                                            </div>
                                            <div class="content">
                                                <div class="text">{{__('LAST TRANSACTION')}}</div>
                                                <div class="number">
                                                    @if($transaction = Auth::user()->transactions(1)->first())
                                                        <span>{{\Carbon\Carbon::parse($transaction->created_at)->toFormattedDateString()}}</span>
                                                    @else
                                                        <span>{{__('Not Available')}}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('amount', __('Amount'), array('class' => 'col-md-3')); !!}
                                    <div class="col-md-9">
                                        <div class="form-line {{ $errors->has('amount') ? ' error ' : '' }}">
                                            <select class="form-control" name="amount" id="amount">
                                                @for($i = $payment_setting->amount_init; $i <= $payment_setting->amount_max; $i += $payment_setting->amount_inc)
                                                    <option value="{{$i}}">{!! money($i) !!}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <span class="help-block">{{__('Please note that credit are non refundable. Thank you')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bt-drop-in-wrapper">
                            <div id="bt-dropin"></div>
                        </div>
                        <div class="row clearfix">
                            <input type="hidden" id="nonce" name="payment_method_nonce" />
                            <div class="col-md-12">
                                {!! Form::button(__('Pay'), array('class' => 'btn btn-success btn-flat margin-bottom-1 pull-right', 'type' => 'submit', 'id' => 'payment-form-submit', 'disabled' => 'false')) !!}
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://js.braintreegateway.com/web/dropin/1.9.4/js/dropin.min.js"></script>

    <script>
        var Page = function(){
            var handleBraintree = function(){
                var form = document.querySelector('#payment-form');
                var client_token = "{{Braintree\ClientToken::generate()}}";

                braintree.dropin.create({
                    authorization: client_token,
                    selector: '#bt-dropin',
                    paypal: {
                        flow: 'vault'
                    }
                }, function (createErr, instance) {
                    if (createErr) {
                        console.log('Create Error', createErr);
                        return;
                    } else {
                        document.querySelector('#payment-form-submit').disabled = false;
                    }

                    form.addEventListener('submit', function (event) {
                        event.preventDefault();

                        // Disable the submit button...
                        document.querySelector('#payment-form-submit').disabled = true;

                        instance.requestPaymentMethod(function (err, payload) {
                            if (err) {
                                document.querySelector('#payment-form-submit').disabled = false;
                                console.log('Request Payment Method Error', err);
                                return;
                            }

                            // Add the nonce to the form and submit
                            document.querySelector('#nonce').value = payload.nonce;
                            form.submit();
                        });
                    });
                });
            };

            return {
                init: function(){
                    handleBraintree();
                }
            }
        }();

        $(document).ready(function () {
            Page.init();
        });
    </script>
@endpush