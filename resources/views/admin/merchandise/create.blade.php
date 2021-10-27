@extends('layouts.Admin.index')


@section('content')
            <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Merchandises</h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <div class="d-md-flex">
                            <ol class="breadcrumb ms-auto">
                                <li><a href="#" class="fw-normal">Merchandise</a></li>
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
                                <form method="POST" action="{{route('admin_store_merchandise')}}" class="form-horizontal form-material">
					@csrf
					<h4 class="box-title"><b>Add Merchandise</b></h4>
					<br>
                                    <div class="form-group mb-4">
                                        <label for="example-email" class="col-md-12 p-0">Type:</label>
                                        <div class="col-md-12 border-bottom px-4">
                                            <input class="form-control px-5 border-0" name="type" type="text" value="{{ old('type') }}">
                                        </div>
					@error('type')
                                	        <p class="help-block text-danger">{{$message}}</p>
                                	@enderror
                                    </div>

				    <div class="form-group mb-4">
                                        <div class="col-md-12  p-0">
				    	 <input class="btn btn-primary px-5" type="submit" value="Add">
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
