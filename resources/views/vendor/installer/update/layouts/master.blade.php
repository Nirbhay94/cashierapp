<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('/installer/img/favicon.ico')}}">
    
    <title>@yield('page') | {{config('installer.name')}} {{__('by')}} {{config('installer.author.name')}} </title>
    
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('/installer/img/apple-icon.png')}}" />
    <link rel="icon" type="image/png" href="{{asset('/installer/img/favicon.png')}}" />
    
    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    
    <!-- CSS Files -->
    <link href="{{asset('/installer/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{asset('/installer/css/material-bootstrap-wizard.css')}}" rel="stylesheet" />
    
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="{{asset('/installer/css/demo.css')}}" rel="stylesheet" />
    
    @stack('css')
</head>
<body>
<div class="image-container set-full-height" style="background-image: url('/installer/img/wizard-city.jpg')">
    <!-- Creative Tim Branding   -->
    <a href="http://creative-tim.com">
        <div class="logo-container">
            <div class="logo">
                <img src="{{config('installer.author.avatar')}}">
            </div>
            <div class="brand">
                <p><strong>{{config('installer.author.name')}}</strong> <br/> <small>{{__('Developer')}}</small></p>
            </div>
        </div>
    </a>
    
    <!--  Made With Material Kit  -->
    <a href="{{config('installer.link')}}" class="made-with-mk">
        <div class="brand"><i class="material-icons">shopping_cart</i></div>
        <div class="made-with">{{__('Purchase Now')}}</div>
    </a>
    
    <!--   Big container   -->
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <!-- Wizard container  -->
                <div class="wizard-container">
                    <div class="card wizard-card" data-color="purple" id="wizardProfile">
                        @yield('content')
                    </div>
                </div> <!-- wizard container -->
            </div>
        </div><!-- end row -->
    </div> <!--  big container -->
    
    <div class="footer">
        <div class="container text-center">
            &copy{{\Carbon\Carbon::now()->year}} {{__('Official Script Installation Wizard')}} {{__('by')}} <a href="{{config('installer.link')}}">{{config('installer.author.name')}}</a></a>
        </div>
    </div>
