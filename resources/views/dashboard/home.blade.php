@extends('layouts.master')
@section('page_title', __('Home'))
@push('css')
	<style rel="stylesheet">
		.slide-fade-enter-active {
			transition: all .3s ease;
		}
		.slide-fade-enter
			/* .slide-fade-leave-active below version 2.1.8 */ {
			transform: translateY(100px);
			opacity: 0;
		}
		div.body.list{
			height: 300px;
		}
	</style>
@endpush
@section('content')
	<div class="block-header">
		<h2>
			{{__('DASHBOARD')}}
			<small>{{__('Sales chart, statistics and reports')}}</small>
		</h2>
	</div>
	
	@include('includes.account-status')
	
	<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="info-box-4">
				<div class="icon">
					<span class="chart chart-line" data-chartcolor="purple">{{implode(',', $statistics['weekly_sales'])}}</span>
				</div>
				<div class="content">
					<div class="text">{{__('DAILY SALES')}}</div>
					<div class="number">{!! money($statistics['weekly_sales'][now()->dayOfWeek], Auth::user()) !!}</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="info-box-4">
				<div class="icon">
					<i class="material-icons col-purple">payment</i>
				</div>
				<div class="content">
					<div class="text">{{__('TOTAL SALES')}}</div>
					<div class="number">{!! money($statistics['total_sales'], Auth::user()) !!}</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="info-box-4">
				<div class="icon">
					<i class="material-icons col-purple">store</i>
				</div>
				<div class="content">
					<div class="text">{{__('PRODUCTS')}}</div>
					<div class="number">{{$statistics['products']}}</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="info-box-4">
				<div class="icon">
					<i class="material-icons col-purple">account_circle</i>
				</div>
				<div class="content">
					<div class="text">{{__('CUSTOMERS')}}</div>
					<div class="number">{{$statistics['customers']}}</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row clearfix">
		<div class="col-md-8">
			<div class="card">
				<div class="header">
					<div class="row clearfix">
						<div class="col-xs-12 col-sm-6">
							<h2>
								{{__('SALES CHART')}}
								<small>{{__('Includes monthly invoice & POS payment')}}</small>
							</h2>
						</div>
						<div class="col-xs-12 col-sm-6 align-right hidden-xs">
							{!! Form::select('sales_chart_month', previous_months(), now()->month, ['id' => 'sales_chart_month']) !!}
						</div>
					</div>
				</div>
				<div class="body">
					<canvas id="line-chart" height="130"></canvas>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card">
				<div class="header">
					<h2>
						{{__('INVOICES CHART')}}
						<small>{{__('Monitor invoices in ratio of the statuses')}}</small>
					</h2>
				</div>
				<div class="body">
					<div class="row clearfix">
						<div class="col-md-12">
							<div class="chart-responsive">
								<canvas id="doughnut-chart" height="250"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row clearfix" id="vue-container">
		<div class="col-md-6">
			<div class="card">
				<div class="header">
					<h2>
						{{__('TOP 10 PRODUCTS')}}
						<small>{{__('You may set filter as you wish.')}}</small>
					</h2>
					<ul class="header-dropdown m-r--5">
						<li class="dropdown">
							<a href="javascript:void(0);" class="filter" title="{{__('Filter Products')}}">
								<i class="material-icons">filter_list</i>
							</a>
							<div class="webui-popover-content">
								<span>
									<input v-model="products.filter.column" type="radio" value="sales" id="popular_product" checked="">
									<label for="popular_product">{{__('Sales')}}</label>
								</span>
								<span>
									<input v-model="products.filter.column" type="radio" value="quantity" id="quantity_product">
									<label for="quantity_product">{{__('Quantity')}}</label>
								</span>
								<span>
									<input v-model="products.filter.column" type="radio" value="price" id="price_product">
									<label for="price_product">{{__('Price')}}</label>
								</span>
								<h6>{{__('Sort')}}</h6>
								<span>
									<input v-model="products.filter.order" type="radio" id="asc_product" value="asc" class="radio-col-purple" checked="">
									<label for="asc_product">{{__('Ascending')}}</label>
								</span>
								<span>
									<input v-model="products.filter.order" type="radio" id="desc_product" value="desc" class="radio-col-purple">
									<label for="desc_product">{{__('Descending')}}</label>
								</span>
								<div class="clearfix">
									<a href="javascript:void(0)" @click="applyProductFilter" class="label bg-purple pull-right">{{__('SET')}}</a>
								</div>
							</div>
						</li>
					</ul>
				</div>
				<div class="body list" id="perfect-scroll-wrapper" ref="productScrollWrapper" infinite-wrapper>
					<transition-group tag="div" name="slide-fade">
						<div class="media" v-for="(product, id) in products.data" :key="product.id">
							<div class="media-left">
								<a href="javascript:void(0);">
									<img class="media-object img-circle" :src="getProductImage(product.images)" width="64" height="64">
								</a>
							</div>
							<div class="media-body">
								<h4 class="media-heading" v-text="product.name"></h4>
								<div>
									<span><b>{{__('Description')}}:</b> <span v-text="product.description"></span></span>
								</div>
								<div>
									<span class="label bg-purple m-r-5">{{__('Price')}}: <span v-text="money(product.price)"></span> </span>
									<span class="label bg-purple m-r-5">{{__('Quantity')}}: <span v-text="product.quantity"></span> </span>
									<span class="label bg-purple m-r-5">{{__('Sales')}}: <span v-text="product.sales"></span> </span>
								</div>
							</div>
						</div>
					</transition-group>
					
					<infinite-loading @infinite="productInfiniteHandler" ref="productInfiniteLoading">
						<h3 slot="no-more" class="text-center">{{__('No more results available!')}}</h3>
					</infinite-loading>
					
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card">
				<div class="header">
					<h2>
						{{__('TOP 10 CUSTOMERS')}}
						<small>{{__('You may set filter as you wish.')}}</small>
					</h2>
					<ul class="header-dropdown m-r--5">
						<li class="dropdown">
							<a href="javascript:void(0);" class="filter" title="{{__('Filter Customers')}}">
								<i class="material-icons">filter_list</i>
							</a>
							<div class="webui-popover-content">
								<span>
									<input v-model="customers.filter.column" type="radio" value="purchases" id="purchases_customer">
									<label for="purchases_customer">{{__('Purchases')}}</label>
								</span>
								<span>
									<input v-model="customers.filter.column" type="radio" value="balance" id="balance_customer">
									<label for="balance_customer">{{__('Balance')}}</label>
								</span>
								<h6>{{__('Sort')}}</h6>
								<span>
									<input  v-model="customers.filter.order"  type="radio" id="asc_customer" value="asc" class="radio-col-purple" checked="">
									<label for="asc_customer">{{__('Ascending')}}</label>
								</span>
								<span>
									<input  v-model="customers.filter.order"  type="radio" id="desc_customer" value="desc" class="radio-col-purple">
									<label for="desc_customer">{{__('Descending')}}</label>
								</span>
								<div class="clearfix">
									<a href="javascript:void(0)" @click="applyCustomerFilter" class="label bg-purple pull-right">{{__('SET')}}</a>
								</div>
							</div>
						</li>
					</ul>
				</div>
				<div class="body list" id="perfect-scroll-wrapper" ref="customerScrollWrapper" infinite-wrapper>
					<transition-group tag="div" name="slide-fade">
						<div class="media" v-for="(customer, id) in customers.data" :key="customer.id">
							<div class="media-body">
								<h4 class="media-heading" v-text="customer.name">Lorem Ipsum</h4>
								<div v-if="customer.email"><b>{{__('Email')}}:</b> <span v-text="customer.email">admin@mail.com</span></div>
								<div v-if="customer.phone_number"><b>{{__('Phone')}}:</b> <span v-text="customer.phone_number">08166337736</span></div>
								<div>
									<span class="label bg-purple m-r-5">{{__('Purchases')}}: <span v-text="customer.purchases"></span></span>
									<span class="label bg-purple m-r-5">{{__('Balance')}}: <span v-text="money(customer.balance)"></span></span>
								</div>
							</div>
							<div class="media-left">
								<a href="javascript:void(0);">
									<img class="media-object img-circle" :src="getCustomerImage(customer.images)" width="64" height="64">
								</a>
							</div>
						</div>
					</transition-group>
					
					<infinite-loading @infinite="customerInfiniteHandler" ref="customerInfiniteLoading">
						<h3 slot="no-more" class="text-center">{{__('No more results available!')}}</h3>
					</infinite-loading>
				</div>
			</div>
		</div>
	</div>
