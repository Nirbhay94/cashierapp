@extends('auth.layouts.master')
@section('page_title', __('Reset Password'))
@section('content')
<form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
    {{ csrf_field() }}
    <h3 class="font-green"><i class="fa fa-key"></i> {{__('Forget Password?')}}</h3>
    @include('includes.form-status')
    <p> {{__('Enter your e-mail address below to reset your password.')}} </p>
    <div class="form-group">
        <input class="form-control placeholder-no-fix" type="email" value="{{ old('email') }}" autocomplete="off" placeholder="{{__('Email')}}" name="email" required/>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-success uppercase">{{__('Submit')}}</button>
        <a href="{{route('login')}}" id="forget-password" class="forget-password">{{__('Login?')}}</a>
    </div>
</form>
@endsection
