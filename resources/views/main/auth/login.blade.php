@extends('layouts.main.index')


@section('content')
    <!-- Header Start -->
    <div class="jumbotron jumbotron-fluid mb-5">
        <div class="container text-center py-5">
            <h1 class="text-white display-3">LogIn</h1>
            <div class="d-inline-flex align-items-center text-white">
                <p class="m-0"><a class="text-white" href="">Home</a></p>
                <i class="fa fa-circle px-3"></i>
                <p class="m-0">Login</p>
            </div>
        </div>
    </div>
    <!-- Header End -->


    <!-- Login Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row">
		<div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <h4 class="text-primary text-uppercase font-weight-bold">LogIn</h4>
                    {{-- <h1 class="mb-4">Contact For Any Queries</h1> --}}
                    <div class="contact-form bg-secondary" style="padding: 30px;">
                        <div id="success"></div>
                         @include('layouts.flash')
                        <form name="login" id="login" method="POST" action="{{route('main_signin_customer')}}" novalidate="novalidate">
                            @csrf
                            <div class="control-group">
                                <input type="text" class="form-control border-0 p-4" value="{{old('username')}}" name="username" id="name" placeholder="User Name"
                                    required="required" data-validation-required-message="Please enter Username" />
                                @error('username')
					                    <p class="help-block text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="control-group">
                                <input type="password" class="form-control border-0 p-4" name="password" id="pass" placeholder="Password"
                                    required="required" data-validation-required-message="Please enter your Password" />
                                @error('password')
					                    <p class="help-block text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div>
                                <button class="btn btn-primary py-3 px-4" type="submit" id="login">LogIn</button>
                            </div>
                        </form>
                        <br>
                        <a href="{{route('main_signup')}}">Create Account</a>
                    </div>
                </div>
		<div class="col-lg-2"></div>
            </div>
        </div>
    </div>
    <!-- Login End -->
@endsection
