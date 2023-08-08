{{-- \resources\views\roles\index.blade.php --}}
@extends('admin.adminlayouts.master')

@section('title', '| Roles')

@section('content')

<div class="table-responsive">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h3 class="text-info"><i class="fa fa-key"></i> Roles
                <section class="float-right">
                    <a href="{{ URL::to('roles/create') }}" class="btn btn-outline-success pull-right"><i class="feather icon-settings"></i>Add Role</a>
                    <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary pull-right"><i class="feather icon-shield"></i>Permission</a>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-primary"><i class="feather icon-users"></i>Users</a>
                </section>
                </h3>
      
            </div>
            <div class="card-block table-border-style">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="rolesTable">
                         <thead>
                <tr>
                    <th>Role</th>
                    <th>Permissions</th>
                   <!-- <th>Operation</th> -->
                </tr>
            </thead>

            <tbody>
                @foreach ($roles as $role)
                <tr>

                    <td>{{ $role->name }}</td>

                    <td>{{ str_replace(array('[',']','"'),'', $role->permissions()->pluck('name')) }}</td>{{-- Retrieve array of permissions associated to a role and convert to string --}}
                    <!-- <td class="table-form">
                    <a href="{{ URL::to('roles/'.$role->id.'/edit') }}" class="btn-sm btn-info"><i class="feather icon-edit"></i></span><span class="pcoded-mtext"> Edit</span></a>

                    {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id] ]) !!}
                   

                    <button type="submit" style="padding:4px;border:none;cursor:pointer;border-radius:4px;margin-right:5px;" class="btn-xsm btn-danger"><i class="feather icon-trash-2 "></i></span><span class="pcoded-mtext"> Delete</span></button>

                    {!! Form::close() !!}

                    </td> -->
                </tr>
                @endforeach
            </tbody>
                    </table>
                    {{$roles->render()}}
                </div>
            </div>
        </div>
    </div>
    <!-- [ Hover-table ] start -->
    
    <!-- [ Hover-table ] end -->
    
    <script>


      $('#rolesTable').DataTable({
      paging: false,
      bInfo : false,
   });
    </script>

    @endsection