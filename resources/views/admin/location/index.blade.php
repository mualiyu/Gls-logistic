@extends('layouts.Admin.index')

@section('content')
            <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">locations</h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <div class="d-md-flex">
                            <ol class="breadcrumb ms-auto">
                                <li><a href="#" class="fw-normal">locations</a></li>
                            </ol>
                           
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            @include('layouts.flash')
            <div class="container-fluid">

		{{-- searche packages --}}
		<div class="row" id="search_t" style="display: ;">
                    <div class="col-sm-12">
                        <a href="{{route('admin_create_location')}}" class="btb btn-primary px-3 py-2" style="float: right;">Create location</a>
                        <div class="white-box">
                            <h3 class="box-title">Location List</h3>
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="border-top-0">#</th>
                                            <th class="border-top-0">Region</th>
                                            <th class="border-top-0">City</th>
                                            <th class="border-top-0">Location</th>
					    {{-- <th class="border-top-0">Zone</th> --}}
					    <th class="border-top-0">COUT</th>
                        <th class="border-top-0">Designation</th>
                                            <th class="border-top-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                         <?php $i = count($locations); ?>
					@foreach ($locations as $l)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$l->region}}</td>
                                            <td>{{$l->city}}</td>
                                            <td>{{$l->location}}</td>
					    {{-- <td>{{$l->zone}}</td> --}}
					    <td>FCFA {{$l->charges[0]->amount}}</td>
                        <td>{{$l->type==1? 'Depart':''}}{{$l->type==2? 'Arrive':''}}{{$l->type==3? 'Depart & Arrive':''}}</td>
                                            <td>
						    <a href="{{route('admin_show_location', ['id' => $l->id])}}" class="btn btn-primary">Open Location</a>
					    </td>
                                        </tr>
					<?php $i--; ?>
					@endforeach
                    @if (!count($locations)>0)
                        <tr>
                            <td colspan="7" style="text-align: center">No Location in system</td>
                        </tr>
                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
@endsection

@section('script')

@endsection

