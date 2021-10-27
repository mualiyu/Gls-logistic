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
                                <div class="row">
                                    <div class="col-md-12" style="text-align: center"><h6>Location details</h6></div>
                                    <div class="col-md-6">
                                        <label for="from">From <small>(region)</small>:</label>
                                        <div class="input-group">
                                        <select class="form-control" name="from" id="from" required="required">
                                            <option value="">Select__</option>
                                            <?php $locations = \App\Models\Location::all(); ?>
                                            @foreach ($locations as $l)
                                                <option value="{{$l->location}}">{{$l->region}}-> {{$l->city}}-> {{$l->location}}</option>
                                            @endforeach
                                        </select>
                                            @error('from')
                                                <p class="help-block text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        {{-- <label for="address_f"> Address:</label>
                                        <div class="input-group">
                                        <input type="text" class="form-control p-4" value="{{old('from_address')}}" name="from_address" id="address_f" placeholder="From Address "
                                            required="required" data-validation-required-message="Please enter Address of departure" />
                                        @error('from_address')
                                        <p class="help-block text-danger">{{$message}}</p>
                                                        @enderror
                                        </div> --}}

                                    </div>

                                    <div class="col-md-6">
                                        <label for="to">To <small>(region)</small>:</label>
                                        <div class="input-group">
                                        <select class="form-control" name="to" id="to" required="required">
                                            <option value="">Select__</option>
                                            <?php $locations = \App\Models\Location::all(); ?>
                                            @foreach ($locations as $l)
                                                <option value="{{$l->location}}">{{$l->region}}-> {{$l->city}}-> {{$l->location}} (FCFA {{$l->charges[0]->amount/100}})</option>
                                            @endforeach
                                        </select>
                                            @error('to')
                                                <p class="help-block text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
            
                                        <label for="address_t">Address:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control p-4" id="address_t" value="{{old('to_address')}}" name="to_address" placeholder="Address To"
                                                required="required" data-validation-required-message="Please enter Address to" />
                                                @error('to_address')
                                                <p class="help-block text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <hr>
                                <br>

                                <div class="row">
                                    <div class="col-md-12" style="text-align: center"><h6>Contact details</h6></div>
                                    <div class="col-md-6">
                                        {{-- <label for="from">Choose Contact Option:</label> --}}
                                        <div class="input-group">
                                        <select class="form-control" name="c_info" id="c_info" required="required">
                                            <option value="0">Use current account contact info</option>
                                            <option value="1">Or add another contact info</option>
                                        </select>
                                        </div><br>

                                    </div>

                                    <div class="col-md-6" id="c_data" style="display: none;">
                                        <label for="phone">Phone:</label>
                                        <div class="input-group">
                                        <input type="number" class="form-control p-4" id="phone" value="{{old('phone')}}" name="phone" placeholder="Phone" />
                                            @error('phone')
                                                <p class="help-block text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
            
                                        <label for="email">Email:</label>
                                        <div class="input-group">
                                            <input type="email" class="form-control p-4" id="email" value="{{old('email')}}" name="email" placeholder="E-mail Address" />
                                                @error('email')
                                                <p class="help-block text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <hr>
                                <br>

                                <div class="row">
                                    <div class="col-md-12" style="text-align: center"><h6>Item details</h6></div>
                                    
                                    <div class="col-md-6">
                                        <label for="phone">Item Type:</label>
                                        <div class="input-group">
                                        <select class="form-control" name="item" id="item" required="required">
                                            <option value="">Select__</option>
                                            <?php $merchandises = \App\Models\Merchandise::all(); ?>
                                            @foreach ($merchandises as $m)
                                                <option value="{{$m->type}}">{{$m->type}}</option>
                                            @endforeach
                                        </select>
                                            @error('item')
                                                <p class="help-block text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="col-md-6">
                                        <div class="col-lx-6">
                                            <label for="">Item Weight:</label>
                                            <div class="input-group">
                                            <input type="text" class="form-control" name="weight" placeholder="In Grams (Optional)"> 
                                                @error('weight')
                                                    <p class="help-block text-danger">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lx-6">
                                            <label for="">Quantity:</label>
                                            <div class="input-group">
                                            <input type="number" class="form-control" name="quantity" placeholder="" value="1"> 
                                                @error('quantity')
                                                    <p class="help-block text-danger">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <div class="col-md-12">
                                        <label for="phone">Description:</label>
                                        <div class="input-group">
                                        <textarea class="form-control px-3 py-3" name="description" id="des" required="required" placeholder="Description..."></textarea>
                                            @error('item')
                                                <p class="help-block text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <hr>
                                <br>

                                <div>
                                    <button class="btn btn-primary py-3 px-4" type="submit" id="pack">Create shipment</button>
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
        
	$('#c_info').on('change', function() {
        	var code = this.value;

            if (code ==1) {
                $('#c_data').css('display', 'block');
            }
            if (code == 0) {
                $('#c_data').css('display', 'none');
            }
		// console.log(code);

		

    	});

   })
</script>
@endsection
