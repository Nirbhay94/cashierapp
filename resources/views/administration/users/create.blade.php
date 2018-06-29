@extends('layouts.master')
@section('page_title', __('Create New User'))
@section('content')
    <div class="block-header">
        <h2>{{__('CREATE USER')}}</h2>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{__('NEW USER FORM')}}
                    </h2>
                </div>
                <form method="POST" action="{{route('administration.users.store')}}" id="create-form">
                    <div class="body">
                        {{csrf_field()}}
                        <div class="form-group">
                            {!! Form::label('name', __('Username'), array('class' => 'col-md-3')); !!}
                            <div class="form-float col-md-9">
                                <div class="form-line {{$errors->has('name') ? ' error ' : ''}}">
                                    {!! Form::text('name', NULL, array('id' => 'name', 'class' => 'form-control', 'placeholder' => __('Username'))) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('email', __('Email'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9 form-float">
                                <div class="form-line  {{$errors->has('email') ? ' error ' : ''}}">
                                    {!! Form::email('email', NULL, array('id' => 'email', 'class' => 'form-control', 'placeholder' => __('Email'))) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('first_name', __('First Name'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9 form-float">
                                <div class="form-line  {{ $errors->has('first_name') ? ' error ' : '' }}">
                                    {!! Form::text('first_name', NULL, array('id' => 'first_name', 'class' => 'form-control', 'placeholder' => __('First Name'))) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('last_name', __('Last Name'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9 form-float">
                                <div class="form-line {{ $errors->has('last_name') ? ' error ' : '' }}">
                                    {!! Form::text('last_name', NULL, array('id' => 'last_name', 'class' => 'form-control', 'placeholder' => __('Last Name'))) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('plan', __('Plan'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9">
                                <div class="row clearfix">
                                    <div class="col-md-6 form-float">
	                                    <div class="form-line {{ $errors->has('plan') ? ' error ' : '' }}">
		                                    <select class="form-control" name="plan" id="plan">
			                                    <option value="0">{{ __('No Active Plan Yet') }}</option>
			                                    @foreach($plans as $plan)
				                                    <option value="{{$plan->id}}">{{$plan->name}}</option>
			                                    @endforeach
		                                    </select>
	                                    </div>
                                    </div>
                                    <div class="col-md-6 form-float">
                                        <div class="input-group spinner"  data-trigger="spinner">
                                            <label class="input-group-addon" for="plan"><span class="material-icons">clear</span></label>
                                            <div class="form-line {{ $errors->has('quantity') ? ' error ' : '' }}">
                                                {!! Form::text('quantity', '1', array('id' => 'quantity', 'class' => 'form-control', 'placeholder' => __('Quantity'), 'data-rule' => 'quantity')) !!}
                                            </div>
                                            <span class="input-group-addon">
                                                <a href="javascript:;" class="spin-up" data-spin="up"><i class="glyphicon glyphicon-chevron-up"></i></a>
                                                <a href="javascript:;" class="spin-down" data-spin="down"><i class="glyphicon glyphicon-chevron-down"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('role', __('User Role'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9 form-float">
	                            <div class="form-line {{$errors->has('role') ? ' error ' : ''}}">
		                            <select class="form-control" name="role" id="role">
			                            <option>{{__('Select User Role')}}</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}">{{ucwords($role->name)}}</option>
                                        @endforeach
		                            </select>
	                            </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('password', __('Password'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9 form-float">
                                <div class="form-line {{ $errors->has('password') ? ' error ' : '' }}">
                                    {!! Form::password('password', array('id' => 'password', 'class' => 'form-control ', 'placeholder' => __('Password'))) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('password_confirmation', __('Confirm Password'), array('class' => 'col-md-3')); !!}
                            <div class="col-md-9 form-float">
                                <div class="form-line {{ $errors->has('password_confirmation') ? ' error ' : '' }}">
                                    {!! Form::password('password_confirmation', array('id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => __('Confirm Password'))) !!}
                                </div>
                            </div>
                        </div>
                        {!! Form::button(__('SUBMIT'), array('class' => 'btn btn-success btn-flat margin-bottom-1 pull-right','type' => 'submit', )) !!}
                        <div class="clearfix"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        var Page = function(){
            var handleFormValidation = function(){
                $('#create-form').validate({
                    rules : {
                        password : {
                            minlength : 6
                        },
                        password_confirmation : {
                            minlength : 6,
                            equalTo : "#password"
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
                }
            }
        }();

        $(document).ready(function(){
            Page.init();
        });
    </script>
@endpush
