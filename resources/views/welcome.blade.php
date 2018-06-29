<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{$settings->site_title}} | {{$settings->site_name}}</title>
    <!-- Animate -->
    <link href="{{asset('/landing/plugins/animate/animate.css')}}" rel="stylesheet" type="text/css">
    <!-- Bootstrap -->
    <link href="{{asset('/landing/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <!-- Magnific Popup -->
    <link href="{{asset('/landing/plugins/magnific-popup/magnific-popup.css')}}" rel="stylesheet" type="text/css">
    <!-- Owl Carousel -->
    <link href="{{asset('/landing/plugins/owl-carousel/owl.carousel.css')}}" rel="stylesheet" type="text/css">
    <!-- Slick -->
    <link href="{{asset('/landing/plugins/slick/slick.css')}}" rel="stylesheet" type="text/css">
    <!-- Font Awesome -->
    <link href="{{asset('/landing/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    <!-- Themify -->
    <link href="{{asset('/landing/plugins/themify-icons/themify-icons.css')}}" rel="stylesheet" type="text/css">
    <!-- Ion Icons -->
    <link href="{{asset('/landing/plugins/ionicons/css/ionicons.min.css')}}" rel="stylesheet" type="text/css">
    <!-- Custom CSS -->
    <link href="{{asset('/landing/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('/landing/css/responsive.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('/favicon.ico')}}" rel="icon">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Cabin:400,500,700|Montserrat:400,500,700" rel="stylesheet" type="text/css">
</head>
<body>
<!-- Preloader Start -->
<div id="preloader">
    <div class="colorlib-load"></div>
</div>
<header class="header_area animated">
    <div class="container-fluid">
        <div class="row align-items-center">
            <!-- Menu Area Start -->
            <div class="col-lg-10">
                <div class="menu_area">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <!-- Logo -->
                        <a class="navbar-brand" href="#"><i class="fa fa-briefcase"></i></a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ca-navbar" aria-controls="ca-navbar" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                        <!-- Menu Area -->
                        <div class="collapse navbar-collapse" id="ca-navbar">
                            <ul class="navbar-nav ml-auto" id="nav">
                                <li class="nav-item active"><a class="nav-link" href="#home">{{__('Home')}}</a></li>
                                <li class="nav-item"><a class="nav-link" href="#about">{{__('About')}}</a></li>
                                <li class="nav-item"><a class="nav-link" href="#features">{{__('Features')}}</a></li>
                                {{--<li class="nav-item"><a class="nav-link" href="#testimonials">{{__('Testimonials')}}</a></li>--}}
                                <li class="nav-item"><a class="nav-link" href="#pricing">{{__('Pricing')}}</a></li>
                            </ul>
                            <div class="sing-up-button d-lg-none">
                                @if(!Auth::check())
                                    <a href="{{route('login')}}">{{__('Login')}}</a>
                                @else
                                    <a href="{{route('home')}}">{{__('Dashboard')}}</a>
                                @endif
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="sing-up-button d-none d-lg-block">
                    @if(!Auth::check())
                        <a href="{{route('login')}}">{{__('Login')}}</a>
                    @else
                        <a href="{{route('home')}}">{{__('Dashboard')}}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>
<!-- ***** Header Area End ***** -->

<!-- ***** Wellcome Area Start ***** -->
<section class="wellcome_area clearfix" id="home">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12 col-md">
                <div class="wellcome-heading">
                    <h2>{{$settings->site_name}}</h2>
                    <h3>{{ucfirst($settings->site_name[0])}}</h3>
                    <p>{{$settings->site_title}}</p>
                </div>
                <div class="get-start-area">
                    <!-- Form Start -->
                    <form action="{{route('register')}}" method="get" class="form-inline">
                        <input type="email" name="email" class="form-control email" placeholder="name@company.com" required>
                        <input type="submit" class="submit" value="{{__('Get Started')}}">
                    </form>
                    <!-- Form End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Welcome thumb -->
    <div class="welcome-thumb wow fadeInDown" data-wow-delay="0.5s">
        <img src="/landing/img/bg-img/welcome-img.png" alt="">
    </div>
