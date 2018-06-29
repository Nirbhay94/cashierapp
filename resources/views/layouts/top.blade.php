<!-- Top Bar -->
<nav class="navbar">
	<div class="container-fluid">
		<div class="navbar-header">
			<a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
			<a href="javascript:void(0);" class="bars"></a>
			<a class="navbar-brand" href="{{route('home')}}">
				@if($settings->logo)
					<img src="{{url($settings->logo)}}" alt="{{$settings->site_name}}" class="logo"/>
				@else
					<img src="{{url('images/logo.png')}}" alt="{{$settings->site_name}}" class="logo"/>
				@endif
			</a>
		</div>
		<div class="collapse navbar-collapse" id="navbar-collapse">
			<ul class="nav navbar-nav navbar-right">
				<li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li>
			
				<li class="pull-right" title="{{__('My Profile')}}"><a href="{{route('profile.show', ['username' => Auth::user()->name])}}"><i class="material-icons">person</i></a></li>
			
				<li class="pull-right" title="{{__('POS Terminal')}}"><a href="{{route('pos')}}"><i class="material-icons">local_atm</i></a></li>
			</ul>
		</div>
	</div>
</nav>
<!-- #Top Bar -->