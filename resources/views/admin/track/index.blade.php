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
            <div class="container-fluid">

		<div class="row">
                    <div class="col-md-12">
                        @include('layouts.flash')
			<div class="card">
				<div class="card-body">
					<h3 class="box-title">Track Package</h3>
				</div>
                            <div class="card-body">
                                <form class="form-horizontal form-material" method="POST" action="{{route('admin_track_package')}}">
					@csrf
                                    <div class="form-group mb-4">
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" id="tracking" name="tracking_number" placeholder="Tracking number" class="form-control p-0 border-0">
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
			</div>

                    </div>
                </div>

		{{-- searche packages --}}
		<div class="row" id="search_t" style="display: none;">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title">Tracking Info</h3>
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="border-top-0">#</th>
                                            <th class="border-top-0">Tracking NO</th>
                                            <th class="border-top-0">Items</th>
                                            <th class="border-top-0">Total Amount</th>
					    <th class="border-top-0">Status</th>
                                            <th class="border-top-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- <div class="row">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h3 class="box-title">Blank Page</h3>
                        </div>
                    </div>
                </div> --}}
            </div>
@endsection



