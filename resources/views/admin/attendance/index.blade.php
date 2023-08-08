@extends('admin.adminlayouts.master')
@section('title', '| Attendances (General)')
@section('content')

<style>
.ui-timepicker-container {z-index: 9999999 !important}
</style>
<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">
         <h3 class="text-info">
         <i class="feather icon-calendar"></i> Manage Attendance (General)
            <section class="float-right">
               <button href="" data-toggle="modal" data-target='#addattendance_modal' class="btn btn-outline-primary"><i class="feather icon-calendar"></i>Add Attendance</button>
               <button href="" data-toggle="modal" data-target='#autoabsent_modal' class="btn btn-outline-warning"><i class="feather icon-user-x"></i>Add absences</button>
            </section>
         </h3>
      </div>
      @include('inc.messages')
      <div class="card-block table-border-style">

     
                            
                         
                            {{ Form::open(array('url' => 'manageattendance/filter', 'method' => 'post')) }}
                            
                           
                            
                            <div class="form-row">
                            <div class="form-group col-md-3">
                                        <label> Start date</label>
                                        
                                        <input class="form-control" type="date" name="from" required="">
                             </div>       
                                   
                             <div class="form-group col-md-3">           
                                        <label> End date</label>
                                        
                                        <input class="form-control" type="date" name="to" required="">
                              </div>   
                            
                              <div class="form-group col-md-5">   
                                     
                                        <button class="btn btn-outline-success" style="margin-top:30px;" type="submit" name="action" value="retrieve"><span class="pcoded-micon"><i class="feather icon-file"></i></span><span class="pcoded-mtext"> Retrieve records</span></button>
                                        <a href="/manageattendance"><button class="btn btn-outline-primary" style="margin-top:30px;" type="button"><span class="pcoded-micon"><i class="feather icon-file"></i></span><span class="pcoded-mtext"> Reset</span></button></a>
                                        </div>   
                           </div>
                                    
                               
                              {{ Form::close() }}
                           
                    
         <div class="table-responsive">
            <table class="table table-hover table-striped" id="attendanceTable">
               <thead>
                  <tr>
                     <th>Name</th>
                     <th>Date</th>
                     <th>Status</th>
                     <th>Time Status</th>
                     <th>Work Setting</th>
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($attendance as $at)
                  <tr>
                     <td>{{$at->name}}</td>
                     <td>{{date('d-m-Y',strtotime($at->time_in))}}</td>
                     <td>{{$at->status}}</td>
                     <td>{{$at->time_status}}</td>

                     <!-- SWITCH WORK SETTINGS-->
                     @if($at->wstatus!=null)
                        @if($at->id != auth()->user()->id)
                        <td><a href="switch-work-setting/{{$at->attendance_id}}" class="btn-sm btn-success"><i class="feather icon-refresh-cw "></i></a> <span class="label label-default text-uppercase font-weight-bold">{{$at->wstatus}}</span> </td>
                        @else
                        <td><span class="label label-default text-uppercase font-weight-bold">{{$at->wstatus}}</span></td>
                        @endif
                     
                     @else
                        <td>
                        @if($at->status =='present')  
                        
                        <div class="row">

                        <a href="assign-work-setting/{{$at->attendance_id}}/work from home"><button style="cursor:pointer;" type="button" class="rounded btn-outline-primary btn-xsm mr-2"><span class="pcoded-mtext">WFH</span></button></a>
                        

                        <a href="assign-work-setting/{{$at->attendance_id}}/in office"><button style="cursor:pointer;" type="button" class="rounded btn-outline-dark btn-xsm"><span class="pcoded-mtext">In Office</span></button></a>
                        
                        </div>

                        @else

                        @endif

                        </td> 
                     
                     @endif

                     @if($at->id != auth()->user()->id)                  
                     <form method="post" action="{{action('ManageAttendanceController@update',$at->attendance_id)}}">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <td>

                        @if($at->status =='present')        
                        <button type="submit" style="padding:4px;border:none;cursor:pointer;border-radius:4px;margin-right:5px;" name="update" class="btn-xsm btn-warning " value="absent"><i class="feather icon-x-circle "></i></span><span class="pcoded-mtext"> Mark Absent</span></button>
                        @endif
                        
                        @if($at->status =='absent')        
                        <a href="" id="presentAttendance" data-toggle="modal" data-target='#present_modal' data-id="{{ $at->attendance_id }}" class="btn-sm btn-info"><i class="feather icon-check-circle "></i></span><span class="pcoded-mtext"> Mark Present</span></a>
                        @endif
                        
                        
                        
                        <a href="" id="deleteAttendance" data-toggle="modal" data-target='#delete_modal' data-id="{{ $at->attendance_id }}" class="btn-sm btn-danger"><i class="feather icon-trash-2 "></i></span><span class="pcoded-mtext"> Delete</span></a>
                        </td>
                     </form>
                     @else
                     <td>
                     </td>
                     @endif
                  </tr>

                  @endforeach
               </tbody>
            </table>

            @if ($attendance instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $attendance->links() }}
            @endif
           
         </div>
      </div>
   </div>
