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
            </div>
        </div>
    </div>
    <!-- Header End -->


    <!-- package Start -->
    <div class="container-fluid py-5">
        {{-- <div class="container"> --}}
            @include('layouts.flash')
            <div class="row">
		    <div class="col-md-1"></div>
                <div class="col">
                    <h4 class="text-primary text-uppercase font-weight-bold">Packages</h4>
                    {{-- <h1 class="mb-4">Contact For Any Queries</h1> --}}
                    <div class="contact-form bg-secondary" style="padding: 20px;">
                        <table class="table">
  			  <thead>
  			    <tr>
			      <th>#</th>
  			      <th>Tracking ID</th>
  			      <th>From</th>
  			      <th>To</th>
			      <th>NO of Items</th>
			      <th>Total Amount</th>
			      <th>Status</th>
			      <th>Action</th>
  			    </tr>
  			  </thead>
  			  <tbody>
			    <?php $i = 1;?>
			    @foreach ($packages as $p)
			    <tr>
			      <td>{{$i}}</td>
			      <td>{{$p->tracking_id}}</td>
			      <?php $f = \App\Models\Region::where('code', '=', $p->from)->get(); ?>
			      <td>{{$p->address_from}}, {{$f[0]->state}}</td>
			      <?php $t = \App\Models\Region::where('code', '=', $p->to)->get(); ?>
			      <td>{{$p->address_to}}, {{$t[0]->state}}</td>
			      <td>{{count($p->items)}}</td>
			      <td>{{$p->total_amount}}</td>
			      <td>
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
			      </td>
			      <td><a href="{{route('main_show_add_item', ['id'=> $p->id])}}" class="btn btn-primary">Open</a></td>
			    </tr>
			    <?php $i++; ?>
			    @endforeach
  			  </tbody>
  			</table>
                    </div>
                </div>
		<div class="col-md-1"></div>
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
