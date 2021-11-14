<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <meta name="keywords" content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Ample lite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Ample admin lite dashboard bootstrap 5 dashboard template"> --}}
    {{-- <meta name="description" content="Ample Admin Lite is powerful and clean admin dashboard template, inpired from Bootstrap Framework"> --}}
    <meta name="robots" content="noindex,nofollow">
    <title>{{ config('app.name', 'GLS') }} - Admin</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('main2/img/core-img/logo.png')}}">
    <!-- Custom CSS -->
    <link href="{{asset('admin_asset/plugins/bower_components/chartist/dist/chartist.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('admin_asset/plugins/bower_components/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.css')}}">
    <link href="{{asset('admin_asset/css/style.min.css')}}" rel="stylesheet">
   {{-- <link href="{{asset('admin_asset/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet"> --}}
   <!-- JavaScript Bundle with Popper -->
   
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> --}}
</head>

<body>
    <!-- Preloader - style you can find in spinners.css -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- Main wrapper - style you can find in pages.scss -->
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
        data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin6">
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <a class="navbar-brand" href="{{url('/admin')}}">
                        <!-- Logo icon -->
                        <b class="logo-icon">
                            <!-- Dark Logo icon -->
		                    <img src="{{asset('main2/img/core-img/logo.png')}}" style="width: 50px; height:50px;" alt="homepage" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span class="logo-text">
                            <!-- dark Logo text -->
                            <span style="color:black">{{ config('app.name', 'GLS') }}</span>
                            {{-- <img src="{{asset('admin_asset/plugins/images/logo-text.png')}}" alt="homepage" /> --}}
                        </span>
                    </a>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <a class="nav-toggler waves-effect waves-light text-dark d-block d-md-none"
                        href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                    <ul class="navbar-nav d-none d-md-block d-lg-none">
                        <li class="nav-item">
                            <a class="nav-toggler nav-link waves-effect waves-light text-white"
                                href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                        </li>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav ms-auto d-flex align-items-center">

                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <li class=" in">
                            <form role="search" class="app-search d-none d-md-block me-3">
                                <input type="text" placeholder="Search..." class="form-control mt-0">
                                <a href="" class="active">
                                    <i class="fa fa-search"></i>
                                </a>
                            </form>
                        </li>

                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                        <li>
                            <div class="dropdown">
                              <a class="profile-pic dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                {{-- <img src="plugins/images/users/varun.jpg" alt="user-img" width="36" class="img-circle"> --}}
                                <i class="fa fa-user" aria-hidden="true"></i>
                                <span class="text-white font-medium">{{Auth::user()->name}}</span>
                            </a>
                              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li>
                                    {{-- <a class="dropdown-item" href="#">Action</a> --}}
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                              </ul>
                            </div>
                            
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->

                    </ul>
                </div>
            </nav>
        </header>



        <aside class="left-sidebar" data-sidebarbg="skin6">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <!-- User Profile-->
                        <li class="sidebar-item pt-2">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin_home')}}"
                                aria-expanded="false">
                                <i class="far fa-clock" aria-hidden="true"></i>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        {{-- <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="#"
                                aria-expanded="false">
                                <i class="fa fa-user" aria-hidden="true"></i>
                                <span class="hide-menu">Profile</span>
                            </a>
                        </li> --}}
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin_packages')}}"
                                aria-expanded="false">
                                <i class="fa fa-table" aria-hidden="true"></i>
                                <span class="hide-menu">Shipments</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin_customers')}}"
                                aria-expanded="false">
                                <i class="fa fa-users" aria-hidden="true"></i>
                                <span class="hide-menu">Customers</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin_trackings')}}"
                                aria-expanded="false">
                                <i class="fa fa-globe" aria-hidden="true"></i>
                                <span class="hide-menu">Trackings</span>
                            </a>
                        </li>
                        @if (Auth::user() && Auth::user()->p==1)   
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('register') }}"
                                aria-expanded="false">
                                <i class="fa fa-users" aria-hidden="true"></i>
                                <span class="hide-menu">Register User</span>
                            </a>
                        </li>
                        @endif
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin_locations')}}"
                                aria-expanded="false">
                                <i class="fa fa-globe" aria-hidden="true"></i>
                                <span class="hide-menu">Locations</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin_merchandises')}}"
                                aria-expanded="false">
                                <i class="fa fa-globe" aria-hidden="true"></i>
                                <span class="hide-menu">Merchandises</span>
                            </a>
                        </li>

                        <?php 
                        $sms_num = '';
                        try {
                             $balance = Http::get("https://auth.sms.to/api/balance?api_key=gHdD8WP3soGaTjDsWTIp9yjgP1egtzIa");
                             $estimate = Http::get("https://api.sms.to/sms/estimate?api_key=gHdD8WP3soGaTjDsWTIp9yjgP1egtzIa&to=+2348167236629&message=test&sender_id=Gls");
                             $sms_num = $balance['balance'] / $estimate['estimated_cost'];
                             //  $b = $balance['balance'] ? $balance['balance'] : '';
                            //  $e = $estimate['estimated_cost'] ? $estimate['estimated_cost'] : '';
                            //  $sms_num = ($b ? $b:0) / ($e?$e:1);
                        } catch (\Throwable $th) {
                            
                        }

                        ?>
                        <li class="sidebar-item" style="background: rgb(78,105,158)">
                            <p class="sidebar-link waves-effect waves-dark sidebar-link"
                                aria-expanded="false">
                                <span style="color: aliceblue; margin-left:3px;">{{(int)$sms_num ?? ''}}</span>&nbsp;
                                <span style="color: aliceblue;"> sms remain</span>&nbsp; 
                        </li>
                    </ul>

                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->


        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper" style="min-height: 250px;">
            
            @yield('content')
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer text-center"> 2021 © <a
                    href="https://glscam.com/">Glscam.com</a>
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{asset('admin_asset/plugins/bower_components/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{asset('admin_asset/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('admin_asset/js/app-style-switcher.js')}}"></script>
    <script src="{{asset('admin_asset/plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js')}}"></script>
    <!--Wave Effects -->
    <script src="{{asset('admin_asset/js/waves.js')}}"></script>
    <!--Menu sidebar -->
    <script src="{{asset('admin_asset/js/sidebarmenu.js')}}"></script>
    <!--Custom JavaScript -->
    <script src="{{asset('admin_asset/js/custom.js')}}"></script>
    <script src="{{asset('admin_asset/plugins/bower_components/chartist/dist/chartist.min.js')}}"></script>
    <script src="{{asset('admin_asset/plugins/bower_components/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js')}}"></script>
    <script src="{{asset('admin_asset/js/pages/dashboards/dashboard1.js')}}"></script>
    @yield('script')
</body>

</html>
