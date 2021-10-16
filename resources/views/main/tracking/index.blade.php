@extends('layouts.main.index')


@section("content")
    <section class="" style="margin-bottom:50px; ">
        <div class="container">
            <div class="row justify-content-center">    
                <!-- Top Catagory Area -->
                <div class="col-md-12 col-sm-1o px-2 py-5" ">
                    {{-- <div class="single-top-catagory"> --}}
                        <div class="card">
                            <h4 class="text-primary text-uppercase font-weight-bold">Track</h4>
                            <div class="card-body">
                                @include('layouts.flash')
                                <form action="{{route('main_get_track_info')}}" method="POST">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" name="track" value="{{old('track')}}" class="form-control border-dark" style="padding: 30px;" placeholder="Tracking Number">
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
                </div>
                
            </div>
        </div>
    </section>

@endsection
