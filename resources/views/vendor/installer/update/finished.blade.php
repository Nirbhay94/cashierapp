@extends('vendor.installer.layouts.master')
@section('page', __('Finished'))
@push('css')
    <style>
        .gold-label{
            color: #FDB931;
        }
    </style>
@endpush
@section('content')
    <div class="wizard-header">
        <h3 class="wizard-title"> {{__('Update Wizard')}} </h3>
        <h5>{{__("Yay! We're done.")}}</h5>
    </div>
    <div class="wizard-navigation">
        <ul class="steps">
            <li><a href="#welcome" data-toggle="tab">{{__('1.')}} {{__('Welcome')}}</a></li>
            <li><a href="#overview" data-toggle="tab">{{__('2.')}} {{__('Overview')}}</a></li>
            <li id="active_step"><a href="#finish" data-toggle="tab">{{__('5.')}} {{__('Finish')}}</a></li>
        </ul>
    </div>
    <div class="tab-content">
        <div class="tab-pane" id="finish">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    @include('vendor.installer.includes.alerts')
                </div>
                <div class="col-md-12">
                    <h4 class="info-text">
                        <i class="material-icons">flag</i> {{__("You are ready to go! We're pleased to serve you")}}
                    </h4>
                </div>
                <div class="col-md-12">
                    <div class="col-sm-4 col-sm-offset-1" style="margin-top: 20px">
                        <div class="picture-container" rel="tooltip" title="{{config('installer.name')}}">
                            <div class="picture">
                                <img src="{{config('installer.thumbnail')}}" class="picture-src"/>
                            </div>
                            <h6>
                                <a href="{{config('installer.link')}}" target="_blank">
                                    <i class="material-icons gold-label">star</i>
                                    <i class="material-icons gold-label">star</i>
                                    <i class="material-icons gold-label">star</i>
                                    <i class="material-icons gold-label">star</i>
                                    <i class="material-icons gold-label">star</i>
                                </a>
                            </h6>
                        </div>
                    </div>
                    <div class="col-sm-6 text-center" style="margin-top: 20px">
                        <h3>{{__('Thank you once again for purchasing! A five star rating will be very much appreciated!')}}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="wizard-footer">
        <div class="pull-right">
            <a href="{{url('/')}}" class="btn btn-fill btn-success btn-wd">
                {{__('Continue')}}
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
@endsection
@push('js')
    <script>
        var Page = function(){

            return {
                init: function(){

                }
            }
        }();

        $(document).ready(function(){
            Page.init()
        });
    </script>
@endpush