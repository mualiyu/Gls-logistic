@extends("layouts.main.index")

@section("content")


<!-- ##### Hero Area Start ##### -->
    <section class="hero-area" style="background:#d7d7d8;">
        <div class="hero-slides owl-carousel">
            <!-- Single Hero Slide -->
            <div class="single-hero-slide bg-img h-110" style="background-image: url(main2/img/bg1.jpg);">
                <div class="container h-100">
                    <div class="row h-100 align-items-center">
                        <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                            <div class="hero-slides-content" data-animation="fadeInUp" data-delay="100ms">
                                <h2 data-animation="fadeInUp" data-delay="300ms">Track Package</h2>
				@include('layouts.flash')
                		<form action="{{route('main_get_track_info')}}" method="POST">
							@csrf
                		<div class="input-group">
                		    <input type="text" name="track" value="{{old('track')}}" class="form-control border-light" style="padding: ;" placeholder="Tracking Number">
                		    <div class="input-group-append">
                		        <button class="btn btn-primary ">Track</button>
                		    </div>
                		</div>
                		</form>
                		@error('track')
				    <p class="help-block text-danger">{{$message}}</p>
				@enderror
                                {{-- <p data-animation="fadeInUp" data-delay="700ms">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras tristique nisl vitae luctus sollicitudin. Fusce consectetur sem eget dui tristique, ac posuere arcu varius.</p>
                                <a href="/products" class="btn delicious-btn" data-animation="fadeInUp" data-delay="1000ms">See Receipe</a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- ##### Hero Area End ##### -->

    <br>

    <!-- ##### Quote Subscribe Area Start ##### -->
    <section class="quote-subscribe-adds" style="">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-12 col-lg-4">
                    <a href="">
                        <div class="card">
                            <div class="card-body">
                                <img src="{{asset('main2/img/air.jpg')}}" alt="" style="height: 200px; width:100%; position: relative;"><br>
                                <br><h5>AIR FREIGHT</h5>
                                <p>High level of security and reduced risk of theft and damage.Shipping by air offers the advantage of a high level of security</p>
                                <button class="btn btn-primary">Open</button>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-lg-4">
                    <a href="">
                        <div class="card">
                            <div class="card-body">
                                <img src="{{asset('main2/img/sea.jpeg')}}" alt="" style="height: 200px; width:100%; position: relative;"><br>
                                <br><h5>SEA FREIGHT</h5>
                                <p>Heavy and bulky items of shipment can be transported with ease through ocean freight without incurring enormous costs in transportation, as ocean freight rates are cheap.</p>
                                <button class="btn btn-primary">Open</button>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-lg-4">
                    <a href="">
                        <div class="card">
                            <div class="card-body">
                                <img src="{{asset('main2/img/land.png')}}" alt="" style="height: 200px; width:100%; position: relative;"><br>
                                <br><h5>SURFACE FREIGHT</h5>
                                <p>Road transport is best applicable for transporting goods to and from remote areas that are not connected by other means like rail, air or water transports</p>
                                <button class="btn btn-primary">Open</button>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