@endsection
@push('js')
	<script>
		var Page = function(){
		    var vue;
            var doughnutChart, lineChart;
            var doughnutData, lineData;
			
            var handleBootstrapPopover = function(){
                $('.filter').webuiPopover();
            };

            var handleSlimScroll = function(){
                $('div.body.list').slimScroll({
                    height: '300px'
                });
            };
            
            var handleLineCart = function() {
                "use strict";
                
                Page.drawLineChart();
            };
            
            var handleDoughnutChart = function(){
                "use strict";
	            
                Page.drawDoughnutChart()
            };
            
            var handleMonthSelect = function () {
	            $('#sales_chart_month').change(function(){
	                var month = $(this).val(), card;
	                
	                card = $(this).closest('.card');

                    card.waitMe({
                        effect: 'rotation',
                        text: '{{__('Loading')}}...',
                        bg: 'rgba(255,255,255,0.50)',
                        color: ['#555', '#9C27B0']
                    });
                    
                    axios.get('{{route('home.line-data') . '?month='}}' + month)
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
            
		    var handleResendVerificationEmail = function(){
		        $('#resend-verification-email').click(function(){
                    $.ajax({
                        url: '{{route('ajax.resend-verification-email')}}',
                        method: 'POST',
                        success: function() {
                            Global.notifySuccess('{{__('Sent successfully!')}}')
                        },
                        error: function(xhr){
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
		    }
		    
			return {
		        construct: function(){
                    lineData = @json($charts['sales']);
                    doughnutData = @json($charts['invoice']);
                },
				
			    init: function(){
                    handleMonthSelect();
                    handleBootstrapPopover();
                    // handleSlimScroll();
                    handleLineCart();
                    handleDoughnutChart();
                    handleResendVerificationEmail();
			    },
			    
				vue: function (){
		            vue = new Vue({
                        el: '#vue-container',
			            data: {
                            products: {
                                total: 0,
                                data: [],
                                current: 0,
                                next: true,
                                currency: '{{ currency(Auth::user()) }}',
                                filter: {
                                    column: 'sales',
	                                order: 'asc',
                                    categories: @json(array_keys($product_categories)),
                                },
                            },
				            
				            customers: {
                                total: 0,
                                data: [],
                                current: 0,
                                next: true,
                                filter: {
                                    column: 'purchases',
                                    order: 'asc'
                                }
                            },
			            },
                        mounted() {
                            new PerfectScrollbar(this.$refs.productScrollWrapper);
                            new PerfectScrollbar(this.$refs.customerScrollWrapper);
                        },
					
			            methods: {
                            money: function(value){
                                return this.products.currency + value;
                            },
				            
                            getProductImage: function(image){
                                let placeholder = '/images/product-placeholder.png';

                                if(image && image != ''){
                                    let images = image.split(',');

                                    let rand = Math.floor(Math.random() * images.length);

                                    let path = images[rand];

                                    return (!path.match(/^[\/\\].*$/))?
                                        '/' + path: path;
                                }

                                return placeholder;
                            },
				            
				            getCustomerImage: function(image){
                                let placeholder = '/images/default-avatar.png';

                                if(image && image != ''){
                                    let images = image.split(',');

                                    let rand = Math.floor(Math.random() * images.length);

                                    let path = images[rand];

                                    return (!path.match(/^[\/\\].*$/))?
                                        '/' + path: path;
                                }

                                return placeholder;
                            },
				            
                            productInfiniteHandler: function ($state) {
                                if(this.products.next){
                                    axios.post('{{route('products.all.ajax.fetch')}}', {
                                        page: this.products.current + 1,
                                        categories: this.products.filter.categories,
                                        column: this.products.filter.column,
                                        order: this.products.filter.order,
                                    }).then(function (response){
                                        var products = response.data;

                                        if(products.data.length && vue.products.next){
                                            vue.products.current = products.current_page;
                                            vue.products.data = vue.products.data.concat(products.data);
                                            vue.products.next = Boolean(products.next_page_url);
                                            vue.products.total = products.total;
                                        }else{
                                            vue.products.next = false;
                                        }

                                        $state.loaded();

                                        if(!vue.products.next){
                                            $state.complete();
                                        }
                                    }).catch(function(error){
                                        if(error.response){
                                            let response = error.response.data;

                                            if($.isPlainObject(response)){
                                                $.each(response.errors, function(key, value){
                                                    Global.notifyDanger(value[0]);
                                                });
                                            }else{
                                                Global.notifyDanger(response);
                                            }

                                            vue.products.next = false;

                                            $state.complete();
                                        }else{
                                            console.log(error.message);
                                        }
                                    });
                                }else{
                                    $state.complete();
                                }
                            },
				            
				            customerInfiniteHandler: function ($state) {
                                if(this.customers.next){
                                    axios.post('{{route('customers.list.ajax.fetch')}}', {
                                        page: this.customers.current + 1,
                                        column: this.customers.filter.column,
                                        order: this.customers.filter.order,
                                    }).then(function (response){
                                        var customers = response.data;

                                        if(customers.data.length && vue.customers.next){
                                            vue.customers.current = customers.current_page;
                                            vue.customers.data = vue.customers.data.concat(customers.data);
                                            vue.customers.next = Boolean(customers.next_page_url);
                                            vue.customers.total = customers.total;
                                        }else{
                                            vue.customers.next = false;
                                        }

                                        $state.loaded();

                                        if(!vue.customers.next){
                                            $state.complete();
                                        }
                                    }).catch(function(error){
                                        if(error.response){
                                            let response = error.response.data;

                                            if($.isPlainObject(response)){
                                                $.each(response.errors, function(key, value){
                                                    Global.notifyDanger(value[0]);
                                                });
                                            }else{
                                                Global.notifyDanger(response);
                                            }
	                                        
                                            vue.customers.next = false;

                                            $state.complete();
                                        }else{
                                            console.log(error.message);
                                        }
                                    });
                                }else{
                                    $state.complete();
                                }
                            },

                            applyProductFilter: function(){
                                vue.products.current = 0;
                                vue.products.data = [];
                                vue.products.next = true;
                                vue.products.total = 0;

                                setTimeout(function(){
                                    vue.resetProductList();
                                }, 200)
                            },
				            
				            applyCustomerFilter: function(){
					            vue.customers.current = 0;
                                vue.customers.data = [];
                                vue.customers.next = true;
                                vue.customers.total = 0;

                                setTimeout(function(){
                                    vue.resetCustomerList();
                                }, 200)
                            },
				            
                            resetProductList: function(){
                                this.$nextTick(function() {
                                    this.$refs.productInfiniteLoading.$emit('$InfiniteLoading:reset');
                                });
                            },
				            
				            resetCustomerList: function(){
                                this.$nextTick(function() {
                                    this.$refs.customerInfiniteLoading.$emit('$InfiniteLoading:reset');
                                });
                            }
			            }
		            });
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
			    },
				
			    drawDoughnutChart: function(){
                    var context = $('#doughnut-chart').get(0).getContext('2d');

                    doughnutChart = new Chart(context, {
                        type: 'doughnut',
                        data:{
                            labels: [
                                '{{__('Paid')}}',
                                '{{__('Partial')}}',
                                '{{__('Unpaid')}}',
                            ],
                            datasets: [{
                                data: [
                                    doughnutData.paid,
                                    doughnutData.partial,
                                    doughnutData.unpaid
                                ],
                                backgroundColor: ["#00a65a","#0ca6a1","#f56954","#ababa9"]
                            }]
                        },
                    });
			    }
			}
		}();
		
		$(document).ready(function () {
		    Page.vue();
		    Page.construct();
		    Page.init();
        });
	</script>
@endpush