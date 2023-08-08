{{-- \resources\views\roles\index.blade.php --}}
@extends('admin.adminlayouts.master')

@section('title', '| Special Attendance')

@section('content')

<div class="table-responsive">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h3 class="text-info"><i class="fa fa-key"></i> Special Attendance
                  <section class="float-right">
                      <a href="{{ URL::to('roles/create') }}" class="btn btn-outline-success pull-right"><i class="feather icon-settings"></i>Add Role</a>
                      <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary pull-right"><i class="feather icon-shield"></i>Permission</a>
                      <a href="{{ route('users.index') }}" class="btn btn-outline-primary"><i class="feather icon-users"></i>Users</a>
                  </section>
                </h3>
      
            </div>

            <div class="card-block table-border-style">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                      <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Attachment</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                      </thead>
                      <tbody>
               
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
  <script>
    $('.table').DataTable({
      paging: false,
      bInfo : false,
   });
  </script>

@endsection