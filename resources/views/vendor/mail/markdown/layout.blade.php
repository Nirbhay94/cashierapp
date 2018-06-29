@if (isset($header))
{!! strip_tags($header) !!}
@endif

{!! strip_tags($slot) !!}

@if (isset($subcopy))
{!! strip_tags($subcopy) !!}
@endif

@if (isset($footer))
{!! strip_tags($footer) !!}
@endif
