@extends('auth.layouts.master')
@section('page_title', __('Login'))
@section('content')
    {!! Form::open(['route' => 'login', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'POST'] ) !!}
    
    {{ csrf_field() }}
    <h3 class="form-title font-green"><i class="fa fa-sign-in"></i> {{__('Sign In')}}</h3>
    @include('includes.form-status')
    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label class="control-label visible-ie8 visible-ie9">{{__('Email')}}</label>
        <input class="form-control form-control-solid placeholder-no-fix" type="email" autocomplete="off" placeholder="{{__('Email')}}" name="email" value="{{ old('email') }}" required />
    </div>
    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <label class="control-label visible-ie8 visible-ie9">{{__('Password')}}</label>
        <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="{{__('Password')}}" name="password" />
    </div>
    @if(config('settings.reCaptchaStatus'))
        <div class="form-group">
            <div class="control-label visible-ie8 visible-ie9 text-center">
                {!! app('captcha')->render('en'); !!}
            </div>
        </div>
    @endif
    <div class="form-actions">
        <button type="submit" class="btn btn-success">{{__('Login')}}</button>
        <label class="rememberme check mt-checkbox mt-checkbox-outline">
            <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}/> {{__('Remember')}}
            <span></span>
        </label>
        <a href="{{route('password.request')}}" id="forget-password" class="forget-password">{{__('Forgot Password?')}}</a>
    </div>
    {{--
    <div class="login-options">
        <h4>{{__('Or login with')}}</h4>
        <ul class="social-icons">
            @include('includes.socials-icons')
        </ul>
    </div>
    --}}
    <div class="create-account">
        <p>
            <a href="{{route('register')}}" id="register-btn" class="uppercase">{{__('Create an account')}}</a>
        </p>
    </div>
    {!! Form::close() !!}
@endsection