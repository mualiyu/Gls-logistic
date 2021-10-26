@extends('layouts.Admin.index')


@section('content')
            <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Track</h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <div class="d-md-flex">
                            <ol class="breadcrumb ms-auto">
                                <li><a href="#" class="fw-normal">Track</a></li>
                            </ol>
                           
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
	    @include('layouts.flash')
            <div class="container-fluid">

                <div class="row">

                    <!-- Column -->
                    <div class="col-lg-12 col-xlg-12 col-md-12">
                        <div class="card">
			    <div class="card-body">
				    <div class="row">
					    <div class="col-md-6">
						    <h3 class="box-title">Tracking ({{$package->tracking_id}})</h3>
					    </div>
					    <div class="col-md-6">
						<div class="row">
							<div class="col-sm-6">
								{{-- Show approve button (departure or arival) --}}
								@if ($package->status == 1) 
								@if (Auth::user()->unit_location)
									@if (Auth::user()->unit_location == $tracking[0]->current_location && $tracking[0]->a_d!=1)
									    <form action="{{route('admin_confirm_track_package')}}" method="POST">
									    @csrf
									    <input type="hidden" value="{{Auth::user()->unit_location}}" name="au_location">
									    <input type="hidden" value="1" name="a_d">
									    <input type="hidden" value="{{$package->id}}" name="p_id">
		
									    <button type="submit" class="btn btn-warning" style="float: right; backgroud:blue;">Confirm Departure from {{Auth::user()->unit_location}}</button>
									    </form>
									@endif
									@if(Auth::user()->unit_location != $tracking[0]->current_location && $tracking[0]->a_d==1)
									    <form action="{{route('admin_confirm_track_package')}}" method="POST">
									    @csrf
									    <input type="hidden" value="{{Auth::user()->unit_location}}" name="au_location">
									    <input type="hidden" value="2" name="a_d">
									    <input type="hidden" value="{{$package->id}}" name="p_id">
		
									    <button type="submit" class="btn btn-secondary" style="float: right; backgroud:blue;">Confirm Arrival To {{Auth::user()->unit_location}}</button>
									    </form>
									@endif
								@endif   
								{{-- <form action="{{route('admin_activate_package', ['id' => $package->id])}}" method="POST">
								@csrf
								<button type="submit" class="btn btn-primary" style="float: right">Activate Package</button>
								</form> --}}
								@endif
							</div>
							<div class="col-sm-6">
								{{-- Activation button --}}
								@if ($package->status == 0)    
								<form action="{{route('admin_activate_package', ['id' => $package->id])}}" method="POST">
								@csrf
								<button type="submit" class="btn btn-primary" style="float: right;">Activate Package</button>
								</form>
								@endif
								@if ($package->status == 1)
								    <button type="submit" class="btn btn-success" style="float: right">Activated</button>
								@endif
								@if ($package->status == 2)
								    <button type="submit" class="btn btn-secondary" disabled style="float: right">Package Delivered</button>
								@endif
							</div>
						</div>

					    </div>
				    </div>
			    </div>
                            <div class="card-body">
                                <form class="form-horizontal form-material">
					<h4 class="box-title"><b>Customer info</b></h4>
					<br>
					<div class="row">
					    <div class="col-sm-6">
						<div class="form-group mb-4">
                                    		    <label for="example-email" class="col-md-12 p-0">Customer Name: </label>
                                    		    <div class="col-md-12 border-bottom p-0">
                                    		       <span class="form-control px-5 border-0"> {{$package->customer->name}}</span>
                                    		    </div>
                                    		</div>
					    </div>
					    <div class="col-sm-6">
						<div class="form-group mb-4">
                                    		    <label for="example-email" class="col-md-12 p-0">Email: </label>
                                    		    <div class="col-md-12 border-bottom p-0">
                                    		        <span class="form-control px-5 border-0"> {{$package->customer->email}}</span>
                                    		    </div>
                                    		</div>
					    </div>
				    </div>
					<hr>
					<h4 class="box-title"><b>Package info</b></h4>
					<br>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Tracking Number: </label>
                                        <div class="col-md-12 border-bottom px-4">
                                            <span class="form-control px-5 border-0"> {{$package->tracking_id}}</span>
					</div>
                                    </div>
				    <div class="row">
					    <div class="col-sm-6">
						<div class="form-group mb-4">
                                    		    <label for="example-email" class="col-md-12 p-0">From: </label>
                                    		    <div class="col-md-12 border-bottom p-0">
                                    		       <span class="form-control px-5 border-0"> {{$package->address_from}}, {{$package->from}} </span>
                                    		    </div>
                                    		</div>
					    </div>
					    <div class="col-sm-6">
						<div class="form-group mb-4">
                                    		    <label for="example-email" class="col-md-12 p-0">To: </label>
                                    		    <div class="col-md-12 border-bottom p-0">
                                    		        <span class="form-control px-5 border-0"> {{$package->address_to}}, {{$package->to}}</span>
                                    		    </div>
                                    		</div>
					    </div>
				    </div>
                                    <div class="form-group mb-4">
                                        <label for="example-email" class="col-md-12 p-0">Items:</label>
                                        <div class="col-md-12 border-bottom px-4">
                                            <span class="form-control px-5 border-0">
						    @foreach ($package->items as $item)
						    {{$item->name}},  
						    @endforeach
						</span>
                                        </div>
                                    </div>
				    <hr>
				    <h4 class="box-title"><b>Tracking info</b></h4>
				    <br>
				    @if ($package->status == 1)
					<div class="form-group mb-4">
                                        <label for="example-email" class="col-md-12 p-0">Package Current Location is:</label>
                                        <div class="col-md-12 border-bottom px-4">
						<span class="form-control px-5 border-0">
						    <a href="" class="btn btn-primary" style="float: right" onclick="$('#update').css('display', 'block');">Update Tracking location</a>
						    {{$package->trackings[count($package->trackings)-1]->current_location}}
						</span>
                                        </div>
                                    </div>
				    @endif
                                    
                                </form>
                            </div>
                        </div>

			{{-- <div class="card">
				<div class="card-body">
					<h3 class="box-title">Update Tracking Location</h3>
				</div>
                            <div class="card-body">
                                <form class="form-horizontal form-material" method="POST" action="{{route('admin_track_package')}}">
					@csrf
                                    <div class="form-group mb-4">
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="tracking_location" placeholder="Tracking location" class="form-control p-0 border-0">
					</div>
					@error('tracking_number')
					    <span style="color: red;">{{$message}}</span>
					@enderror
					
                                    </div>

				    <div class="form-group mb-4">
                                        <div class="col-md-12">
                                            <input type="submit" id="track" value="Track" class="btn btn-primary">
					</div>
                                    </div>

				</form>
			    </div>
			</div> --}}
                    </div>
                    <!-- Column -->

                </div>
            </div>
@endsection
