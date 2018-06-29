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
	<!-- Bootstrap Progress Bar -->
	<link href="{{asset('plugins/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet">
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
	<!-- Bootstrap Spinner Css -->
	<link href="{{asset('plugins/jquery-spinner/css/bootstrap-spinner.css')}}" rel="stylesheet">
	<!-- Bootstrap Select2 -->
	<link href="{{asset('plugins/select2/css/select2.min.css')}}" rel="stylesheet" />
	<link href="{{asset('plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet" />
	<!-- Pace Css -->
	<link href="{{asset('plugins/pace/pace.css')}}" rel="stylesheet">
	<!-- Perfect Scrollbar -->
	<link href="{{asset('plugins/vue-perfectscroll/perfect-scrollbar.css')}}" rel="stylesheet">
	<!-- Custom Css -->
	<link href="{{asset('css/style.css')}}" rel="stylesheet">
	<link href="{{asset('css/chip.css')}}" rel="stylesheet">
	<link href="{{asset('css/thumbnail.css')}}" rel="stylesheet">
	@stack('css')
	<!-- Bootstrap FileInput -->
	<link href="{{asset('plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css">
	<!-- Bootstrap Spinner Css -->
	<link href="{{asset('plugins/jquery-spinner/css/bootstrap-spinner.css')}}" rel="stylesheet">
	<!-- Sweet Alerts -->
	<link href="{{asset('plugins/sweetalert/sweetalert.css')}}" rel="stylesheet" />
	<!-- Bootstrap Datetime Picker -->
	<link href="{{asset('plugins/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" />
	<!-- WaitMe Css-->
	<link href="{{asset('plugins/waitme/waitMe.css')}}" rel="stylesheet" />
	<!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
	<link href="{{asset('css/themes/all-themes.css')}}" rel="stylesheet" />
	<!-- JQuery DataTable -->
	<link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
	<link href="{{asset('plugins/jquery-datatable/jquery.dataTables.css')}}" rel="stylesheet">
	<link href="{{asset('plugins/jquery-datatable/extensions/responsive/responsive.dataTables.css')}}" rel="stylesheet">
	<script>
        window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
	</script>
</head>

