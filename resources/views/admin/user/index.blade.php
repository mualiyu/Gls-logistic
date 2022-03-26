@extends('layouts.Admin.index')


@section('content')
            <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Users</h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <div class="d-md-flex">
                            <ol class="breadcrumb ms-auto">
                                <li><a href="#" class="fw-normal">Users</a></li>
                            </ol>
                           
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="container-fluid">
                 @include('layouts.flash')

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
                                            {{-- <th class="border-top-0">Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
					                    <?php $i = 1; ?>
					                    @foreach ($users as $u)
                                        @if ($u->id == 1)
                                        @else
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
						                        <span style="cursor: pointer; border:black 1px solid; padding:5px;" id="" onclick="document.getElementById('modal[{{$u->id}}]').style.display = 'block';"><i class="fa fa-eye" aria-hidden="true"></i></span>
						                     	&nbsp;   
						                        <span style="cursor: pointer; border:black 1px solid; padding:5px;"
                                                    onclick="
                                                        if(confirm('Are you sure you want to delete {{$u->name}} ({{$u->email}})? ')){
                                                            document.getElementById('delete_form[{{$u->id}}]').submit();
                                                        }
                                                        event.preventDefault();"
                                                ><i class="fa fa-trash" aria-hidden="true"></i></span>
						                        <form action="{{route("admin_delete_user", ['id'=>$u->id])}}" id="delete_form[{{$u->id}}]" method="post">
                                                @csrf
                                                </form>
					                        </td>
                                        </tr>

                                        <div class="modal" style="display: none;" id="modal[{{$u->id}}]" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
						                  <div class="modal-dialog modal-lg modal-scrollable">
						                    <div class="modal-content">
						                      <div class="modal-header">
						                	<h5 class="modal-title" id="staticBackdropLabel">Profile Detail</h5>
						                	<button type="button" class="close" onclick="document.getElementById('modal[{{$u->id}}]').style.display = 'none';" aria-label="Close">
						                	  <span aria-hidden="true">&times;</span>
						                	</button>
						                      </div>
						                      <div class="modal-body" style="overflow-y: scroll; height:500px;">
                                                    <div class="card">
                                                        <form action="{{route("admin_update_user", ['id'=>$u->id])}}" method="post">
                                                      @csrf
                                                       @include('layouts.flash')
                                                      <div class="card-header pb-1">
                                                        <div class="d-flex align-items-center">
                                                          <p class="mb-0">Edit Profile</p>
                                                          <button class="btn btn-primary btn-sm ms-auto">Update</button>
                                                        </div>
                                                      </div>
                                                      <div class="card-body">
                                                        <p class="text-uppercase text-sm">User Information</p>
                                                        <div class="row">
                                                          <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="example-text-input" class="form-control-label">Full Name</label>
                                                              <input class="form-control" name="name" type="text" value="{{$u->name ?? ''}}" onfocus="focused(this)" onfocusout="defocused(this)">
                                                            </div>
                                                          </div>
                                                          <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="example-text-input" class="form-control-label">Email address</label>
                                                              <input class="form-control" name="email" type="email" value="{{$u->email ?? ''}}" onfocus="focused(this)" onfocusout="defocused(this)">
                                                            </div>
                                                          </div>
                                                          <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="example-text-input" class="form-control-label">Phone</label>
                                                              <input class="form-control" name="phone" type="number" value="{{$u->phone ?? ''}}" onfocus="focused(this)" onfocusout="defocused(this)">
                                                            </div>
                                                          </div>
                                                          <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="example-text-input" class="form-control-label">Role</label>
                                                              <select class="form-control" name="role" type="number" value="{{$u->phone ?? ''}}" required>
                                                                  @if ($u->p == 1)
                                                                      <option value="1">Admin</option>
                                                                  @endif
                                                                  @if ($u->p == 0)
                                                                      <option value="0">Regular Agent</option>
                                                                  @endif
                                                                  <option>select</option>
                                                                  <option value="0">Regular Agent</option>
                                                                  <option value="1">Admin</option>
                                                              </select>
                                                            </div>
                                                          </div>
                                                        </div>
                                                        <hr class="horizontal dark">
                                                        <p class="text-uppercase text-sm">Contact Information</p>
                                                        <div class="row">
                                                          <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="example-text-input" class="form-control-label">Unit</label>
                                                              <input class="form-control" name="unit_location" type="text" value="{{$u->unit_location ?? ''}}" onfocus="focused(this)" onfocusout="defocused(this)">
                                                            </div>
                                                          </div>
                                                          <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="example-text-input" class="form-control-label">Staff Id</label>
                                                              <input class="form-control" name="staff_id" type="text" value="{{$u->staff_id ?? ''}}" onfocus="focused(this)" onfocusout="defocused(this)">
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                        </form>
                                                    </div>
						                      </div>
						                    </div>
						                  </div>
						                </div>

					                    <?php $i++; ?>
                                        @endif
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

    var url_string = window.location;
    var url = new URL(url_string);
    var i = url.searchParams.get("i");
    document.getElementById('modal['+i+']').style.display = 'block';

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

