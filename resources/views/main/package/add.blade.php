@extends('layouts.main.index')


@section('content')
    <!-- Header Start -->
    <div class="jumbotron jumbotron-fluid mb-2">
        <div class="container text-center py-1">
            <h1 class="text-white display-3">Package</h1>
            <div class="d-inline-flex align-items-center text-white">
                <p class="m-0"><a class="text-white" href="">Home</a></p>
                <i class="fa fa-circle px-3"></i>
                <p class="m-0">Package</p>
		<i class="fa fa-circle px-3"></i>
                <p class="m-0">Add</p>
            </div>
        </div>
    </div>
    <!-- Header End -->


    <!-- package Start -->
    <div class="container-fluid py-2">
        <div class="container">
            @include('layouts.flash')
            <div class="row">
		<div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <h6 class="text-primary text-uppercase font-weight-bold">Create Package</h6>
                    {{-- <h1 class="mb-4">Contact For Any Queries</h1> --}}
                    <div class="contact-form bg-secondary" style="padding: 30px;">
                        <div id="success"></div>
                        <form name="package" method="POST" action="{{route('main_create_package')}}"novalidate="novalidate">
				@csrf
			    <div class="control-group">
				<label for="from">From <small>(region)</small></label>
                                <select class="form-control border-0" name="from" id="from" required="required" data-validation-required-message="Please enter From" >
					<option value="">Select__</option>
					<?php $regions = \App\Models\Region::all(); ?>
					@foreach ($regions as $r)
					    <option value="{{$r->code}}">{{$r->state}}</option>
					@endforeach
				</select>
				@error('from')
					<p class="help-block text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="control-group">
                                <input type="text" class="form-control border-0 p-4" value="{{old('from_address')}}" name="from_address" id="address" placeholder="From Address "
                                    required="required" data-validation-required-message="Please enter Address of departure" />
				@error('from_address')
				<p class="help-block text-danger">{{$message}}</p>
                                @enderror
                            </div>
			    <div class="control-group">
				<label for="from">To <small>(region)</small></label>
                                <select class="form-control border-0 " id="to" name="to" placeholder="To"
                                    required="required" data-validation-required-message="Please enter From" >
				</select>
				@error('to')
				<p class="help-block text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="control-group">
                                <input type="text" class="form-control border-0 p-4" id="address" value="{{old('to_address')}}" name="to_address" placeholder="To Address "
                                    required="required" data-validation-required-message="Please enter Address to" />
				    @error('to_address')
				    <p class="help-block text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div>
                                <button class="btn btn-primary py-3 px-4" type="submit" id="pack">Create Package</button>
                            </div>
                        </form>
                    </div>
                </div>
		<div class="col-lg-2"></div>
            </div>
        </div>
    </div>
    <!-- Package End -->
@endsection

@section('script')
<script>
   $(document).ready(function() {
        
	$('#from').on('change', function() {
        	var code = this.value;
		// console.log(code);

		 $.ajax({

            	    url:"{{ route('main_get_to_region') }}",
			
            	    type:"GET",
			
            	    data:{'dept':code},
			
            	    success:function (data) {

			// console.log(data);
            	        $('#to').html(data);
            	    }
            	});

    	});

   })
</script>
@endsection
