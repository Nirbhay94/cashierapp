@extends('auth.layouts.master')
@section('page_title', __('Reset Password'))
@section('content')
<form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
    {{ csrf_field() }}
    <input type="hidden" name="token" value="{{ $token }}">
    <h3 class="font-green">{{__('Password Reset')}}</h3>
    @include('includes.form-status')
    <p> {{__('Enter your new password and confirm it.')}} </p>
    <div class="form-group">
        <input class="form-control placeholder-no-fix" type="email" name="email" value="{{ $email or old('email') }}" autofocus autocomplete="off" placeholder="{{__('Email')}}"  required/>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">{{__('Password')}}</label>
        <input class="form-control placeholder-no-fix" type="password" placeholder="{{__('Password')}}" name="password" required />
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">{{__('Confirm Password')}}</label>
        <input class="form-control placeholder-no-fix" type="password" placeholder="{{__('Confirm Password')}}" name="password_confirmation" required />
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-success uppercase">{{__('Submit')}}</button>
    </div>
</form>
@endsection
