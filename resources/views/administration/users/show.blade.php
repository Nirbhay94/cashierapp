@extends('layouts.master')
@section('page_title', __('Showing User').' '.$user->name)
@section('content')
    <div class="block-header">
        <h2>{{__('USER PROFILE')}}</h2>
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
                        <ul class="list-group list-group-unbordered text-left">
                            <li class="list-group-item">
                                <b>{{__('Status')}}</b>
                                <span class="pull-right">
                                    @if ($user->verified)
                                        <span class="label label-info">
                                            {{__('Activated')}}
                                        </span>
                                    @else
                                        <span class="label label-danger">
                                            {{__('Not Activated')}}
                                        </span>
                                    @endif
                                </span>
                            </li>
                            <li class="list-group-item">
                                <b>{{__('Balance')}}</b>
                                <span class="pull-right">
                                    {!! money($user->currentPoints()) !!}
                                </span>
                            </li>
                        </ul>
                        <div class="clearfix">
                            @php $created_at = new \Carbon\Carbon($user->created_at); @endphp
                            <span class="widget-user-desc">{{__('Since')}} <b>{{$created_at->diffForHumans()}}</b></span>
                        </div>
                        <div class="clearfix">
                            <a href="{{route('administration.users.edit', ['id' => $user->id])}}" class="col-green">
                                <span class="material-icons">mode_edit</span>
                            </a>
    
                            <a href="{{route('administration.users.destroy', ['id' => $user->id])}}" id="delete-user" class="col-red" data-id="{{$user->id}}">
                                <span class="material-icons">clear</span>
                            </a>
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
                        <small>{{__('You can view the above users account, subscription and activities from here.')}}</small>
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="{{route('administration.users.index')}}">
                                <i class="material-icons">reply</i>
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
                        <li role="presentation">
                            <a href="#tracker" data-toggle="tab">
                                <i class="material-icons">place</i> {{__('TRACKER')}}
                            </a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in animated fadeIn active" id="profile">
                            <br/>
                            <div class="list-group">
                                <div class="list-group-item">
                                    <div class="row">
                                        <strong class="col-xs-4" style="margin: 0">{{__('Username')}}</strong>
                                        <span class="col-xs-8" style="margin: 0">{{$user->name}}</span>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="row">
                                        <strong class="col-xs-4" style="margin: 0">{{__('Email')}}</strong>
                                        <span class="col-xs-8" style="margin: 0">{{HTML::mailto($user->email, $user->email)}}</span>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="row">
                                        <strong class="col-xs-4" style="margin: 0">{{__('First Name')}}</strong>
                                        <span class="col-xs-8" style="margin: 0">{{$user->first_name}}</span>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="row">
                                        <strong class="col-xs-4" style="margin: 0">{{__('Last Name')}}</strong>
                                        <span class="col-xs-8" style="margin: 0">{{$user->last_name}}</span>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="row">
                                        <strong class="col-xs-4" style="margin: 0">{{__('Roles')}}</strong>
                                        <span class="col-xs-8" style="margin: 0">
                                            @foreach($user->getRoleNames() as $role)
                                                @if($role == 'user')
                                                    <span class="label label-primary" style="margin-right: 4px">{{ucwords($role)}}</span>
                                                @elseif($role == 'admin')
                                                    <span class="label label-warning" style="margin-right: 4px">{{ucwords($role)}}</span>
                                                @else
                                                    <span class="label label-default">{{ucwords($role)}}</span>
                                                @endif
                                            @endforeach
                                        </span>
                                    </div>
                                </div>
                                @if($user->profile)
                                    @if($user->profile->location)
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
                            <form id="ajax-form">
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
                                                   <span>{{ucfirst($user->auto_renewal)}}</span>
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
                        <div role="tabpanel" class="tab-pane fade animated fadeIn" id="tracker">
                            <br/>
                            <div class="list-group">
                                @if($user->signup_ip_address)
                                    <div class="list-group-item">
                                        <div class="row">
                                            <strong class="col-xs-4" style="margin: 0">{{__('Signup IP Address')}}</strong>
                                            <span class="col-xs-8" style="margin: 0">{{$user->signup_ip_address}}</span>
                                        </div>
                                    </div>
                                @endif
                                @if($user->social_signup_ip_address)
                                    <div class="list-group-item">
                                        <div class="row">
                                            <strong class="col-xs-4" style="margin: 0">{{__('Social Signup IP Address')}}</strong>
                                            <span class="col-xs-8" style="margin: 0">{{$user->social_signup_ip_address}}</span>
                                        </div>
                                    </div>
                                @endif
                                @if($user->admin_signup_ip_address)
                                    <div class="list-group-item">
                                        <div class="row">
                                            <strong class="col-xs-4" style="margin: 0">{{__('Admin Signup IP Address')}}</strong>
                                            <span class="col-xs-8" style="margin: 0">{{$user->admin_signup_ip_address}}</span>
                                        </div>
                                    </div>
                                @endif
                                @if($user->last_login_ip_address)
                                    <div class="list-group-item">
                                        <div class="row">
                                            <strong class="col-xs-4" style="margin: 0">{{__('Last Login IP Address')}}</strong>
                                            <span class="col-xs-8" style="margin: 0">{{$user->last_login_ip_address}}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    @push('js')
        <script>
            var Page = function(){
                var handleDeleteListeners = function(){
                    var data_id, data_link;

                    $('#delete-user').click(function(e){
                        e.preventDefault();

                        if(data_id = $(this).data('id')){
                            data_link = $(this).attr('href');

                            swal({
                                title: "{{__('Are you sure?')}}",
                                text: "{{__('You are about to completely delete users record!')}}",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "{{__('Yes, remove it!')}}",
                                closeOnConfirm: false,
                                showLoaderOnConfirm: true
                            }, function () {
                                $.ajax({
                                    url: data_link,
                                    method: 'DELETE',
                                    success: function(response) {
                                        swal("{{__('Successful')}}", response, "success");
                                        window.location.href = '{{route('administration.users.index')}}';
                                    },
                                    error: function(xhr){
                                        swal.close();

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
                            });
                        }

                        return false;
                    });
                };
                
                return {
                    init: function(){
                        handleDeleteListeners();
                    }
                }
            }();

            $(document).ready(function(){
                Page.init();
            });
        </script>
@endpush