@extends('layouts.main.index')

@section('content')

    <!-- package Start -->
    <div class="container">
        {{-- <div class="container"> --}}
	<a onclick="window.history.back()" id="back" class="pre">&laquo; Previous</a><br> <br>
            @include('layouts.flash')
	    <div class="row justify-content-center py-2 px-2">
		    <div class="col">
			    <div class="card  py-4 px-3">
			<div class="card-block">
				      <h5 class="text-primary text-uppercase font-weight-bold">Search Shipments</h5>
				      
                                  <form class="form-material" method="GET" action="{{route('main_search_package')}}">
					@csrf
					<div class="row">
						<div class="col col-md-6 col-sm-12">
							<div class="form-group form-default">
							    {{-- <input type="text" name="name" value="{{old('name')}}" class="form-control" required=""> --}}
							    <label class="float-label" style="color: blue;">Chooce Range <small>(from - to)</small></label>
							    <input name="date_range" value="{{old('date_range')}}" required class="form-control" id="range" type="text" >
							    <span class="form-bar"></span>
							    <p class="" id="small"></p>
							    @error('date_range')
								  <Span style="color: red;">{{$message}}</Span>
							    @enderror
							</div>
						</div>
						<div class="col col-md-6 col-sm-12">
							<div class="form-group form-default">
								<label class="float-label" style="color: blue;">Select Category</label>
								<select name="type" class="form-control" required>
									<option value="all">All</option>
									<option value="not_shipped">Not Shipped</option>
									<option value="shipped">Shipped</option>
									<option value="delivered">Delivered</option>
								</select>
							    @error('type')
								  <Span style="color: red;">{{$message}}</Span>
							    @enderror
							</div>
						</div>
					</div>
                                      <div class="form-group form-default justify-content-center">
                                          <input type="submit" class="btn btn-primary" id="load"  value="Search" style="float: ;" id="">
                                      </div>
                                  </form>
                              </div>
                            </div>
		    </div>
	    </div>
            <div class="row lustify-content-center">
                <div class="col">
		   <div class="card">
			<div class="card-header">
				<h5 class="text-primary text-uppercase font-weight-bold">Shipments list</h5>
			</div>   
			
			    <div class="card-body">
				<div class="list-group">
				@if (count($packages) > 0)    
				<?php $k = count($packages);?>
				@foreach ($packages as $p)
				<div class="list-group-item list-group-item-action border-dark">
					<span style="float: right">#{{$k}}</span>
					<a href=""  style="color: blue;" onclick="event.preventDefault(); document.getElementById('track-form[{{$k}}]').submit();">
						<h5 style="color: blue;">
						{{$p->tracking_id}}
						</h5> 
					</a>
					<form action="{{route('main_get_track_info')}}" id="track-form[{{$k}}]" method="POST" class="d-none">
                                	    @csrf
                                	        <input type="hidden" name="track" value="{{$p->tracking_id}}">  
                                	</form>   
					<?php 
						if ($p->status == 0) {
							echo '<button class="btn btn-warning disabled" disabled>Not Activated</button>';
						}
						if ($p->status == 1) {
							echo '<button class="btn btn-success disabled" disabled>Activated</button>';
						}
						if ($p->status == 2) {
							echo '<button class="btn btn-primary disabled" disabled>Deliverd</button>';
						}
					      ?>
					      <a href="{{route('main_show_activate_package', ['id' => $p->id])}}" class="btn btn-primary" style="float: right">Open</a>
					<p style="margin:0"><b>Customer:</b> {{$p->customer->name}}</p>
					<p style="margin:0"><b>Total Amount:</b> {{$p->total_amount/100}}</p>
					<p style="margin:0"><b>Item type:</b>  {{$p->item_type}}</p>
					<div class="row" style="margin:0">
						<div class="col-md-6">
							<p style="margin:0"><b>From:</b></p>
							<p style="margin:0"> {{$p->from}}</p>
						</div>
						<div class="col-md-6">
							<p style="margin:0"><b>To:</b></p>
							<p style="margin:0">{{$p->address_to}}, {{$p->to}}</p>
						</div>
					</div>
					<p style="margin:0"><b>Created At: {{$p->created_at}}</b> </p>
					<hr>
				</div>
				<?php $k--; ?>
				@endforeach
				@else
				<a href="" class="list-group-item list-group-item-action border-dark"> No Parcel collections</a>
				@endif
				</div>
			    </div>
		    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Package End -->
@endsection

@section('script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
$(function() {

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#small').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
    }

    $('input[name="date_range"]' || ' ').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);


    cb(start, end);

});
</script>

@endsection
