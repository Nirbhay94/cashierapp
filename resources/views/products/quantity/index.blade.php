@extends('layouts.master')
@section('page_title', __('Add Quantity'))
@section('content')
	<div class="block-header">
		<h2>
			{{__('PRODUCTS')}}
			<small class="clearfix">
				{{__('Quantity')}}
			</small>
		</h2>
	</div>
	
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="card">
				<div class="header">
					<h2>
						{{__('ADD QUANTITY')}}
						<small>{{__('You can add bulk quantity to products from here.')}}</small>
					</h2>
				</div>
				<div class="body">
					{!! Form::open(['method' => 'POST']) !!}
					<div class="clearfix repeater row">
						<div data-repeater-list="products" class="col-md-12">
							<div data-repeater-item class="form-group row">
								<div class="form-float col-sm-5">
									<div class="form-line {{ $errors->has('product_id') ? ' error ' : '' }}">
										{!! Form::select('product_id', [], null, array('class' => 'form-control product_id ms', 'placeholder' => __('Select Product'), 'required') ) !!}
									</div>
								</div>
								<div class="form-float col-sm-5">
									<div class="form-line {{ $errors->has('quantity') ? ' error ' : '' }}">
										{!! Form::number('quantity', null, array('class' => 'form-control', 'maxlength' => 200, 'placeholder' => __('Quantity'))) !!}
									</div>
								</div>
								<div class="col-sm-2 align-right">
									<a href="javascript:void(0)" class="material-icons col-red" data-repeater-delete>clear</a>
								</div>
							</div>
						</div>
						<div class="row clearfix">
							<div class="col-md-9 col-md-offset-3">
								<input data-repeater-create type="button" class="btn btn-primary" value="{{__('More Products')}}"/>
								<input type="submit" class="btn bg-purple" value="{{__('Update')}}"/>
							</div>
						</div>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
@endsection
@push('js')
	<script>
        var Page = function(){
            var handleFormRepeater = function(){
                $('.repeater').repeater({
                    initEmpty: false,

                    show: function () {
                        if($(this).slideDown()){
                            Page.initProductSelect();
                        }
                    },

                    hide: function (deleteElement) {
                        if(confirm('Are you sure?')) {
                            $(this).slideUp(deleteElement);
                        }
                    },

                    isFirstItemUndeletable: true
                })
            };

            return {
                init: function(){
                    handleFormRepeater();

                    Page.initProductSelect();
                },
	            
                initProductSelect: function(){
                    $('.product_id').select2({
                        minimumInputLength: 2,

                        ajax: {
                            url: '{{route('products.all.ajax.search')}}',
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
                },
            }
        }();

        $(document).ready(function(){
            Page.init();
        });
	</script>
@endpush