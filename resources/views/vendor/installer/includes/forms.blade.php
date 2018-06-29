<div class="col-sm-6">
	@switch($data['type'])
		@case('radio')
		<div class="radio">
			@foreach($data['options'] as $value => $label)
				<label>
					{!! Form::radio(strtolower($key), $value, $data['value']) !!}
					<span>{{$label}}</span>
				</label>
			@endforeach
			@if($data['hint'])
				<span class="help-block">{{ $data['hint'] }}</span>
			@endif
		</div>
		@break
		
		@case('text')
		<div class="form-group label-floating {{ $errors->has(strtolower($key)) ? ' has-error ' : '' }}">
			<label class="control-label">{{$data['label']}}</label>
			{!! Form::text(strtolower($key), isset($data['value']) ? $data['value'] : '', ['class' => 'form-control']) !!}
			@if($data['hint'])11
				<span class="help-block">{{ $data['hint'] }}</span>
			@endif
		</div>
		@break
		
		@case('textarea')
		<div class="form-group label-floating {{ $errors->has(strtolower($key)) ? ' has-error ' : '' }}">
			{!! Form::textarea(strtolower($key), $data['value'], ['class' => 'form-control', 'required']) !!}
			@if($data['hint'])
				<span class="help-block">{{ $data['hint'] }}</span>
			@endif
		</div>
		@break
		
		@case('select')
		<div class="form-group label-floating {{ $errors->has(strtolower($key)) ? ' has-error ' : '' }}">
			<label class="control-label">{{$data['label']}}</label>
			{!! Form::select(strtolower($key), $data['options'],  isset($data['value']) ? $data['value'] : '', ['class' => 'form-control', 'required']) !!}
			@if($data['hint'])
				<span class="help-block">{{ $data['hint'] }}</span>
			@endif
		</div>
		@break
	@endswitch
</div>