</div>
<!-- Modal for deleting of attendance-->
<div class="modal fade" id="delete_modal">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-body" id="delete_modal_body">
         </div>
      </div>
   </div>
</div>

<!-- Modal for marking of attendance to present-->
<div class="modal fade" id="present_modal">
   <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
      <div class="modal-header panel-heading bg-info">
                     <h5 class="modal-title" style="color:white;"><i class="feather icon-check-circle "></i> Mark as Present</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
         <div class="modal-body">
          
            
                <!-- start FORM FOR SETTING OF ATTENDANCE AS PRESENT -->
                <form method="post" action="/markpresent">
				   @csrf
               <input type="hidden" value="" name="present_attendance_id" id="present_attendance_id">

                
                <div class="form-group">
                <label for="time_in_time">Time in time (HH:MM:SS) 24 Hours Format</label>
                <input type="text" class="timepicker form-control" name="time_in_time" required>
                </div>

                <div class="form-group">
                <label for="time_out_time">Time out time (HH:MM:SS) 24 Hours Format</label>
                <input type="text" class="timepicker form-control" name="time_out_time" required>
                </div>

               <!--work setting -->
               <div class="form-group">
					<label for="work_setting">Select Work Setting</label>
					<select class="form-control" name="work_setting" required> 
               <option value="work from home">Work From Home</option>
               <option value="in office">In Office</option>                  
					</select>
   				</div>
               <!--end work setting -->

      
                <div class="form-group">
                <label>Accomplishment</label>
                <textarea rows="10" name="accomplishment" class="form-control" required></textarea>
                </div>

               

                <!-- end form for time in -->
         </div>
         <div class="modal-footer">
         <button type="submit" class="btn btn-success col-md-4"><i class="feather icon-save"></i> Submit</button>
				</form>
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        
         </div>
      </div>
   </div>
</div>



<!-- Modal for absent of Attendance-->
<div class="modal fade" id="autoabsent_modal">
   <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
      <div class="modal-header panel-heading bg-info">
                     <h5 class="modal-title" style="color:white;"><i class="feather icon-user-x"></i> Add absences</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
         <div class="modal-body">
               

                <!-- start FORM FOR absent -->
                <form method="get" action="/autoabsent" id="">
				@csrf
            <div class="form-group">
            <label for="employee">Select employee</label>
					<select class="form-control" name="employee" required> 
						<option disabled selected value>-- Choose here --</option>
                  <optgroup label="Office of the Executive Director (OED)">
                                        @if(isset($oed))
                                        @foreach($oed as $user)

                                                <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                                    
                                        @endforeach
                                        @endif
                                        </optgroup>

                                        <optgroup label="Employment Research Division (ERD)">
                                        @if(isset($erd))
                                        @foreach($erd as $user)

                                                <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                                             
                                        @endforeach
                                        @endif
                                        </optgroup>

                                        <optgroup label="Labor and Social Relations Research Division (LSRRD)">
                                        @if(isset($lssrd))
                                        @foreach($lssrd as $user)

                                                <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                                             
                                        @endforeach
                                        @endif
                                        </optgroup>

                                        <optgroup label="Workers Welfare Research Division (WWRD)">
                                        @if(isset($wwrd))
                                        @foreach($wwrd as $user)

                                                <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                                             
                                        @endforeach
                                        @endif
                                        </optgroup>

                                        <optgroup label="Advocacy and Pulications Division (APD)">
                                        @if(isset($apd))
                                        @foreach($apd as $user)

                                                <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                                             
                                        @endforeach
                                        @endif
										
                                        </optgroup>

                                        <optgroup label="Finance and Administrative Division (FAD)">
                                        @if(isset($fad))
                                        @foreach($fad as $user)

                                                <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                                             
                                        @endforeach
                                        @endif
                                        </optgroup>                
					</select>
               </div>

                <div class="form-group">
				<label for="time_in_date">Date (YYYY-MM-DD)</label>
				<input class="date form-control" type="text" name="time_in_date" placeholder="YYYY-MM-DD" required>
				</div>

                      <button type="submit" class="btn btn-success col-md-4"><i class="feather icon-save"></i> Submit</button>
				</form>

                <!-- end form for absent -->

         </div>
         <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        
         </div>
      </div>
   </div>
</div>