</div>
</body>
<!-- Core JS Files -->
<script src="{{asset('/installer/js/jquery-2.2.4.min.js')}}" type="text/javascript"></script>
<script src="{{asset('/installer/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('/installer/js/jquery.bootstrap.js')}}" type="text/javascript"></script>
<!--  More information about jquery.validate here: http://jqueryvalidation.org/	 -->
<script src="{{asset('/installer/js/jquery.validate.min.js')}}"></script>
<script>
    var Global = function(){
        var searchVisible = 0;
        var transparent = true;
        var mobile_device = false;

        var handleMaterial = function(){
            $.material.init();
            $('[rel="tooltip"]').tooltip();
        };

        var handleDefaultTab = function(){
            var current = $(".steps #active_step").index("li");
            $('.wizard-card').bootstrapWizard('show', current);
        };

        var debounce = function(func, wait, immediate) {
            var timeout;
            return function() {
                var context = this, args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                }, wait);
                if (immediate && !timeout) func.apply(context, args);
            };
        };
        var refreshAnimation = function($wizard, index){
            var $total = $wizard.find('.nav li').length;
            var $li_width = 100/$total;

            var total_steps = $wizard.find('.nav li').length;
            var move_distance = $wizard.width() / total_steps;
            var index_temp = index;
            var vertical_level = 0;

            mobile_device = $(document).width() < 600 && $total > 3;

            if(mobile_device){
                move_distance = $wizard.width() / 2;
                index_temp = index % 2;
                $li_width = 50;
            }

            $wizard.find('.nav li').css('width',$li_width + '%');

            var step_width = move_distance;
            move_distance = move_distance * index_temp;

            var $current = index + 1;

            if($current == 1 || (mobile_device == true && (index % 2 == 0) )){
                move_distance -= 8;
            } else if($current == total_steps || (mobile_device == true && (index % 2 == 1))){
                move_distance += 8;
            }

            if(mobile_device){
                vertical_level = parseInt(index / 2);
                vertical_level = vertical_level * 38;
            }

            $wizard.find('.moving-tab').css('width', step_width);
            $('.moving-tab').css({
                'transform':'translate3d(' + move_distance + 'px, ' + vertical_level +  'px, 0)',
                'transition': 'all 0.5s cubic-bezier(0.29, 1.42, 0.79, 1)'

            });
        };

        var handlePageSize = function(){
            $('.set-full-height').css('height', 'auto');
            $(window).resize(function(){
                $('.wizard-card').each(function(){
                    var $wizard = $(this);

                    var index = $wizard.bootstrapWizard('currentIndex');
                    refreshAnimation($wizard, index);

                    $('.moving-tab').css({
                        'transition': 'transform 0s'
                    });
                });
            });
        };

        var handleWizard = function(){
            $('.wizard-card').bootstrapWizard({
                'tabClass': 'nav nav-pills',
                'nextSelector': '.btn-next',
                'previousSelector': '.btn-previous',

                onNext: function(tab, navigation, index) {
                    var $valid = $('.wizard-card form').valid();
                    if(!$valid) {
                        $validator.focusInvalid();
                        return false;
                    }
                },

                onInit : function(tab, navigation, index){
                    //check number of tabs and fill the entire row
                    var $total = navigation.find('li').length;
                    var $wizard = navigation.closest('.wizard-card');

                    $first_li = navigation.find('li:first-child a').html();
                    $moving_div = $('<div class="moving-tab">' + $first_li + '</div>');
                    $('.wizard-card .wizard-navigation').append($moving_div);

                    refreshAnimation($wizard, index);

                    $('.moving-tab').css('transition','transform 0s');
                },

                onTabClick : function(tab, navigation, index){
                    return false;
                },

                onTabShow: function(tab, navigation, index) {
                    var $total = navigation.find('li').length;
                    var $current = index+1;

                    var $wizard = navigation.closest('.wizard-card');

                    // If it's the last tab then hide the last button and show the finish instead
                    if($current >= $total) {
                        $($wizard).find('.btn-next').hide();
                        $($wizard).find('.btn-finish').show();
                    } else {
                        $($wizard).find('.btn-next').show();
                        $($wizard).find('.btn-finish').hide();
                    }

                    var button_text = navigation.find('li:nth-child(' + $current + ') a').html();

                    setTimeout(function(){
                        $('.moving-tab').text(button_text);
                    }, 150);

                    var checkbox = $('.footer-checkbox');

                    if( !index == 0 ){
                        $(checkbox).css({
                            'opacity':'0',
                            'visibility':'hidden',
                            'position':'absolute'
                        });
                    } else {
                        $(checkbox).css({
                            'opacity':'1',
                            'visibility':'visible'
                        });
                    }

                    refreshAnimation($wizard, index);
                }
            });


            $('[data-toggle="wizard-radio"]').click(function(){
                wizard = $(this).closest('.wizard-card');
                wizard.find('[data-toggle="wizard-radio"]').removeClass('active');
                $(this).addClass('active');
                $(wizard).find('[type="radio"]').removeAttr('checked');
                $(this).find('[type="radio"]').attr('checked','true');
            });

            $('[data-toggle="wizard-checkbox"]').click(function(){
                if( $(this).hasClass('active')){
                    $(this).removeClass('active');
                    $(this).find('[type="checkbox"]').removeAttr('checked');
                } else {
                    $(this).addClass('active');
                    $(this).find('[type="checkbox"]').attr('checked','true');
                }
            });
        };

        return {
            init: function(){
                handleMaterial();
                handleWizard();
                handlePageSize();
                handleDefaultTab();
            }
        }
    }();

    $(document).ready(function(){
        Global.init();
    });
</script>
@stack('js')
</html>
