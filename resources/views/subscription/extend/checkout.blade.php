@extends('layouts.master')
@section('page_title', __('Checkout'))
@section('content')
    <div class="row clearfix">
        <div class="col-md-10 col-md-offset-1">
            <div class="card">
                <div class="header">
                    <h2>
                        <b>{{__('Secured Checkout')}}</b>
                        <small>{{__('Confirm the following transaction details before you proceed')}}</small>
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a role="button" data-toggle="collapse" href="javascript:void(0);">
                                <i class="material-icons">lock</i>
                            </a>
                        </li>
                    </ul>
                </div>
                <form method="POST" id="payment-form">
                    <div class="body">
                        {{csrf_field()}}
                        <div class="list-group">
                            <div class="list-group-item">
                                <div class="row">
                                    <strong class="col-xs-4" style="margin: 0">{{__('Current Status')}}</strong>
                                    <p class="col-xs-8" style="margin: 0">{{$details['status']}}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="row">
                                    <strong class="col-xs-4" style="margin: 0">{{__('Transaction Statement')}}</strong>
                                    <p class="col-xs-8" style="margin: 0">{{$details['statement']}}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="row">
                                    <strong class="col-xs-4" style="margin: 0">{{__('Price')}}</strong>
                                    <p class="col-xs-8" style="margin: 0">{!! money($plan->price) !!}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="row">
                                    <strong class="col-xs-4" style="margin: 0">{{__('Quantity')}}</strong>
                                    <p class="col-xs-8" style="margin: 0">{{$details['quantity']}}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="row">
                                    <strong class="col-xs-4" style="margin: 0">{{__('Total Price')}}</strong>
                                    <p class="col-xs-8" style="margin: 0">{!! money($details['price']) !!}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="row">
                                    <strong class="col-xs-4" style="margin: 0">{{__('Current Balance')}}</strong>
                                    <div class="col-xs-3" style="margin: 0">
                                        {!! money(Auth::user()->currentPoints()) !!}
                                    </div>
                                    <div class="col-xs-5" style="margin: 0">
                                        @if(Auth::user()->currentPoints() >= $details['price'])
                                            <a href="javascript:void(0)" class="btn btn-success btn-sm btn-flat" id="apply-credit">
                                                {{__('Apply Credit')}}
                                            </a>
                                        @endif
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
                            <div class="clearfix"></div>
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
            
            var handleApplyCreditClick = function () {
                $('#apply-credit').click(function () {
                    swal({
                        title: "{{__('Are you sure?')}}",
                        text: "{{__('The price will be deducted from your credit.')}}",
                        type: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#2b982b",
                        confirmButtonText: "{{__('Proceed!')}}",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true
                    }, function () {
                        $.ajax({
                            url: '{{route('subscription.extend.checkout.apply-credit')}}',
                            method: 'POST',
                            success: function() {
                                swal("{{__('Successful')}}", "{{__('Transaction ended with a success!')}}", "success");
                                window.location.href = '{{route('home')}}';
                            },
                            error: function(xhr){
                                swal.close();

                                var response = xhr.responseJSON;

                                if($.isPlainObject(response)){
                                    $.each(response.errors, function(key, value){
                                        Global.notifyDanger(value[0]);
                                    });
                                }else{
                                    Global.notifyDanger(response);
                                }
                            }
                        });
                    });
                });
            };

            return {
                init: function(){
                    handleBraintree();
                    handleApplyCreditClick();
                }
            }
        }();

        $(document).ready(function () {
            Page.init();
        });
    </script>
@endpush