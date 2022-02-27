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
							<div class="col-sm-4">
								{{-- Show approve button (departure or arival) --}}
								{{-- @if ($package->status == 1) 
								 @if (Auth::user()->unit_location)
									@if (Auth::user()->unit_location == $tracking[0]->current_location && $tracking[0]->a_d==1)
									    <form action="{{route('admin_confirm_track_package')}}" method="POST">
									    @csrf
									    <input type="hidden" value="{{Auth::user()->unit_location}}" name="au_location">
									    <input type="hidden" value="{{$package->id}}" name="p_id">
									    <input type="hidden" value="2" name="a_d">
		
									    <button type="submit" class="btn btn-warning" style="float: right; backgroud:blue;">Confirm Departure from {{Auth::user()->unit_location}}</button>
									    </form>
									@endif
									@if(Auth::user()->unit_location !== $tracking[0]->current_location)
									    <form action="{{route('admin_confirm_track_package')}}" method="POST">
									    @csrf
									    <input type="hidden" value="{{Auth::user()->unit_location}}" name="au_location">
									    <input type="hidden" value="1" name="a_d">
									    <input type="hidden" value="{{$package->id}}" name="p_id">
		
									    <button type="submit" class="btn btn-secondary" style="float: right; backgroud:blue;">Confirm Arrival To {{Auth::user()->unit_location}}</button>
									    </form>
									@endif
								@endif  

								@endif --}}
								<button type="button" class="btn btn-primary"  id="m_confirm" style="float: right">Confirm package location</button>
							</div>
							<div class="col-sm-4">
								@if ($package->status == 2)
								@if (!$package->c_d == null)
								    <button type="submit" class="btn btn-secondary" style="float: right" disabled>Confirmed By Client</button>
								@else
								    <button type="submit" class="btn btn-warning" disabled style="float: right">Client Not Confirmed</button>
								@endif
								@endif
							</div>
							<div class="col-sm-4">
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
                                {{-- <form class="form-horizontal form-material"> --}}
					<h4 class="box-title"><b>Contact info</b></h4>
					<br>
					<div class="row">
					    <div class="col-sm-6">
						<div class="form-group mb-4">
                                    		    <label for="example-email" class="col-md-12 p-0">Contact name: </label>
                                    		    <div class="col-md-12 border-bottom p-0">
                                    		       <span class="form-control px-5 border-0"> {{$package->name}}</span>
                                    		    </div>
                                    		</div>
					    </div>
					    <div class="col-sm-4">
						<div class="form-group mb-4">
                                    		    <a href="{{route('ship_label', ['t_id'=>$package->tracking_id])}}" class="btn btn-success" style="float: right">Print Shipment Label</a>
                                    		</div>
					    </div>
					    <div class="col-sm-2">
						<img style="width: 130px; height:130px; float:right;" src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                                            		->errorCorrection('H')
                                            		->size(200)
                                            		->generate($package->tracking_id)) !!}" />
					    </div>
				    </div>
					<div class="row">
					    <div class="col-sm-6">
						<div class="form-group mb-4">
                                    		    <label for="example-email" class="col-md-12 p-0">Contact Phone: </label>
                                    		    <div class="col-md-12 border-bottom p-0">
                                    		       <span class="form-control px-5 border-0"> {{$package->phone}}</span>
                                    		    </div>
                                    		</div>
					    </div>
					    <div class="col-sm-6">
						<div class="form-group mb-4">
                                    		    <label for="example-email" class="col-md-12 p-0">Contact Email: </label>
                                    		    <div class="col-md-12 border-bottom p-0">
                                    		        <span class="form-control px-5 border-0"> {{$package->email}}</span>
                                    		    </div>
                                    		</div>
					    </div>
					    {{-- <div class="col-sm-2">
						<img style="width: 130px; height:130px; float:right;" src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                                            		->errorCorrection('H')
                                            		->size(200)
                                            		->generate($package->tracking_id)) !!}" />
					    </div> --}}
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
                                    		       <span class="form-control px-5 border-0"> {{$package->from}} </span>
                                    		    </div>
                                    		</div>
					    </div>
					    <div class="col-sm-6">
						<div class="form-group mb-4">
                                    		    <label for="example-email" class="col-md-12 p-0">To: </label>
                                    		    <div class="col-md-12 border-bottom p-0">
                                    		        <span class="form-control px-5 border-0"> {{$package->address_to}} {{$package->to}}, {{$package->to_location->city}} {{$package->to_location->region}}</span>
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
						    <a href="" class="btn btn-primary" style="float: right" onclick="$('#update').css('display', 'block');">Update Tracking location</a>
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

			<div class="row" style="z-index:999999; position:fixed; top:10px; width:100%; display:none;" id="m_dataa">
				<div class="col-md-12 col-sm-12" style="text-align: center;">
				<div class="modal" style="display:block" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				  <div class="modal-dialog">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 class="modal-title" id="exampleModalLabel">Confirm Location</h5>
				        <button type="button" class="close" data-dismiss="modal" id="m_close" onclick="$('#m_dataa').css('display', 'none');" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
				      </div>
				      <div class="modal-body">
					      @if ($package->status == 1)

					      <div class="row">
						      <div class="col">
							      <h5><b>confirm package location status:</b></h5>
							      <form action="{{route('admin_confirm_track_package')}}" method="POST" style="text-align: left;">
								      @csrf
								      {{-- <input type="text"  class="form-control" value="{{Auth::user()->unit_location}}" name="au_location"> --}}
								      <select class="form-control" name="au_location" id="">
									      <?php $locations = \App\Models\Location::all(); ?>
                                            					@foreach ($locations as $l)
                                            					    <option value="{{$l->location}}">{{$l->region}}-> {{$l->city}}-> {{$l->location}}</option>
                                            					@endforeach
								      </select>
								      <input type="hidden" value="{{$package->id}}" name="p_id">
								      <br> 
								      <button type="submit" name="a_d"  value="1" class="btn btn-warning" style="float: left; backgroud:blue;">Arrival</button>
								 	@if ($package->trackings[count($package->trackings)-1]->current_location == $package->to)
									 <button type="submit" name="a_d"  value="3" class="btn btn-success" style="float: right; backgroud:blue;">Dispatched</button>
									@else
								      	 <button type="submit" name="a_d"  value="2" class="btn btn-success" style="float: right; backgroud:blue;">Departure</button>
					      				@endif     
							      </form>
						      </div>
					      </div>
					      <hr>
					      @endif
					      <div class="row">
						      <div class="col">
							      <h5><b>Confirm Package Delivery:</b></h5>
								<form action="{{route('admin_confirm_package_delivery')}}" method="POST"  enctype="multipart/form-data" style="text-align: left;">
								      	@csrf
								      	@if ($package->c_way_bill == null)
										  
									      @if ($package->delivery_image == null)  
										<div class="form-group">
										    <label for="exampleInputEmail1">Delivery document (Image):</label>
										    <input type="file" class="form-control" name="delivery_image" id="file" aria-describedby="fileHelp" required>
										    <small id="filelHelp" class="form-text text-muted">Document must be an image Type (Jpeg, Jpg, Png, Gif) And file-size (Max: 9MB).</small>
										  </div>  
										<div class="form-group">
										  <label for="wbil">Way bill No: (*)</label>
										  <input type="text" class="form-control" name="way_bill" id="wbil">
										</div>
										<div class="form-group">
										  <label for="s_by">Sign by<small>(collector)</small>: (*)</label>
										  <input type="text" class="form-control" name="s_by" id="s_by">
										</div>
									      @else
										<div class="form-group">
										    <label for="exampleInputEmail1">Client Delivery document (Image):</label>
										    <input type="file" class="form-control" name="delivery_image" id="file" aria-describedby="fileHelp" required>
										    <small id="filelHelp" class="form-text text-muted">Document must be an image Type (Jpeg, Jpg, Png, Gif) And file-size (Max: 9MB).</small>
										  </div> 
										<div class="form-group">
										  <label for="wbil">Client Way bill No: (*)</label>
										  <input type="text" class="form-control" name="way_bill" id="wbil">
										</div>
									      @endif
									      <input type="hidden" value="{{$package->id}}" name="p_id">
									      
									      <button type="submit" class="btn btn-warning" style="float: left; backgroud:blue;">Confirm Delivery</button>
									@else
										<div class="form-group">
										    <label for="exampleInputEmail1">Document (Image):</label>
										    <input type="file" class="form-control" name="delivery_image" id="file" aria-describedby="fileHelp" required>
										    <small id="filelHelp" class="form-text text-muted">Document must be an image Type (Jpeg, Jpg, Png, Gif) And file-size (Max: 9MB).</small>
										  </div> 
										  <input type="hidden" value="{{$package->id}}" name="p_id">
										  <button type="submit" class="btn btn-warning" style="float: left; backgroud:blue;">Add Document</button>
									@endif
									{{-- <div class="form-group">
  									  <label for="wbil1">Client Way bill No: (optional)</label>
  									  <input type="text" class="form-control" name="c_way_bill" id="wbil1">
  									</div> --}}

								      
								</form>
						      </div>
					      </div>
				      </div>
				      {{-- <div class="modal-footer">
				        <button type="button" class="btn btn-secondary" id="m_close">Close</button>
				        <button type="button" class="btn btn-primary">Save changes</button>
				      </div> --}}
				    </div>
				  </div>
				</div>
				</div>
			</div>
			

                    </div>
                    <!-- Column -->

                </div>
            </div>
@endsection

@section('script')
    <script>
	$('#m_confirm').on('click', function () {
		$('#m_dataa').css('display','block');
	});
	$('#m_close').on('click', function () {
		$('#m_dataa').css('display','none');
	});
    </script>
@endsection
