@extends('vendor.installer.layouts.master')
@section('page', __('Environment'))
@section('content')
    <form method="POST">
        {{csrf_field()}}
        <div class="wizard-header">
            <h3 class="wizard-title"> {{__('Installation Wizard')}} </h3>
            <h5>{{__('We need to gather some important information...')}}</h5>
        </div>
        <div class="wizard-navigation">
            <ul class="steps">
                <li><a href="#welcome" data-toggle="tab">{{__('1.')}} {{__('Welcome')}}</a></li>
                <li><a href="#requirements" data-toggle="tab">{{__('2.')}} {{__('Requirements')}}</a></li>
                <li><a href="#permissions" data-toggle="tab">{{__('3.')}} {{__('Permissions')}}</a></li>
                <li id="active_step"><a href="#environment" data-toggle="tab">{{__('4.')}} {{__('Environments')}}</a></li>
                <li><a href="#finish" data-toggle="tab">{{__('5.')}} {{__('Finish')}}</a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane" id="environment">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        @include('vendor.installer.includes.alerts')
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <h3 class="info-text">
                                <i class="material-icons">public</i> {{__("APPLICATON DETAILS")}}
                            </h3>
                            @foreach($content as $key => $data)
                                @if(is_app_env($key))
                                    @include('vendor.installer.includes.forms')
                                @endif
                            @endforeach
                        </div>
                        <div class="row">
                            <h3 class="info-text">
                                <i class="material-icons">archive</i> {{__("DATABASE DETAILS")}}
                            </h3>
                            @foreach($content as $key => $data)
                                @if(is_db_env($key))
                                    @include('vendor.installer.includes.forms')
                                @endif
                            @endforeach
                        </div>
                        <div class="row">
                            <h3 class="info-text">
                                <i class="material-icons">mail</i> {{__("MAIL SERVER DETAILS")}}
                            </h3>
                            @foreach($content as $key => $data)
                                @if(is_mail_env($key))
                                    @include('vendor.installer.includes.forms')
                                @endif
                            @endforeach
                        </div>
                        <div class="row">
                            <h3 class="info-text">
                                <i class="material-icons">extension</i> {{__("SOME EXTRA DETAILS")}}
                            </h3>
                            @foreach($content as $key => $data)
                                @if(!is_app_env($key) && !is_db_env($key) && !is_mail_env($key))
                                    @include('vendor.installer.includes.forms')
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wizard-footer">
            <div class="pull-right">
                <input type="submit" class="btn btn-fill btn-success btn-wd" value="{{__('Submit')}}" />
            </div>
            <div class="clearfix"></div>
        </div>
    </form>
@endsection
@push('js')
    <script>
        var Page = function(){
            var handleValidation = function(){
                var form = $('.wizard-card form');
                
                var validator = form.validate({
                    errorPlacement: function(error, element) {
                        $(element).parent('div').addClass('has-error');
                    }
                });
            };

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