@extends('admin.adminlayouts.master')
@section('title', '| Holidays')
@section('content')

<style>
.ui-timepicker-container {z-index: 9999999 !important}
</style>
<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">
         <h3 class="text-info">
         <i class="feather icon-star"></i> Manage Holidays
            <section class="float-right">
               <a href="/manageholidays/create"><button class="btn btn-outline-primary"><i class="feather icon-star"></i> Add Holiday</button></a>
            </section>
         </h3>
      </div>
      @include('inc.messages')
      <div class="card-block table-border-style">
         <div class="table-responsive">
            <table class="table table-hover table-striped" id="attendanceTable">
               <thead>
                  <tr>
                     <th>Holiday Name</th>
                     <th>Inclusive Dates</th>
                     <th>Remarks</th>
                     <th>Actions</th>
                     
                  </tr>
               </thead>
               <tbody>
                  @foreach($holidays as $at)
                  <tr>
                     <td>{{$at->holiday_name}}</td>
                     <td>{{$at->inclusive_dates}}</td>
                     <td><textarea style="width: 100%;" readonly>{{$at->remarks}}</textarea></td>
                        @csrf
                        <td>  
                        <a href="" id="editHoliday" data-toggle="modal" data-target='#edit_modal' data-id="{{ $at->id }}" class="btn-sm btn-warning"><i class="feather icon-edit"></i></span><span class="pcoded-mtext"> Edit</span></a>
                           <a href="" id="deleteHoliday" data-toggle="modal" data-target='#delete_modal' data-id="{{ $at->id }}" class="btn-sm btn-danger"><i class="feather icon-trash-2 "></i></span><span class="pcoded-mtext"> Delete</span></a>
                        </td>
                  </tr>

                  @endforeach
               </tbody>
            </table>

            
            @if ($holidays instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $holidays->render() }}
            @endif


           
         </div>
      </div>
   </div>
</div>

<!-- Modal for deleting of holiday-->
<div class="modal fade" id="delete_modal">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-body" id="delete_modal_body">
         </div>
      </div>
   </div>
</div>


<!-- Modal for edit of holiday-->
<div class="modal fade" id="edit_modal">
   <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
      <div class="modal-header panel-heading bg-info">
                     <h5 class="modal-title" style="color:white;"><i class="feather icon-edit"></i> Edit Holiday</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
         <div class="modal-body">       
               
                <form method="post" action="/editHoliday">
				@csrf
            <input type="hidden" name="holiday_id" id="holiday_id" value="">
                <div class="form-group">
				<label for="time_in_date">Holiday Name</label>
				<input class="form-control" type="text" value="" id="edit_holiday_name" name="edit_holiday_name" required>
				</div>


                <div class="form-group">
				<label for="time_in_date">Inclusive Dates (YYYY-MM-DD)</label>
				<input class="date form-control" type="text" value="" id="edit_inclusive_dates" name="edit_inclusive_dates" placeholder="YYYY-MM-DD" required>
				</div>

                <div class="form-group">
                <label for="time_in_time">Remarks</label>
                <textarea class="form-control" value="" id="edit_remarks" name="edit_remarks" required></textarea>
                
                </div>


                <button type="submit" class="btn btn-success col-md-4"><i class="feather icon-save"></i> Save</button>
				</form>

         </div>
         <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        
         </div>
      </div>
   </div>
</div>




<!-- Modal for Adding of holiday-->
<div class="modal fade" id="holiday_modal">
   <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
      <div class="modal-header panel-heading bg-info">
                     <h5 class="modal-title" style="color:white;"><i class="feather icon-star"></i> Add Holiday</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
         <div class="modal-body">
                <form method="post" action="{{action('HolidaysController@store')}}">
				@csrf
               

                <div class="form-group">
				<label for="time_in_date">Holiday Name</label>
				<input class="form-control" type="text" name="holiday_name" required>
				</div>


                <div class="form-group">
				<label for="time_in_date">Inclusive Dates (YYYY-MM-DD)</label>
				<input class="date form-control" type="text" name="inclusive_dates" placeholder="YYYY-MM-DD" required>
				</div>

                <div class="form-group">
                <label for="time_in_time">Remarks</label>
                <textarea class="form-control" name="remarks" required></textarea>
                
                </div>


                <button type="submit" class="btn btn-success col-md-4"><i class="feather icon-save"></i> Submit</button>
				</form>

         </div>
         <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        
         </div>
      </div>
   </div>
</div>

<!-- Script for Data table -->
<script>


   $('#attendanceTable').DataTable({
      paging: false,
      bInfo : false,
   });



   $('.timepicker').timepicker({
    timeFormat: 'H:i:s',
    defaultTime: 'now',
    dynamic: false,
    dropdown: false,
    scrollbar: false,
   
});


$('.date').datepicker({  
  format: 'yyyy-mm-dd',
multidate: true,
clearBtn: true,
orientation:'auto',
autoclose: false,
}); 
</script>

@endsection