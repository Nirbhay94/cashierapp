<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>@yield('page_title') | {{$settings->site_name}}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="{{asset('/auth/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/auth/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/auth/css/style.css')}}" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="favicon.ico" />
    @stack('css')
    <script>
        window.Laravel = '{!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!}';
    </script>
</head>
<body class="login">
    <div class="logo">
        <a href="/">
            @if($settings->logo)
                <img src="{{url($settings->logo)}}"/>
            @else
                <img src="{{url('images/logo.png')}}"/>
            @endif
        </a>
    </div>
    <div class="content">
        @yield('content')
    </div>
    <!--[if lt IE 9]>
    <script src="/auth/plugins/respond.min.js"></script>
    <script src="/auth/plugins/excanvas.min.js"></script>
    <script src="/auth/plugins/ie8.fix.min.js"></script>
    <![endif]-->
    <script src="{{asset('/auth/plugins/jquery.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('/auth/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('/auth/plugins/js.cookie.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('/auth/plugins/jquery-validation/js/jquery.validate.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('/auth/plugins/jquery-validation/js/additional-methods.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('/auth/js/app.js')}}" type="text/javascript"></script>
    @stack('scripts')
</body>
</html>