<body class="theme-purple">
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
	<!-- #END# Overlay For Sidebars -->
	@include('layouts.top')
	<section>
		@include('layouts.left')
		@include('layouts.right')
	</section>
	
	<section class="content">
		<div class="container-fluid">
			@yield('content')
		</div>
	</section>
	
	<!-- Jquery Core Js -->
	<script src="{{asset('plugins/jquery/jquery.min.js')}}" type="text/javascript"></script>
	<!-- Vue Js -->
	<script src="{{asset('plugins/vue/vue.min.js')}}" type="text/javascript"></script>
	<script src="{{asset('plugins/vue-infinite-loading/vue-infinite-loading.js')}}" type="text/javascript"></script>
	<script src="{{asset('plugins/vue-perfectscroll/perfect-scrollbar.min.js')}}" type="text/javascript"></script>
	<!-- Jquery Moment Js -->
	<script src="{{asset('plugins/momentjs/moment.js')}}" type="text/javascript"></script>
	<!-- Bootstrap Core Js -->
	<script src="{{asset('plugins/bootstrap/js/bootstrap.js')}}" type="text/javascript"></script>
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
	<!-- Jquery Form -->
	<script src="{{asset('plugins/jquery.form.min.js')}}" type="text/javascript"></script>
	<!-- Axios -->
	<script src="{{asset('plugins/axios/axios.min.js')}}" type="text/javascript"></script>
	<!-- Waves Effect Plugin Js -->
	<script src="{{asset('plugins/node-waves/waves.js')}}"></script>
	<!-- Jquery CountTo Plugin Js -->
	<script src="{{asset('plugins/jquery-countto/jquery.countTo.js')}}"></script>
	<!-- Bootstrap Notify Plugin Js -->
	<script src="{{asset('plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
	<!-- Select Plugin Js -->
	<script src="{{asset('plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>
	<!-- Jquery Spinner Plugin Js -->
	<script src="{{asset('plugins/jquery-spinner/js/jquery.spinner.js')}}"></script>
	<!-- Sparkline Chart Plugin Js -->
	<script src="{{asset('plugins/jquery-sparkline/jquery.sparkline.js')}}"></script>
	<!-- Charts JS -->
	<script src="{{asset('plugins/chartjs/chart.bundle.min.js')}}"></script>
	<!-- WebUI Popover -->
	<script src="{{asset('plugins/webui-popover/jquery.webui-popover.min.js')}}"></script>
	<!-- ColorBox -->
	<script src="{{asset('/plugins/colorbox/jquery.colorbox.js')}}" type="text/javascript"></script>
	<!-- Jquery Validation Plugin Css -->
	<script src="{{asset('plugins/jquery-validation/jquery.validate.js')}}"></script>
	<!-- Jquery Form Repeater -->
	<script src="{{asset('plugins/jquery-formrepeater/jquery.repeater.min.js')}}"></script>
	<!-- Bootstrap Select2 -->
	<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
	<!-- Jquery Spinner Plugin Js -->
	<script src="{{asset('plugins/jquery-spinner/js/jquery.spinner.js')}}"></script>
	<!-- Pace Js -->
	<script src="{{asset('plugins/pace/pace.min.js')}}"></script>
	<!-- JQuery Datatable -->
	<script src="{{asset('plugins/jquery-datatable/jquery.dataTables.js')}}"></script>
	<script src="{{asset('plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')}}"></script>
	<script src="{{asset('plugins/jquery-datatable/extensions/responsive/dataTables.responsive.js')}}"></script>
	<script src="{{asset('plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js')}}"></script>
	<script src="{{asset('plugins/jquery-datatable/extensions/export/buttons.flash.min.js')}}"></script>
	<script src="{{asset('plugins/jquery-datatable/extensions/export/jszip.min.js')}}"></script>
	<script src="{{asset('plugins/jquery-datatable/extensions/export/pdfmake.min.js')}}"></script>
	<script src="{{asset('plugins/jquery-datatable/extensions/export/vfs_fonts.js')}}"></script>
	<script src="{{asset('plugins/jquery-datatable/extensions/export/buttons.html5.min.js')}}"></script>
	<script src="{{asset('plugins/jquery-datatable/extensions/export/buttons.print.min.js')}}"></script>
	<!-- Jquery Input Mask -->
	<script src="{{asset('plugins/jquery-inputmask/dist/jquery.inputmask.bundle.js')}}"></script>
	<script src="{{asset('plugins/jquery-inputmask/dist/inputmask/phone-codes/phone.js')}}"></script>
	<script src="{{asset('plugins/jquery-inputmask/dist/inputmask/phone-codes/phone-be.js')}}"></script>
	<script src="{{asset('plugins/jquery-inputmask/dist/inputmask/phone-codes/phone-ru.js')}}"></script>
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
	
	
	<script>
        $.fn.select2.defaults.set("theme", "bootstrap");
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ajaxStart(function() {
            Pace.restart();
        });
        
        window.axios.defaults.headers.common = {
            'X-Requested-With' : 'XMLHttpRequest',
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        };
	</script>
	@stack('js')
	<script>
		var Global = function(){
		    var handleCharts = function(){
                var colors = $.AdminBSB.options.colors, chartColor;
                
                //Chart Bar
                $.each($('.chart.chart-bar'), function (i, key) {
	                chartColor = colors[$(key).data('chartcolor')];
                    
                    $(key).sparkline(undefined, {
                        type: 'bar',
                        barColor: chartColor,
                        negBarColor: chartColor,
                        barWidth: '8px',
                        height: '34px'
                    });
                });

                //Chart Pie
                $.each($('.chart.chart-pie'), function (i, key) {
                    chartColor = colors[$(key).data('chartcolor')];

                    $(key).sparkline(undefined, {
                        type: 'pie',
                        height: '50px',
                        sliceColors: [
                            hexToRgba(chartColor, '0.55'),
	                        hexToRgba(chartColor, '0.70'),
	                        hexToRgba(chartColor, '0.85'),
	                        hexToRgba(chartColor, '1')
                        ]
                    });
                });

                //Chart Line
                $.each($('.chart.chart-line'), function (i, key) {
                    chartColor = colors[$(key).data('chartcolor')];

                    $(key).sparkline(undefined, {
                        type: 'line',
                        width: '60px',
                        height: '45px',
                        lineColor: chartColor,
                        lineWidth: 1.3,
                        fillColor: 'rgba(0,0,0,0)',
                        spotColor: chartColor,
                        maxSpotColor: chartColor,
                        minSpotColor: chartColor,
                        spotRadius: 3,
                        highlightSpotColor: chartColor
                    });
                });
		    };
		    
		    var handleProgressBar = function(){
		        var progressBar;
		        
                if (progressBar = $(".progress .progress-bar")) {
                    progressBar.progressbar({display_text: 'fill'});
                }
		    };
		    
		    var handleQuickSettings = function(){
		        let link = '{{route('ajax.profile.update', ['username' => Auth::user()->name])}}';
		        
		        $('.quick-settings input[type=checkbox]').change(function(){
                    var name = $(this).attr('name');
		            var value = (this.checked)? 'yes': 'no';
			        
                    $.ajax({
                        url: link,
                        method: 'POST',
                        data: {
                            name: name,
                            value: value
                        },
                        success: function(response) {
                            Global.notifySuccess(response)
                        },
                        error: function(xhr){
                            var response = xhr.responseJSON;
                            
                            if($.isPlainObject(response)){
                                $.each(response.errors, function(key, value){
                                    Global.notifyDanger(value[0]);
                                });
                            }else{
                                Global.notifyDanger(response);
                            }
                        }
                    });
		        });
		    };

            var handleMaterialComponents = function(){
                $('select.ms').each(function (i, obj) {
                    if(!$(obj).hasClass("select2-hidden-accessible")){
                        $(this).select2();
                    }
                });
            };
            
            var handleAxiosPace = function(){
                var pending = 0;
                
                axios.interceptors.request.use(function (config) {
                    pending++, Pace.restart();
                    
                    return config;
                }, function (error) {
                    return Promise.reject(error);
                });
	            
                axios.interceptors.response.use(function (response) {
                    pending--;
                    
                    if (pending === 0) {
                        Pace.stop()
                    }
                    
                    return response;
                }, function (error) {
                    pending--;
                    
                    if (pending === 0) {
                        Pace.stop()
                    }
                    return Promise.reject(error);
                });
            }
            
		    return {
                init: function () {
                    handleCharts();
                    handleQuickSettings();
                    handleProgressBar();
                    handleMaterialComponents();
                    //handleAxiosPace();
                },
			    
			    resetForm: function(form){
                    form[0].reset();
			    },
			    
			    prepareForm: function(form){
                    $('select:not(.ms)').selectpicker('refresh');
                    form.find(':input').trigger('change');
			    },

                openPrintWindow: function(url){
                    let specs = 'width=400, height=800';

                    window.open(url, '_blank', specs);
                },

                bindModalAjaxForm: function(form, action, method, table, modal){
                    let submit = form.find('#form-submit');
                    
                    modal = (modal)? modal: $('#alter-row');

                    form.validate({
                        highlight: function (input) {
                            $(input).parents('.form-line').addClass('error');
                        },
                        unhighlight: function (input) {
                            $(input).parents('.form-line').removeClass('error');
                        },
                        errorPlacement: function (error, element) {
                            $(element).parents('.form-float').append(error);
                        }
                    });

                    form.ajaxForm({
                        dataType: 'json',
                        url: action,
                        method: method,

                        beforeSubmit: function(){
                            submit.attr('disabled', true);
                        },
                        error: function(xhr){
                            var response = xhr.responseJSON;

                            if($.isPlainObject(response)){
                                $.each(response.errors, function(key, value){
                                    var input = form.find(':input#'+key),
	                                    error = '', body;
	                                
                                    if(input.length){
                                        error += '<label id="name-error" class="error" for="'+key+'">';
                                        error += value[0];
                                        error += '</label>';

                                        input.closest('.form-line').addClass('error');
                                        input.closest('.form-float').append(error);
                                    }else{
                                        body = modal.find('.modal-body');
                                        
                                        error += '<div class="alert alert-danger alert-dismissible" role="alert">';
                                        error += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
                                        error += value[0];
                                        error += '</div>';
                                        
                                        body.prepend(error);
                                    }

                                    Global.notifyDanger(value[0]);
                                });
                            }else{
                                modal.modal('hide');
                                Global.notifyDanger(response);
                            }

                            submit.attr('disabled', false);
                        },
	                    
                        success: function(message){
                            submit.attr('disabled', false);

                            Global.resetForm(form);
                            Global.prepareForm(form);

                            modal.modal('hide');

                            Global.notifySuccess(message);
                            
                            if(table){table.ajax.reload();}
                        }
                    });
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