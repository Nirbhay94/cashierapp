@extends('layouts.master')
@section('page_title', __('Editing User').' '.$user->name)
@section('content')
    <div class="block-header">
        <h2>{{__('USERS MANAGEMENT')}}</h2>
    </div>
    <div class="row clearfix">
        <div class="col-md-10 col-md-offset-1">
            <div class="card">
                <div class="header">
                    <h2>
                        <strong>{{ $user->name }}</strong> {{__('Profile')}}
                        <small>{{__('You are able edit the user profile from here')}}</small>
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a role="button" data-toggle="collapse" href="#change-password" aria-expanded="false" aria-controls="change-password">
                                <i class="material-icons">lock</i>
                            </a>
                        </li>
                    </ul>
                </div>
                {!! Form::model($user, ['route' => ['administration.users.update', $user->id], 'method' => 'PUT', 'id' => 'edit-form']) !!}
                {!! csrf_field() !!}
                <div class="body">
                    <div class="form-group">
                        {!! Form::label('name', __('Username') , array('class' => 'col-md-3 control-label')); !!}
                        <div class="form-float col-md-9">
                            <div class="form-line {{ $errors->has('name') ? ' error ' : '' }}">
                                {!! Form::text('name', $user->name, array('id' => 'name', 'class' => 'form-control', 'placeholder' => __('Username'), 'required')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('email', __('E-mail') , array('class' => 'col-md-3 control-label')); !!}
                        <div class="form-float col-md-9">
                            <div class="form-line {{ $errors->has('email') ? ' error ' : '' }}">
                                {!! Form::text('email', $user->email, array('id' => 'email', 'class' => 'form-control', 'placeholder' => __('User Email'), 'required')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('first_name', __('First Name'), array('class' => 'col-md-3 control-label')); !!}
                        <div class="form-float col-md-9">
                            <div class="form-line {{ $errors->has('first_name') ? ' error ' : '' }}">
                                {!! Form::text('first_name', $user->first_name, array('id' => 'first_name', 'class' => 'form-control', 'placeholder' => __('First Name'), 'required')) !!}
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        {!! Form::label('last_name', __('Last Name'), array('class' => 'col-md-3 control-label')); !!}
                        <div class="form-float col-md-9">
                            <div class="form-line {{ $errors->has('last_name') ? ' error ' : '' }}">
                                {!! Form::text('last_name', $user->last_name, array('id' => 'last_name', 'class' => 'form-control', 'placeholder' => __('Last Name'), 'required')) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('role', __('User Role'), array('class' => 'col-md-3 control-label')); !!}
                        <div class="form-float col-md-9">
                            <div class="form-line {{ $errors->has('role') ? ' error ' : '' }}">
                                <select class="form-control" name="role" id="role">
                                    <option>{{ __('Select User Role') }}</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->name}}" {{$current_role->id == $role->id ? 'selected' : ''}}>{{ucwords($role->name)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="collapse" id="change-password">
                        <div class="form-group">
                            {!! Form::label('password', __('New Password'), ['class' => 'col-md-3']); !!}
                            <div class="form-float col-md-9">
                                <div class="form-line {{$errors->has('password') ? ' error ' : ''}}">
                                    {!! Form::password('password', ['id' => 'password', 'class' => 'form-control ', 'placeholder' => __('Password')]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('password_confirmation', __('Confirm Password'), ['class' => 'col-md-3']); !!}
                            <div class="form-float col-md-9">
                                <div class="form-line {{$errors->has('password_confirmation') ? ' error ' : ''}}">
                                    {!! Form::password('password_confirmation', ['id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => __('Confirm Password')]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-9 col-md-offset-3">
                            {!! Form::button(__('Save Changes'), ['class' => 'btn btn-success btn-save','type' => 'submit', 'disabled']) !!}
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
            var handleFormChangeListeners = function(){
                $("input, select").keyup(function() {
                    if(!$(this).val()){
                        $(".btn-save").attr('disabled', true);
                    } else {
                        $(".btn-save").attr('disabled', false);
                    }
                }).change(function() {
                    if(!$(this).val()){
                        $(".btn-save").attr('disabled', true);
                    } else {
                        $(".btn-save").attr('disabled', false);
                    }
                });
            };
            
            var handleFormValidation = function(){
                $('#edit-form').validate({
                    rules : {
                        name: {
                            required: true
                        },
                        email: {
                            required: true
                        },
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
                    handleFormChangeListeners();
                    handleFormValidation();
                }
            }
        }();
        
        $(document).ready(function(){
            Page.init();
        });
    </script>
@endpush