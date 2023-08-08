{{-- \resources\views\permissions\index.blade.php --}}
@extends('admin.adminlayouts.master')
@section('title', '| Permissions')
@section('content')


<div class="table-responsive">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h3 class="text-info"><i class="fa fa-key"></i>Available Permissions
                <section class="float-right">
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-success pull-right"><i class="feather icon-settings"></i>Roles</a>
                    <a href="{{ route('permissions.create') }}" class="btn btn-outline-secondary pull-right"><i class="feather icon-shield"></i>Add Permission</a>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-primary"><i class="feather icon-user-plus"></i>Users</a>
                </section>
                </h3>
             </div>
            <div class="card-block table-border-style">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="permissionsTable">
                         <thead>
                <tr>
                    <th>Permissions</th>
                   <!-- <th>Operation</th> -->
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $permission)
                <tr>
                    <td>{{ $permission->name }}</td>
                    <!--
                    <td class="table-form">
                        <a href="{{ URL::to('permissions/'.$permission->id.'/edit') }}" class="btn-sm btn-info pull-left" style="margin-right: 3px;"><i class="feather icon-edit"></i></span><span class="pcoded-mtext"> Edit</span></a>
                        {!! Form::open(['method' => 'DELETE', 'route' => ['permissions.destroy', $permission->id] ]) !!}
                        <button type="submit" style="padding:3px;border:none;cursor:pointer;border-radius:4px;margin-right:5px;" class="btn-xsm btn-danger "><i class="feather icon-trash-2 "></i></span><span class="pcoded-mtext"> Delete</span></button>
                    </td>
                    -->
                </tr>
                @endforeach
            </tbody>
                    </table>
                    {{$permissions->render()}}
                </div>
            </div>
        </div>
    </div>
    <!-- [ Hover-table ] start -->
    
    <!-- [ Hover-table ] end -->
    
    <script>
     // $('#permissionsTable').DataTable();

      $('#permissionsTable').DataTable({
      paging: false,
      bInfo : false,
   });

    </script>

    @endsection