@extends('layouts.Admin.index')


@section('content')
            <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Package</h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <div class="d-md-flex">
                            <ol class="breadcrumb ms-auto">
                                <li><a href="#" class="fw-normal">Package</a></li>
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
						    <h3 class="box-title">Package Info</h3>
					    </div>
					    <div class="col-md-6">
						@if ($package->status == 0)    
						<form action="{{route('admin_activate_package', ['id' => $package->id])}}" method="POST">
						@csrf
						<button type="submit" class="btn btn-primary" style="float: right">Activate Package</button>
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
                            <div class="card-body">
                                {{-- <form class="form-horizontal form-material"> --}}
					<h4 class="box-title"><b>Contact info</b></h4>
					<br>
				   <div class="row">
					    <div class="col-sm-6">
						<div class="form-group mb-4">
                                    		    <label for="example-email" class="col-md-12 p-0">Customer Email: (Creator)</label>
                                    		    <div class="col-md-12 border-bottom p-0">
                                    		       <span class="form-control px-5 border-0"> {{$package->customer->email}}</span>
                                    		    </div>
                                    		</div>
					    </div>
				    </div>
				    <div class="row">
					    <div class="col-sm-5">
						<div class="form-group mb-4">
                                    		    <label for="example-email" class="col-md-12 p-0">Contact Phone: </label>
                                    		    <div class="col-md-12 border-bottom p-0">
                                    		       <span class="form-control px-5 border-0"> {{$package->phone}}</span>
                                    		    </div>
                                    		</div>
					    </div>
					    <div class="col-sm-5">
						<div class="form-group mb-4">
                                    		    <label for="example-email" class="col-md-12 p-0">Contact Email: </label>
                                    		    <div class="col-md-12 border-bottom p-0">
                                    		        <span class="form-control px-5 border-0"> {{$package->email}}</span>
                                    		    </div>
                                    		</div>
					    </div>
					    <div class="col-sm-2">
						<img style="width: 130px; height:130px; float:right;" src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                                            		->errorCorrection('H')
                                            		->size(200)
                                            		->generate($package->tracking_id)) !!}" />
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
                                    		       <span class="form-control px-5 border-0">  {{$package->from}} </span>
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
						   [ Type: {{$item->name}}, Weight: {{$item->weight}} ], 
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
						    {{-- <a href="" class="btn btn-primary" style="float: right" onclick="$('#update').css('display', 'block');">Update Tracking location</a> --}}
						    {{$package->trackings[count($package->trackings)-1]->current_location}}
						</span>
                                        </div>
                                    </div>
				    @endif
				    @if ($package->status == 2)
					<div class="form-group mb-4">
                                        <label for="example-email" class="col-md-12 p-0">Package Delivered To:</label>
                                        <div class="col-md-12 border-bottom px-4">
						<span class="form-control px-5 border-0">
						    {{-- <a href="" class="btn btn-primary" style="float: right" onclick="$('#update').css('display', 'block');">Update Tracking location</a> --}}
						    {{$package->trackings[count($package->trackings)-1]->current_location}}
						</span>
                                        </div>
                                    </div>
				    @endif

				    <hr>
				    <h4 class="box-title"><b>Proof of Delivery</b></h4>
				    <br>
				    @if ($package->status == 2)
				    <?php 
				    	$imgs = explode(', ', $package->delivery_image);
				    ?>
				    <div class="row">
					    <?php $k = 1; ?>
					@foreach ($imgs as $i)
                                    	<div class="col-md-3 border-bottom px-4">
					    	<div class="card border">
                        			    <div class="card-body">
                        			        <img src="{{$i}}" alt="Delivery_confirmation_image" style="height: 200px; width:100%; position: relative;"><br>
							<br>
							<a class="btn btn-primary w-100" onclick="$('#m_data{{$k}}').css('display', 'block');">Open</a>
                        			    </div>
                        			</div>
				    	{{-- <img src="{{$package->delivery_image}}" style="width: " alt="Deliveri_confirmation_img"> --}}
                                    	</div>
					<div class="row" style="z-index:999999; position:fixed; top:10px; width:100%; display:none;" id="m_data{{$k}}">
						<div class="col-md-12 col-sm-12" style="text-align: center;">
						<div class="modal" style="display:block; background:rgba(47,50,62,0.7);" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
						  <div class="modal-dialog modal-xl">
						    <div class="modal-content w-100">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" onclick="$('#m_data{{$k}}').css('display', 'none');" id="m_close" aria-label="Close">
						          <span aria-hidden="true">&times;</span>
						        </button>
						      </div>
						      <div class="modal-body"  style="height: 600px">     
							<img src="{{$i}}" alt="Delivery_confirmation_image" style="height: 100%; width:100%; position: relative;"><br>
						      </div>
						    </div>
						  </div>
						</div>
						</div>
					</div>
					<?php $k++; ?>
					@endforeach
                                    </div>
				    @endif
				    
                                {{-- </form> --}}
                            </div>
                        </div>
                    </div>
                    <!-- Column -->

                </div>
            </div>
@endsection
