@extends('layouts.master')
@section('page_title', __('POS'))
@push('css')
	<link rel="stylesheet" href="{{asset('css/shop/style.css')}}">
	
	<!-- Vue Transitions -->
	<style rel="stylesheet">
		body {
			padding-right: 0 !important
		}
		
		.slide-fade-enter-active {
			transition: all .3s ease;
		}
		
		.slide-fade-enter
			/* .slide-fade-leave-active below version 2.1.8 */ {
			transform: translateY(100px);
			opacity: 0;
		}
		
		@media (max-width: 992px) {
			.affix {
				position: relative;
			}
		}
		
		@media (min-width: 992px) {
			.affix {
				right: 0px;
				transition: all 1s;
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
	<div class="row" id="vue-container">
		<div class="col-md-8" id="products-container">
			<div class="row margin-bottom-5">
				<div class="col-sm-6 result-category">
					<h2>{{__('PRODUCTS')}}</h2>
					<small class="shop-bg-red badge-results">
						<span v-text="products.total"></span> {{__('Results')}}
					</small>
				</div>
				<div class="col-sm-6">
					<ul class="list-inline clear-both">
						<li class="grid-list-icons">
							<a href="#"  class="filter">
								<i class="fa fa-filter"></i>
							</a>
							<div class="webui-popover-content">
								<h6>{{__('Categories')}}</h6>
								<span v-for="category in products.categories">
									<input type="checkbox" v-model="filter.categories" :id="'cat_' + category.id" @change="applyFilter()" :value="category.id" class="filled-in chk-col-purple">
									<label :for="'cat_' + category.id" style="white-space: nowrap"><span v-text="category.name"></span> (<span v-text="category.products"></span>)</label>
								</span>
							</div>
						</li>
						<li class="sort-list-btn">
							<input type="text" v-model="filter.keywords" @change="applyFilter()" placeholder="{{__('Type a keyword..')}}" class="form-control"/>
						</li>
					</ul>
				</div>
			</div><!--/end result category-->
			
			<div class="filter-results">
				<transition-group tag="div" name="slide-fade" class="row illustration-v2 margin-bottom-30">
					<div class="col-md-4 col-sm-4" v-for="(product, id) in products.data" :key="product.id">
						<div class="product-img product-img-brd">
							<a href="#"><div class="product-preview" :style="{backgroundImage: 'url(' + getProductImage(product.images) + ')' }"></div></a>
							<a class="product-review" href="#" data-toggle="modal" @click="updateProductModal(id)" data-target="#product-view">{{__('Quick View')}}</a>
							<a class="add-to-cart" href="#" v-if="product.quantity > 0" @click.prevent="addItem(id)"><i class="fa fa-shopping-cart"></i>{{__('Add to cart')}}</a>
							<div class="shop-rgba-red rgba-banner" v-if="product.quantity < 1">{{__('Out Of Stock')}}</div>
							<div class="shop-rgba-dark-green rgba-banner" v-else-if="isNewProduct(product.created_at)">{{__('New')}}</div>
						</div>
						<div class="product-description product-description-brd margin-bottom-30">
							<div class="overflow-h margin-bottom-5">
								<div class="pull-left">
									<h4 class="title-price"><a href="#" v-text="product.name"></a></h4>
									<span class="gender text-uppercase" v-text="product.brand"></span>
									<span class="gender" v-text="products.categories[product.product_category_id].name"></span>
								</div>
								<div class="product-price">
									<span class="title-price" v-text="products.currency + product.price"></span>
								</div>
							</div>
							<ul class="list-inline product-ratings">
								<li class="pull-left">
									<i class="rating fa fa-folder"></i>
									<strong>{{__('Quantity')}}</strong>
									<span v-text="product.quantity"></span>
								</li>
								<li class="pull-right">
									<i class="rating fa fa-shopping-cart"></i>
									<strong>{{__('Sales')}}</strong>
									<span v-text="product.sales"></span>
								</li>
							</ul>
						</div>
					</div>
				</transition-group>
				
				<infinite-loading @infinite="infiniteHandler" ref="infiniteLoading">
					<h3 slot="no-more" class="text-center">{{__('No more results available!')}}</h3>
				</infinite-loading>
				
			</div>
		</div>
		
		
		<div class="col-md-4">
			<div class="card">
				<div class="header">
					<h2>
						{{__('CHECKOUT')}}
						<small>{{__('Complete sale process and print receipt')}}</small>
					</h2>
				</div>
				<div class="body">
					<div class="input-group">
						{!! Form::select('customer_id', [], null, array('id' => 'customer_id', 'class' => 'form-control ms',  'placeholder' => __('Select Customer'), 'required')) !!}
						<span class="input-group-btn">
							<button class="btn bg-purple" data-toggle="modal" data-target="#add-customer" type="button">
								{{__('Add')}}
							</button>
						</span>
					</div>
					
					<div class="items">
						<div class="media" v-for="(item, id) in checkout.items">
							<div class="media-left">
								<a href="javascript:void(0);">
									<img class="media-object img-circle" :src="getProductImage(item.product.images)" width="50" height="50">
								</a>
							</div>
							<div class="media-body">
								<h4 class="media-heading">
									<span v-text="item.product.name"></span>
									<input type="number" min="1" :max="item.product.quantity" v-model.number="item.quantity" class="pull-right text-center" style="width: 40px"/>
								</h4>
								<div class="clearfix">
									<b>{{__('Price:')}}</b> <span v-text="money(item.product.price)"></span>
									<b>{{__('Sum:')}}</b> <span v-text="money(item.product.price * item.quantity)"></span>
									<a class="label label-danger" @click="checkout.items.splice(id, 1)">{{__('Remove')}}</a>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row clearfix">
						<div class="col-md-12">
							<ul class="amount-breakdown">
								<li>{{__('Sub Total')}}: <span class="pull-right">@{{money(getCheckoutSubTotal())}}</span></li>
								<li>{{__('Tax')}}: <span class="pull-right">@{{money(getCheckoutTax())}}</span></li>
								<li v-if="checkout.customer.balance > 0">{{__('Balance')}}: <span class="pull-right" v-text="'-' + money(checkout.customer.balance)"></span></li>
								<li><h3>{{__('Total Bill')}}: <span class="pull-right">@{{money(getCheckoutTotal())}}</span></h3></li>
							</ul>
						</div>
						<div class="col-md-12">
							<div class="btn-group btn-group-justified" role="group" aria-label="Justified button group">
								<a href="javascript:void(0);" @click="resetCheckout()" class="btn bg-pink waves-effect" role="button">
									<i class="material-icons">clear</i> <span>{{__('CLEAR')}}</span>
								</a>
								<a href="javascript:void(0);" :data-toggle="(canCheckout())? 'modal': ''" data-target="#issue-invoice" :disabled="!canCheckout()" class="btn bg-teal waves-effect" role="button">
									<i class="material-icons">receipt</i> <span>{{__('INVOICE')}}</span>
								</a>
								<a href="javascript:void(0);" :data-toggle="(canCheckout())? 'modal': ''" data-target="#complete-transactions" :disabled="!canCheckout()" class="btn bg-purple waves-effect" role="button">
									<i class="material-icons">shopping_cart</i> <span>{{__('CHECKOUT')}}</span>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Product View -->
		<div class="modal fade" id="product-view" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="modal-label" v-text="productModal.title"></h4>
					</div>
					<div class="modal-body">
						<div class="well" v-text="productModal.description"></div>
						<div class="row clearfix">
							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('cost', __('Cost'), ['class' => 'control-label']) !!}
									<div class="form-float">
										<div class="form-line">
											{!! Form::text('cost', null, ['class' => 'form-control', 'disabled' => true, 'v-model' => 'productModal.cost']) !!}
										</div>
									</div>
								</div>
								<div class="form-group">
									{!! Form::label('weight', __('Weight'), ['class' => 'control-label']) !!}
									<div class="form-float">
										<div class="form-line">
											{!! Form::text('weight', null, ['class' => 'form-control', 'disabled' => true, 'v-model' => 'productModal.weight']) !!}
										</div>
									</div>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('price', __('Price'), ['class' => 'control-label']) !!}
									<div class="form-float">
										<div class="form-line">
											{!! Form::text('price', null, ['class' => 'form-control', 'disabled' => true, 'v-model' => 'productModal.price']) !!}
										</div>
									</div>
								</div>
								<div class="form-group">
									{!! Form::label('barcode', __('Barcode'), ['class' => 'control-label']) !!}
									<div class="form-float">
										<div class="form-line">
											{!! Form::text('barcode', null, ['class' => 'form-control', 'disabled' => true, 'v-model' => 'productModal.barcode']) !!}
										</div>
									</div>
								</div>
							</div>
						</div>
						<h6 class="card-inside-title">{{__('Product Taxes')}}</h6>
						<div tabindex="0" class="chip" v-for="id in productModal.taxes">
							<span>
								@{{products.taxes[id].name + '@'}}
								<b v-if="products.taxes[id].type == 'fixed'" v-text="products.currency"></b>
								<b v-else>%</b>@{{products.taxes[id].amount}}
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!-- New Customer -->
		<div class="modal fade" id="add-customer" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<form id="modal-form">
						<div class="modal-header">
							<h4 class="modal-title" id="modal-label">{{__('Add Customer')}}</h4>
						</div>
						<div class="modal-body">
							<div class="row clearfix">
								<div class="col-md-6">
									<div class="form-group row">
										{!! Form::label('name', __('Name'), array('class' => 'col-md-12')); !!}
										<div class="form-float col-md-12">
											<div class="form-line {{ $errors->has('name') ? ' error ' : '' }}">
												{!! Form::text('name', null, array('id' => 'name', 'class' => 'form-control', 'maxlength' => 100, 'placeholder' => __('Customers Name'), 'required')) !!}
											</div>
										</div>
									</div>
									
									<div class="file-thumbnail">
										<div class="file-thumbnail-body">
											<div class="file-thumbnail-list">
												<div class="file-thumbnail-placeholder">
													{{__('Attach Square Size Images')}}
												</div>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="file-thumbnail-footer">
											<a href="#" id="filemanager" class="item popup_selector">
												<i class="fa fa-plus"></i>
											</a>
											<div class="clearfix"></div>
										</div>
									</div>
									
									<div class="form-group row">
										{!! Form::label('email', __('Email'), array('class' => 'col-md-12')); !!}
										<div class="form-float col-md-12">
											<div class="form-line {{ $errors->has('email') ? ' error ' : '' }}">
												{!! Form::email('email', null, array('id' => 'email', 'class' => 'form-control', 'maxlength' => 100, 'placeholder' => __('Customers Email (if available)'))) !!}
											</div>
										</div>
									</div>
									
									<div class="form-group row">
										{!! Form::label('phone_number', __('Phone'), array('class' => 'col-md-12')); !!}
										<div class="form-float col-md-12">
											<div class="form-line {{ $errors->has('phone_number') ? ' error ' : '' }}">
												{!! Form::text('phone_number', null, array('id' => 'phone_number', 'class' => 'form-control', 'maxlength' => 100, 'placeholder' => __('Customers Phone (if available)'))) !!}
											</div>
										</div>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group row">
										{!! Form::label('location', __('Location'), array('class' => 'col-md-12')); !!}
										<div class="form-float col-md-12">
											<div class="form-line {{ $errors->has('location') ? ' error ' : '' }}">
												{!! Form::textarea('location', null, array('id' => 'location', 'class' => 'form-control', 'maxlength' => 200, 'placeholder' => __('Enter Location (optional)'), 'rows' => 3)) !!}
											</div>
										</div>
									</div>
									
									<div class="form-group row">
										{!! Form::label('city', __('City'), array('class' => 'col-md-12')); !!}
										<div class="form-float col-md-12">
											<div class="form-line {{ $errors->has('city') ? ' error ' : '' }}">
												{!! Form::text('city', null, array('id' => 'city', 'class' => 'form-control', 'maxlength' => 100, 'placeholder' => __('Enter City (optional)'))) !!}
											</div>
										</div>
									</div>
									
									<div class="form-group row">
										{!! Form::label('zip', __('Zip'), array('class' => 'col-md-12')); !!}
										<div class="form-float col-md-12">
											<div class="form-line {{ $errors->has('zip') ? ' error ' : '' }}">
												{!! Form::text('zip', null, array('id' => 'zip', 'class' => 'form-control', 'maxlength' => 100, 'placeholder' => __('Enter Zip (optional)'))) !!}
											</div>
										</div>
									</div>
									
									<div class="form-group row">
										{!! Form::label('country', __('Country'), array('class' => 'col-md-12')); !!}
										<div class="form-float col-md-12">
											<div class="form-line {{ $errors->has('country') ? ' error ' : '' }}">
												{!! Form::text('country', null, array('id' => 'country', 'class' => 'form-control', 'maxlength' => 100, 'placeholder' => __('Enter Country (optional)'))) !!}
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" id="form-submit" class="btn bg-purple waves-effect">{{__('SUBMIT')}}</button>
							<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">{{__('CLOSE')}}</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<!-- Issue Invoice -->
		<div class="modal fade" id="issue-invoice" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<form id="modal-form">
						<div class="modal-header">
							<h4 class="modal-title" id="modal-label">{{__('ISSUE INVOICE')}}</h4>
						</div>
						<div class="modal-body">
							<div class="form-group row">
								{!! Form::label('details', __('Details'), array('class' => 'col-md-3')); !!}
								<div class="form-float col-md-9">
									<div class="form-line {{ $errors->has('details') ? ' error ' : '' }}">
										{!! Form::textarea('details', null, array('id' => 'details', 'class' => 'form-control', 'maxlength' => 200, 'placeholder' => __('Enter details of transaction...'), 'rows' => 3, 'required')) !!}
									</div>
								</div>
							</div>
							
							<div class="form-group row">
								{!! Form::label('note', __('Note'), array('class' => 'col-md-3')); !!}
								<div class="form-float col-md-9">
									<div class="form-line {{ $errors->has('note') ? ' error ' : '' }}">
										{!! Form::textarea('note', null, array('id' => 'note', 'class' => 'form-control', 'maxlength' => 200, 'placeholder' => __('A short note on invoice...'), 'rows' => 3, 'required')) !!}
									</div>
								</div>
							</div>
							
							<div class="form-group row">
								{!! Form::label('allow_partial', __('Allow Partial'), array('class' => 'col-md-3')); !!}
								<div class="form-float col-md-9">
									<div class="form-line {{ $errors->has('allow_partial') ? ' error ' : '' }}">
										@php $options = array_combine_single(['yes', 'no']) @endphp
										{!! Form::select('allow_partial', $options, null, array('id' => 'allow_partial', 'class' => 'form-control', 'required') ) !!}
									</div>
								</div>
							</div>
							
							<input type="hidden" name="customer_id" :value="checkout.customer.id" />
							
							<div class="hidden-xs" v-for="(item, id) in checkout.items">
								<input type="hidden" :name="'items['+id+'][product_id]'" :value="item.product.id" />
								<input type="hidden" :name="'items['+id+'][quantity]'" :value="item.quantity" />
							</div>
						</div>
						<div class="modal-footer">
							<div class="row">
								<div class="col-md-6">
									<input type="checkbox" name="notify" class="filled-in chk-col-purple" id="notify_invoice" checked="checked"/>
									<label for="notify_invoice">{{__('Send Notification')}}</label>
								</div>
								<div class="col-md-6">
									<button type="submit" id="form-submit" class="btn bg-purple waves-effect">{{__('SUBMIT')}}</button>
									<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">{{__('CLOSE')}}</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<!-- Issue Invoice -->
		<div class="modal fade" id="complete-transactions" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<form id="modal-form">
						<div class="modal-header">
							<h4 class="modal-title" id="modal-label">{{__('COMPLETE TRANSACTION')}}</h4>
						</div>
						<div class="modal-body">
							<div class="form-group row">
								{!! Form::label('total', __('Total Bill'), array('class' => 'col-md-3')); !!}
								<div class="form-float col-md-9">
									<div class="form-line {{ $errors->has('total') ? ' error ' : '' }}">
										{!! Form::text('total', null, array('id' => 'total', 'class' => 'form-control', 'placeholder' => __('Total Amount Payable'), ':value' => 'money(getCheckoutTotal())','rows' => 3, 'disabled' => true)) !!}
									</div>
								</div>
							</div>
							
							<div class="form-group row">
								{!! Form::label('details', __('Details'), array('class' => 'col-md-3')); !!}
								<div class="form-float col-md-9">
									<div class="form-line {{ $errors->has('details') ? ' error ' : '' }}">
										{!! Form::textarea('details', null, array('id' => 'details', 'class' => 'form-control', 'maxlength' => 200, 'placeholder' => __('Enter details of transaction...'), 'rows' => 3, 'required')) !!}
									</div>
								</div>
							</div>
							
							<div class="form-group row">
								{!! Form::label('coupon', __('Coupon'), array('class' => 'col-md-3')); !!}
								<div class="col-md-9">
									<div class="row">
										<div class="form-float col-xs-6">
											<div class="form-line {{ $errors->has('coupon') ? ' error ' : '' }}">
												{!! Form::text('coupon', null, array('id' => 'coupon', 'class' => 'form-control', '@change' => 'updateCouponDetails', 'v-model' => 'checkout.coupon.code', 'maxlength' => 200, 'placeholder' => __('Enter Coupon (optional)'), 'rows' => 3)) !!}
											</div>
										</div>
										<div class="form-float col-xs-6">
											<div class="form-line {{ $errors->has('discount') ? ' error ' : '' }}">
												{!! Form::number('discount', null, array('id' => 'discount', 'class' => 'form-control', 'placeholder' => __('Discount'), 'v-model' => 'checkout.coupon.discount', 'rows' => 3, 'disabled' => true)) !!}
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div class="form-group row">
								{!! Form::label('amount_paid', __('Amount Paid'), array('class' => 'col-md-3')); !!}
								<div class="form-float col-md-9">
									<div class="form-line {{ $errors->has('amount_paid') ? ' error ' : '' }}">
										{!! Form::number('amount_paid', null, array('id' => 'amount_paid', 'class' => 'form-control', ':min' => 'getCheckoutTotal() - checkout.coupon.discount', 'maxlength' => 200, 'rows' => 3, 'required')) !!}
									</div>
									<span>{{__('Extra amount will reflect on customers balance.')}}</span>
								</div>
							</div>
							
							<div class="form-group row">
								{!! Form::label('note', __('Note'), array('class' => 'col-md-3')); !!}
								<div class="form-float col-md-9">
									<div class="form-line {{ $errors->has('note') ? ' error ' : '' }}">
										{!! Form::textarea('note', null, array('id' => 'note', 'class' => 'form-control', 'maxlength' => 200, 'placeholder' => __('Enter transaction note...'), 'rows' => 3, 'required')) !!}
									</div>
								</div>
							</div>
							
							<input type="hidden" name="customer_id" :value="checkout.customer.id" />
							
							<div class="hidden-xs" v-for="(item, id) in checkout.items">
								<input type="hidden" :name="'items['+id+'][product_id]'" :value="item.product.id" />
								<input type="hidden" :name="'items['+id+'][quantity]'" :value="item.quantity" />
							</div>
						</div>
						<div class="modal-footer">
							<div class="row">
								<div class="col-md-6">
									<input type="checkbox" name="notify" class="filled-in chk-col-purple" id="notify_transaction" checked="checked"/>
									<label for="notify_transaction">{{__('Send Notification')}}</label>
								</div>
								<div class="col-md-6">
									<button type="submit" id="form-submit" class="btn bg-purple waves-effect">{{__('SUBMIT')}}</button>
									<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">{{__('CLOSE')}}</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<!-- Checkout Successful -->
		<div class="modal fade" id="checkout-successful" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content modal-col-purple">
					<div class="modal-header">
						<h4 class="modal-title"></h4>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer"></div>
				</div>
			</div>
		</div>
		
	</div>
