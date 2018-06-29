<script>
	var timeout = 0;
	@foreach($errors->all() as $error)
		setTimeout(function(){Global.notifyDanger('{{$error}}');}, timeout += 1000);
	@endforeach
	
	@if($status = session()->get('status'))
		@if(isset($status['type']))
			@if(isset($status['message']) && is_array($status['message']))
				@foreach($status['message'] as $message)
					setTimeout(function(){Global.alert('{{$status['type']}}','{{$message}}');}, timeout += 1000);
				@endforeach
			@else
	            setTimeout(function(){Global.alert('{{$status['type']}}','{{$status['message']}}');}, timeout += 1000);
			@endif
		@endif
	@endif
	
	@if($success = session()->get('success'))
		@if(is_array($success))
			@foreach($success as $message)
	            setTimeout(function(){Global.notifySuccess('{{$message}}')}, timeout += 1000);
			@endforeach
		@else
	        setTimeout(function(){Global.notifySuccess('{{$success}}')}, timeout += 1000);
		@endif
	@endif
	
	@if($error = session()->get('error'))
		@if(is_array($error))
			@foreach($error as $message)
	            setTimeout(function(){Global.notifyDanger('{{$message}}')}, timeout += 1000);
			@endforeach
		@else
	        setTimeout(function(){Global.notifyDanger('{{$error}}')}, timeout += 1000);
		@endif
	@endif
	
	@if($info = session()->get('info'))
		@if(is_array($info))
			@foreach($info as $message)
	            setTimeout(function(){Global.notifyInfo('{{$message}}')}, timeout += 1000);
	            timeout += 1000;
			@endforeach
		@else
	        setTimeout(function(){Global.notifyInfo('{{$info}}')}, timeout += 1000);
		@endif
	@endif
	
	@if($warning = session()->get('warning'))
		@if(is_array($warning))
			@foreach($warning as $message)
	            setTimeout(function(){Global.notifyWarning('{{$message}}')}, timeout += 1000);
			@endforeach
		@else
	        setTimeout(function(){Global.notifyWarning('{{$warning}}')}, timeout += 1000);
		@endif
	@endif
	
	@if($danger = session()->get('danger'))
		@if(is_array($danger))
			@foreach($danger as $message)
	            setTimeout(function(){Global.notifyDanger('{{$message}}')}, timeout += 1000);
			@endforeach
		@else
	        setTimeout(function(){Global.notifyDanger('{{$danger}}')}, timeout += 1000);
		@endif
	@endif
</script>