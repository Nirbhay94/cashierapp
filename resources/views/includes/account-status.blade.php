@if(Auth::user()->verified)
	@can('subscribe to services')
		@if($subscription = Auth::user()->subscription('main'))
			@if($subscription->onStrictTrial())
				<div class="callout-block callout-success">
					<div class="icon-holder">
						<i class="fa fa-hourglass-start"></i>
					</div>
					<div class="content">
						<h4 class="callout-title">{{__('System Administrator')}}</h4>
						<p>{{__('You are on trial/evaluation period of')}} {{$subscription->plan->name}}. {{__('Expires in')}} {{\Carbon\Carbon::parse($subscription->trial_ends_at)->toDayDateTimeString()}}</p>
					</div>
				</div>
			@elseif(!$subscription->isActive())
				<div class="callout-block callout-danger">
					<div class="icon-holder">
						<i class="fa fa-exclamation-triangle"></i>
					</div>
					<div class="content">
						<h4 class="callout-title">{{__('System Administrator')}}</h4>
						<p>{{__('Your subscription to')}} {{$subscription->plan->name}}. {{__('has expired on')}} {{\Carbon\Carbon::parse($subscription->ends_at)->toDayDateTimeString()}}</p>
					</div>
				</div>
			@endif
		@else
			<div class="callout-block callout-info">
				<div class="icon-holder">
					<i class="fa fa-life-ring"></i>
				</div>
				<div class="content">
					<h4 class="callout-title">{{__('System Administrator')}}</h4>
					<p>{{__('Thank you for registering an account with us. Please select any subscription plan that suites your needs to proceed')}}.</p>
				</div>
			</div>
		@endif
	@endcan
@else
	<div class="callout-block callout-warning">
		<div class="icon-holder">
			<i class="fa fa-info-circle"></i>
		</div>
		<div class="content">
			<h4 class="callout-title">{{__('System Administrator')}}</h4>
			<p>{{__('We have sent you an email and would like you to follow the link to activate your account.')}} <a href="javascript:void(0)" id="resend-verification-email">{{__('Resend Confirmation Email')}}</a></p>
		</div>
	</div>
@endif