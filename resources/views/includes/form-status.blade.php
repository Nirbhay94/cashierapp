@if (session('message'))
  <div class="alert alert-{{ Session::get('status') }} status-box alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;<span class="sr-only">Close</span></a>
    {{ session('message') }}
  </div>
@endif

@if (session('success'))
  <div class="alert alert-success alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{ session('success') }}
  </div>
@endif

@if(session('status'))
    <div class="alert alert-success">
        <button class="close" data-close="alert"></button>
        <span>{{ session('status') }}</span>
    </div>
@endif

@if (session('error'))
  <div class="alert alert-danger alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{ session('error') }}
  </div>
@endif

@if (count($errors) > 0)
  <div class="alert alert-danger alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif