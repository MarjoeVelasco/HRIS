@extends('admin.adminlayouts.master')
@section('title', '| Leave Card')
@section('content')
<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">
         <h3 class="text-info">
            <i class="fa fa-clipboard"></i> Leave Credits (as of November 13, 2022)
            <section class="float-right">
            <button href="" data-toggle="modal" data-target='#addcredits_modal' class="btn btn-outline-primary"><i class="feather icon-plus"></i>Add</button>
            </section>
         </h3>
      </div>
      @include('inc.messages')
      <div class="card-block table-border-style">
         <div class="table-responsive">
            <table class="table table-hover table-striped" id="leave_card_table">
                <thead>
                  <tr>
                     <th>Name</th>
                     <th>Vacation Leave</th>
                     <th>Sick Leave</th>
                     <th>CTO</th>
                  </tr>
                </thead>
                <tbody>
                @foreach($leave_cards as $leave)
                    <tr>
                        <td>
                        {{$leave->firstname}} {{$leave->lastname}}
                        </td>

                        <td>
                        {{$leave->total_vl}} 
                        </td>

                        <td>
                        {{$leave->total_sl}}
                        </td>

                        <td>
                        {{$leave->hours_earned}}
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
            
         </div>
      </div>
   </div>
</div>



<!-- Modal for Adding of Attendance-->
<div class="modal fade" id="addcredits_modal">
<div class="modal-dialog modal-dialog-centered">
   <!-- Modal content-->
   <div class="modal-content">
      <div class="modal-header panel-heading bg-info">
         <h5 class="modal-title" style="color:white;"><i class="feather icon-calendar"></i> Add</h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span>
         </button>
      </div>
      <div class="modal-body">

        
         <!-- start FORM -->
         <form method="post" action="{{action('LeaveCardController@store')}}">
            @csrf
              <div class="form-group">
               <label for="work_setting">Select Employe</label>

               <select class="form-control" name="user_id" required>
                    @foreach($employees as $employee)
                        <option value="{{$employee->id}}">{{$employee->firstname}} {{$employee->lastname}}</option>
                    @endforeach
               </select>
            </div>

            <div class="form-group">
                <label>Vacation Leave</label>
                <input type="text" name="enter_vl" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Sick Leave</label>
                <input type="text" name="enter_sl" class="form-control" required>
            </div>

            <div class="form-group">
                <label>CTO/ILC Credits</label>
                <input type="text" name="enter_cto" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success col-md-4"><i class="feather icon-save"></i> Submit</button>
         </form>
         <!-- end FORM-->
      </div>
   </div>
</div>


<script>
    //  $('#admin_leaves').DataTable();

      $('#leave_card_table').DataTable({
      paging: false,
      bInfo : false,
      "order": [ 0, 'desc' ], 
   });

</script>

@endsection