@extends('vendor.installer.update.layouts.master')
@section('page', __('Welcome'))
@section('content')
    <form method="POST">
    {{csrf_field()}}
    <!--  You can switch " data-color="purple" with one of the next bright colors: "green", "orange", "red", "blue" -->
        <div class="wizard-header">
            <h3 class="wizard-title"> {{__('Update Wizard')}} </h3>
            <h5>{{__('This will take no time.')}}</h5>
        </div>
        <div class="wizard-navigation">
            <ul class="steps">
                <li id="active_step"><a href="#welcome" data-toggle="tab">{{__('1.')}} {{__('Welcome')}}</a></li>
                <li><a href="#overview" data-toggle="tab">{{__('2.')}} {{__('Overview')}}</a></li>
                <li><a href="#finish" data-toggle="tab">{{__('5.')}} {{__('Finish')}}</a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane" id="welcome">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        @include('vendor.installer.includes.alerts')
                    </div>
                    <div class="col-md-12">
                        <h4 class="info-text">
                            {{__("You are brought here simply because we have some new updates to install.")}}
                        </h4>
                    </div>
                    <div class="col-sm-4 col-sm-offset-1" style="margin-top: 25px">
                        <div class="picture-container" rel="tooltip" title="{{config('installer.name')}}">
                            <div class="picture">
                                <img src="{{config('installer.thumbnail')}}" class="picture-src"/>
                            </div>
                            <a href="{{config('installer.documentation')}}" style="color: black;">
                                <i class="fa fa-file"></i> {{__('DOCUMENTATION')}}
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-6 text-center" style="margin-top: 25px">
                        <h3>{{__('This will guide you through the whole process!')}}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="wizard-footer">
            <div class="pull-right">
                <a href="{{route('LaravelUpdater::overview')}}" class="btn btn-fill btn-success btn-wd">{{__('Proceed')}}</a>
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
                    rules: {
                        verification: {
                            required: true,
                            minlength: 10
                        }
                    },

                    errorPlacement: function(error, element) {
                        $(element).parent('div').addClass('has-error');
                    }
                });

                form.submit(function(e){
                    if(!$(this).valid()){
                        validator.focusInvalid();
                        return false;
                    }else{
                        return true
                    }
                });
            };

            return {
                init: function(){
                    handleValidation();
                }
            }
        }();

        $(document).ready(function(){
            Page.init()
        });
    </script>
@endpush

