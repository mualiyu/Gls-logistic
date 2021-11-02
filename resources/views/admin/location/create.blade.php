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
                                <form method="POST" action="{{route('admin_store_location')}}" class="form-horizontal form-material">
					@csrf
					<h4 class="box-title"><b>Create Location</b></h4>
					<br>
					<div class="row">
					    <div class="col-sm-6">
						<div class="form-group mb-4">
                                    		    <label for="example-email" class="col-md-12 p-0">Region: </label>
                                    		    <div class="col-md-12 border-bottom p-0">
							<select class="form-control px-5 border-0" name="region" required>
                                			    <option value="">Select__</option>
                                			    <?php $regions = \App\Models\Region::all(); ?>
                                			    @foreach ($regions as $r)
                                			        <option value="{{$r->state}}">{{$r->state}}</option>
                                			    @endforeach
                                			</select>
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
                                    		         <input class="form-control px-5 border-0" name="city" type="text" value="{{ old('city')}}" required>
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
                                    		    <label for="example-email" class="col-md-12 p-0">Zone: </label>
                                    		    <div class="col-md-12 border-bottom p-0">
							<select class="form-control px-5 border-0" name="zone" required>
                                			    <option value="">Select__</option>
                                			    <option value="a">A</option>
							    <option value="b">B</option>
                                			</select>
                                    		        {{-- <input class="form-control px-5 border-0" name="zone" type="text" value="{{ old('zone')}}"> --}}
                                    		    </div>
							@error('zone')
                                			        <p class="help-block text-danger">{{$message}}</p>
                                			@enderror
                                    		</div>
					    </div>
					    <div class="col-sm-6">
						<div class="form-group mb-4">
                                    		    <label for="example-email" class="col-md-12 p-0">Location: </label>
                                    		    <div class="col-md-12 border-bottom p-0">
							 <input class="form-control px-5 border-0" name="location" type="text" value="{{ old('location')}}" required>
                                    		    </div>
							@error('location')
                                			        <p class="help-block text-danger">{{$message}}</p>
                                			@enderror
                                    		</div>
					    </div>
				    </div>
				    <div class="row">
					    <div class="col-sm-6">
						    <div class="form-group mb-4">
							<label for="example-email" class="col-md-12 p-0">Amount:</label>
							<div class="col-md-12 border-bottom px-4">
							    <input class="form-control px-5 border-0" name="amount" type="number" value="{{ old('amount')}}" required>
							</div>
							@error('amount')
								<p class="help-block text-danger">{{$message}}</p>
							@enderror
						    </div>
					    </div>
					    <div class="col-sm-6">
						<div class="form-group mb-4">
                                    		    <label for="example-email" class="col-md-12 p-0">Designation: </label>
                                    		    <div class="col-md-12 border-bottom p-0">
							<select class="form-control px-5 border-0" name="desig" required>
                                			    <option value="">Select__</option>
                                			    <option value="1">From</option>
							    <option value="2">To</option>
							    <option value="3">From & To</option>
                                			</select>
                                    		        {{-- <input class="form-control px-5 border-0" name="zone" type="text" value="{{ old('zone')}}"> --}}
                                    		    </div>
							@error('desig')
                                			        <p class="help-block text-danger">{{$message}}</p>
                                			@enderror
                                    		</div>
					    </div>
				    </div>

				    <div class="form-group mb-4">
                                        <div class="col-md-12 p-0">
				    	 <input class="btn btn-primary" type="submit" value="Create Location">
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
