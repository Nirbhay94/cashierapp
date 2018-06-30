@extends('vendor.installer.layouts.master')
@section('page', __('Permissions'))
@section('content')
    <div class="wizard-header">
        <h3 class="wizard-title"> {{__('Installation Wizard')}} </h3>
        <h5>{{__('You are almost there.')}}</h5>
    </div>
    <div class="wizard-navigation">
        <ul class="steps">
            <li><a href="#welcome" data-toggle="tab">{{__('1.')}} {{__('Welcome')}}</a></li>
            <li><a href="#requirements" data-toggle="tab">{{__('2.')}} {{__('Requirements')}}</a></li>
            <li id="active_step"><a href="#permissions" data-toggle="tab">{{__('3.')}} {{__('Permissions')}}</a></li>
            <li><a href="#environment" data-toggle="tab">{{__('4.')}} {{__('Environments')}}</a></li>
            <li><a href="#finish" data-toggle="tab">{{__('5.')}} {{__('Finish')}}</a></li>
        </ul>
    </div>
    <div class="tab-content">
        <div class="tab-pane" id="permissions">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    @include('vendor.installer.includes.alerts')
                </div>
                <div class="col-md-12">
                    <h4 class="info-text">
                        {{__("This step is to ensure that all required folders are accessible by the script.")}}
                    </h4>
                </div>
                <div class="col-md-12">
                    <ul class="list-group">
                        @foreach($permissions['permissions'] as $permission)
                            <li class="list-group-item ">
                                <span>{{ $permission['folder'] }}</span>
                                <span class="pull-right">{{ $permission['permission'] }}</span>
                                <strong class="pull-right">
                                    @if($permission['isSet'])
                                        <i class="material-icons" style="color: #13ae10">done</i>
                                    @else
                                        <i class="material-icons" style="color: #df1711">clear</i>
                                    @endif
                                </strong>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="wizard-footer">
        <div class="pull-right">
            @if(!isset($permissions['errors']))
                <a href="{{ route('LaravelInstaller::environment') }}" class="btn btn-fill btn-success btn-wd">
                    {{__('Next')}}
                </a>
            @endif
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