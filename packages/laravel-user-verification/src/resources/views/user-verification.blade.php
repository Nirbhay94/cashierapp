@extends('layouts.auth.app')
@section('page_title', __('Verification Error'))
@section('content')
    <div class="panel panel-danger" style="margin-top: 20px">
        <div class="panel-heading">
            {!! trans('laravel-user-verification::user-verification.verification_error_header') !!}
        </div>
        <div class="panel-body">
            @include('includes.form-status')
            <div>
                {!! trans('laravel-user-verification::user-verification.verification_error_message') !!}
            </div>
        </div>
    </div>
@endsection