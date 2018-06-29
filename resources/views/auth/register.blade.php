@extends('auth.layouts.master')
@section('page_title', __('Register'))
@section('content')
    {!! Form::open(['route' => 'register', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'POST'] ) !!}
    {{ csrf_field() }}
    <h3 class="form-title font-green"><i class="fa fa-user-plus"></i> {{__('Sign Up')}}</h3>
    @include('includes.form-status')
    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        <label for="name" class="control-label visible-ie8 visible-ie9">{{__('Username')}}</label>
        {!! Form::text('name', null, ['class' => 'form-control form-control-solid placeholder-no-fix', 'id' => 'name', 'required', 'autofocus']) !!}
    </div>
    <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
        <label for="first_name" class="control-label visible-ie8 visible-ie9">{{__('First Name')}}</label>
        {!! Form::text('first_name', null, ['class' => 'form-control form-control-solid placeholder-no-fix', 'id' => 'first_name']) !!}
    </div>
    <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
        <label for="last_name" class="control-label visible-ie8 visible-ie9">{{__('Last Name')}}</label>
        {!! Form::text('last_name', null, ['class' => 'form-control form-control-solid placeholder-no-fix', 'id' => 'last_name']) !!}
    </div>
    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label for="email" class="control-label visible-ie8 visible-ie9">{{__('E-Mail Address')}}</label>
        @if(request()->get('email'))
            {!! Form::email('email', null, ['class' => 'form-control form-control-solid placeholder-no-fix', 'id' => 'email', 'required', 'readonly', 'value' => request()->get('email')]) !!}
        @else
            {!! Form::email('email', null, ['class' => 'form-control form-control-solid placeholder-no-fix', 'id' => 'email', 'required']) !!}
        @endif
    </div>
    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <label for="password" class="control-label visible-ie8 visible-ie9">{{__('Password')}}</label>
        {!! Form::password('password', ['class' => 'form-control form-control-solid placeholder-no-fix', 'id' => 'password', 'required']) !!}
    </div>
    <div class="form-group">
        <label for="password-confirm" class="control-label visible-ie8 visible-ie9">{{__('Confirm Password')}}</label>
        {!! Form::password('password_confirmation', ['class' => 'form-control form-control-solid placeholder-no-fix', 'id' => 'password-confirm', 'required']) !!}
    </div>
    @if(config('settings.reCaptchaStatus'))
        <div class="form-group">
            <div class="control-label visible-ie8 visible-ie9 text-center">
                {!! app('captcha')->render('en'); !!}
            </div>
        </div>
    @endif
    <div class="form-actions">
        <a href="{{route('login')}}" class="btn btn-default">
            {{__('Back')}}
        </a>
        <button type="submit" class="btn btn-success">
            {{__('Register')}}
        </button>
    </div>
    {{--
    <div class="login-options">
        @include('includes.socials')
    </div>
    --}}
    {!! Form::close() !!}
@endsection