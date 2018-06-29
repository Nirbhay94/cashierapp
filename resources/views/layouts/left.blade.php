<!-- Left Sidebar -->
<aside id="leftsidebar" class="sidebar">
	<!-- User Info -->
	<div class="user-info">
		<div class="image">
			@if ((Auth::User()->profile) && Auth::user()->profile->avatar_status == 1)
				<img src="{{Auth::user()->profile->avatar }}" width="48" height="48" alt="{{ Auth::user()->name }}" />
			@else
				<img src="{{Gravatar::get(Auth::user()->email)}}" width="48" height="48" alt="{{ Auth::user()->name }}">
			@endif
		</div>
		<div class="info-container">
			<div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{Auth::user()->name}}</div>
			<div class="email">{{Auth::user()->email}}</div>
			<div class="btn-group user-helper-dropdown">
				<i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
				<ul class="dropdown-menu pull-right">
					<li>
						<a href="{{route('profile.show', ['username' => Auth::user()->name])}}">
							<i class="material-icons">person</i>{{__('Profile')}}
						</a>
					</li>
					<li role="seperator" class="divider"></li>
					<li>
						<a href="javascript:void(0);" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
							<i class="material-icons">input</i>{{__('Log Out')}}
						</a>
						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							{{ csrf_field() }}
						</form>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- #User Info -->
	<!-- Menu -->
	<div class="menu">
		<ul class="list">
			<li class="header">{{__('MAIN NAVIGATION')}}</li>
			<li>
				<a href="javascript:void(0);" class="menu-toggle">
					<i class="material-icons">home</i>
					<span>{{__('Dashboard')}}</span>
				</a>
				<ul class="ml-menu">
					<li>
						<a href="{{route('home')}}">
							<span>{{__('Home')}}</span>
						</a>
					</li>
					<li>
						<a href="{{route('dashboard.reports')}}">
							<span>{{__('Reports')}}</span>
						</a>
					</li>
					<li>
						<a href="{{route('dashboard.tax')}}">
							<span>{{__('Tax')}}</span>
						</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="javascript:void(0);" class="menu-toggle">
					<i class="material-icons">store</i>
					<span>{{__('Products')}}</span>
				</a>
				<ul class="ml-menu">
					<li>
						<a href="{{route('products.all')}}">
							<span>{{__('Manage Products')}}</span>
						</a>
					</li>
					<li>
						<a href="{{route('products.import')}}">
							<span>{{__('Import CSV Data')}}</span>
						</a>
					</li>
					<li>
						<a href="{{route('products.quantity')}}">
							<span>{{__('Update Quantities')}}</span>
						</a>
					</li>
					<li>
						<a href="{{route('products.category')}}">
							<span>{{__('Manage Categories')}}</span>
						</a>
					</li>
					<li>
						<a href="{{route('products.coupon')}}">
							<span>{{__('Manage Coupons')}}</span>
						</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="javascript:void(0);" class="menu-toggle">
					<i class="material-icons">all_inclusive</i>
					<span>{{__('Expenses')}}</span>
				</a>
				<ul class="ml-menu">
					<li>
						<a href="{{route('expenses.list')}}">
							<span>{{__('Manage Expenses')}}</span>
						</a>
					</li>
					<li>
						<a href="{{route('expenses.categories')}}">
							<span>{{__('Manage Categories')}}</span>
						</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="javascript:void(0);" class="menu-toggle">
					<i class="material-icons">receipt</i>
					<span>{{__('Invoices')}}</span>
				</a>
				<ul class="ml-menu">
					<li>
						<a href="{{route('invoice.list')}}">
							<span>{{__('Manage Invoices')}}</span>
						</a>
					</li>
					<li>
						<a href="{{route('invoice.import')}}">
							<span>{{__('Import CSV Data')}}</span>
						</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="{{route('pos')}}">
					<i class="material-icons">local_atm</i>
					<span>{{__('POS Terminal')}}</span>
				</a>
			</li>
			<li>
				<a href="javascript:void(0);" class="menu-toggle">
					<i class="material-icons">contact_mail</i>
					<span>{{__('Customers')}}</span>
				</a>
				<ul class="ml-menu">
					<li>
						<a href="{{route('customers.list')}}">
							<span>{{__('Manage Customers')}}</span>
						</a>
					</li>
					<li>
						<a href="{{route('customers.import')}}">
							<span>{{__('Import CSV Data')}}</span>
						</a>
					</li>
					<li>
						<a href="{{route('customers.broadcast')}}">
							<span>{{__('Send Email Broadcast')}}</span>
						</a>
					</li>
					<li>
						<a href="{{route('customers.balance')}}">
							<span>{{__('Update Balance')}}</span>
						</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="javascript:void(0);" class="menu-toggle">
					<i class="material-icons">person</i>
					<span>{{__('People')}}</span>
				</a>
				<ul class="ml-menu">
					<li>
						<a href="{{route('people.suppliers')}}">
							<span>{{__('Suppliers')}}</span>
						</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="javascript:void(0);" class="menu-toggle">
					<i class="material-icons">money</i>
					<span>{{__('Payment')}}</span>
				</a>
				<ul class="ml-menu">
					<li>
						<a href="{{route('payment.bank')}}">
							<span>{{__('Bank')}}</span>
						</a>
					</li>
					<li>
						<a href="{{route('payment.paypal')}}">
							<span>{{__('Paypal')}}</span>
						</a>
					</li>
					<li>
						<a href="{{route('payment.stripe')}}">
							<span>{{__('Stripe')}}</span>
						</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="javascript:void(0);" class="menu-toggle">
					<i class="material-icons">assignment</i>
					<span>{{__('Transactions')}}</span>
				</a>
				<ul class="ml-menu">
					<li>
						<a href="{{route('transactions.pos')}}">
							<span>{{__('POS Transactions')}}</span>
						</a>
					</li>
					<li>
						<a href="{{route('transactions.invoice')}}">
							<span>{{__('Invoice Transactions')}}</span>
						</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="javascript:void(0);" class="menu-toggle">
					<i class="material-icons">business_center</i>
					<span>{{__('Configuration')}}</span>
				</a>
				<ul class="ml-menu">
					<li>
						<a href="{{route('configuration.email')}}">
							<span>{{__('Email')}}</span>
						</a>
					</li>
					{{--
					<li>
						<a href="{{route('configuration.payment')}}">
							<span>{{__('Payment')}}</span>
						</a>
					</li>
					
					<li>
						<a href="{{route('configuration.sms')}}">
							<span>{{__('Notification')}}</span>
						</a>
					</li>
					--}}
					<li>
						<a href="{{route('configuration.pos')}}">
							<span>{{__('POS')}}</span>
						</a>
					</li>
					<li>
						<a href="{{route('configuration.invoice')}}">
							<span>{{__('Invoice')}}</span>
						</a>
					</li>
					<li>
						<a href="{{route('configuration.tax')}}">
							<span>{{__('Tax')}}</span>
						</a>
					</li>
					<li>
						<a href="{{route('configuration.unit')}}">
							<span>{{__('Units')}}</span>
						</a>
					</li>
				</ul>
			</li>
			@if(Auth::user()->hasAnyPermission('view financial data', 'subscribe to services'))
			<li>
				<a href="javascript:void(0);" class="menu-toggle">
					<i class="material-icons">payment</i>
					<span>{{__('Subscription')}}</span>
				</a>
				<ul class="ml-menu">
					<li>
						<a href="{{route('subscription.invoices')}}">
							<span>{{__('Transaction Invoices')}}</span>
						</a>
					</li>
					@can('view financial data')
						<li>
							<a href="{{route('subscription.summary')}}">
								<span>{{__('Account Summary')}}</span>
							</a>
						</li>
					@endcan
					@can('subscribe to services')
					<li>
						<a href="{{route('subscription.change')}}">
							<span>{{__('Change Plan')}}</span>
						</a>
					</li>
					<li>
						<a href="{{route('subscription.topup')}}">
							<span>{{__('Topup Balance')}}</span>
						</a>
					</li>
					<li>
						<a href="{{route('subscription.extend')}}">
							<span>{{__('Extend Subscription')}}</span>
						</a>
					</li>
					@endcan
				</ul>
			</li>
			@endif
			@if(Auth::user()->hasAnyPermission('read users', 'alter users', 'manage plans', 'manage permissions'))
			<li>
				<a href="javascript:void(0);" class="menu-toggle">
					<i class="material-icons">pets</i>
					<span>{{__('Administration')}}</span>
				</a>
				<ul class="ml-menu">
					@if(Auth::user()->hasAnyPermission('read users', 'alter users'))
						<li>
							<a href="{{route('administration.users.index')}}">
								<span>{{__('Manage Users')}}</span>
							</a>
						</li>
					@endif
					@can('alter users')
						<li>
							<a href="{{route('administration.users.create')}}">
								<span>{{__('Create User')}}</span>
							</a>
						</li>
					@endcan
					@can('manage permissions')
						<li>
							<a href="{{route('administration.permissions.index')}}">
								<span>{{__('Roles & Permissions')}}</span>
							</a>
						</li>
					@endcan
					@can('manage plans')
						<li>
							<a href="{{route('administration.plans.index')}}">
								<span>{{__('Manage Plans')}}</span>
							</a>
						</li>
					@endcan
				</ul>
			</li>
			@endif
			@if(Auth::user()->hasAnyPermission('configure services'))
			<li>
				<a href="javascript:void(0);" class="menu-toggle">
					<i class="material-icons">work</i>
					<span>{{__('Services')}}</span>
				</a>
				<ul class="ml-menu">
					@can('configure services')
						<li>
							<a href="{{route('services.braintree')}}">
								<span>{{__('Braintree')}}</span>
							</a>
						</li>
						<li>
							<a href="{{route('services.exchange')}}">
								<span>{{__('Exchange')}}</span>
							</a>
						</li>
						{{--
						<li>
							<a href="{{route('services.facebook')}}">
								<span>{{__('Facebook')}}</span>
							</a>
						</li>
						<li>
							<a href="{{route('services.twitter')}}">
								<span>{{__('Twitter')}}</span>
							</a>
						</li>
						--}}
						<li>
							<a href="{{route('services.google')}}">
								<span>{{__('Google')}}</span>
							</a>
						</li>
					@endcan
				</ul>
			</li>
			@endif
			@if(Auth::user()->hasAnyPermission('configure settings'))
			<li>
				<a href="javascript:void(0);" class="menu-toggle">
					<i class="material-icons">build</i>
					<span>{{__('Settings')}}</span>
				</a>
				<ul class="ml-menu">
					@can('configure settings')
						<li>
							<a href="{{route('settings.global')}}">
								<span>{{__('Global Settings')}}</span>
							</a>
						</li>
						<li>
							<a href="{{route('settings.payment')}}">
								<span>{{__('Payment Settings')}}</span>
							</a>
						</li>
					@endcan
				</ul>
			</li>
			<li class="header">{{__('HELP / SUPPORT')}}</li>
			<li>
				<a href="http://products.oluwatosin.me/mycashier/docs">
					<i class="material-icons col-red">description</i>
					<span>{{__('Documentation')}}</span>
				</a>
			</li>
			<li>
				<a href="mailto:support@oluwatosin.me">
					<i class="material-icons col-blue">mail</i>
					<span>{{__('Contact Support')}}</span>
				</a>
			</li>
			<li>
				<a href="javascript:void(0);">
					<i class="material-icons col-gold">star</i>
					<i class="material-icons col-gold">star</i>
					<i class="material-icons col-gold">star</i>
					<i class="material-icons col-gold">star</i>
					<i class="material-icons col-gold">star</i>
					<span>({{__('Rate us')}})</span>
				</a>
			</li>
			@endif
		</ul>
	</div>
	<!-- #Menu -->
	@can('subscribe to services')
		@if(Auth::user()->verified && ($subscription = Auth::user()->subscription('main')))
			<div class="legal">
				@if($subscription->onStrictTrial())
					<div class="copyright">
						{{__('Subscription')}}: <a href="{{route('subscription.change')}}">{{$subscription->plan->name}}</a> ({{__('On Trial')}})
					</div>
					<div class="version">
						{{__('Expires')}} <b>{{\Carbon\Carbon::parse($subscription->trial_ends_at)->toDayDateTimeString()}}</b>
					</div>
				@elseif($subscription->isActive())
					<div class="copyright">
						{{__('Subscription')}}: <a href="{{route('subscription.change')}}">{{$subscription->plan->name}}</a>
					</div>
					<div class="version">
						{{__('Expires')}} <b>{{\Carbon\Carbon::parse($subscription->ends_at)->toDayDateTimeString()}}</b>
					</div>
				@else
					<div class="copyright">
						{{__('Subscription')}}: <a href="{{route('subscription.change')}}">{{$subscription->plan->name}}</a> ({{__('Expired')}})
					</div>
					<div class="version">
						{{__('Extend your subscription to continue using service.')}}
					</div>
				@endif
			</div>
		@endif
	@endcan
</aside>
<!-- #END# Left Sidebar -->