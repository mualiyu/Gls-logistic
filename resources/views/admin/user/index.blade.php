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

                {{-- <div class="row">
                    <div class="col-md-12">

			<div class="card">
                            <div class="card-body">
				    <a href="{{route('register')}}" class="btn btn-secondary">Register New User</a>
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
                </div> --}}

		{{-- packages --}}
		<div class="row">
                    <div class="col-sm-12">
			     <a href="{{route('register')}}" class="btb btn-primary px-3 py-2" style="float: right;">Register New User</a>
                        <div class="white-box">
                            <h3 class="box-title">Users</h3>
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="border-top-0">#</th>
                                            <th class="border-top-0">Name</th>
                                            <th class="border-top-0">Phone</th>
                                            <th class="border-top-0">Email</th>
					    <th class="border-top-0">Staff ID</th>
					    <th class="border-top-0">Unit</th>
					    <th class="border-top-0">Role</th>
                                            <th class="border-top-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
					                    <?php $i = 1; ?>
					                    @foreach ($users as $u)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$u->name}}</td>
                                            <td>{{$u->phone}}</td>
                                            <td>{{$u->email}}</td>
					    <td>{{$u->staff_id}}</td>
					    <td>{{$u->unit_location}}</td>
					    <td>
					            @if ($u->p == 1)
					        	<span class="btn btn-warning">Admin</span>
						    @else
						   	<span class="btn btn-success">Regular Agents</span>
						    @endif
					            {{-- @if ($p->status == 1)
					            @endif
					            @if ($p->status == 2)
					        	<span class="btn btn-secondary">Deliverd</span>
					            @endif --}}
					    </td>
                                            <td>
						    <span style="cursor: pointer; border:black 1px solid; padding:5px;" id="" onclick="alert('test{{$i}}');"><i class="fa fa-eye" aria-hidden="true"></i></span>
						 	&nbsp;   
						    <span style="cursor: pointer; border:black 1px solid; padding:5px;"><i class="fa fa-trash" aria-hidden="true"></i></span>
						    {{-- <a href="{{route('admin_package_info', ['id' => $u->id])}}" class="btn btn-primary">View Profile</a> --}}
					    </td>
                                        </tr>
					                    <?php $i++; ?>
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

