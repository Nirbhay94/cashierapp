@extends('layouts.master')
@section('page_title', __('Setup Bank Details'))
@section('content')
	<div class="block-header">
		<h2>
			{{__('PAYMENT')}}
			<small class="clearfix">
				{{__('Bank')}}
			</small>
		</h2>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<!-- BEGIN SAMPLE FORM PORTLET-->
			<div class="card">
				<div class="header">
					<h2>
						{{__('Bank Account Details')}}
						<small>{{__('You can set your bank account details but payment transactions has to be updated manually.')}}</small>
					</h2>
				</div>
				{!! Form::model($credential, ['method' => 'POST']) !!}
				<div class="body">
					{{csrf_field()}}
					<div class="row">
						<div class="col-md-12 clearfix">
							{!! Form::label('details', __('Details')) !!}
							{!! Form::textarea('details', null, ['class' => 'tinymce']) !!}
						</div>
					</div>
					
					
					<h2 class="card-inside-title">{{__('Configuration')}}</h2>
					<div class="form-group">
						{!! Form::label('enable', __('Enable Payment'), array('class' => 'col-xs-9')); !!}
						<div class="col-xs-3">
							<div class="switch">
								<label>
									{!! Form::checkbox('enable', '1', null) !!}
									<span class="lever switch-col-brown"></span>
								</label>
							</div>
						</div>
					</div>
					
					<div class="row clearfix">
						<div class="col-md-9 col-md-offset-3">
							<button type="submit" class="btn bg-teal btn-flat">{{__('Submit')}}</button>
						</div>
					</div>
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
@endsection
@push('js')
	<script>
        var Page = function(){
            var handleEditor = function(){
                tinymce.init({
                    selector: "textarea.tinymce",
                    theme: "modern",
                    height: 100,
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

            return {
                init: function(){
                    handleEditor();
                }
            }
        }();

        $(document).ready(function(){
            Page.init();
        });
	</script>
@endpush