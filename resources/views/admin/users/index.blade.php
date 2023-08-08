{{-- \resources\views\users\index.blade.php --}}
@extends('admin.adminlayouts.master')
@section('title', '| Users')
@section('content')
<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
  
  
  
      <div class="card-header">
         <h3 class="text-info">
         <i class="feather icon-users"></i> User Administration
            <section class="float-right">
               <a href="{{ route('roles.index') }}" class="btn btn-outline-success pull-right"><i class="feather icon-settings"></i><span class="pcoded-mtext"> Roles</span></a>
               <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary pull-right"><i class="feather icon-shield"></i><span class="pcoded-mtext"> Permissions</span></a>
               <a href="{{ route('users.create') }}" class="btn btn-outline-primary"><i class="feather icon-user-plus"></i><span class="pcoded-mtext"> Add User</span></a>
            </section>
         </h3>
        
      </div>
      @include('inc.messages')
      <div class="card-block table-border-style">
         <div class="table-responsive">
            <table class="table table-hover table-striped" id="userTable">
               <thead>
                  <tr>
                     <th>Display Name</th>
                     <th>Email</th>
                     <th>User Roles</th>
                     <th>Operations</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach ($users as $user)
                  <tr>
                     <td>{{ $user->name }}</td>
                     <td>{{ $user->email }}</td>
                     <td>{{  $user->roles()->pluck('name')->implode(' ') }}</td>
                     {{-- Retrieve array of roles associated to a user and convert to string --}}
                     <td class="table-form">
                        <a href="" id="showUser" data-toggle="modal" data-target='#user_modal' data-id="{{ $user->id }}" class="btn-sm btn-info"><span class="pcoded-micon"><i class="feather icon-eye"></i></span><span class="pcoded-mtext"> View</span></a>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn-sm btn-success"><span class="pcoded-micon"><i class="feather icon-edit"></i></span><span class="pcoded-mtext"> Edit</span></a>
                        <!-- {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id] ]) !!} -->
                        <!-- {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!} -->
                        <!-- {!! Form::close() !!} -->

                        @if($user->is_disabled==0)   
                        <a href="" id="disableUser" data-toggle="modal" data-target='#delete_modal' data-id="{{ $user->id }}" class="btn-sm btn-danger"><span class="pcoded-micon"><i class="feather icon-x-circle"></i></span><span class="pcoded-mtext"> Disable</span></a>
                        <a href="" id="resetPassword" data-toggle="modal" data-target='#resetpassword_modal' data-id="{{ $user->id }}" class="btn-sm btn-warning"><span class="pcoded-micon"><i class="feather icon-refresh-ccw"></i></span><span class="pcoded-mtext"> Reset Password</span></a>
                        @else
                        <a href="" id="enableUser" data-toggle="modal" data-target='#delete_modal' data-id="{{ $user->id }}" class="btn-sm btn-primary"><span class="pcoded-micon"><i class="feather icon-trash-2"></i></span><span class="pcoded-mtext"> Enable</span></a>
                        @endif
                       
                     </td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
           

            @if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $users->links() }}
            @endif

         </div>
      </div>
   </div>
</div>
<!-- [ Hover-table ] start -->
<!-- [ Hover-table ] end -->
@if($message=Session::get('flash_message'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
   <p>{{$message}}</p>
   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
   <span aria-hidden="true">&times;</span>
   </button>
</div>
@endif
@if($message=Session::get('found'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
   <p>{{$message}}</p>
   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
   <span aria-hidden="true">&times;</span>
   </button>
</div>
@endif

<div class="modal fade" id="user_modal">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-body" id="user_modal_body">
         </div>
      </div>
      </form>
   </div>
</div>

<div class="modal fade" id="delete_modal">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-body" id="modal_body">
         </div>
      </div>
   </div>
</div>


<div class="modal fade" id="resetpassword_modal">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
      <div class="modal-header panel-heading bg-info">
                     <h5 class="modal-title" style="color:white;">Reset Password</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
         <div class="modal-body">
            <form method="post" action="{{ url('/resetpassword') }}" id="reset_password_form">
               @csrf
               <input type="hidden" id="employee_id_reset" name="id" value="">
               <span class="text-danger">
               <strong id="error-reset-password"></strong>
               </span>
               <div class="form-group">
                  <label for="password">New Password</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password" required>
               </div>
               <div class="form-group">
                  <label for="password-confirm">Confirm Password</label>
                  <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
               </div>
               <center><input type="submit" value="Reset" id="reset" class="btn btn-primary"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button></center>
            </form>
         </div>
      </div>
   </div>
</div>

<script>
      $('#userTable').DataTable({
      paging: false,
      bInfo : false,
   });
</script>


@endsection