<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    
    <!-- CSS only -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <!-- Title -->
    <title>Gls - Logistic | Home</title>

    <!-- Favicon -->
    <link rel="icon" href="{{asset('main2/img/core-img/favicon.ico')}}">

    <!-- main_css Stylesheet -->
    <link rel="stylesheet" href="{{asset('main2/main_css/style.css')}}">

    <style>
        #back {
    text-decoration: none;
    display: inline-block;
    padding: 8px 16px;
    background-color: rgb(238, 238, 238);
}

#back:hover {
    background-color: rgb(184, 179, 179);
    color: black;
}

.prev {
    background-color: #f1f1f1;
    color: black;
}
    </style>

</head>

<body>
    <!-- Preloader -->
    <div id="preloader">
        <i class="circle-preloader"></i>
        <img src="{{asset('main2/img/core-img/logo.png')}}" alt="">
    </div>

    
    <!-- Search Wrapper -->
    <div class="search-wrapper">
        <!-- Close Btn -->
        <div class="close-btn"><i class="fa fa-times" aria-hidden="true"></i></div>

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form action="#" method="post">
                        <input type="search" name="search" placeholder="Type any keywords...">
                        <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ##### Header Area Start ##### -->
    <header class="header-area">


        <!-- Navbar Area -->
        <div class="delicious-main-menu">
            <div class="classy-nav-container breakpoint-off">
                <div class="container">
                    <!-- Menu -->
                    <nav class="classy-navbar justify-content-between" id="deliciousNav">

                        <!-- Logo -->
                        <a class="nav-brand" href="{{url('/')}}">
				            <img style="width: 40px; height:40px;"  src="{{asset('main2/img/core-img/logo.png')}}" alt="">
                            <span>GLS</span>
			            </a>

                        <div class="ui_info">
                            <span class="search-btn" style="float: left; margin-right:5px;">
                                <i class="fa fa-search fa-lg" aria-hidden="true"></i>
                            </span>&nbsp;
                            <span style="margin-left:5px; border:solid 1px black; border-radius:50%; float:left;">
                                <a href="
                                @if(session()->has('customer'))
                                    {{route('main_home')}}
                                @else
                                    {{route('main_signin')}}
                                @endif
                                ">
                                    <span style="margin: 5px 5px 2px 5px;" class="fa fa-user fa-lg" ></span>
                                </a>
                            </span>
                            <!-- Navbar Toggler -->&nbsp;
                            <span class="classy-navbar-toggler" style="float: left; margin-left:10px;">
                                <span class="navbarToggler"><span></span><span></span><span></span></span>
                            </span>
                        </div>

                        <!-- Menu -->
                        <div class="classy-menu">

                            <!-- close btn -->
                            <div class="classycloseIcon">
                                <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                            </div>

                            <!-- Nav Start -->
                            <div class="classynav">
                                <ul>
                                    <li class=""><a href="/">Home</a></li>
                                    <li><a href="#">Quick Start</a>
                                        <ul class="dropdown w-170 py-3 px-3" style="width: 300px;">
                                            <li><b>Hello.</b> Get Started with Gls.</li>
                                            <hr>
                                            <li class="py-2"><a href="#">Track</a>
                                                <form action="{{route('main_get_track_info')}}" method="POST">
						                        	@csrf
                		                        <div class="input-group">
                		                            <input type="text" name="track" value="{{old('track')}}" class="form-control border-light" style="padding: ;" placeholder="Tracking Number">
                		                            <div class="input-group-append">
                		                                <button class="btn btn-primary">></button>
                		                            </div>
                		                        </div>
                		                        </form>
                                            </li>
                                            <li class="py-2"><a href="{{route('main_show_add_package')}}">Ship</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="{{route('main_show_track_index')}}">Tracking</a></li>
                                    <li><a href="#">Shipping</a>
                                        <ul class="dropdown w-170 py-3 px-3" style="width: 300px;">
                                            <li class="py-2"><a href="{{route('main_show_add_package')}}">Create a Shipment: (Package)</a></li>
                                            <hr>
                                             @if (Session()->has('customer'))
                                                <li class="py-2"><a href="{{route('main_show_packages')}}">View Current Shipments</a></li>
                                            @endif
                                            
                                        </ul>
                                    </li>
                                    @if(session()->has('customer'))
                                    <?php $customer = session('customer') ?>
                            	    <li>
                                        <a href="#">
                                            {{ $customer->name }}
                            	        </a>
                            	        <ul class="dropdown" style="width: 250px;">
                                            <li>
                                                <a  href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                                 document.getElementById('logout-form').submit();">
                                                    {{ __('Logout') }}
                                                </a>
                                            </li>
                            	            <form id="logout-form" action="{{ route('main_customer_logout') }}" method="POST" class="d-none">
                            	                @csrf
                            	            </form>
                            	        </ul>
                            	    </li>
                                    @else
                                    <li><a href="{{route('main_signin')}}">Login</a></li>
                                    <li><a href="{{route('main_signup')}}">Register</a></li>
                                    @endif
                                    <li><a href="{{route('admin_home')}}">Admin</a></li>
                                </ul>

                                <!-- Newsletter Form -->
                                <div class="search-btn">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </div>

                            </div>
                            <!-- Nav End -->
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ##### Header Area End ##### -->


   @yield("content")


    <!-- ##### Footer Area Start ##### -->
    <footer class="footer-area">
        <div class="container h-100">
            <div class="row h-100">
                <div class="col-12 h-100 d-flex flex-wrap align-items-center justify-content-between">
                    <!-- Footer Social Info -->
                    <div class="footer-social-info text-right">
                        <a href="#"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
                        <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                        <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                        <a href="#"><i class="fa fa-dribbble" aria-hidden="true"></i></a>
                        <a href="#"><i class="fa fa-behance" aria-hidden="true"></i></a>
                        <a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                    </div>
                    <!-- Footer Logo -->
                    <div class="footer-logo">
                    <a href="{{url('/')}}">
				        <span>GLS</span>
				        {{-- <img src="{{asset('img/core-img/logo.png')}}" alt=""> --}}
			        </a>
                    </div>
                    <!-- Copywrite -->
                    <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
		        Copyright &copy; <script>document.write(new Date().getFullYear());</script> <a href="https://glscam.com">Glscam.com</a>.
		        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                </div>
            </div>
        </div>
    </footer>
    <!-- ##### Footer Area Start ##### -->
    
    <!-- ##### All Javascript Files ##### -->
    <!-- jQuery-2.2.4 js -->
    <script src="{{asset('main2/js/jquery/jquery-2.2.4.min.js')}}"></script>
    <!-- Popper js -->
    <script src="{{asset('main2/js/bootstrap/popper.min.js')}}"></script>
    <!-- Bootstrap js -->
    <script src="{{asset('main2/js/bootstrap/bootstrap.min.js')}}"></script>
    <!-- All Plugins js -->
    <script src="{{asset('main2/js/plugins/plugins.js')}}"></script>
    <!-- Active js -->
    <script src="{{asset('main2/js/active.js')}}"></script>
    @yield('script')
    
</body>

</html>
