@extends('layouts.main.index')

@section('content')

    <!-- Package Start -->
    <div class="container y py-2">
        <div class="container">
		<a onclick="window.history.back()" id="back" class="pre">&laquo; Previous</a><br><br>
		@include('layouts.flash')
		<div class="card">
			<div class="card-body">
				<div class="row justify-content-center">
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
					<h5 class="mb-4">Tracking ID:  {{$package->tracking_id}}</h5>
					
					<div class="row">
					    <div class="col-sm-6">
						<h5 class="text-primary mb-2" data-toggle="counter-up">{{count($items)}}</h5>
						<h6 class="font-weight-bold mb-4">Items</h6>
					    </div>
					    <div class="col-sm-6">
						<h5 class="text-primary mb-2" >FCFA <span data-toggle="counter-up">{{$package->total_amount/100}}</span></h5>
						<h6 class="font-weight-bold ">Amount</h6>
					    </div>
					</div>
					<div class="row">
					    <div class="col-sm-6">
						    <label class="text-primary" for="from">From: </label>
						    <span id="from">{{$package->address_from}}, {{$package->from}}</span>
					    </div>
					    <div class="col-sm-6">
						    <label class="text-primary" for="to">To: </label>
						    <span id="to">{{$package->address_from}}, {{$package->to}}</span>
					    </div>
					</div>
					<br>
					<h6 class="text-primary text-uppercase font-weight-bold">Current Items</h6>
					<div class="row justify-content-center" style="margin:0;">
						<div class="col-md-12">
							<div class="list-group" style="margin:0;">
							@if (count($items) > 0)
							<?php $k = count($items);?>
							@foreach ($items as $item)
							<a href="#" class="list-group-item list-group-item-action border-dark">
								<span style="float: right">#{{$k}}</span>&nbsp;&nbsp;
								<form action="" method="">
									@csrf
									<button class="btn btn-warning" style="background: red; float: right;">Delete</button>
								</form>
								<h5>{{$item->name}}</h5>
								<p style="margin:0"><b>Description:</b> {{$item->description}}</p>
								{{-- <p><b>length / Height / Width:</b> {{$item->length}} / {{$item->height}} / {{$item->width}}</p> --}}
								<p style="margin:0"><b>Weight:</b> {{$item->weight}}g</p>
								{{-- <p style="margin:0"><b>Created At: {{$item->created_at}}</b> </p> --}}
								<hr>
							</a>
							<?php $k--; ?>
							@endforeach
							@else  
							<a href="#" class="list-group-item list-group-item-action border-dark">
								No Items Found
							</a>
							@endif
							</div>
						</div>
					</div>
				    </div>
				    @if ($package->status == 0)
				    <div class="col-lg-5">
					<div class="bg-primary py-5 px-4 px-sm-5">
					    <h3 class="font-weight-bold mb-4">ADD ITEM</h3>
					    <form class="" method="POST" action="{{route('main_add_item_to_package')}}">
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

						<div class="form-group">
						    <input type="number" name="amount" value="{{old('amount')}}" class="form-control border-0 p-4" placeholder="Amount (FCFA)" required="required" />
						    @error('amount')
						    <p class="help-block text-danger">{{$message}}</p>
						    @enderror
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
