@extends('layouts.main.index')

@section('content')
    <!-- Header Start -->
    <div class="jumbotron jumbotron-fluid mb-2">
        <div class="container text-center">
            <h1 class="text-white display-3">Package</small></h1>
            <div class="d-inline-flex align-items-center text-white">
                <p class="m-0"><a class="text-white" href="">Home</a></p>
                <i class="fa fa-circle px-3"></i>
                <p class="m-0">Package</p>
		<i class="fa fa-circle px-3"></i>
                <p class="m-0">Item</p>
		<i class="fa fa-circle px-3"></i>
                <p class="m-0">Add</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Package Start -->
    <div class="container-fluid bg-secondary py-2">
        <div class="container">
		@include('layouts.flash')
            <div class="row align-items-center">
                <div class="{{$package->status == 0 ? 'col-lg-7' : 'col'}} py-5 py-lg-0">
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
                    <h1 class="mb-4">Tracking ID:  {{$package->tracking_id}}</h1>
                    {{-- <p class="mb-4">Dolores lorem lorem ipsum sit et ipsum. Sadip sea amet diam dolore sed et. Sit rebum labore sit sit ut vero no sit. Et elitr stet dolor sed sit et sed ipsum et kasd ut. Erat duo eos et erat sed diam duo</p> --}}
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="text-primary mb-2" data-toggle="counter-up">{{count($items)}}</h1>
                            <h6 class="font-weight-bold mb-4">Items</h6>
                        </div>
                        <div class="col-sm-6">
                            <h1 class="text-primary mb-2" >NGN <span data-toggle="counter-up">{{$package->total_amount/100}}</span></h1>
                            <h6 class="font-weight-bold ">Amount</h6>
                        </div>
                    </div>
		    <div class="row">
			<div class="col-sm-6">
				<label class="text-primary" for="from">From: </label>
				<?php $region_from = \App\Models\Region::where('code', '=', $package->from)->get(); ?>
				<span id="from">{{$package->address_from}}, {{$region_from[0]->state}}</span>
			</div>
			<div class="col-sm-6">
				<label class="text-primary" for="to">To: </label>
				<?php $region_to = \App\Models\Region::where('code', '=', $package->to)->get(); ?>
				<span id="to">{{$package->address_from}}, {{$region_to[0]->state}}</span>
			</div>
		    </div>
		    <br>
		    <div class="row">
			<h6 class="text-primary text-uppercase font-weight-bold">Current Items</h6>
			<table class="table table-bordered">
			  <thead>
			    <tr>
			      <th scope="col">#</th>
			      <th scope="col">Name</th>
			      <th scope="col">Description</th>
			      <th scope="col">Length/Height/Width</th>
			      <th scope="col">Weight</th>
			      <th scope="col">Action</th>
			    </tr>
			  </thead>
			  <tbody>
				  <?php $i = 1; ?>
			  @foreach ($items as $item)  
			  <tr>
			    <th scope="row">{{$i}}</th>
			    <td>{{$item->name}}</td>
			    <td>{{$item->description}}</td>
			    <td>{{$item->length}} / {{$item->height}} / {{$item->width}}</td>
			    <td>{{$item->weight}}</td>
			    <td>
				    <form action="" method="POST">
					    @csrf
					    <button class="btn btn-warning" style="background: red;">Delete</button>
				    </form>
			    </td>
			  </tr>
			  @endforeach
			  </tbody>
			</table>
		    </div>
                </div>
		@if ($package->status == 0)
                <div class="col-lg-5">
                    <div class="bg-primary py-5 px-4 px-sm-5">
			<h3 class="font-weight-bold mb-4">ADD ITEM</h3>
                        <form class="py-5" method="POST" action="{{route('main_add_item_to_package')}}">
				@csrf
				<input type="hidden" name="p_id" value="{{$package->id}}">
                            <div class="form-group">
                                <input type="text" name="name" value="{{old('name')}}" class="form-control border-0 p-4" placeholder="Item Name" required="required" />
				@error('name')
				<p class="help-block text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <textarea class="form-control border-0" name="description" placeholder="Description" required="required" ></textarea>
				@error('description')
				<p class="help-block text-danger">{{$message}}</p>
                                @enderror
                            </div>
			    <div class="row">
				    <div class="col-sm-6">
					    	<div class="form-group">
						    <input type="number" name="length" class="form-control border-0 p-4" placeholder="Length" required="required" />
						@error('length')
						<p class="help-block text-danger">{{$message}}</p>
                                		@enderror
						</div>
				    </div>
				    <div class="col-sm-6">
					    	<div class="form-group">
						    <input type="number" name="width" class="form-control border-0 p-4" placeholder="width" required="required" />
						@error('width')
						<p class="help-block text-danger">{{$message}}</p>
                                		@enderror
						</div>
				    </div>
			    </div>
			    <div class="row">
				    <div class="col-sm-6">
					    	<div class="form-group">
						    <input type="number" name="height" class="form-control border-0 p-4" placeholder="Height" required="required" />
						@error('height')
						<p class="help-block text-danger">{{$message}}</p>
                                		@enderror
						</div>
				    </div>
				    <div class="col-sm-6">
					    	<div class="form-group">
						    <input type="number" name="weight" class="form-control border-0 p-4" placeholder="Weight" required="required" />
						@error('weight')
						<p class="help-block text-danger">{{$message}}</p>
                                		@enderror
						</div>
				    </div>
			    </div>
                                <button class="btn btn-dark btn-block border-0 py-3" type="submit">Add Item</button>
                            </div>
                        </form>
                    </div>
		@endif
                </div>
            </div>
        </div>
    </div>
    <!-- Package Start -->

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
