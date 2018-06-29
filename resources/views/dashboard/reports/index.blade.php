@extends('layouts.master')
@section('page_title', __('Profit Report'))
@section('content')
	<div class="block-header row">
		<div class="col-xs-12 col-sm-6">
			<h2>
				{{__('DASHBOARD')}}
				<small>{{__('Reports')}}</small>
			</h2>
		</div>
		<div class="col-xs-12 col-sm-6 align-right hidden-xs">
			{!! Form::open(['class' => 'form-inline', 'method' => 'GET']) !!}
			<div class="row">
				<div class="col-sm-5">
					{!! Form::text('from', request()->get('from'), ['placeholder' => __('From'), 'class' => 'form-control datetimepicker']) !!}
				</div>
				<div class="col-sm-5">
					{!! Form::text('to', request()->get('to'), ['placeholder' => __('To'), 'class' => 'form-control datetimepicker']) !!}
				</div>
				<div class="col-sm-2">
					{!! Form::submit(__('Go'), ['class' => 'btn bg-purple']) !!}
				</div>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="info-box-4">
				<div class="icon">
					<i class="material-icons col-purple">payment</i>
				</div>
				<div class="content">
					<div class="text">{{__('SALES')}}</div>
					<div class="number">{!! money($statistics['total_sales'], Auth::user()) !!}</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="info-box-4">
				<div class="icon">
					<i class="material-icons col-green">trending_up</i>
				</div>
				<div class="content">
					<div class="text">{{__('PROFIT')}}</div>
					<div class="number">{!! money($statistics['profit'], Auth::user()) !!}</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="info-box-4">
				<div class="icon">
					<i class="material-icons col-orange">loyalty</i>
				</div>
				<div class="content">
					<div class="text">{{__('TAX')}}</div>
					<div class="number">{!! money($statistics['tax'], Auth::user()) !!}</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="info-box-4">
				<div class="icon">
					<i class="material-icons col-teal">shopping_cart</i>
				</div>
				<div class="content">
					<div class="text">{{__('PURCHASES')}}</div>
					<div class="number">{{$statistics['purchases']}}</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="block-header">
		<h2>
			<small>{{__('Charts')}}</small>
		</h2>
	</div>
	
	<div class="card">
		<div class="header">
			<div class="row clearfix">
				<div class="col-xs-12 col-sm-6">
					<h2>
						{{__('PROFIT CHART')}}
						<small>{{__('Includes monthly invoice & POS payment')}}</small>
					</h2>
				</div>
				<div class="col-xs-12 col-sm-6 align-right hidden-xs">
					{!! Form::select('profits_chart_month', previous_months(), now()->month, ['id' => 'profits_chart_month']) !!}
				
				</div>
			</div>
		</div>
		<div class="body">
			<canvas id="line-chart" height="100"></canvas>
		</div>
	</div>
	
@endsection
@push('js')
	<script>
        var Page = function(){
            var lineData, lineChart;


            var handleLineCart = function() {
                "use strict";
                
                Page.drawLineChart();
            };

            var handleMonthSelect = function () {
                $('#profits_chart_month').change(function(){
                    var month = $(this).val(), card;

                    card = $(this).closest('.card');

                    card.waitMe({
                        effect: 'rotation',
                        text: '{{__('Loading')}}...',
                        bg: 'rgba(255,255,255,0.50)',
                        color: ['#555', '#9C27B0']
                    });

                    axios.get('{{route('dashboard.reports.line-data') . '?month='}}' + month)
                        .then(function (response){
                            lineData = response.data;

                            lineChart.destroy();
                            Page.drawLineChart();

                            card.waitMe('hide');
                        }).catch(function(error){
                        if (error.response) {
                            let response = error.response.data;

                            if ($.isPlainObject(response)) {
                                $.each(response.errors, function (key, value) {
                                    Global.notifyDanger(value[0]);
                                });
                            } else {
                                Global.notifyDanger(response);
                            }
                        }else{
                            console.log(error.message);
                        }

                        card.waitMe('hide');
                    });
                });
            };

            var handleDatetimePicker = function(){
                $('.datetimepicker').datetimepicker({
                    format: 'YYYY-MM-DD HH:mm:ss'
                });
            };
            
            return {
                construct: function(){
                    lineData = @json($charts['profits']);
                },
	            
                init: function(){
                    handleLineCart();
                    handleMonthSelect();
                    handleDatetimePicker();
                },

                drawLineChart: function(){
                    var context = $('#line-chart').get(0).getContext("2d");

                    lineChart = new Chart(context, {
                        type: 'line',
                        data: {
                            labels: lineData.labels,

                            datasets: [{
                                label: "{{__('Total')}}",
                                data: lineData.data,
                                borderColor: 'rgba(76, 175, 80, 0.75)',
                                backgroundColor: 'rgba(76, 175, 80, 0.3)',
                                pointBorderColor: 'rgba(76, 175, 80, 0)',
                                pointBackgroundColor: 'rgba(76, 175, 80, 0.9)',
                                pointBorderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            legend: true,
                        }
                    });
                },
            }
        }();

        $(document).ready(function () {
            Page.construct();
            Page.init();
        });
	</script>
@endpush