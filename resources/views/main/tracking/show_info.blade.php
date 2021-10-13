@extends('layouts.main.index')

@section('content')
    <!-- Header Start -->
    <div class="jumbotron jumbotron-fluid mb-2">
        <div class="container text-center py-1">
            <h1 class="text-white display-3">Track</h1>
            <div class="d-inline-flex align-items-center text-white">
                <p class="m-0"><a class="text-white" href="">Home</a></p>
                <i class="fa fa-circle px-3"></i>
                <p class="m-0">Tracking</p>
		<i class="fa fa-circle px-3"></i>
                <p class="m-0">info</p>
            </div>
        </div>
    </div>
    <!-- Header End -->


     <!-- Contact Start -->
    <div class="container-fluid py-5">
        <div class="container">
		<h4 class="text-primary text-uppercase font-weight-bold">Package Info</h4> 
            <div class="row">
                <div class="col-lg-5 pb-4 pb-lg-0">
                    <div class="bg-primary text-dark text-center p-4">
                        <h4 class="m-0"><i class="fa fa-map-marker-alt text-white mr-2"></i>{{$tracking[0]->current_location}}</h4>
                    </div>
                    <iframe style="width: 100%; height: 470px;"
		    	src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCpSikij-kMiF0hTxXQn9Vu4B2v2WxkR_E&q={{$tracking[0]->current_location}},+cameroon"
                        {{-- src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3001156.4288297426!2d-78.01371936852176!3d42.72876761954724!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4ccc4bf0f123a5a9%3A0xddcfc6c1de189567!2sNew%20York%2C%20USA!5e0!3m2!1sen!2sbd!4v1603794290143!5m2!1sen!2sbd" --}}
                        frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                </div>
                <div class="col-lg-7">
                    <h6 class="text-primary text-uppercase font-weight-bold">Package Tracking Infomation</h6>
                    {{-- <h1 class="mb-4">Contact For Any Queries</h1> --}}
                    <div class="contact-form bg-secondary" style="padding: 30px;">
                        <div id="success"></div>
                        <h5>Package with tracking number : {{$package[0]->tracking_id}}</h5>
			<br>
			<p>Package Details: </p>
			<div class="row">
				<div class="col-md-10">
					<div class="row">
						<div class="col-sm-4" style="border-right: 1px black solid;">
                            <span style="float: right;"><b>Customer</b></span><br>
							<span style="float: right;"><b>Items</b></span><br>
							<span style="float: right;"><b>From</b></span><br>
							<span style="float: right;"><b>To</b></span><br>
						</div>
						<div class="col-sm-8">
                            <span style="float: left;">{{$package[0]->customer->name}}</span><br>
							<span style="float: left;">
								@if (count($package[0]->items) > 2)
								    {{$package[0]->items[0]->name}}, {{$package[0]->items[1]->name}}, And Others
								@else
								@foreach ($package[0]->items as $item)
								{{$item->name}}, 
								@endforeach
								@endif
							</span><br>
							<span style="float: left;">{{$package[0]->address_from}}</span><br>
							<span style="float: left;">{{$package[0]->address_to}}</span><br>
						</div>
					</div>
				</div>
			</div><br>
			<p>Package Current location: {{$tracking[0]->current_location}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->
@endsection

@section('script')
<script>
   $(document).ready(function() {

   })
</script>
@endsection
