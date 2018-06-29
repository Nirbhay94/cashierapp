@extends('layouts.master')
@section('page_title', __('Editing Permissions'))
@section('content')
	<div class="block-header">
		<h2>{{__('ROLES & PERMISSIONS')}}</h2>
	</div>
	<div class="row clearfix">
		<div class="col-md-12">
			<div class="card">
				<div class="header">
					<h2>
						<strong>{{ucwords($role->name)}} {{__('Permissions')}}</strong>
						<small>{{__('You are able edit the permissions assign to the above role from here. Be careful, and ensure you know what you are doing.')}}</small>
					</h2>
					<ul class="header-dropdown m-r--5">
						<li>
							<a href="{{route('administration.permissions.index')}}" class="refresh-toggle">
								<i class="material-icons">reply</i>
							</a>
						</li>
					</ul>
				</div>
				{!! Form::model($role, ['route' => ['administration.permissions.update', $role->id], 'method' => 'PUT', 'id' => 'edit-form']) !!}
				{!! csrf_field() !!}
				<div class="body">
					<div class="row clearfix">
						<div class="form-group">
							{!! Form::label('name', __('Name') , array('class' => 'col-md-3 control-label')); !!}
							<div class="form-float col-md-9">
								<div class="form-line {{ $errors->has('name') ? ' error ' : '' }}">
									{!! Form::text('name', null, array('id' => 'name', 'class' => 'form-control', 'placeholder' => __('Name'), 'required',  'disabled' => (in_array($role->name, ['user', 'admin'])))) !!}
								</div>
							</div>
						</div>
					</div>
					<h2 class="card-inside-title">{{__('Permissions')}}</h2>
					<div class="row clearfix">
						@foreach($permissions as $permission)
							<div class="form-group">
								<div class="col-xs-9" >
									{{ucwords($permission->name)}} <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-original-title="{{$permission->description}}"></i>
								</div>
								<div class="col-xs-3">
									<input type="checkbox" id="{{$permission->id}}" name="permissions[]" value="{{$permission->name}}" class="chk-col-deep-purple" {{($role->hasPermissionTo($permission->name))? 'checked': ''}}>
									<label for="{{$permission->id}}"></label>
								</div>
							</div>
						@endforeach
						
						<div class="row clearfix">
							<div class="col-xs-9 col-xs-offset-3">
								{!! Form::button(__('Save Changes'), ['class' => 'btn btn-success btn-save','type' => 'submit']) !!}
							</div>
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
            var handleTooltips = function(){
                $('[data-toggle="tooltip"]').tooltip({
                    container: 'body'
                });
            };
            
            var handleFormValidation = function(){
                $('#edit-form').validate({
                    rules : {
                        name: {
                            required: true
                        }
                    },

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
            };

            return {
                init: function(){
                    handleFormValidation();
                    handleTooltips();
                }
            }
        }();

        $(document).ready(function(){
            Page.init();
        });
	</script>
@endpush