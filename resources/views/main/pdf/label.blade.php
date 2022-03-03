
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Shipment({{$package->tracking_id}})-{{time()}}-({{$package->customer->name}})</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
	{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> --}}
	<style>
	</style>
</head>
<body>
	<div class="container-fluid">
	<div class="row">
		<div class="" style="border: black 1px solid; padding:20px;">
			<img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('main2/img/core-img/logo.png'))) }}" alt="" style="height: 120px; width: 120px; float: left;">
			{{-- {{ public_path("main2/img/logo.svg") }} --}}
			<img alt="" style="width: 120px; height: 120px; float:right; margin:0;" src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                        ->errorCorrection('H')
                        ->size(200)
                        ->generate($package->tracking_id)) !!}">
		<br><br><br><br><br><br>
		</div>
		<div class="row">
			<div class="col-12" style="border: black 1px solid;  font-size: 20px; justify-content: center;">
				<div align="center">
					<p><b>GLOBE LINE SERVICES SARL</b></p>
				</div>
			</div>
		</div>
		<div class="col-12" style="border: black 1px solid;display: flex;  font-size: 15px;">
			<div style="padding-left:10px;"> <p><b>Reciever(Name / Phone):</b> </p> </div>
			<div style="padding-left:20px;"> <p> {{$package->name}} / {{$package->phone}}</p> </div>
		</div>
		<div class="col-12" style="border: black 1px solid;display: flex;  font-size: 15px;">
			<div style="padding-left:10px;"> <p><b>FROM:</b> </p> </div>
			<div style="padding-left:20px;"> <p> {{$package->from}}</p> </div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-9 col-md-3" style="border: black 1px solid; display: flex; font-size: 15px;" >
			<div style="padding-left:10px;"> <p><b>TO:</b> </p> </div>
			<div style="padding-left:20px;"> <p>{{$package->address_to}} {{$package->to}}, {{$package->to_location->city}} {{$package->to_location->region}}</p> </div>
		</div>
	</div>
	<div class="row">
		<div class="col-6" style="border: black 1px solid; height:; float:left; width: 50%;">
			<div style="margin-left: 20px; padding-left: 10px; font-size: 15px;"> <p><b>TRACKING NO:</b> </p> <p><b>SHIP DATE:</b> </p> </div>
		</div>
		<div class="col-6" style="border: black 1px solid; height:; float:left; width: 50%;">
			<div style="padding-left:20px; font-size: 15px;"> <p> {{$package->tracking_id}}</p>  <p> {{$package->created_at}}</p> </div>
		</div>
	</div>
	<div class="row" style="position:absolute;">
		<div class="col-6" style="border: black 1px solid; height: 50px; float:left; width: 50%; position: relative;">
			<div style="margin-left: 20px; padding-left: 10px; font-size: 15px;"> <p><b>SHIP DATE:</b> </p> </div>
		</div>
		<div class="col-6" style="border: black 1px solid; height: 50px; float:right; width: 50%;">
			<div style="padding-left:20px;"> <p> {{$package->created_at}}</p> </div>
		</div>
	</div>
	<div class="row" style="display: flex">
		{{-- <div class="col-6" style="border: black 1px solid; width: 50%;"> --}}
			<div class="row" style="border: black 1px solid; width: 100%;">
				@foreach ($package->items as $item)
				    {{-- <p style="margin-left: 5px;">{{$item->name}}</p> --}}
				    <div style="display:flex; margin-left:10px;"> 
					<div><p><b>Item Name: </b>{{$item->name}}</p> <p><b>Weight: </b>{{$item->weight}}</p><p><b>Description: </b>{{$item->description}}</p></div>
				    </div>
				@endforeach	
			</div>
		{{-- </div> --}}
		<div class="col-6" style="border: ; width: 100%;">
			<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<img alt="" style="width: 200px; height: 200px; float:left;" src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                        ->errorCorrection('H')
                        ->size(200)
                        ->generate($package->tracking_id)) !!}">
			
			<span style="float: right;">{!! DNS1D::getBarcodeHTML($package->tracking_id, 'PHARMA') !!}</span>
			
		</div>
	</div>
	</div>
</body>
</html>
