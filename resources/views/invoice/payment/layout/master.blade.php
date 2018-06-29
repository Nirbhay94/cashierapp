<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<meta name="csrf-token" content="{{csrf_token()}}">
	<title>@yield('page_title') | {{$settings->site_name}}</title>
	<!-- Favicon-->
	<link rel="icon" href="{{asset('favicon.ico')}}" type="image/x-icon">
	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
	<!-- Bootstrap Core Css -->
	<link href="{{asset('plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet">
	<!-- Waves Effect Css -->
	<link href="{{asset('plugins/node-waves/waves.css')}}" rel="stylesheet" />
	<!-- Font Awesome -->
	<link href="{{asset('/landing/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
	<!-- Animation Css -->
	<link href="{{asset('plugins/animate-css/animate.css')}}" rel="stylesheet" />
	<!-- Material Icons -->
	<link href="{{asset('plugins/material-icons/material-icons.css')}}" rel="stylesheet" type="text/css">
	<!-- WebUI Popover -->
	<link href="{{asset('plugins/webui-popover/jquery.webui-popover.min.css')}}" rel="stylesheet" type="text/css">
	<!-- Color Box -->
	<link href="{{asset('/plugins/colorbox/example1/colorbox.css')}}" rel="stylesheet" type="text/css" />
	<!-- Bootstrap Select -->
	<link href="{{asset('plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
	<!-- Pace Css -->
	<link href="{{asset('plugins/pace/pace.css')}}" rel="stylesheet">
	@stack('css')
	<!-- Custom Css -->
	<link href="{{asset('css/style.css')}}" rel="stylesheet">
	<link href="{{asset('css/chip.css')}}" rel="stylesheet">
	<!-- Sweet Alerts -->
	<link href="{{asset('plugins/sweetalert/sweetalert.css')}}" rel="stylesheet" />
	<!-- Bootstrap Datetime Picker -->
	<link href="{{asset('plugins/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" />
	<!-- WaitMe Css-->
	<link href="{{asset('plugins/waitme/waitMe.css')}}" rel="stylesheet" />
	<!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
	<link href="{{asset('css/themes/all-themes.css')}}" rel="stylesheet" />
	<!-- JQuery DataTable -->
	<script>
        window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
	</script>
</head>

