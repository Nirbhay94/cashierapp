@extends('layouts.master')
@section('page_title', __('Pos Receipt #') . $transaction->id)
@push('css')
	<style>
		@page {
			size: 80mm 296mm;
			margin: 0;
		}
		.sheet {
			margin: 0;
			overflow: hidden;
			position: relative;
			box-sizing: border-box;
			page-break-after: always;
		}
		
		@media screen {
			div.preview {
				background: #e0e0e0;
			}
			
			.sheet {
				background: white;
				box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
				margin: 5mm auto;
			}
		}
		@media print {
			.sheet {
				width: 80mm;
			}
		}
		
		.amount-breakdown{
			list-style: none;
			padding: 0px
		}
		
		.amount-breakdown li{
			border-bottom: 1px dotted #939393;
			padding-top: 10px;
		}
		
		.amount-breakdown li:last-child{
			border: none;
		}
	</style>
@endpush
@section('content')
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="card">
				<div class="header">
					<h2>
						{{__('RECEIPT #') . $transaction->id}}
					</h2>
					<ul class="header-dropdown m-r--5">
						<li>
							<a href="#" id="print">
								<i class="material-icons">print</i>
							</a>
						</li>
					</ul>
				</div>
				<div class="body preview">
					<div class="row sheet">
						<div class="col-md-12 text-center m-t-20">
							@if($header) {!! $header !!} @endif
						</div>
						<div class="col-md-12 m-t-20">
							<div class="row clearfix">
								<span class="col-md-3 font-bold">{{__('Customer')}}</span>
								<span class="col-md-9">{{$transaction->customer->name}}</span>
							</div>
							<table class="table table-striped m-t-10">
								<thead>
								<tr>
									<th>{{__('Name')}}</th>
									<th>{{__('Price')}}</th>
									<th>{{__('Quantity')}}</th>
									<th>{{__('Total')}}</th>
								</tr>
								</thead>
								<tbody>
								@foreach(json_decode($transaction->items) as $item)
								<tr>
									<td>{{$item->name}}</td>
									<td>{{$item->price}}</td>
									<td>{{$item->amount}}</td>
									<td>{{bcmul($item->price, $item->amount)}}</td>
								</tr>
								@endforeach
								</tbody>
							</table>
							<div>
								<i>{{$transaction->details}}</i>
							</div>
							<ul class="amount-breakdown">
								<li>{{__('Discount')}}: <span class="pull-right">{!! money($transaction->discount, $transaction->user) !!}</span></li>
								<li>{{__('Sub Total')}}: <span class="pull-right">{!! money($transaction->sub_total, $transaction->user) !!}</span></li>
								<li>{{__('Tax')}}: <span class="pull-right">{!! money($transaction->tax, $transaction->user) !!}</span></li>
								<li><h3>{{__('Total Bill')}}: <span class="pull-right">{!! money($transaction->total, $transaction->user) !!}</span></h3></li>
							</ul>
						</div>
						<div class="col-md-12 text-center m-t-20">
							@if($footer) {!! $footer !!} @endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@push('js')
	<script src="{{asset('plugins/jquery-print/jquery.print.min.js')}}" type="text/javascript"></script>
	
	<script>
        var Page = function() {
            var handlePageLayout = function () {
                $.AdminBSB.options.leftSideBar.breakpointWidth = 2000;
                $.AdminBSB.leftSideBar.checkStatusForResize(true);
            };
            
            var handlePagePrint = function(){
                $('#print').click(function(){
                    $.print('div.body.preview');
                });
            };
            
            return {
                init: function(){
                    handlePageLayout();
                    handlePagePrint();
                }
            }
        }();

        $(document).ready(function(){
            Page.init();
        });
	</script>
@endpush