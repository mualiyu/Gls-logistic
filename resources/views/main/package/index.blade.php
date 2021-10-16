@extends('layouts.main.index')

@section('content')

    <!-- package Start -->
    <div class="container">
        {{-- <div class="container"> --}}
            @include('layouts.flash')
            <div class="row lustify-content-center">
                <div class="col">
		   <div class="card">
			<div class="card-header">
				<h4 class="text-primary text-uppercase font-weight-bold">Shipments list</h4>
			</div>   
			
			    <div class="card-body">
				<div class="list-group">
				@if (count($packages) > 0)    
				<?php $k = count($packages);?>
				@foreach ($packages as $p)
				<?php $f = \App\Models\Region::where('code', '=', $p->from)->get(); ?>
				<?php $t = \App\Models\Region::where('code', '=', $p->to)->get(); ?>
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
							<p style="margin:0">{{$p->address_from}}, {{$f[0]->state}}</p>
						</div>
						<div class="col-md-6">
							<p style="margin:0"><b>To:</b></p>
							<p style="margin:0">{{$p->address_to}}, {{$t[0]->state}}</p>
						</div>
					</div>
					<p style="margin:0"><b>Created At: {{$p->created_at}}</b> </p>
					<hr>
				</a>
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
<script>
   $(document).ready(function() {

   })
</script>
@endsection
