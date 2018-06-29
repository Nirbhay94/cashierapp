@extends('layouts.master')
@section('page_title', __('Add Balance'))
@section('content')
	<div class="block-header">
		<h2>
			{{__('CUSTOMERS')}}
			<small class="clearfix">
				{{__('Balance')}}
			</small>
		</h2>
	</div>
	
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="card">
				<div class="header">
					<h2>
						{{__('ADD BALANCE')}}
						<small>{{__('You can update your customers balance which could later be used for POS or Invoice transaction.')}}</small>
					</h2>
				</div>
				<div class="body">
					{!! Form::open(['method' => 'POST']) !!}
					<div class="form-group row">
						{!! Form::label('customer_id', __('Customer'), array('class' => 'col-md-3')); !!}
						<div class="form-float col-md-9">
							<div class="form-line {{ $errors->has('customer_id') ? ' error ' : '' }}">
								{!! Form::select('customer_id', [], null, array('id' => 'customer_id', 'class' => 'form-control ms',  'placeholder' => __('Select Customer'), 'required')) !!}
							</div>
						</div>
					</div>
					
					<div class="form-group row">
						{!! Form::label('balance', __('Balance'), array('class' => 'col-md-3')); !!}
						<div class="form-float col-md-9">
							<div class="form-line {{ $errors->has('balance') ? ' error ' : '' }}">
								{!! Form::number('balance', null, array('id' => 'note', 'class' => 'form-control', 'maxlength' => 200, 'placeholder' => __('Set Amount'))) !!}
							</div>
						</div>
					</div>
					
					<div class="row clearfix">
						<div class="col-md-9 col-md-offset-3">
							<button type="submit" class="btn bg-purple">
								{{__('Update')}}
							</button>
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
            var handleBootstrapSelect = function(){
                $('#customer_id').select2({
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
                    handleBootstrapSelect();
                }
            }
        }();

        $(document).ready(function(){
            Page.init();
        });
	</script>
@endpush