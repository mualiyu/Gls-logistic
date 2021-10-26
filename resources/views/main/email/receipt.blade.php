<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
	<title>Gls</title>
</head>
<body>
	<div class="container-fluid px-3 py-3" style="text-align: center;">
	<div class="row justify-content-center">
		<div class="col-md-2 col-sm-2">
				<img style="width: 50px; height:50px;" src="http://18.218.78.236/main2/img/core-img/logo.png" alt="">
			
		</div>
		<div class="col-md-9 col-sm-9" style="font-size: 13">
				<h4 style="text-decoration: underline;">GLOBE LINE SERVICES SARL</h4>
				{{-- <span style="font-size: 12">COMEMERCE GENERAL - NEGOCE - TRANSPORT - SERVICES</span> --}}
			
		</div>
	</div>

	<div class="row py-3 px4">
        <div class="col">
             <div class="card" style="font-size: 12px; text-align: left;">
                 <div class="card-header">Welcome!</div>
                   <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh mail has been sent to your email address.') }}
                        </div>
                    @endif
                    <p>Dear {!! $email !!}, </p>
                    <p>{!! $content !!}</p>
                    <br>
                   <p>To track your shipment flow this link! {{!! url('/track') !!}}</p>
                </div>
            </div>
        </div>
	</div>
    <br><br>

	<div class="row" style="bottom: 0;">
		<div class="col justify-content-center" style="background-color: aqua;">
			<div>
				<!-- <h2 style="text-decoration: underline;">GLOBE LINE SERVICES SARL</h2> -->
				<P style="margin:0;">Akwa, Rue Jamot-Doula BP:2409 Doula - Cameroun Tel:(237) 696987131 - 677822819 - 699505956</P>
				<p style="margin:0;">Email: glscameroun@yahoo.fr - RC No B/361 - NIU : M030600020460-E - No EMP : 351-0106190-W</p>
			</div>
		</div>
	</div>
</div>
</body>
</html>
