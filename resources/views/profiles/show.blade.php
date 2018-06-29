@extends('layouts.master')
@section('page_title', $user->name.' '.__('Profile'))
@push('css')
    <style>
        .col-gold{
            background: radial-gradient(ellipse farthest-corner at right bottom, #FEDB37 0%, #FDB931 8%, #9f7928 30%, #8A6E2F 40%, transparent 80%),
            radial-gradient(ellipse farthest-corner at left top, #FFFFFF 0%, #FFFFAC 8%, #D1B464 25%, #5d4a1f 62.5%, #5d4a1f 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
@endpush
@section('content')
    <div class="block-header">
        <h2>
            {{__('PROFILE')}}
            <small>{{__('Show')}}</small>
        </h2>
    </div>
	<!-- Main content -->
    <div class="row">
        <div class="col-lg-4 col-xlg-3 col-md-5 col-sm-5">
            <div class="card">
                <div class="header text-center">
                    @if (($user->profile) && $user->profile->avatar_status == 1)
                        <img src="{{ $user->profile->avatar }}" alt="{{ $user->name }}" class="img-circle" width="150" >
                    @else
                        <img src="{{Gravatar::get($user->email)}}" class="img-circle" width="150" alt="{{ $user->name }}">
                    @endif
                </div>
                <div class="body">
                    <div class="m-t-30 text-center">
                        <h4 class="m-t-10">{{$user->first_name}} {{$user->last_name}}</h4>
                        <h6>{{$user->email}}</h6>
                        <div>
                            @for($i = 1; $i <= count($user->getPermissionsViaRoles()); $i++)
                                <span class="material-icons col-gold">pets</span>
                            @endfor
                        </div>
                        <div>
                            @php $created_at = new \Carbon\Carbon($user->created_at); @endphp
                            <span class="widget-user-desc">{{__('Since')}} {{$created_at->diffForHumans()}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-xlg-9 col-md-7 col-sm-7">
            <div class="card">
                <div class="header bg-purple">
                    <h2>
                        <b>{{__('ACCOUNT DASHBOARD')}}</b>
                        <small>{{__('You can view your account and subscription status from here')}}</small>
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="{{route('profile.edit', ['username' => $user->name])}}">
                                <i class="material-icons">create</i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs tab-nav-right tab-col-purple" role="tablist">
                        <li role="presentation"  class="active">
                            <a href="#profile" data-toggle="tab">
                                <i class="material-icons">face</i> {{__('PROFILE')}}
                            </a>
                        </li>
                        @if($user->can('subscribe to services'))
                        <li role="presentation">
                            <a href="#subscription" data-toggle="tab">
                                <i class="material-icons">credit_card</i> {{__('SUBSCRIPTION')}}
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#resource-usage" data-toggle="tab">
                                <i class="material-icons">memory</i> {{__('RESOURCE USAGE')}}
                            </a>
                        </li>
                        @endif
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in animated fadeIn active" id="profile">
                            <br/>
                            <div class="list-group">
                                <div class="list-group-item">
                                    <div class="row">
                                        <strong class="col-xs-4" style="margin: 0">{{__('Username')}}</strong>
                                        <p class="col-xs-8" style="margin: 0">{{$user->name}}</p>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="row">
                                        <strong class="col-xs-4" style="margin: 0">{{__('First Name')}}</strong>
                                        <p class="col-xs-8" style="margin: 0">{{$user->first_name}}</p>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="row">
                                        <strong class="col-xs-4" style="margin: 0">{{__('Last Name')}}</strong>
                                        <p class="col-xs-8" style="margin: 0">{{$user->last_name}}</p>
                                    </div>
                                </div>
                                @if ($user->profile)
                                    @if ($user->profile->location)
                                        <div class="list-group-item">
                                            <div class="row">
                                                <strong class="col-xs-4" style="margin: 0">{{__('Location')}}</strong>
                                                <span class="col-xs-8" style="margin: 0">{{$user->profile->location}}</span>
                                            </div>
                                        </div>
                                    @endif
                                    @if($user->profile->bio)
                                        <div class="list-group-item">
                                            <div class="row">
                                                <div class="list-group-item-heading">
                                                    <span class="col-md-12">{{__('About Me')}}</span>
                                                </div>
                                                <div class="list-group-item-text">
                                                    <span class="col-md-12">{{$user->profile->bio}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        
                        @if($user->can('subscribe to services'))
                        <div role="tabpanel" class="tab-pane fade animated fadeIn" id="subscription">
                            <div class="row clearfix">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="info-box-4 hover-zoom-effect">
                                        <div class="icon">
                                            <i class="material-icons col-green">play_circle_filled</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">{{__('CURRENT PLAN')}}</div>
                                            @if($subscription = $user->subscription('main'))
                                                <div class="number">{{$subscription->plan->name}}</div>
                                            @else
                                                <div class="number">{{__('No Active Plan')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="info-box-4 hover-zoom-effect">
                                        <div class="icon">
                                            <i class="material-icons col-orange">timer</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">{{__('EXPIRY DATE')}}</div>
                                            @if($subscription = $user->subscription('main'))
                                                @if($subscription->onStrictTrial())
                                                    @php $expiry = new \Carbon\Carbon($subscription->trial_ends_at) @endphp
                                                    
                                                    <div class="number">{{$expiry->toFormattedDateString()}}</div>
                                                @else
                                                    @php $expiry = new \Carbon\Carbon($subscription->ends_at); @endphp
                                                    
                                                    <div class="number">{{$expiry->toFormattedDateString()}}</div>
                                                @endif
                                            @else
                                                <div class="number">{{__('N/A')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form id="ajax-profile-form">
                                <div class="list-group">
                                    <div class="list-group-item">
                                        <div class="row">
                                            <strong class="col-xs-4" style="margin: 0">{{__('Balance')}}</strong>
                                            <p class="col-xs-8" style="margin: 0">
                                                <span>{!! money($user->currentPoints()) !!}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="row">
                                            <strong class="col-xs-4" style="margin: 0">{{__('Auto Renewal')}}</strong>
                                            <p class="col-xs-8 switch" style="margin: 0">
                                                <label>
                                                    <input type="checkbox" name="auto_renewal" value="yes" {{($user->auto_renewal == 'yes')? 'checked': ''}}>
                                                    <span class="lever switch-col-green"></span>
                                                </label>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div role="tabpanel" class="tab-pane fade animated fadeIn" id="resource-usage">
                            @if($subscription = $user->subscription('main'))
                                <div class="list-group">
                                    @foreach($features as $key => $data)
                                        @if($data['type'] == 'quantity')
                                            <div class="list-group-item">
                                                <div class="row">
                                                    <strong class="col-xs-4" style="margin: 0">{{$data['label']}}</strong>
                                                    <p class="col-xs-8" style="margin: 0">
                                                        <span>{{$subscription->ability()->consumed($key)}}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <h2 class="text-center">{{__('No Available Data')}}</h2>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        var Page = function(){
            var handleAjaxForm = function(){
                $('#ajax-profile-form input[type=checkbox]').change(function(){
                    var name = $(this).attr('name');
                    var value = (!this.checked)? 'no': 'yes';
                    
                    $.ajax({
                        url: '{{route('ajax.profile.update', ['username' => $user->name])}}',
                        method: 'POST',
                        data: {
                            name: name,
                            value: value
                        },
                        success: function() {
                            Global.notifySuccess('{{__('Your settings has been updated!')}}')
                        },
                        error: function(xhr){
                            var response = xhr.responseJSON;

                            if($.isPlainObject(response)){
                                $.each(response.errors, function(key, value){
                                    Global.notifyDanger(value[0]);
                                });
                            }else{
                                Global.notifyDanger(response);
                            }
                        }
                    });
                })
            };
            
            return {
                init: function () {
                    handleAjaxForm();
                }
            }
        }();
        
        $(document).ready(function(){
            Page.init();
        });
    </script>
@endpush