@endsection
@push('js')
	<script>
        function processSelectedFile(path, field) {
            var prefix = '/{{collect((array) config('elfinder.dir'))->first()}}/';

            if(!path.startsWith(prefix)){
                path = encodeURI(prefix + path);
            }

            var container = $('#'+field).closest('.file-thumbnail');
            var list = container.find('.file-thumbnail-list');
            var placeholder = list.find('.file-thumbnail-placeholder');
            var thumbnail = Page.getThumbnailFromPath(path);

            if(thumbnail){
                placeholder.hide();

                var item = '<div class="item" style="background-image: url('+thumbnail+')">';
                item += '<input type="hidden" name="media[]" value="'+path+'">';
                item += '<button type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>';
                item += '</div>';

                list.append(item);

                Page.initRemoveListener();
            }else{
                Global.notifyDanger('{{_('The file type is not supported!')}}')
            }
        }
	</script>
	
	<script>
		var Page = function(){
		    var vue;
		    
		    var handlePageLayout = function(){
                $.AdminBSB.options.leftSideBar.breakpointWidth = 2000;
                $.AdminBSB.leftSideBar.checkStatusForResize(true);
		    };

            var handleBootstrapPopover = function(){
                $('.filter').webuiPopover();
            };

            var handleNewCustomer = function(){
                var action = '{{route('customers.list')}}';
                var modal = $('#add-customer');
                var form = modal.find('#modal-form');

                modal.on('show.bs.modal', function (e) {
                    Global.bindModalAjaxForm(form, action, 'POST', null, modal);
                });
            };

            var handleIssueInvoice = function (){
                var action = '{{route('pos.invoice')}}';
                var modal = $('#issue-invoice');
                var form = modal.find('#modal-form');

                modal.on('show.bs.modal', function (e) {
                    Page.bindCheckoutForm(form, action, 'POST', 'invoice', modal);
                });
            };
            
            var handleCompleteCheckout = function (){
                var action = '{{route('pos.checkout')}}';
                var modal = $('#complete-transactions');
                var form = modal.find('#modal-form');

                modal.on('show.bs.modal', function (e) {
                    Page.bindCheckoutForm(form, action, 'POST', 'checkout', modal);
                });
            };
            
            var handlePopupSelector = function(){
                $('.popup_selector').on('click', function (e) {
                    e.preventDefault();

                    var id = $(this).attr('id');
                    var url = '/elfinder/popup/'+id;

                    $.colorbox({
                        href: url,
                        fastIframe: true,
                        iframe: true,
                        width: '90%',
                        height: '80%'
                    });
                });
            };
            
            var handleCustomerSelect = function(){
                $('#customer_id').change(function(){
                    Page.updateCustomerData()
                });
            };
            
            var handleBootstrapSelect = function(){
                $('#customer_id').select2({
	                width: '100%',
                    minimumInputLength: 2,
	                
                    ajax: {
                        url: '{{route('customers.list.ajax.search')}}',
                        dataType: 'json',

                        data: function (params) {
                            return {
                                q: $.trim(params.term)
                            };
                        },

                        processResults: function (data) {
                            return {
                                results: data
                            };
                        },

                        cache: true
                    }
                });
            };

            return {
			    init: function(){
			        handlePageLayout();
			        handlePopupSelector();
                    handleBootstrapPopover();
                    handleBootstrapSelect();
                    handleCustomerSelect();
                    handleNewCustomer();
                    handleIssueInvoice();
                    handleCompleteCheckout();
                },
	            
                getThumbnailFromPath: function(input){
                    if(input.match(/^([\\\/][a-z_\-0-9\.%]+)+\.(jpg|png|jpeg)$/i)) {
                        return $(location).attr('protocol') + '//' + $(location).attr('host') + input;
                    }else{
                        return false;
                    }
                },

	            bindCheckoutForm: function(form, action, method, type, modal){
                    let submit = form.find('#form-submit');

                    modal = (modal)? modal: $('#alter-row');

                    form.validate({
                        highlight: function (input) {
                            $(input).parents('.form-line').addClass('error');
                        },
                        unhighlight: function (input) {
                            $(input).parents('.form-line').removeClass('error');
                        },
                        errorPlacement: function (error, element) {
                            $(element).parents('.form-float').append(error);
                        }
                    });

                    form.ajaxForm({
                        dataType: 'json',
                        url: action,
                        method: method,

                        beforeSubmit: function(){
                            submit.attr('disabled', true);
                        },
	                    
                        error: function(xhr){
                            var response = xhr.responseJSON;

                            if($.isPlainObject(response)){
                                $.each(response.errors, function(key, value){
                                    var input = form.find(':input#'+key),
                                        error = '', body;

                                    if(input.length){
                                        error += '<label id="name-error" class="error" for="'+key+'">';
                                        error += value[0];
                                        error += '</label>';

                                        input.closest('.form-line').addClass('error');
                                        input.closest('.form-float').append(error);
                                    }else{
                                        body = modal.find('.modal-body');

                                        error += '<div class="alert alert-danger alert-dismissible" role="alert">';
                                        error += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
                                        error += value[0];
                                        error += '</div>';

                                        body.prepend(error);
                                    }

                                    Global.notifyDanger(value[0]);
                                });
                            }else{
                                modal.modal('hide');
                                Global.notifyDanger(response);
                            }

                            submit.attr('disabled', false);
                        },

                        success: function(response){
                            submit.attr('disabled', false);

                            Global.resetForm(form);
	                        
                            modal.modal('hide');
                            
                            if(response.length){
                                let link = '', successModal = $('#checkout-successful');
                                
                                successModal.on('hide.bs.modal', function(){
                                    vue.resetCheckout();
                                });
                                
                                if(type == 'invoice'){
                                    link += '<a class="btn btn-link waves-effect" href="' + response + '">';
                                    link += '{{__('DOWNLOAD')}}';
                                    link += '</a>';

                                    successModal.find('.modal-header h4').html('{{__('Successful!')}}');
                                    successModal.find('.modal-body').html('{{__('Invoice has been set!')}}');
                                    successModal.find('.modal-footer').html(link);

                                    successModal.modal('show');
                                }else if(type == 'checkout'){
                                    link += '<a  class="btn btn-link waves-effect" href="javascript:Global.openPrintWindow(\'' + response + '\')">';
                                    link += '{{__('PRINT')}}';
                                    link += '</a>';

                                    successModal.find('.modal-header h4').html('{{__('Successful!')}}');
                                    successModal.find('.modal-body').html('{{__('Transaction was completed!')}}');
                                    successModal.find('.modal-footer').html(link);

                                    successModal.modal('show');
                                }
                            }
                        }
                    });
                },
	            
	            updateCustomerData : function(){
			        let value = $('#customer_id').val();
			        
                    axios.post('{{route('customers.list.ajax.get')}}', {
                        id: value
                    }).then(function (response){
                        vue.checkout.customer = response.data;
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
                    });
	            },
	            
                initRemoveListener: function(){
                    $('.item .close').on('click', function(e){
                        e.preventDefault();

                        var item = $(this).closest('div.item');
                        var container = $(this).closest('.file-thumbnail');
                        var list = container.find('.file-thumbnail-list');
                        var placeholder = list.find('.file-thumbnail-placeholder');

                        if(list.find('.item').length < 2 ){
                            placeholder.show();
                        }

                        item.remove();
                    })
                },

                vue: function (){
			        vue = new Vue({
				        el: '#vue-container',
				        data: {
				            products: {
				                total: 0,
                                data: [],
                                categories: @json($categories),
					            taxes: @json($taxes),
                                current: 0,
					            next: true,
					            currency: '{{ currency(Auth::user()) }}',
				            },
					        
					        productModal: {
				                title: '',
                                description: '',
                                cost: '',
						        price: '',
						        barcode: '',
						        weight: '',
						        taxes: []
					        },
					        
					        checkout: {
				                items: [],
						        customer: [],
						        coupon: {
                                    details: [],
                                    code: '',
							        discount: 0,
						        }
					        },
					        
					        filter: {
					            keywords: '',
					            categories: @json(array_keys($categories)),
					        },
					        
					        list: []
				        },
				        methods: {
				            money: function(value){
				                return this.products.currency + value;
				            },
					        
				            addItem: function(id){
				                let product = vue.products.data[id];
				                
				                let data = {
				                    product: product,
					                quantity: 1
				                };
				                
				                vue.checkout.items = vue.checkout.items.concat(data);
				            },
					        
					        canCheckout: function(){
                                return Boolean(this.checkout.customer.id && this.checkout.items.length);
					        },
					        
					        resetCheckout: function(){
                                this.checkout.items = [];
                                this.checkout.customer = [];
                                Page.updateCustomerData();
					        },
					        
					        getCheckoutSubTotal: function(){
				                let subTotal = 0, items;
				                
				                if(items = this.checkout.items){
                                    for(i in items){
                                        subTotal += items[i].product.price * items[i].quantity;
                                    }
				                }
						        
				                return subTotal;
					        },
					        
					        getCheckoutTotal: function(){
				                let subTotal = this.getCheckoutSubTotal();
				                let tax = this.getCheckoutTax(), balance = 0;
				                
				                if(this.checkout.customer.balance) {
                                    balance = (this.checkout.customer.balance);
                                }
                                
                                let total = subTotal + tax - balance;
				                
				                total = (total < 0)? 0: total;
				                
				                return  Math.round(total * 100) / 100;
					        },
					        
					        
					        getCheckoutTax: function(){
				                let taxes = [], tax = 0, items, i;
				                let subTotal = this.getCheckoutSubTotal();

                                if(items = this.checkout.items){
                                    for(i in items){
                                        if(items[i].product.taxes){
                                            let ids = items[i].product.taxes.split(',');

                                            for(j = 0; j < ids.length; j++){
                                                taxes[ids[j]] = this.products.taxes[ids[j]];
                                            }
                                        }
                                    }
                                }
                                
                                for(i in taxes){
                                    if(taxes[i].type === 'percentage'){
                                        tax += (taxes[i].amount * subTotal) / 100;
                                    }else if(taxes[i].type === 'fixed'){
                                        tax += taxes[i].amount;
                                    }
                                }
                                
                                return tax;
					        },
					        
					        updateCouponDetails: function(){
                                axios.post('{{route('products.coupon.ajax.get')}}', {
                                    code: vue.checkout.coupon.code
                                }).then(function (response){
                                    vue.checkout.coupon.details = response.data;

                                    let total = vue.getCheckoutSubTotal();
                                    let details = vue.checkout.coupon.details;
                                    
                                    if(details.type == 'percentage'){
                                        vue.checkout.coupon.discount = (details.discount * total) / 100;
                                    }else if(details.type == 'fixed'){
                                        vue.checkout.coupon.discount = details.discount;
	                                }else{
                                        vue.checkout.coupon.discount = 0;
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
                                    }else{
                                        console.log(error.message);
                                    }
                                });
					        },
					        
                            getProductImage: function(image){
                                let placeholder = 'images/product-placeholder.png';
                                
                                if(image && image !== ''){
                                    let images = image.split(',');

                                    let rand = Math.floor(Math.random() * images.length);

                                    let path = images[rand];

                                    return (!path.match(/^[\/\\].*$/))?
                                        '/' + path: path;
                                }

                                return placeholder;
                            },
					        
					        isNewProduct: function(date){
                                let current = moment(date, 'YYYY-MM-DD HH:mm:ss');
                                
                                return (moment().subtract('1', 'months') < current);
					        },
					        
                            infiniteHandler: function ($state) {
                                if(this.products.next){
                                    axios.post('{{route('products.all.ajax.fetch')}}', {
                                        page: this.products.current + 1,
	                                    categories: this.filter.categories,
                                        keywords: this.filter.keywords,
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
					        
					        updateProductModal: function(id){
                                let data = this.products.data[id];
						        
                                vue.productModal.title = data.name;
                                vue.productModal.description = data.description;
                                vue.productModal.cost = this.products.currency + data.cost;
                                vue.productModal.price = this.products.currency + data.price;
                                vue.productModal.barcode = data.barcode;
                                vue.productModal.weight = data.weight;
                                vue.productModal.taxes = data.taxes.split(',');
					        },
					        
					        applyFilter: function(){
                                vue.products.current = 0;
                                vue.products.data = [];
                                vue.products.next = true;
                                vue.products.total = 0;

                                setTimeout(function(){
                                    vue.resetGrid();
                                }, 200)
                            },

                            resetGrid: function(){
                                this.$nextTick(function() {
                                    this.$refs.infiniteLoading.$emit('$InfiniteLoading:reset');
                                });
					        }
				        }
			        });
	            }
			}
		}();
		
		$(document).ready(function(){
		    Page.vue();
            Page.init();
        });
	</script>
@endpush