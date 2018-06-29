@extends('layouts.master')
@section('page_title', __('Plans'))
@push('css')
    <link href="{{asset('/css/page_pricing.css')}}" type="text/css" rel="stylesheet"/>
@endpush
@section('content')
    <div class="block-header">
        <h2>
            {{__('ADMINISTRATION')}}
            <a href="{{route('administration.plans.create')}}" class="pull-right btn btn-primary">
                {{__('Add')}}
            </a>
        </h2>
        <div class="clearfix"></div>
    </div>
    @foreach($records as $plans)
    <div class="row text-center">
        @foreach($plans as $plan)
        <div class="col-md-3 col-sm-6" style="margin: 0 auto;">
            <div class="pricing hover-effect">
                <div class="pricing-head">
                    <h3 class="bg-purple">
                        {{$plan->name}}
                        @if($plan->trial_period_days == 0)
                            <span>{{__('No trial offer!')}}</span>
                        @else
                            <span><i class="fa fa-asterisk"></i> <b>{{$plan->trial_period_days}}</b> {{__('days of trial')}}</span>
                        @endif
                    </h3>
                    @php list($whole, $decimal) = explode('.', $plan->price); @endphp
                    <h4><i>{{ currency() }}</i>{{$whole}}<i>.{{$decimal}}</i> <span>{{__('Per')}} {{ucwords($plan->interval)}}</span></h4>
                </div>
                <ul class="pricing-content list-unstyled">
                    @foreach($plan->features()->orderBy('sort_order', 'ASC')->get() as $feature)
                        <li class="text-center">
                            @if($feature->type == 'boolean')
                                @if(in_array($feature->value, config('laraplans.positive_words')))
                                    <i class="fa fa-check"></i>
                                @else
                                    <i class="fa fa-times"></i>
                                @endif
                            @else
                                @if($feature->value >= 0 || in_array($feature->value, config('laraplans.positive_words')))
                                    <b>{{$feature->value}}</b>
                                @else
                                    <b>{{__('Unlimited')}}</b>
                                @endif
                            @endif
                            <span>{{$feature->label}}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="pricing-footer">
                    <p>{{$plan->description}}</p>
                    <a href="{{route('administration.plans.edit', ['id' => $plan->id])}}" data-id="{{$plan->id}}">
                        <span class="material-icons col-green">create</span>
                    </a>
                    <a href="{{route('administration.plans.destroy', ['id' => $plan->id])}}" data-id="{{$plan->id}}" class="delete-plan">
                        <span class="material-icons col-red">clear</span>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach
    @if(!count($records))
        <h3 class="text-center">{{__('No Plans Found Yet!')}}</h3>
    @endif
@endsection
@push('js')
    <script>
        var Page = function(){
            var handleDeleteListeners = function () {
                var data_id, data_link;

                $('.delete-plan').click(function(e){
                    if(data_id = $(this).data('id')){
                        data_link = $(this).attr('href');
                        swal({
                            title: "{{__('Are you sure?')}}",
                            text: "{{__('All subscribed users will lose their subscrition status!')}}",
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
                                success: function() {
                                    swal("{{__('Successful')}}", "{{__('Plan was successfully removed!')}}", "success");
                                    window.location.reload();
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
            return{
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