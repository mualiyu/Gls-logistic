@extends('layouts.main.index')

@section('content')
    <!-- Contact Start -->
    <section style="">
        <div class="container" >
            <div class="container">
                <a onclick="window.history.back()" id="back" class="pre">&laquo; Previous</a><br><br>
            <h4 class="text-primary text-uppercase font-weight-bold">Package Info</h4> 
                <div class="row">
                    <div class="col-lg-8 pb-4 pb-lg-0">
                        {{-- <div class="bg-primary text-dark text-center p-4">
                            <h4 class="m-0"><i class="fa fa-map-marker-alt text-white mr-2"></i>{{$tracking[count($tracking)-1]->current_location}}</h4>
                        </div>
                        <iframe style="width: 100%; height: 470px;"
                            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCpSikij-kMiF0hTxXQn9Vu4B2v2WxkR_E&q={{$tracking[count($tracking)-1]->current_location}},+cameroon"
                            frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe> --}}
                        <div class="card">
                            
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <span>Your Shipment</span>
                                        <p>{{$package[0]->tracking_id}}</p>
                                    </div>
                                    <div class="col-md-12">
                                        @if ($tracking[count($tracking)-1]->current_location == $package[0]->from)
                                            <span>Parcel is on process:</span>
                                        @endif
                                        @if ($tracking[count($tracking)-1]->current_location == $package[0]->to)
                                            <span>Delivered on</span>
                                            <p>{{$tracking[count($tracking)-1]->created_at}}</p>
                                        @endif
                                        @if ($tracking[count($tracking)-1]->current_location != $package[0]->from && $tracking[count($tracking)-1]->current_location != $package[0]->to)
                                            <span>Package is on process</span> &
                                            <span>Currently At:</span>
                                            <p>{{$tracking[count($tracking)-1]->current_location}}</p>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        @if ($tracking[count($tracking)-1]->current_location == $package[0]->from)
                                            <span>Currently At:</span>
                                            <p>{{$tracking[count($tracking)-1]->current_location}}</p>
                                        @endif
                                        @if ($tracking[count($tracking)-1]->current_location == $package[0]->to)
                                            <span>Delivered To</span>
                                            <p>{{$tracking[count($tracking)-1]->current_location}}</p>
                                        @endif
                                        @if ($tracking[count($tracking)-1]->current_location != $package[0]->from && $tracking[count($tracking)-1]->current_location != $package[0]->to)
                                            <span>{{$tracking[count($tracking)-1]->a_d==1 ? 'Departed From: ':''}}{{$tracking[count($tracking)-1]->a_d==2 ? 'Arrived At: ':''}}:</span>
                                            <p>{{$tracking[count($tracking)-1]->current_location}}</p>
                                        @endif
                                    </div>
                                    <div class="col-sm-6">
                                        @if ($tracking[count($tracking)-1]->current_location == $package[0]->from)
                                            <span>Received By :</span>
                                            <p>Nill</p>
                                        @endif
                                        @if ($tracking[count($tracking)-1]->current_location == $package[0]->to)
                                            <span>Received By:</span>
                                            <p>{{$package[0]->customer->name}}</p>
                                        @endif
                                        @if ($tracking[count($tracking)-1]->current_location != $package[0]->from && $tracking[count($tracking)-1]->current_location != $package[0]->to)
                                            <span>Confirmed By:</span>
                                            <p>Agent</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="" class="text-primary"  data-toggle="modal" data-target="#videoModal" >View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="col-lg-4">
                        {{-- <h1 class="mb-4">Contact For Any Queries</h1> --}}
                        <div class="card">
                            <div class="card-header">
                                <h6 class="text-primary text-uppercase font-weight-bold">Track Another parcel</h6>
                            </div>
                            <div class="card-body">
                                @include('layouts.flash')
                                <form action="{{route('main_get_track_info')}}" method="POST">
                                        @csrf
                                        <div class="input-group">
                                            <input type="text" name="track" value="{{old('track')}}" class="form-control border-dark" style="padding: ;" placeholder="Tracking Number">
                                            <div class="input-group-append border-dark">
                                                <button class="btn btn-primary ">Track</button>
                                            </div>
                                        </div>
                                    </form>
                                    @error('track')
                                        <p class="help-block text-danger">{{$message}}</p>
                                    @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="bg-primary text-dark text-center p-3">
                                    <h4 class="m-0"><i class="fa fa-map-marker-alt text-white mr-2"></i>{{$tracking[count($tracking)-1]->current_location}}</h4>
                                </div>
                                <iframe style="width: 100%; height: 250px;"
                                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCpSikij-kMiF0hTxXQn9Vu4B2v2WxkR_E&q={{$tracking[count($tracking)-1]->current_location}},+cameroon"
                                    frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                            </div>
                        </div>

                    </div>
                </div>
                
            </div>
        </div>
        {{-- modal Details --}}
        <div class="modal fade " id="videoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body px-4 py-3">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>        
                        <!-- 16:9 aspect ratio -->
                        <div class="row justify-centent-center px-4 py-3">
                            <h5>Shipment Progress</h5>
                        </div>
                        
                        <div class="row border-dark" style="border:black 1px solid; overflow-y:scroll; height:500px;">
                            <div class="col-md-11 px-3">
                                {{-- if sipment is not departed  show this--}}
                                @if ($tracking[count($tracking)-1]->current_location == $package[0]->from)
                                <div class="row px-4 py-3" style="margin: 0;">
                                    <div style="margin: 0;" class="col-sm-4">
                                        <?php 
                                        $time = explode(' ', $tracking[count($tracking)-1]->created_at);
                                        $d = explode('-', $time[0]);
                                        $day = $d[2].'/'.$d[1].'/'.$d[0];
                                        $h = explode(':', $time[1]);
                                        $hour = $h[0].':'.$h[1];
                                      ?>
                                        <span>{{$day}}</span><br>
                                        <span>{{$hour}}</span>
                                    </div>
                                    <div style="margin: 0;" class="col-sm-8">
                                        <p style="margin: 0;"><b>{{$tracking[count($tracking)-1]->a_d==1 ? 'Currently At: ':''}}{{$tracking[count($tracking)-1]->a_d==2 ? 'Arrived At: ':''}}</b></p>
                                        <p style="margin: 0;">{{$tracking[0]->current_location}}</p>
                                    </div>
                                </div><hr>
                                <div class="row px-4 py-3">
                                    <div class="col-sm-4">................</div>
                                    <div class="col-sm-8">.................</div>
                                </div><hr>
                                <div class="row px-4 py-3">
                                    <div class="col-sm-4">
                                        <span>--/--/----</span>
                                        <span>--:--</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <p style="margin: 0;">Final Destination:</b></p>
                                        <p style="margin: 0;">{{$package[0]->to}}</p>
                                    </div>
                                </div><hr>
                                @endif

                                {{-- if sipment Has Arrived  show this--}}
                                @if ($package[0]->status == 2)    
                                {{-- @if ($tracking[0]->current_location == $package[0]->to) --}}
                                    @foreach ($tracking as $t)
                                     <?php 
                                        $time = explode(' ', $t->created_at);
                                        $d = explode('-', $time[0]);
                                        $day = $d[2].'/'.$d[1].'/'.$d[0];
                                        $h = explode(':', $time[1]);
                                        $hour = $h[0].':'.$h[1];
                                      ?>
                                        <div class="row px-4 py-3" style="margin: 0;">
                                            <div style="margin: 0;" class="col-sm-4">
                                                <span>{{$day}}</span><br>
                                                <span>{{$hour}}</span>
                                            </div>
                                            <div style="margin: 0;" class="col-sm-8">
                                                <p style="margin: 0;"><b>{{$t->a_d==1 ? 'Departed From: ':''}}{{$t->a_d==2 ? 'Arrived At: ':''}}</b></p>
                                                <p style="margin: 0;">{{$t->current_location}}</p>
                                            </div>
                                        </div><hr>
                                    @endforeach
                                {{-- @endif --}}
                                @endif


                                {{-- if sipment is on process  show this--}}
                                @if ($tracking[count($tracking)-1]->current_location != $package[0]->from && $package[0]->status == 1)
                                    @foreach ($tracking as $t)
                                     <?php 
                                        $time = explode(' ', $t->created_at);
                                        $d = explode('-', $time[0]);
                                        $day = $d[2].'/'.$d[1].'/'.$d[0];
                                        $h = explode(':', $time[1]);
                                        $hour = $h[0].':'.$h[1];
                                      ?>
                                        <div class="row px-4 py-3" style="margin: 0;">
                                            <div style="margin: 0;" class="col-sm-4">
                                                <span>{{$day}}</span><br>
                                                <span>{{$hour}}</span>
                                            </div>
                                            <div style="margin: 0;" class="col-sm-8">
                                                <p style="margin: 0;"><b>{{$t->a_d==1 ? 'Departed From: ':''}}{{$t->a_d==2 ? 'Arrived At: ':''}}</b></p>
                                                <p style="margin: 0;">{{$t->current_location}}</p>
                                            </div>
                                        </div><hr>
                                    @endforeach
                                    
                                @endif

                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <button type="button" class=" btn btn-warning" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">Close</span>
                        </button> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
