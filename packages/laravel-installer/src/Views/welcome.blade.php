@extends('vendor.installer.layouts.master')
@section('page', __('Welcome'))
@section('content')
    <form method="POST">
        {{csrf_field()}}
        <!--  You can switch " data-color="purple" with one of the next bright colors: "green", "orange", "red", "blue" -->
        <div class="wizard-header">
            <h3 class="wizard-title"> {{__('Installation Wizard')}} </h3>
            <h5>{{__('This will get you started in a jiffy.')}}</h5>
        </div>
        <div class="wizard-navigation">
            <ul class="steps">
                <li id="active_step"><a href="#welcome" data-toggle="tab">{{__('1.')}} {{__('Welcome')}}</a></li>
                <li><a href="#requirements" data-toggle="tab">{{__('2.')}} {{__('Requirements')}}</a></li>
                <li><a href="#permissions" data-toggle="tab">{{__('3.')}} {{__('Permissions')}}</a></li>
                <li><a href="#environment" data-toggle="tab">{{__('4.')}} {{__('Environments')}}</a></li>
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
                            {{__("Let's start with the basic information, but first we'd like to validate your purchase.")}}
                        </h4>
                    </div>
                    <div class="col-sm-4 col-sm-offset-1" style="margin-top: 20px">
                        <div class="picture-container" rel="tooltip" title="{{config('installer.name')}}">
                            <div class="picture">
                                <img src="{{config('installer.thumbnail')}}" class="picture-src"/>
                            </div>
                            <h6>
                                <a href="{{config('installer.documentation')}}" style="color: black;">
                                    <i class="fa fa-file"></i> {{__('DOCUMENTATION')}}
                                </a>
                            </h6>
                        </div>
                    </div>
                    <div class="col-sm-6" style="margin-top: 20px">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">enhanced_encryption</i>
                            </span>
                            <div class="form-group label-floating {{$errors->has('verification') ? ' has-error ' : ''}}">
                                <label class="control-label">{{__('Purchase Code')}} <small>({{__('required')}})</small></label>
                                <input name="verification" type="text" class="form-control" required>
                            </div>
                        </div>
                        <span>{{__('Thank you for purchasing! Please copy your purchase code here and click next to proceed!')}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="wizard-footer">
            <div class="pull-right">
                <input type="submit" class="btn btn-fill btn-success btn-wd" value="{{__('Next')}}" />
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
