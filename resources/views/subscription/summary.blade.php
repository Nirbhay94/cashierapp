@extends('layouts.master')
@section('page_title', __('Overview'))
@push('css')
    <link rel="stylesheet" href="{{asset('plugins/morrisjs/morris.css')}}">
@endpush
@section('content')
    <div class="block-header">
        <h2>{{__('SUBSCRIPTION')}}</h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-green hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">playlist_add_check</i>
                </div>
                <div class="content">
                    <div class="text">{{__('Active Subscrption')}}</div>
                    <div class="number count-to" data-from="0" data-to="{{$statistics['active']}}" data-speed="15" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-orange hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">schedule</i>
                </div>
                <div class="content">
                    <div class="text">{{__('Trial Subscription')}}</div>
                    <div class="number count-to" data-from="0" data-to="{{$statistics['trial']}}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-red hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">clear</i>
                </div>
                <div class="content">
                    <div class="text">{{__('Expired Subscription')}}</div>
                    <div class="number count-to" data-from="0" data-to="{{$statistics['expired']}}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
    
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-cyan hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">money</i>
                </div>
                <div class="content">
                    <div class="text">{{__('Total Income')}}</div>
                    <div class="number">{!! money($statistics['total_income']) !!}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-8">
            <div class="card">
                <div class="header">
                    <h2>{{__('Sales Graph')}}</h2>
                </div>
                <div class="body">
                    <canvas id="line-chart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="header">
                    <h2>{{__('Subscription Chart')}}</h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="chart-responsive">
                                <canvas id="doughnut-chart" height="170"></canvas>
                            </div>
                        </div>
                    </div>
	                <div class="clearfix">
		                <p class="clearfix">
			                <strong class="material-icons pull-left" style="color: #00a65a;  font-size: 1.4em; margin-right: 5px">done</strong>
			                <span>{{__('Total Subscribed')}}</span>
			                <span class="pull-right">{{$subscribed}}</span>
		                </p>
		                
		                <p class="clearfix">
			                <strong class="material-icons pull-left" style="color: #f56954; font-size: 1.4em; margin-right: 5px">clear</strong>
			                <span>{{__('Total Unsubscribed')}}</span>
			                <span class="pull-right">{{$non_subscribed}}</span>
		                </p>
	                </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
	    var Page = function(){
	        var lineChart, lineData;
	        
	        var handleLineCart = function() {
                "use strict";
                
	            var context = $('#line-chart').get(0).getContext("2d");
                
                lineChart = new Chart(context, {
                    type: 'line',
                    data: {
                        labels: lineData.labels,
                        datasets: [{
                            label: "{{__('Total')}}",
                            data: lineData.data,
                            borderColor: 'rgba(156, 39, 176, 0.75)',
                            backgroundColor: 'rgba(156, 39, 176, 0.3)',
                            pointBorderColor: 'rgba(156, 39, 176, 0)',
                            pointBackgroundColor: 'rgba(156, 39, 176, 0.9)',
                            pointBorderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        legend: true,
                    }
                });
	        };
	        
	        var handleDoughnutChart = function(){
                "use strict";

                var canvas = $('#doughnut-chart').get(0).getContext('2d');
                var doughnutChart = new Chart(canvas, {
                    type: 'doughnut',
                    data:{
                        labels: [
                            '{{__('Subscribed')}}',
                            '{{__('Unsubscribed')}}'
                        ],
                        datasets: [{
                            data: [{{$subscribed}}, {{$non_subscribed}}],
                            backgroundColor: ["#00a65a","#f56954"]
                        }]
                    },
                    options: {
                        // Boolean - Whether we should show a stroke on each segment
                        segmentShowStroke    : true,
                        // String - The colour of each segment stroke
                        segmentStrokeColor   : '#fff',
                        // Number - The width of each segment stroke
                        segmentStrokeWidth   : 1,
                        // Number - The percentage of the chart that we cut out of the middle
                        percentageInnerCutout: 50, // This is 0 for Pie charts
                        // Number - Amount of animation steps
                        animationSteps       : 100,
                        // String - Animation easing effect
                        animationEasing      : 'easeOutBounce',
                        // Boolean - Whether we animate the rotation of the Doughnut
                        animateRotate        : true,
                        // Boolean - Whether we animate scaling the Doughnut from the centre
                        animateScale         : false,
                        // Boolean - whether to make the chart responsive to window resizing
                        responsive           : true,
                        // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                        maintainAspectRatio  : false,

                    }
                });
            };
	        
	        return {
	            construct: function(){
	                lineData = @json($sales);
                },
                
	            init: function () {
		            handleLineCart();
		            handleDoughnutChart();
                }
	        }
	    }();
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            Page.construct();
            Page.init();
        });
    </script>
@endpush