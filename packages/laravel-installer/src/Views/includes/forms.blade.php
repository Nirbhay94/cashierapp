<div class="col-sm-6">
	@switch($data['type'])
		@case('radio')
		<div class="radio">
			@foreach($data['options'] as $value => $label)
				<label>
					@if(old(strtolower($key)))
						<input type="radio" name="{{strtolower($key)}}" value="{{$value}}" {{(old(strtolower($key)) == $value)? 'checked':'' }}>
					@else
						<input type="radio" name="{{strtolower($key)}}" value="{{$value}}" {{($data['value'] == $value)? 'checked':'' }}>
					@endif
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
			<input type="text" class="form-control" name="{{strtolower($key)}}" value="{{old(strtolower($key))? old(strtolower($key)): $data['value']}}" required/>
			@if($data['hint'])
				<span class="help-block">{{ $data['hint'] }}</span>
			@endif
		</div>
		@break
		
		@case('textarea')
		<div class="form-group">
			<textarea class="form-control" placeholder="{{$label}}" name="{{strtolower($key)}}">{{old(strtolower($key))? old(strtolower($key)): $data['value']}}</textarea>
			@if($data['hint'])
				<span class="help-block">{{ $data['hint'] }}</span>
			@endif
		</div>
		@break
		
		@case('select')
		<div class="form-group">
			<select name="{{strtolower($key)}}" class="form-control" id="{{strtolower($key)}}">
				@foreach($data['options'] as $value => $label)
					@if(old(strtolower($key)))
						<option value="{{$value}}" {{(old(strtolower($key)) == $value)? 'selected':'' }}> {{$label}} </option>
					@else
						<option value="{{$value}}" {{($data['value'] == $value)? 'selected':'' }}> {{$label}} </option>
					@endif
				@endforeach
			</select>
			@if($data['hint'])
				<span class="help-block">{{ $data['hint'] }}</span>
			@endif
		</div>
		@break
	@endswitch
</div>