<body class="theme-purple ls-closed">
	<!-- Page Loader -->
	<div class="page-loader-wrapper">
		<div class="loader">
			<div class="preloader">
				<div class="spinner-layer pl-purple">
					<div class="circle-clipper left">
						<div class="circle"></div>
					</div>
					<div class="circle-clipper right">
						<div class="circle"></div>
					</div>
				</div>
			</div>
			<p>{{__('Please wait...')}}</p>
		</div>
	</div>
	<!-- #END# Page Loader -->
	<!-- Overlay For Sidebars -->
	<div class="overlay"></div>
	
	<section class="content">
		<div class="container-fluid">
			@yield('content')
		</div>
	</section>
	
	<!-- Jquery Core Js -->
	<script src="{{asset('plugins/jquery/jquery.min.js')}}" type="text/javascript"></script>
	<!-- Vue Js -->
	<script src="{{asset('plugins/vue/vue.min.js')}}" type="text/javascript"></script>
	<!-- Jquery Moment Js -->
	<script src="{{asset('plugins/momentjs/moment.js')}}" type="text/javascript"></script>
	<!-- Bootstrap Core Js -->
	<script src="{{asset('plugins/bootstrap/js/bootstrap.js')}}" type="text/javascript"></script>
	<!-- Bootstrap Notify Plugin Js -->
	<script src="{{asset('plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
	<!-- Bootstrap FileInput -->
	<script src="{{asset('plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>
	<!-- Bootstrap Select -->
	<script src="{{asset('plugins/bootstrap-fileselect/bootstrap-fileselect.min.js')}}" type="text/javascript"></script>
	<!-- Bootstrap Progress Bar -->
	<script src="{{asset('plugins/bootstrap-progressbar/bootstrap-progressbar.min.js')}}"></script>
	<!-- Bootstrap Datetime Picker -->
	<script src="{{asset('plugins/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>
	<!-- Select Plugin Js -->
	<script src="{{asset('plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>
	<!-- Slimscroll Plugin Js -->
	<script src="{{asset('plugins/jquery-slimscroll/jquery.slimscroll.js')}}"></script>
	<!-- Waves Effect Plugin Js -->
	<script src="{{asset('plugins/node-waves/waves.js')}}"></script>
	<!-- Select Plugin Js -->
	<script src="{{asset('plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>
	<!-- WebUI Popover -->
	<script src="{{asset('plugins/webui-popover/jquery.webui-popover.min.js')}}"></script>
	<!-- ColorBox -->
	<script src="{{asset('/plugins/colorbox/jquery.colorbox.js')}}" type="text/javascript"></script>
	<!-- Pace Js -->
	<script src="{{asset('plugins/pace/pace.min.js')}}"></script>
	<!-- Ckeditor -->
	<script src="{{asset('plugins/ckeditor/ckeditor.js')}}"></script>
	<!-- TinyMCE -->
	<script src="{{asset('plugins/tinymce/tinymce.js')}}"></script>
	<!-- Custom Js -->
	<script src="{{asset('js/admin.js')}}"></script>
	<script src="{{asset('js/pages/index.js')}}"></script>
	<!-- Sweet Alerts -->
	<script src="{{asset('plugins/sweetalert/sweetalert.min.js')}}"></script>
	<!-- Wait Me Plugin Js -->
	<script src="{{asset('plugins/waitme/waitMe.js')}}"></script>
	<!-- Jquery CountTo Plugin Js -->
	<script src="{{asset('plugins/jquery-countto/jquery.countTo.js')}}"></script>
	
	<script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ajaxStart(function() {
            Pace.restart();
        });
	</script>
	@stack('js')
	<script>
		var Global = function(){
            var handlePageLayout = function(){
                $.AdminBSB.options.leftSideBar.breakpointWidth = 2000;
                $.AdminBSB.leftSideBar.checkStatusForResize(true);
            };
            
		    return {
                init: function () {
                    handlePageLayout();
                },
                scrollTo: function(el, offeset) {
                    var pos = (el && el.size() > 0) ? el.offset().top : 0;

                    if (el) {
                        pos = pos + (offeset ? offeset : -1 * el.height());
                    }

                    $('html,body').animate({
                        scrollTop: pos
                    }, 'slow');
                },

                alert: function(type, message, icon,  close, reset, focus, lifetime) {
                    close = (close != null)? close: true;
                    reset = (reset != null)? reset: true;
                    focus = (focus != null)? focus: true;
                    lifetime = (lifetime != null)? lifetime: 0;
                    icon = (icon != null)? icon: "";

                    var options = {
                        container: ".block-header", // alerts parent container(by default placed after the page breadcrumbs)
                        place: "append", // "append" or "prepend" in container
                        type: type, // alert's type
                        message: message, // alert's message
                        close: close, // make alert closable
                        reset: reset, // close all previouse alerts first
                        focus: focus, // auto scroll to the alert after shown
                        closeInSeconds: lifetime, // auto close after defined seconds
                        icon: icon // put icon before the message
                    };

                    var id = this.getUniqueID("alert");

                    var html = '<div id="' + id + '" class="custom-alerts alert alert-' + options.type + ' fade in" style="margin-top: 10px">' +
                        (options.close ? '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>' : '') +
                        (options.icon !== "" ? '<i class="fa-lg fa fa-' + options.icon + '"></i>  ' : '') + options.message + '</div>';

                    if (options.reset) {
                        $('.custom-alerts').remove();
                    }

                    if (options.place === "append") {
                        $(options.container).append(html);
                    } else {
                        $(options.container).prepend(html);
                    }

                    if (options.focus) {
                        this.scrollTo($('#' + id));
                    }

                    if (options.closeInSeconds > 0) {
                        setTimeout(function() {
                            $('#' + id).remove();
                        }, options.closeInSeconds * 1000);
                    }

                    return id;
                },

                getUniqueID: function(prefix) {
                    return 'prefix_' + Math.floor(Math.random() * (new Date()).getTime());
                },

                notify: function(type, message, allowDismiss, animateEnter, animateExit, placementFrom, placementAlign) {
                    allowDismiss = (allowDismiss)? allowDismiss: true;
                    animateEnter = (animateEnter != null)? animateEnter: 'animated fadeInDown';
                    animateExit = (animateExit != null)? animateExit: 'animated fadeOutUp';
                    placementFrom = (placementFrom != null)? placementFrom: 'bottom';
                    placementAlign = (placementAlign != null)? placementAlign: 'center';

                    $.notify({
                        message: message
                    }, {
                        type: type,
                        allow_dismiss: allowDismiss,
                        newest_on_top: true,
                        timer: 1000,
                        placement: {
                            from: placementFrom,
                            align: placementAlign
                        },
                        animate: {
                            enter: animateEnter,
                            exit: animateExit
                        },
                        template: '<div data-notify="container" class="bootstrap-notify-container alert alert-dismissible {0} ' + (allowDismiss ? "p-r-35" : "") + '" role="alert">' +
                        '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                        '<span data-notify="icon"></span> ' +
                        '<span data-notify="title">{1}</span> ' +
                        '<span data-notify="message">{2}</span>' +
                        '<div class="progress" data-notify="progressbar">' +
                        '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                        '</div>' +
                        '<a href="{3}" target="{4}" data-notify="url"></a>' +
                        '</div>'
                    });
                },

                notifySuccess: function(message){
                    this.notify('alert-success', message);
                },

                notifyDanger: function(message){
                    this.notify('alert-danger', message);
                },

                notifyWarning: function(message){
                    this.notify('alert-warning', message);
                },

                notifyInfo: function(message){
                    this.notify('alert-info', message);
                }
            }
		}();
		
		$(document).ready(function () {
			Global.init();
        })
	</script>
	
	@include('includes.notifications')

</body>
</html>