@extends('layouts.Admin.index')


@section('content')
            <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Locations</h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <div class="d-md-flex">
                            <ol class="breadcrumb ms-auto">
                                <li><a href="#" class="fw-normal">Location</a></li>
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
                                <form method="POST" action="{{route('admin_update_location', ['id'=>$location->id])}}" class="form-horizontal form-material">
					@csrf
					<h4 class="box-title"><b>Location info</b></h4>
					<br>
					<div class="row">
					    <div class="col-sm-6">
						<div class="form-group mb-4">
                                    		    <label for="example-email" class="col-md-12 p-0">Region: </label>
                                    		    <div class="col-md-12 border-bottom p-0">
							<select class="form-control px-5 border-0" name="region" required>
                                			    <option value="{{$location->region}}">{{$location->region}}</option>
                                			    <?php $regions = \App\Models\Region::all(); ?>
                                			    @foreach ($regions as $r)
                                			        <option value="{{$r->state}}">{{$r->state}}</option>
                                			    @endforeach
                                			</select>
                                    		       {{-- <input class="form-control px-5 border-0" name="region" type="text" value="{{$location->region}}"> --}}
                                    		    </div>
							@error('region')
						    <p class="help-block text-danger">{{$message}}</p>
						@enderror
                                    		</div>
					    </div>
					    <div class="col-sm-6">
						<div class="form-group mb-4">
                                    		    <label for="example-email" class="col-md-12 p-0">City: </label>
                                    		    <div class="col-md-12 border-bottom p-0">
                                    		         <input class="form-control px-5 border-0" name="city" type="text" value="{{$location->city}}">
                                    		    </div>
							@error('city')
                                			        <p class="help-block text-danger">{{$message}}</p>
                                			@enderror
                                    		</div>
					    </div>
					</div>
				    <div class="row">
					    <div class="col-sm-6">
						<div class="form-group mb-4">
                                    		    <label for="example-email" class="col-md-12 p-0">zone: </label>
                                    		    <div class="col-md-12 border-bottom p-0">
							<select class="form-control px-5 border-0" name="zone" required>
                                			    <option value="{{$location->zone}}">{{$location->zone == 'a'? 'A':''}}{{$location->zone == 'b'? 'B':''}}</option>
                                			    <option value="a">A</option>
							    <option value="b">B</option>
                                			</select>
                                    		        {{-- <input class="form-control px-5 border-0" name="zone" type="text" value="{{$location->zone}}"> --}}
                                    		    </div>
							@error('zone')
                                			        <p class="help-block text-danger">{{$message}}</p>
                                			@enderror
                                    		</div>
					    </div>
					    <div class="col-sm-6">
						<div class="form-group mb-4">
                                    		    <label for="example-email" class="col-md-12 p-0">Location: </label>
                                    		    <div class="col-md-12 p-0">
							 <input class="form-control px-5 border-0" name="location" type="text" value="{{$location->location}}">
                                    		    </div>
							@error('location')
                                			        <p class="help-block text-danger">{{$message}}</p>
                                			@enderror
                                    		</div>
					    </div>
				    </div>

				    	<div class="form-group mb-4">
                                    	    <label for="example-email" class="col-md-12 p-0">Designation: </label>
                                    	    <div class="col-md-12 border-bottom p-0">
						<select class="form-control px-5 border-0" name="desig" required>
                                		    <option value="{{$location->type}}">{{$location->type==1? 'Depart': ''}}{{$location->type==2? 'Arrive': ''}}{{$location->type==3? 'Depart & Arrive':''}}</option>
                                		    <option value="1">Depart</option>
						    <option value="2">Arrive</option>
						    <option value="3">Depart & Arrive</option>
                                		</select>
                                    	        {{-- <input class="form-control px-5 border-0" name="zone" type="text" value="{{ old('zone')}}"> --}}
                                    	    </div>
						@error('desig')
                                		        <p class="help-block text-danger">{{$message}}</p>
                                		@enderror
                                    	</div>

				    	<div class="form-group mb-4">
                                	    <div class="col-md-12  p-0">
						 <input class="btn btn-primary px-5 " type="submit" value="Update Location">
                                	    </div>
                                	</div>
				</form>
				<hr>
				<form action="{{route('admin_update_charge_location', ['id'=>$location->charges[0]->id])}}" method="POST">
					@csrf
                                    <div class="form-group mb-4">
                                        <label for="example-email" class="col-md-12 p-0">Amount:</label>
                                        <div class="col-md-12 border-bottom px-4">
                                            <input class="form-control px-5 border-0" name="amount" type="number" value="{{$location->charges[0]->amount}}">
                                        </div>
					@error('amount')
                                	        <p class="help-block text-danger">{{$message}}</p>
                                	@enderror
                                    </div>

				    <div class="form-group mb-4">
                                        <div class="col-md-12  p-0">
				    	 <input class="btn btn-primary px-5" type="submit" value="Update Location Amount">
                                        </div>
                                    </div>
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->

                </div>
            </div>
@endsection
