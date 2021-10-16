@extends("layouts.main.index")

@section("content")


<!-- ##### Hero Area Start ##### -->
    <section class="hero-area" style="background:#d7d7d8;">
        <div class="hero-slides owl-carousel">
            <!-- Single Hero Slide -->
            <div class="single-hero-slide bg-img h-110" style="background-image: url(main2/img/core-img/header.jpg);">
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


    <!-- ##### Quote Subscribe Area Start ##### -->
    <br>
    {{-- <section class="quote-subscribe-adds">
        <div class="container">
            <div class="row align-items-end">
                <!-- Quote -->
                <div class="col-12 col-lg-4">
                    <div class="quote-area text-center">
                        <span>"</span>
                        <h4>Nothing is better than going home to family and eating good food and relaxing</h4>
                        <p>John Smith</p>
                        <div class="date-comments d-flex justify-content-between">
                            <div class="date">January 04, 2018</div>
                            <div class="comments">2 Comments</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section> --}}
    <!-- ##### Quote Subscribe Area End ##### -->


@endsection
