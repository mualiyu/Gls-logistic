@extends('layouts.Admin.index')


@section('content')
            <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Packages</h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <div class="d-md-flex">
                            <ol class="breadcrumb ms-auto">
                                <li><a href="#" class="fw-normal">Package</a></li>
                            </ol>
                           
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-12">
                        {{-- <div class="white-box">
                            <h3 class="box-title">Blank Page</h3>
                        </div> --}}

			<div class="card">
                            <div class="card-body">
                                <form class="form-horizontal form-material">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Search</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" id="search" name="search" placeholder="Tracking number, from, to" class="form-control p-0 border-0">
					</div>
                                    </div>

				</form>
			    </div>
			</div>

                    </div>
                </div>

		{{-- searche packages --}}
		<div class="row" id="search_t" style="display: none;">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title">Searched Info</h3>
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="border-top-0">#</th>
                                            <th class="border-top-0">Tracking NO</th>
                                            <th class="border-top-0">Item type</th>
                                            <th class="border-top-0">Total Amount</th>
					                        <th class="border-top-0">Status</th>
                                            <th class="border-top-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


		{{-- packages --}}
		<div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title">Packages</h3>
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="border-top-0">#</th>
                                            <th class="border-top-0">Tracking NO</th>
                                            <th class="border-top-0">Item type</th>
                                            <th class="border-top-0">Total Amount</th>
					                        <th class="border-top-0">Status</th>
                                            <th class="border-top-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
					    <?php $i = count($packages); ?>
					@foreach ($packages as $p)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$p->tracking_id}}</td>
                                            <td>{{$p->item_type}} </td>
                                            <td>{{$p->total_amount/100}}</td>
					    <td>
						    @if ($p->status == 0)
							<span class="btn btn-warning">Inactive</span>
						    @endif
						    @if ($p->status == 1)
							<span class="btn btn-success">Active</span>
						    @endif
						    @if ($p->status == 2)
							<span class="btn btn-warning">Deliverd</span>
						    @endif
					    </td>
                                            <td>
						    <a href="{{route('admin_package_info', ['id' => $p->id])}}" class="btn btn-primary">Open Package</a>
					    </td>
                                        </tr>
					<?php $i--; ?>
					@endforeach
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
@endsection

@section('script')
<script type="text/javascript">
$(document).ready(function () {
             
        $('#search').on('keyup',function() {
            var query = $(this).val(); 
            $.ajax({
               
                url:"{{ route('admin_package_search') }}",
          
                type:"GET",
               
                data:{'search':query},
               
                success:function (data) {
                  
                    $('#search_t').css('display', 'block');
		    $('#tbody').html(data);
		//     console.log(data);
                }
            })
            // end of ajax call
        });

        // $('#submit').on('click',function() {
        //     console.log(val);
        // });
        
    });
    
    function generate_t_sumary() {
        var val = [];
        $('input[type=checkbox]:checked').each(function(i){
          val[i] = $(this).val();
        });

        $('#card_form').addClass("card-load");
        $('#card_form').append('<div class="card-loader"><i class="fa fa-spinner rotate-refresh"></div>');

    
}

</script>
@endsection

