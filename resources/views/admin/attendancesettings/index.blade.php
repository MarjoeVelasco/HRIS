@extends('admin.adminlayouts.master')
@section('title', '| Attendance System Settings')
@section('content')
<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">
         <h3 class="text-info">
         <i class="feather icon-settings"></i> Attendance Settings
         <section class="float-right">
            <a href="/hybrid-employee/create" class="btn btn-outline-primary"><i class="feather icon-users"></i>Add WFH Exceptions</a>
          </section>
         </h3>
      </div>
      @include('inc.messages')
      <div class="card-block table-border-style">
        <div class="card-block table-border-style">
          <div class="table-responsive">
            <h4 class="text-info">Time In/Time Out Options</h4>
            <table class="table table-hover table-striped" id="address_table">
              <thead>
                <tr>
                  <th >Settings</th>
                  <th> </th>
                  <th> </th>
                </tr>
               </thead>

              <tbody>
                @foreach($settings as $setting)
                <tr>
                  <td><span class="font-weight-bold text-uppercase">{{$setting->system_setting_name}}</span></td>
                  <td><span class="font-weight-bold text-success">{{$setting->system_setting_value}}</span></td>
                  <td><a href="/attendance-setting/{{ $setting->id }}/edit" class="btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Change"><span class="pcoded-micon"><i class="feather icon-edit"></i></span><span class="pcoded-mtext"> CHANGE</span></a></td>
                </tr>
                @endforeach
              </tbody>

            </table>


            <h4 class="text-info mt-5">WFH Exemptions</h4>
            <p>These employees are allowed to work from home regardless if onsite or hybrid</p>
            <table class="table table-hover table-striped" id="users_table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Position</th>
                  <th>Actions</th>
                </tr>
               </thead>

              <tbody>
                @foreach($users as $user)
                <tr>
                  <td><img src="{{$user->image}}" height="40px" class="rounded"/> <span class="font-weight-bold text-uppercase ml-3">{{$user->firstname}} {{$user->lastname}}</span></td>
                  <td class="align-middle"><span>{{$user->position}}</span></td>
                  <td class="align-middle">

                  <form action="/hybrid-employee/{{$user->id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="submit" style="cursor:pointer" class="btn-xsm btn-danger rounded" data-toggle="tooltip" data-placement="top"><i class="feather icon-trash"></i> Remove</button>               
                  </form>

                  </td>
                </tr>
                @endforeach
              </tbody>

            </table>
          </div>
        </div>
      </div>
   </div>
</div>



<script>
    //  $('#admin_leaves').DataTable();

      $('#address_table').DataTable({
      paging: false,
      bInfo : false,
      "searching": false
   });

   $('#users_table').DataTable({
      paging: false,
      bInfo : false,
      "searching": false
   });

</script>


@endsection