</section>
<!-- ***** Wellcome Area End ***** -->

<!-- ***** Special Area Start ***** -->
<section class="special-area bg-white section_padding_100" id="about">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Section Heading Area -->
                <div class="section-heading text-center">
                    <h2>{{__('HOW TO GET STARTED')}}</h2>
                    <div class="line-shape"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Single Special Area -->
            <div class="col-12 col-md-4">
                <div class="single-special text-center wow fadeInUp" data-wow-delay="0.2s">
                    <div class="single-icon">
                        <i class="ti-panel" aria-hidden="true"></i>
                    </div>
                    <h4>{{__('SIGN UP')}}</h4>
                    <p>{{__('Select the right plan that suites your needs and complete the registration form to get started.')}}</p>
                </div>
            </div>
            <!-- Single Special Area -->
            <div class="col-12 col-md-4">
                <div class="single-special text-center wow fadeInUp" data-wow-delay="0.4s">
                    <div class="single-icon">
                        <i class="ti-folder" aria-hidden="true"></i>
                    </div>
                    <h4>{{__('SET YOUR BUSINESS PROFILE')}}</h4>
                    <p>{{__('Configure your preferred payment channels and business details. This will be used to customize your experience and improve relationship with customers')}}</p>
                </div>
            </div>
            <!-- Single Special Area -->
            <div class="col-12 col-md-4">
                <div class="single-special text-center wow fadeInUp" data-wow-delay="0.6s">
                    <div class="single-icon">
                        <i class="ti-money" aria-hidden="true"></i>
                    </div>
                    <h4>{{__('START MANAGEMENT')}}</h4>
                    <p>{{__('Your interface will be setup immediately with the most outstanding features you require.')}}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Special Description Area -->
    <div class="special_description_area mt-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="special_description_img">
                        <img src="/landing/img/bg-img/special.png" alt="">
                    </div>
                </div>
                <div class="col-lg-6 col-xl-5 ml-xl-auto">
                    <div class="special_description_content">
                        <h2>{{__('Our Best Propositions for You!')}}</h2>
                        <p>{{__('We are now opened to new feature requests. Let us know how best we can improve to suite your business. Help us make this bigger and better.')}}</p>
                        <p>{{__('Coming on your way is android app to further make your management activities way more easier. Anticipate!')}}</p>
                        <div class="app-download-area">
                            <div class="app-download-btn wow fadeInUp" data-wow-delay="0.2s">
                                <!-- Google Store Btn -->
                                <a href="javascript:void(0)">
                                    <i class="fa fa-android"></i>
                                    <p class="mb-0"><span>coming soon on</span> Google Store</p>
                                </a>
                            </div>
                            {{--
                            <div class="app-download-btn wow fadeInDown" data-wow-delay="0.4s">
                                <!-- Apple Store Btn -->
                                <a href="#">
                                    <i class="fa fa-apple"></i>
                                    <p class="mb-0"><span>available on</span> Apple Store</p>
                                </a>
                            </div>
                            --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Special Area End ***** -->

<!-- ***** Awesome Features Start ***** -->
<section class="awesome-feature-area bg-white section_padding_0_50 clearfix" id="features">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Heading Text -->
                <div class="section-heading text-center">
                    <h2>{{__('Awesome Features')}}</h2>
                    <div class="line-shape"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Single Feature Start -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="single-feature">
                    <i class="ti-briefcase" aria-hidden="true"></i>
                    <h5>{{__('Product Inventory')}}</h5>
                    <p>{{__('Basically for any kind of product or services you offer, it helps you manage your available stock and track your sales.')}}</p>
                </div>
            </div>
            <!-- Single Feature Start -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="single-feature">
                    <i class="ti-receipt" aria-hidden="true"></i>
                    <h5>{{__('Flexible Invoicing')}}</h5>
                    <p>{{__('You are giving absolute super power in issuing and scheduling repeated invoices to customers, fully customized with your business details')}}</p>
                </div>
            </div>
            <!-- Single Feature Start -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="single-feature">
                    <i class="ti-user" aria-hidden="true"></i>
                    <h5>{{__('Manage Customers')}}</h5>
                    <p>{{__('This helps you get rid of any paper work of clients and customers, and allows you to manage them every where you go.')}}</p>
                </div>
            </div>
            
            <!-- Single Feature Start -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="single-feature">
                    <i class="ti-import" aria-hidden="true"></i>
                    <h5>{{__('Import Data')}}</h5>
                    <p>{{__('Just in case you have an existing database of customers and invoice, we have made it easy for you to transfer it into the system using a csv data file')}}</p>
                </div>
            </div>
            <!-- Single Feature Start -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="single-feature">
                    <i class="ti-flag" aria-hidden="true"></i>
                    <h5>{{__('Locale Support')}}</h5>
                    <p>{{__('We have made support for your locale currency and all your prices are formatted using your own currency symbol')}}</p>
                </div>
            </div>
            
            <!-- Single Feature Start -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="single-feature">
                    <i class="ti-dashboard" aria-hidden="true"></i>
                    <h5>{{__('Multi Tax Support')}}</h5>
                    <p>{{__('If you are in a region where multiple taxes applies to your different products, we have got you covered. All tax charges are stated per customer transaction.')}}</p>
                </div>
            </div>
            
            <!-- Single Feature Start -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="single-feature">
                    <i class="ti-announcement" aria-hidden="true"></i>
                    <h5>{{__('Send Broadcast')}}</h5>
                    <p>{{__('This comes in handy whenever you wish to pass some information to customers. Messages are customized according to your business profile setup')}}</p>
                </div>
            </div>
            
            <!-- Single Feature Start -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="single-feature">
                    <i class="ti-signal" aria-hidden="true"></i>
                    <h5>{{__('Notifications')}}</h5>
                    <p>{{__('Customers receives detailed information about invoice status and progress. So you need not bother on manual notification')}}</p>
                </div>
            </div>
            
            <!-- Single Feature Start -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="single-feature">
                    <i class="ti-money" aria-hidden="true"></i>
                    <h5>{{__('Receive Money')}}</h5>
                    <p>{{__('A QR code is generated on each invoices with which your customers upon scanning can pay using any of your specified payment channels, while transactions are recorded immediately.')}}</p>
                </div>
            </div>
            
            
            <!-- Single Feature Start -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="single-feature">
                    <i class="ti-tablet" aria-hidden="true"></i>
                    <h5>{{__('Awesome Experience')}}</h5>
                    <p>{{__('Our carefully crafted user interface is very pleasing to the eye. We guarantee you an awesome experiece.')}}</p>
                </div>
            </div>
            
            <!-- Single Feature Start -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="single-feature">
                    <i class="ti-server" aria-hidden="true"></i>
                    <h5>{{__('POS Terminal')}}</h5>
                    <p>{{__('Made flexible for any kind of business, you may print out receipt on the point of sale or issue an invoice to be paid later')}}</p>
                </div>
            </div>
            <!-- Single Feature Start -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="single-feature">
                    <i class="ti-pie-chart" aria-hidden="true"></i>
                    <h5>{{__('Statistics Monitor')}}</h5>
                    <p>{{__('Comprehensive highlight, statistics and charts are made available so that you can easily track your progress.')}}</p>
                </div>
            </div>
            <!-- Single Feature Start -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="single-feature">
                    <i class="ti-palette" aria-hidden="true"></i>
                    <h5>{{__('Full Customization')}}</h5>
                    <p>{{__('We provide you the ability to customize templates to be used for customers notification and printing of receipts')}}</p>
                </div>
            </div>
            <!-- Single Feature Start -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="single-feature">
                    <i class="ti-crown" aria-hidden="true"></i>
                    <h5>{{__('Best Industry Leader')}}</h5>
                    <p>{{__('Our service is the best of its kind and we are gradually evolving. We are open to feature suggestions, let us know how we can serve you better.')}}</p>
                </div>
            </div>
            <!-- Single Feature Start -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="single-feature">
                    <i class="ti-headphone" aria-hidden="true"></i>
                    <h5>{{__('24/7 Online Support')}}</h5>
                    <p>{{__('If you have any enquiry or complaints, our online support agent is always available. We would love to hear from you.')}}</p>
                </div>
            </div>
        </div>

    </div>
</section>
<!-- ***** Awesome Features End ***** -->

<!-- ***** Video Area Start ***** -->
<div class="video-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Video Area Start -->
                <div class="video-area" style="background-image: url(/landing/img/bg-img/video.jpg);">
                    <div class="video-play-btn">
                        <a href="javascript:void(0);" class="video_btn"><i class="fa fa-play" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ***** Video Area End ***** -->

<!-- ***** Cool Facts Area Start ***** -->
<section class="cool_facts_area clearfix">
    <div class="container">
        <div class="row">
            <!-- Single Cool Fact-->
            <div class="col-12 col-md-3 col-sm-6">
                <div class="single-cool-fact d-flex justify-content-center wow fadeInUp" data-wow-delay="0.4s">
                    <div class="counter-area">
                        <h3><span class="counter">10</span>+</h3>
                    </div>
                    <div class="cool-facts-content">
                        <i class="ion-flag"></i>
                        <p>{{__('SUPPORTED')}} <br> {{__('LANGUAGES')}}</p>
                    </div>
                </div>
            </div>
            
            <!-- Single Cool Fact-->
            <div class="col-12 col-md-3 col-sm-6">
                <div class="single-cool-fact d-flex justify-content-center wow fadeInUp" data-wow-delay="0.6s">
                    <div class="counter-area">
                        <h3><span class="counter">103</span>+</h3>
                    </div>
                    <div class="cool-facts-content">
                        <i class="ion-person"></i>
                        <p>{{__('SUPPORTED')}} <br>{{__('CURRENCIES')}}</p>
                    </div>
                </div>
            </div>
            
            <!-- Single Cool Fact-->
            <div class="col-12 col-md-3 col-sm-6">
                <div class="single-cool-fact d-flex justify-content-center wow fadeInUp" data-wow-delay="0.6s">
                    <div class="counter-area">
                        <h3><span class="counter">20</span>+</h3>
                    </div>
                    <div class="cool-facts-content">
                        <i class="ion-paintbucket"></i>
                        <p>{{__('AMAZING')}} <br>{{__('FEATURES')}}</p>
                    </div>
                </div>
            </div>
            
            <!-- Single Cool Fact-->
            <div class="col-12 col-md-3 col-sm-6">
                <div class="single-cool-fact d-flex justify-content-center wow fadeInUp" data-wow-delay="0.6s">
                    <div class="counter-area">
                        <h3><span class="counter">3</span>+</h3>
                    </div>
                    <div class="cool-facts-content">
                        <i class="ion-cash"></i>
                        <p>{{__('PAYMENT')}} <br>{{__('PROCESSORS')}}</p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>
<!-- ***** Cool Facts Area End ***** -->

{{--
<!-- ***** Client Feedback Area Start ***** -->
<section class="footer-contact-area clients-feedback-area bg-white section_padding_100 clearfix" id="testimonials">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10">
                <div class="slider slider-for">
                    <!-- Client Feedback Text  -->
                    <div class="client-feedback-text text-center">
                        <div class="client">
                            <i class="fa fa-quote-left" aria-hidden="true"></i>
                        </div>
                        <div class="client-description text-center">
                            <p>“ I have been using it for a number of years. I use Colorlib for usability testing. It's great for taking images and making clickable image prototypes that do the job and save me the coding time and just the general hassle of hosting. ”</p>
                        </div>
                        <div class="star-icon text-center">
                            <i class="ion-ios-star"></i>
                            <i class="ion-ios-star"></i>
                            <i class="ion-ios-star"></i>
                            <i class="ion-ios-star"></i>
                            <i class="ion-ios-star"></i>
                        </div>
                        <div class="client-name text-center">
                            <h5>Aigars Silkalns</h5>
                            <p>Ceo Colorlib</p>
                        </div>
                    </div>
                    <!-- Client Feedback Text  -->
                    <div class="client-feedback-text text-center">
                        <div class="client">
                            <i class="fa fa-quote-left" aria-hidden="true"></i>
                        </div>
                        <div class="client-description text-center">
                            <p>“ I use Colorlib for usability testing. It's great for taking images and making clickable image prototypes that do the job and save me the coding time and just the general hassle of hosting. ”</p>
                        </div>
                        <div class="star-icon text-center">
                            <i class="ion-ios-star"></i>
                            <i class="ion-ios-star"></i>
                            <i class="ion-ios-star"></i>
                            <i class="ion-ios-star"></i>
                            <i class="ion-ios-star"></i>
                        </div>
                        <div class="client-name text-center">
                            <h5>Jennifer</h5>
                            <p>Developer</p>
                        </div>
                    </div>
                    <!-- Client Feedback Text  -->
                    <div class="client-feedback-text text-center">
                        <div class="client">
                            <i class="fa fa-quote-left" aria-hidden="true"></i>
                        </div>
                        <div class="client-description text-center">
                            <p>“ I have been using it for a number of years. I use Colorlib for usability testing. It's great for taking images and making clickable image prototypes that do the job.”</p>
                        </div>
                        <div class="star-icon text-center">
                            <i class="ion-ios-star"></i>
                            <i class="ion-ios-star"></i>
                            <i class="ion-ios-star"></i>
                            <i class="ion-ios-star"></i>
                            <i class="ion-ios-star"></i>
                        </div>
                        <div class="client-name text-center">
                            <h5>Helen</h5>
                            <p>Marketer</p>
                        </div>
                    </div>
                    <!-- Client Feedback Text  -->
                    <div class="client-feedback-text text-center">
                        <div class="client">
                            <i class="fa fa-quote-left" aria-hidden="true"></i>
                        </div>
                        <div class="client-description text-center">
                            <p>“ I have been using it for a number of years. I use Colorlib for usability testing. It's great for taking images and making clickable image prototypes that do the job and save me the coding time and just the general hassle of hosting. ”</p>
                        </div>
                        <div class="star-icon text-center">
                            <i class="ion-ios-star"></i>
                            <i class="ion-ios-star"></i>
                            <i class="ion-ios-star"></i>
                            <i class="ion-ios-star"></i>
                            <i class="ion-ios-star"></i>
                        </div>
                        <div class="client-name text-center">
                            <h5>Henry smith</h5>
                            <p>Developer</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Client Thumbnail Area -->
            <div class="col-12 col-md-6 col-lg-5">
                <div class="slider slider-nav">
                    <div class="client-thumbnail">
                        <img src="/landing/img/bg-img/client-3.jpg" alt="">
                    </div>
                    <div class="client-thumbnail">
                        <img src="/landing/img/bg-img/client-2.jpg" alt="">
                    </div>
                    <div class="client-thumbnail">
                        <img src="/landing/img/bg-img/client-1.jpg" alt="">
                    </div>
                    <div class="client-thumbnail">
                        <img src="/landing/img/bg-img/client-2.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Client Feedback Area End ***** -->
--}}

<!-- ***** Pricing Plane Area Start *****==== -->
@if(count($records))
<section class="pricing-plane-area section_padding_100_70 clearfix" id="pricing">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Heading Text  -->
                <div class="section-heading text-center">
                    <h2>{{__('Pricing Plan')}}</h2>
                    <div class="line-shape"></div>
                </div>
            </div>
        </div>
        @foreach($records as $plans)
        <div class="row no-gutters text-center">
            @foreach($plans as $plan)
            <div class="col-md-6 col-lg-3" style="margin: 0 auto;">
                <!-- Package Price  -->
                <div class="single-price-plan text-center">
                    <!-- Package Text  -->
                    <div class="package-plan">
                        <h5>{{$plan->name}}</h5>
                        <div class="ca-price d-flex justify-content-center">
                            <span>{{ currency() }}</span>
                            <h4>{{money_number_format($plan->price)}}</h4>
                        </div>
                        <h5>
                            <small>{{$plan->interval_count}} {{$plan->interval}}</small>
                            @if($plan->trial_period_days != 0)
                                <small><b>{{__('with')}} {{$plan->trial_period_days}} {{__('days of trial')}}</b></small>
                            @endif
                        </h5>
                    </div>
                    <div class="package-description">
                        @foreach($plan->features()->orderBy('sort_order', 'ASC')->get() as $feature)
                            <p>
                                @if($feature->type == 'boolean')
                                    @if(in_array($feature->value, config('laraplans.positive_words')))
                                        <strong><i class="fa fa-check"></i></strong>
                                    @else
                                        <strong><i class="fa fa-times"></i></strong>
                                    @endif
                                @else
                                    @if($feature->value >= 0 || in_array($feature->value, config('laraplans.positive_words')))
                                        <strong>{{$feature->value}}</strong>
                                    @else
                                        <strong>{{__('Unlimited')}}</strong>
                                    @endif
                                @endif
                                <span>{{$feature->label}}</span>
                            </p>
                        @endforeach
                    </div>
                    <p style="padding: 0px 5px">{{$plan->description}}</p>
                    <!-- Plan Button  -->
                    <div class="plan-button">
                        <a href="{{route('register')}}">{{__('Register')}}</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</section>
@endif
<!-- ***** Pricing Plane Area End ***** -->

<!-- ***** Footer Area Start ***** -->
<footer class="footer-social-icon text-center section_padding_70 clearfix">
    <!-- footer logo -->
    <div class="footer-text">
        <h2>{{$settings->site_name_abbr}}.</h2>
    </div>
    <!-- social icon-->
    <div class="footer-social-icon">
        @if($settings->facebook_url)
            <a href="{{$settings->facebook_url}}"><i class="fa fa-facebook" aria-hidden="true"></i></a>
        @endif
        @if($settings->twitter_url)
            <a href="{{$settings->twitter_url}}"><i class="fa fa-twitter" aria-hidden="true"></i></a>
        @endif
        @if($settings->instagram_url)
        <a href="{{$settings->instagram_url}}"><i class="fa fa-instagram" aria-hidden="true"></i></a>
        @endif
        @if($settings->google_plus_url)
        <a href="{{$settings->google_plus_url}}"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
        @endif
    </div>
    <!-- Foooter Text-->
    <div class="copyright-text">
        <!-- ***** Removing this text is now allowed! This template is licensed under CC BY 3.0 ***** -->
        <p>Copyright &copy;{{\Carbon\Carbon::now()->year}} <a href="http://oluwatosin.me" rel="dofollow">HolluwaTosin360</a></p>
    </div>
</footer>
<!-- JQuery -->
<script src="{{asset('/landing/plugins/jquery.min.js')}}" type="text/javascript"></script>
<!-- Popper -->
<script src="{{asset('/landing/plugins/popper.min.js')}}"></script>
<!-- Bootstrap -->
<script src="{{asset('/landing/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- Slick -->
<script src="{{asset('/landing/plugins/slick/slick.min.js')}}"></script>
<!-- Custom JS Plugins-->
<script src="{{asset('/landing/plugins/plugins.js')}}"></script>
<script src="{{asset('/landing/plugins/active.js')}}"></script>
</body>
</html>
