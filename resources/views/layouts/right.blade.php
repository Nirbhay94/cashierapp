<aside id="rightsidebar" class="right-sidebar">
	<ul class="nav nav-tabs" role="tablist">
		<li class="active"><a href="#settings" data-toggle="tab">{{__('PANEL')}}</a></li>
	</ul>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane fade in active in active" id="settings">
			<div class="quick-settings">
				<p>{{__('AUTOMATION')}}</p>
				<ul class="setting-list">
					<li>
						<span>{{__('Last Run')}}</span>
						<div class="switch">
							@if($date = Cache::get('cron_last_run'))
								{{\Carbon\Carbon::parse($date)->toDayDateTimeString()}}
							@else
								{{__('Not Available')}}
							@endif
						</div>
					</li>
				</ul>
				@can('subscribe to services')
					@if(Auth::user()->verified && ($subscription = Auth::user()->subscription('main')))
						<p>{{__('SUBSCRIPTION')}}</p>
						<ul class="setting-list">
							<li>
								<span>{{__('Subscription')}}</span>
								@if($subscription->onStrictTrial())
									<div class="switch">
										<a href="{{route('subscription.change')}}">{{$subscription->plan->name}}</a> ({{__('On Trial')}})
									</div>
								@elseif($subscription->isActive())
									<div class="switch">
										<a href="{{route('subscription.change')}}">{{$subscription->plan->name}}</a>
									</div>
								@else
									<div class="switch">
										<a href="{{route('subscription.change')}}">{{$subscription->plan->name}}</a> ({{__('Expired')}})
									</div>
								@endif
							</li>
							<li>
								<span>{{__('Auto Renewal')}}</span>
								<div class="switch">
									<label>
										{!! Form::checkbox('auto_renewal', 1, Auth::user()->auto_renewal == 'yes') !!}
										<span class="lever"></span>
									</label>
								</div>
							</li>
						</ul>
					@endif
				@endcan
				<p>{{__('VERSION')}}</p>
				<ul class="setting-list">
					<li>
						<span>{{__('Current')}}</span>
						<div class="switch">
							<span>@version</span>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</aside>