@extends('layouts.main.index')

@section('content')
    <!-- Header Start -->
    <div class="jumbotron jumbotron-fluid mb-2">
        <div class="container text-center py-1">
            <h1 class="text-white display-3">Track</h1>
            <div class="d-inline-flex align-items-center text-white">
                <p class="m-0"><a class="text-white" href="">Home</a></p>
                <i class="fa fa-circle px-3"></i>
                <p class="m-0">Tracking</p>
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
                    <h4 class="text-primary text-uppercase font-weight-bold">Track</h4>
                    {{-- <h1 class="mb-4">Contact For Any Queries</h1> --}}
                    <div class="contact-form bg-secondary" style="padding: 20px;">
			<div class="mx-auto" style="width: 100%; max-width: 600px;">
				<form action="{{route('main_get_track_info')}}" method="POST">
					@csrf
					<div class="input-group">
					    <input type="text" name="track" value="{{old('track')}}" class="form-control border-light" style="padding: 30px;" placeholder="Tracking Number">
					    <div class="input-group-append">
						    <button class="btn btn-primary px-3">Track</button>
						</div>
					</div>
				</form>
				@error('track')
				    <p class="help-block text-danger">{{$message}}</p>
				@enderror
			</div>
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
