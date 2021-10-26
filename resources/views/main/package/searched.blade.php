@extends('layouts.main.index')

@section('content')

    <!-- package Start -->
    <div class="container">
        {{-- <div class="container"> --}}
	    <a onclick="window.history.back()" id="back" class="pre">&laquo; Previous</a><br><br>
            @include('layouts.flash')
	    <div class="row">
		    <div class="col">
			    <div class="card shadow" style="width:100%;">
                          <div class="card-body">  
			    <div class="row">
                              <div class="col-sm-3">
                                <h5 class="mb-0" style="float: right;">Shipments Summary</h5>
                              </div>
                              <div class="col-sm-9 text-secondary">
                              </div>
                            </div>
                            <hr>
                            <div class="row">
                              <div class="col-sm-3">
                                <h6 class="mb-0" style="float: right;">Total number of Shipments</h6>
                              </div>
                              <div class="col-sm-9 text-secondary">
                                {{count($packages)}}
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-3">
                                <h6 class="mb-0" style="float: right;">Customer:</h6>
                              </div>
                              <div class="col-sm-9 text-secondary">
                                {{$packages[0]->customer->name}}
                              </div>
                            </div>
                             <div class="row">
                    	       <div class="col-sm-3">
                    	         <h6 class="mb-0" style="float: right;">Date between: </h6>
                    	       </div>
                    	       <div class="col-sm-9 text-secondary">
                    	           <?php $f = explode('-', $from); $fromm = $f[2]. ' '.$months[(int)$f[1]].', '.$f[0]; ?>
                    	           <?php $t = explode('-', $to); $too = $t[2].' '.$months[(int)$t[1]].', '.$t[0]; ?>
                    	        	{{$fromm}} And {{$too}}
                    	         <div id="small"></div>
                    	       </div>
                    	     </div><br>
                          </div>
                        </div>
			
		    </div>
	    </div>
<br>

            <div class="row lustify-content-center">
                <div class="col">
		   <div class="card">
			<div class="card-header">
				<h4 class="text-primary text-uppercase font-weight-bold" style="float: left;">Search list</h4>

				<form action="{{route('main_pdf_shipments_search')}}" method="GET">
				@csrf
				<input type="hidden" name="from" value="{{$from}}">
				<input type="hidden" name="to" value="{{$to}}">
				<input type="hidden" name="type" value="{{$type}}">
				<button type="submit" class="btn btn-primary" style="float: right">Export As PDF</button>
				</form>
			</div>   
			
			    <div class="card-body">
				<div class="list-group">
				@if (count($packages) > 0)    
				<?php $k = count($packages);?>
				@foreach ($packages as $p)

				<a href="{{route('main_show_add_item', ['id' => $p->id])}}" class="list-group-item list-group-item-action border-dark">
					<span style="float: right">#{{$k}}</span>
					<h5>{{$p->tracking_id}}</h5> 
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
					<p style="margin:0"><b>Customer:</b> Muktar usman</p>
					<p style="margin:0"><b>Total Amount:</b> {{$p->total_amount}}</p>
					<p style="margin:0"><b>Items:</b> 
						@if (count($p->items) > 0)
						@foreach($p->items as $i)
						{{$i->name}}, 
						@endforeach
						@else
						No Item
						@endif
					</p>
					<div class="row" style="margin:0">
						<div class="col-md-6">
							<p style="margin:0"><b>From:</b></p>
							<p style="margin:0">{{$p->address_from}}, {{$p->from}}</p>
						</div>
						<div class="col-md-6">
							<p style="margin:0"><b>To:</b></p>
							<p style="margin:0">{{$p->address_to}}, {{$p->to}}</p>
						</div>
					</div>
					<p style="margin:0"><b>Created At: {{$p->created_at}}</b> </p>
					<hr>
				</a>
				<?php $k--; ?>
				@endforeach
				@else
				<a href="" class="list-group-item list-group-item-action border-dark"> No Shipment collections</a>
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
<script>
   $(document).ready(function() {

   })
</script>
@endsection
