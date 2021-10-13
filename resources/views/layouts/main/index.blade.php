<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>GLS - Logistics Company</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free logistics services" name="keywords">
    <meta content="Free transportation" name="description">

    <!-- Favicon -->
    <link href="{{Asset('main/img/favicon.ico')}}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{asset('main/lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{asset('main/css/style.css')}}" rel="stylesheet">
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid bg-dark">
        <div class="row py-2 px-lg-5">
            <div class="col-lg-6 text-center text-lg-left mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center text-white">
                    <small><i class="fa fa-phone-alt mr-2"></i>+012 345 6789</small>
                    <small class="px-3">|</small>
                    <small><i class="fa fa-envelope mr-2"></i>info@gls.com</small>
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                    <a class="text-white px-2" href="">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a class="text-white px-2" href="">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a class="text-white px-2" href="">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a class="text-white px-2" href="">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a class="text-white pl-2" href="">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-lg-5">
            <a href="index.html" class="navbar-brand ml-lg-3">
                <h1 class="m-0 display-5 text-uppercase text-primary"><i class="fa fa-truck mr-2"></i>GLS</h1>
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between px-lg-3" id="navbarCollapse">
                <div class="navbar-nav m-auto py-0">
                    <a href="{{route('main_home')}}" class="nav-item nav-link">Home</a>
                    <a href="{{route('main_home')}}#about" class="nav-item nav-link">About</a>
                    <a href="{{route('main_home')}}#services" class="nav-item nav-link">Service</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">package</a>
                        <div class="dropdown-menu rounded-0 m-0">
                            @if (Session()->has('customer'))
                                <a href="{{route('main_show_packages')}}" class="dropdown-item">View Packages</a>
                            @endif
                            <a href="{{route('main_show_add_package')}}" class="dropdown-item">Add Package</a>
                            {{-- <a href="#" class="dropdown-item">Blog Detail</a> --}}
                        </div>
                    </div>
                    <a href="{{route('main_show_track_index')}}" class="nav-item nav-link">Track</a>
                    @if (session()->has('customer'))
                    <?php $customer = Session('customer'); ?>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">{{$customer->name}}</a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <form action="{{route('main_customer_logout')}}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </div>
                    </div>
                    @else
                    <a href="{{route('main_signin')}}" class="nav-item nav-link">LogIn</a>
                    <a href="{{route('main_signup')}}" class="nav-item nav-link">SignUp</a>
                    @endif
                    
                    {{-- <a href="contact.html" class="nav-item nav-link active">Contact</a> --}}
                </div>
                {{-- <a href="" class="btn btn-primary py-2 px-4 d-none d-lg-block">Get A Quote</a> --}}
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    	@yield('content')

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white mt-5 py-5 px-sm-3 px-md-5">
        <div class="row pt-5">
	    <div class="col-md-3 mb-2"></div>
            <div class="col-lg-7 col-md-6">
                <div class="row">
                    <div class="col-md-6 mb-5">
                        <h3 class="text-primary mb-4">Get In Touch</h3>
                        {{-- <p><i class="fa fa-map-marker-alt mr-2"></i>123 Street, New York, USA</p> --}}
                        <p><i class="fa fa-phone-alt mr-2"></i>+012 345 67890</p>
                        <p><i class="fa fa-envelope mr-2"></i>info@example.com</p>
                        <div class="d-flex justify-content-start mt-4">
                            <a class="btn btn-outline-light btn-social mr-2" href="#"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-social mr-2" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social mr-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a class="btn btn-outline-light btn-social" href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-5">
                        <h3 class="text-primary mb-4">Quick Links</h3>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-white mb-2" href="{{route('main_home')}}"><i class="fa fa-angle-right mr-2"></i>Home</a>
                            <a class="text-white mb-2" href="{{route('main_home')}}#about"><i class="fa fa-angle-right mr-2"></i>About Us</a>
                            <a class="text-white mb-2" href="{{route('main_home')}}#services"><i class="fa fa-angle-right mr-2"></i>Our Services</a>
                            <a class="text-white mb-2" href="{{route('main_signin')}}"><i class="fa fa-angle-right mr-2"></i>LogIn</a>
                            <a class="text-white" href="{{route('main_signup')}}"><i class="fa fa-angle-right mr-2"></i>SignUp</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-2"></div>
        </div>
    </div>
    <div class="container-fluid bg-dark text-white border-top py-4 px-sm-3 px-md-5" style="border-color: #3E3E4E !important;">
        <div class="row">
            <div class="col-lg-6 text-center text-md-left mb-3 mb-md-0">
                <p class="m-0 text-white">&copy; <a href="{{route('main_home')}}">Ajisaq.com</a>. All Rights Reserved. Developed by <a href="https://ajisaq.com/">AJISAQ</a>
                </p>
            </div>
            <div class="col-lg-6 text-center text-md-right">
                <ul class="nav d-inline-flex">
                    <li class="nav-item">
                        <a class="nav-link text-white py-0" href="#">FAQs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white py-0" href="#">Help</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('main/lib/easing/easing.min.js')}}"></script>
    <script src="{{asset('main/lib/waypoints/waypoints.min.js')}}"></script>
    <script src="{{asset('main/lib/counterup/counterup.min.js')}}"></script>
    <script src="{{asset('main/lib/owlcarousel/owl.carousel.min.js')}}"></script>

    <!-- Contact Javascript File -->
    <script src="{{asset('main/mail/jqBootstrapValidation.min.js')}}"></script>
    <script src="{{asset('main/mail/contact.js')}}"></script>

    <!-- Template Javascript -->
    <script src="{{asset('main/js/main.js')}}"></script>
    @yield('script')
</body>

</html>