<!-- Modal for Adding of Attendance-->
<div class="modal fade" id="addattendance_modal">
   <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
      <div class="modal-header panel-heading bg-info">
                     <h5 class="modal-title" style="color:white;"><i class="feather icon-calendar"></i> Add Attendance</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
         <div class="modal-body">
          
               <p>This feature is intended for manually timing in and out an employee.</p>
               <div class="form-group">
					<label for="attendance_type">Select attendance status</label>
					<select class="form-control" id="attendance_type"> 
						<option selected disabled>-- Choose here --</option>
						<option value="time in">Time in</option>
						<option value="time out">Time out</option>
					</select>
				</div>


                <!-- start FORM FOR TIME IN -->
                <form method="post" action="{{action('ManageAttendanceController@store')}}" id="time_in_form">
				@csrf
                <div class="form-group">
					<label for="employee">Select employee</label>
					<select class="form-control" name="employee" required> 
						<option disabled selected value>-- Choose here --</option>
                  <optgroup label="Office of the Executive Director (OED)">
                  @if(isset($oed))
                                        @foreach($oed as $user)

                                                <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                                             
                                        @endforeach
                                        @endif
                                        </optgroup>

                                        <optgroup label="Employment Research Division (ERD)">
                                        @if(isset($erd))
                                        @foreach($erd as $user)

                                                <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                                             
                                        @endforeach
                                        @endif
                                        </optgroup>

                                        <optgroup label="Labor and Social Relations Research Division (LSRRD)">
                                        @if(isset($lssrd))
                                        @foreach($lssrd as $user)

                                                <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                                             
                                        @endforeach
                                        @endif
                                        </optgroup>

                                        <optgroup label="Workers Welfare Research Division (WWRD)">
                                        @if(isset($wwrd))
                                        @foreach($wwrd as $user)

                                                <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                                             
                                        @endforeach
                                        @endif
                                        </optgroup>

                                        <optgroup label="Advocacy and Pulications Division (APD)">
                                        @if(isset($apd))
                                        @foreach($apd as $user)

                                                <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                                             
                                        @endforeach
                                        @endif
										
                                        </optgroup>

                                        <optgroup label="Finance and Administrative Division (FAD)">
                                        @if(isset($fad))
                                        @foreach($fad as $user)

                                                <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                                          
                                        @endforeach
                                        @endif
                                        </optgroup>
					</select>
				</div>

                <div class="form-group">
				<label for="time_in_date">Time in date (YYYY-MM-DD)</label>
				<input class="date form-control" type="text" name="time_in_date" placeholder="YYYY-MM-DD" required>
				</div>

                <div class="form-group">
                <label for="time_in_time">Time in time (HH:MM:SS) 24 Hours Format</label>
                <input type="text" class="timepicker form-control" id="time_in_time" name="time_in_time" required>
                </div>

                <div class="form-group">
					<label for="work_setting">Select Work Setting</label>
					<select class="form-control" name="work_setting" required> 
                <option value="work from home">Work From Home</option>
                <option value="in office">In Office</option>                  
					</select>
				</div>





                <button type="submit" class="btn btn-success col-md-4"><i class="feather icon-save"></i> Submit</button>
				</form>

                <!-- end form for time in -->






                <!-- start FORM FOR TIME OUT -->

                <form method="post" action="/accomplishmentadmin" id="time_out_form">
				@csrf

                <div class="form-group">
					<label for="employee">Select employee</label>
					<select class="form-control" name="employee" required> 
						<option disabled selected value>-- Choose here --</option>
                  <optgroup label="Office of the Executive Director (OED)">
                                        @if(isset($oed))
                                        @foreach($oed as $user)
                                        <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                                        @endforeach
                                        @endif
                                        </optgroup>

                                        <optgroup label="Employment Research Division (ERD)">
                                        @if(isset($erd))
                                        @foreach($erd as $user)
                                        <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                                        @endforeach
                                        @endif
                                        </optgroup>

                                        <optgroup label="Labor and Social Relations Research Division (LSRRD)">
                                        @if(isset($lssrd))
                                        @foreach($lssrd as $user)
                                        <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                                        @endforeach
                                        @endif
                                        </optgroup>

                                        <optgroup label="Workers Welfare Research Division (WWRD)">
                                        @if(isset($wwrd))
                                        @foreach($wwrd as $user)
                                        <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                                        @endforeach
                                        @endif
                                        </optgroup>

                                        <optgroup label="Advocacy and Pulications Division (APD)">
                                        @if(isset($apd))
                                        @foreach($apd as $user)
                                        <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                                        @endforeach
                                        @endif
                                        </optgroup>

                                        <optgroup label="Finance and Administrative Division (FAD)">
                                        @if(isset($fad))
                                        @foreach($fad as $user)
                                        <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                                        @endforeach
                                        @endif
                                        </optgroup>                
					</select>
				</div>

                <div class="form-group">
				<label for="time_out_date">Time out date (YYYY-MM-DD)</label>
				<input class="date form-control" type="text" name="time_out_date" placeholder="YYYY-MM-DD" required>
				</div>

                <div class="form-group">
                <label for="time_out_time">Time out time (HH:MM:SS) 24 Hours Format</label>
                <input type="text" class="timepicker form-control" id="time_out_time" name="time_out_time" required>
                </div>

                <div class="form-group">
                <label>Accomplishment</label>
                <textarea rows="10" name="accomplishment" class="form-control" required></textarea>
                </div>

                <button type="submit" class="btn btn-success col-md-4"><i class="feather icon-save"></i> Submit</button>
				</form>

                <!-- end form for time out -->

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
multidate: false,
clearBtn: true,
orientation:'auto',
autoclose: true,
endDate: new Date(),
}); 
</script>

@endsection