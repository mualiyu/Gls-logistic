@extends('layouts.main.index')

@section('content')

<!-- Package Start -->
<div class="container y py-2">
	<div class="container">
		<a onclick="window.history.back()" id="back" class="pre">&laquo; Previous</a><br><br>
		@include('layouts.flash')
		<div class="card">
			<div class="card-body">
				<div class="row justify-content-center">
					<div class="col py-5 py-lg-0">
						@if ($package->status == 0)
						<form action="{{route('main_activate_package', ['id' => $package->id])}}" method="POST">
							@csrf
							<button type="submit" class="btn btn-primary" style="float: right">Activate Package</button>
						</form>
						@endif
						@if ($package->status == 1)
						<button type="submit" class="btn btn-success" style="float: right">Activated</button>
						@endif
						@if ($package->status == 2)
						<button type="submit" class="btn btn-secondary" disabled style="float: right">Activate Package</button>
						@endif
						<h6 class="text-primary text-uppercase font-weight-bold">Package</h6>
						<a href="" onclick="event.preventDefault(); document.getElementById('track-form').submit();"><h5 class="mb-4" style="color: blue;">Tracking ID: {{$package->tracking_id}}</h5></a>
						<form action="{{route('main_get_track_info')}}" id="track-form" method="POST" class="d-none">
                                		    @csrf
                                		        <input type="hidden" name="track" value="{{$package->tracking_id}}">  
                                		</form> 
						<div class="row">
							<div class="col-sm-6">
								<h5 class="text-primary mb-2" data-toggle="counter-up">1</h5>
								<h6 class="font-weight-bold mb-4">Items</h6>
							</div>
							<div class="col-sm-6">
								<h5 class="text-primary mb-2">FCFA <span data-toggle="counter-up">{{$package->to_location->charges[0]->amount/100}}</span></h5>
								<h6 class="font-weight-bold ">Amount</h6>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<label class="text-primary" for="from">From: </label>
								<span id="from">{{$package->from}}</span>
							</div>
							<div class="col-sm-6">
								<label class="text-primary" for="to">To: </label>
								<span id="to">{{$package->address_to}}, {{$package->to}}</span>
							</div>
						</div>
						<br>
						<h6 class="text-primary text-uppercase font-weight-bold">Current Items</h6>
						<div class="row justify-content-center" style="margin:0;">
							<div class="col-md-12">
								<div class="list-group" style="margin:0;">
									@if (count($package->items) > 0)
									<?php $k = count($package->items); ?>
									@foreach ($package->items as $item)
									<a class="list-group-item list-group-item-action border-dark">
										<span style="float: right">#{{$k}}</span>&nbsp;&nbsp;
										<h6>{{$item->name}}</h6>
										<p style="margin:0"><b>Description:</b> {{$item->description}}</p>
										<p style="margin:0"><b>Weight:</b> {{$item->weight ?? "No Weight Specified in "}}gram</p>
										<p style="margin:0"><b>Quantity:</b> {{$item->quantity ?? "1"}}</p>
										<hr>
									</a>
									<?php $k--; ?>
									@endforeach
									@else
									<a href="#" class="list-group-item list-group-item-action border-dark">
										No Items Found
									</a>
									@endif
								</div>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<!-- Package Start -->

@endsection

@section('script')

@endsection
