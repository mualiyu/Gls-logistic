@extends('layouts.Admin.index')


@section('content')
            <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Merchandise</h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <div class="d-md-flex">
                            <ol class="breadcrumb ms-auto">
                                <li><a href="#" class="fw-normal">merchandises</a></li>
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
                        <a href="{{route('admin_create_merchandise')}}" class="btb btn-primary px-3 py-2" style="float: right;">Add Merchandise</a>
                        <div class="white-box">
                            <h3 class="box-title">merchandice List</h3>
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="border-top-0">#</th>
                                            <th class="border-top-0">Type</th>
                                            <th class="border-top-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                         <?php $i = count($merchandises); ?>
					@foreach ($merchandises as $m)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$m->type}}</td>
                                            <td>
						    <a href="{{route('admin_show_merchandise', ['id' => $m->id])}}" class="btn btn-primary">Open Merchandise</a>
					    </td>
                                        </tr>
					<?php $i--; ?>
					@endforeach
                    @if (!count($merchandises)>0)
                        <tr>
                            <td colspan="7" style="text-align: center">No Merchandise in system</td>
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

