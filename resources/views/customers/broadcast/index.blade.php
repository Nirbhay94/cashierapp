@extends('layouts.master')
@section('page_title', __('Send Broadcast'))
@section('content')
	<div class="block-header">
		<h2>
			{{__('CUSTOMER')}}
			<small class="clearfix">
				{{__('Broadcast')}}
			</small>
		</h2>
	</div>
	
	<div class="card">
		<div class="header">
			<h2>
				{{__('SEND BROADCAST')}}
				<small>{{__('Send broadcast messages to your customers.')}}</small>
			</h2>
		</div>
		{!! Form::open(['route' => 'customers.broadcast', 'method' => 'POST']) !!}
		<div class="body">
			<div class="row">
				{!! Form::label('customers', __('Customers'), ['class' => 'col-md-3']) !!}
				<div class="col-md-9">
					<div class="form-group row">
						<div class="col-sm-8 form-float">
							<div class="form-line {{ $errors->has('customers') ? ' error ' : '' }}">
								{!! Form::select('customers[]', [], null, ['id' => 'customers', 'class' => 'form-control ms', 'multiple']) !!}
							</div>
						</div>
						<div class="col-sm-4 form-float">
							<input type="checkbox" id="all_customers" value="yes" class="filled-in chk-col-purple" name="all_customers">
							<label for="all_customers">ALL</label>
						</div>
					</div>
				</div>
			</div>
			
			<div class="form-group row">
				{!! Form::label('subject', __('Subject'), array('class' => 'col-md-3')); !!}
				<div class="form-float col-md-9">
					<div class="form-line {{ $errors->has('subject') ? ' error ' : '' }}">
						{!! Form::text('subject', null, array('id' => 'subject', 'class' => 'form-control', 'maxlength' => 200, 'placeholder' => __('Enter subject...'))) !!}
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12 clearfix">
					{!! Form::label('body', __('Body')) !!}
					{!! Form::textarea('body', null, ['class' => 'tinymce']) !!}
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					<button type="submit" class="btn bg-purple">{{__('SUBMIT')}}</button>
				</div>
			</div>
		</div>
		{!! Form::close() !!}
	</div>
@endsection
@push('js')
	<script>
        var Page = function(){
            var handleBootstrapSelect = function(){
                $('#customers').select2({
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
            
            var handleEditor = function(){
                tinymce.init({
                    selector: "textarea.tinymce",
                    theme: "modern",
                    height: 200,
                    plugins: [
                        'autolink link image preview hr anchor',
                        'searchreplace wordcount visualblocks visualchars code',
                        'insertdatetime nonbreaking table contextmenu directionality',
                        'paste textcolor colorpicker textpattern'
                    ],
                    toolbar1: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | preview | forecolor backcolor',
                    image_advtab: true,
                    menubar: false,
                    relative_urls : false,
                    remove_script_host : false,
                    convert_urls : true,
                });
            };
            
            var handleCheckboxSelect = function () {
	            $('#all_customers').change(function(){
	                if($(this).is(':checked')){
	                    $('#customers').prop('disabled', true);
	                }else{
	                    $('#customers').prop('disabled', false);
                    }
	            });
            }

            return {
                init: function(){
                    handleBootstrapSelect();
                    handleEditor();
                    handleCheckboxSelect();
                }
            }
        }();

        $(document).ready(function(){
            Page.init();
        });
	</script>
@endpush