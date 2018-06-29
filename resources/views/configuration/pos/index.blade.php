@extends('layouts.master')
@section('page_title', __('Pos Configuration'))
@section('content')
	<div class="block-header">
		<h2>
			{{__('CONFIGURATION')}}
			<small>{{__('Pos')}}</small>
		</h2>
	</div>
	
	<div class="card">
		<div class="header">
			<h2>
				{{__('CONFIGURATION')}}
				<small>{{__('Configure your POS terminal from here.')}}</small>
			</h2>
		</div>
		{!! Form::model($config, ['route' => 'configuration.pos', 'method' => 'POST']) !!}
		<div class="body">
			<h2 class="card-inside-title">{{__('Receipt Setup')}}</h2>
			<div class="row">
				<div class="col-md-12 clearfix">
					{!! Form::label('header', __('Header')) !!}
					{!! Form::textarea('header', null, ['class' => 'tinymce']) !!}
				</div>
				
				<div class="col-md-12 clearfix">
					{!! Form::label('footer', __('Footer')) !!}
					{!! Form::textarea('footer', null, ['class' => 'tinymce']) !!}
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