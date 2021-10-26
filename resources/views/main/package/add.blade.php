@extends('layouts.main.index')


@section('content')

    <!-- package Start -->
        <div class="container">
            <a onclick="window.history.back()" id="back" class="pre">&laquo; Previous</a><br><br>
            @include('layouts.flash')
            <div class="row justify-content-center">
                <div class="col-lg-11">
                    {{-- <h1 class="mb-4">Contact For Any Queries</h1> --}}
                    <div class="card">
                        <div class="card-header">
                            <h6 class="text-primary text-uppercase font-weight-bold">Create Shipment</h6>
                        </div>
                        <div class="card-body" style="">
                            <div id="success"></div>
                            <form name="package" method="POST" action="{{route('main_create_package')}}">
                                @csrf
                                <label for="from">From <small>(region)</small>:</label>
                                <div class="input-group">
                                <select class="form-control" name="from" id="from" required="required">
                                    <option value="">Select__</option>
                                    <?php $regions = \App\Models\Region::all(); ?>
                                    @foreach ($regions as $r)
                                        <option value="{{$r->state}}">{{$r->state}}</option>
                                    @endforeach
                                </select>
                                    @error('from')
                                        <p class="help-block text-danger">{{$message}}</p>
                                    @enderror
                                </div>

                                <label for="address_f"> Address:</label>
                                <div class="input-group">
                                <input type="text" class="form-control p-4" value="{{old('from_address')}}" name="from_address" id="address_f" placeholder="From Address "
                                    required="required" data-validation-required-message="Please enter Address of departure" />
                                @error('from_address')
                                <p class="help-block text-danger">{{$message}}</p>
                                                @enderror
                                </div>
                                <br>
                                <hr>
                                <br>
                                <label for="to">To <small>(region)</small>:</label>
                                <div class="input-group">
                                <select class="form-control" name="to" id="to" required="required">
                                    <option value="">Select__</option>
                                    <?php $regions = \App\Models\Region::all(); ?>
                                    @foreach ($regions as $r)
                                        <option value="{{$r->state}}">{{$r->state}}</option>
                                    @endforeach
                                </select>
                                    @error('to')
                                        <p class="help-block text-danger">{{$message}}</p>
                                    @enderror
                                </div>
    
                                <label for="address_t">Address:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control p-4" id="address_t" value="{{old('to_address')}}" name="to_address" placeholder="To Address "
                                        required="required" data-validation-required-message="Please enter Address to" />
                        @error('to_address')
                        <p class="help-block text-danger">{{$message}}</p>
                                    @enderror
                                </div>
                                <br>
                                <div>
                                    <button class="btn btn-primary py-3 px-4" type="submit" id="pack">Create Package</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Package End -->
@endsection

@section('script')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
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

            	        $('#kk').html(data);
			            console.log(data);
            	    }
            	});

    	});

   })
</script>
@endsection
