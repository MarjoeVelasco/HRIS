@extends('admin.adminlayouts.master')
@section('title', '| OB/AO')
@section('content')

<style>
.ui-timepicker-container {z-index: 9999999 !important}
</style>
<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">
         <h3 class="text-info">
         <i class="feather icon-file-text"></i> Manage OB / AO
            <section class="float-right">
               <button href="" data-toggle="modal" data-target='#obao_modal' class="btn btn-outline-primary"><i class="feather icon-file-text"></i> Add OB/AO</button>
            </section>
         </h3>
      </div>
      @include('inc.messages')
      <div class="card-block table-border-style">
         <div class="table-responsive">
            <table class="table table-hover table-striped" id="attendanceTable">
               <thead>
                  <tr>
                     
                     <th>Name</th>
                     <th>Details</th>
                   
                    
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($obao as $at)
                  <tr>
                    
                     <td>{{$at->firstname}} {{$at->lastname}}</td>
                     <td><textarea style="width: 100%;" readonly>{{$at->status}}. {{$at->note}} {{$at->title}} - {{$at->details}}</textarea></td>
                     
                    
                        @csrf
                        <td>  
                        <a href="" id="editOBAO" data-toggle="modal" data-target='#edit_modal' data-id="{{ $at->id }}" class="btn-sm btn-warning"><i class="feather icon-edit"></i></span><span class="pcoded-mtext"> Edit</span></a>
                           <a href="" id="deleteOBAO" data-toggle="modal" data-target='#delete_modal' data-id="{{ $at->id }}" class="btn-sm btn-danger"><i class="feather icon-trash-2 "></i></span><span class="pcoded-mtext"> Delete</span></a>
                        </td>
                     
                  </tr>

                  @endforeach
               </tbody>
            </table>

            
            @if ($obao instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $obao->render() }}
            @endif


           
         </div>
      </div>
   </div>
</div>

<!-- Modal for deleting of OB/AO-->
<div class="modal fade" id="delete_modal">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-body" id="delete_modal_body">
         </div>
      </div>
   </div>
</div>


<!-- Modal for edit of OB/AO-->
<div class="modal fade" id="edit_modal">
   <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
      <div class="modal-header panel-heading bg-info">
                     <h5 class="modal-title" style="color:white;"><i class="feather icon-edit"></i> Edit OB/AO</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
         <div class="modal-body">       


               
               <!-- start FORM FOR ADD OB/AO -->
               <form method="post" action="/editOBAO">
				@csrf

            <input type="hidden" name="obao_id" id="obao_id">

            <div class="form-group">
					<label for="edit_status_type">Select type</label>
					<select class="form-control" id="edit_status_type" name="status_type" required> 
						<option selected disabled value>-- Choose here --</option>
						<option value="OB">OB (Official Business)</option>
						<option value="AO">AO (Administrative Order)</option>
						<option value="OO">OO (Office Order)</option>
					</select>
				</div>


            <div class="form-group">
                <label for="edit_ao_no">AO/OO No. (leave blank if unapplicable)</label>
                <input type="number" class="form-control" id="edit_ao_no" name="ao_no">
            </div>


                <div class="form-group">
					<label for="employee">Select employee</label>
					<select class="form-control" name="employee" id="edit_employee"required> 
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
				<label for="edit_date">Date (YYYY-MM-DD)</label>
				<input class="date form-control" type="text" name="date" id="edit_date" placeholder="YYYY-MM-DD" required>
				</div>

               

                <div class="form-group">
                <label for="edit_title">Title / Purpose</label>
                <textarea rows="3" name="title" id="edit_title" class="form-control" required></textarea>
                </div>

                <div class="form-group">
                <label for="edit_details">Details / Destination</label>
                <textarea rows="3" name="details" id="edit_details" class="form-control" required></textarea>
                </div>
               



                <!-- end FORM FOR ADD OB/AO -->

         </div>
         <div class="modal-footer">
      
        
         <button type="submit" class="btn btn-success col-md-4"><i class="feather icon-save"></i> Save</button>
				</form>




         <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>








         </div>
      </div>
   </div>
</div>

<!-- Modal for Adding of OB/AO-->
<div class="modal fade" id="obao_modal">
   <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
      <div class="modal-header panel-heading bg-info">
                     <h5 class="modal-title" style="color:white;"><i class="feather icon-calendar"></i> Add OB/AO</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
         <div class="modal-body">
          
              


                <!-- start FORM FOR ADD OB/AO -->
                <form method="post" action="{{action('OBAOController@store')}}">
				@csrf

            <div class="form-group">
					<label for="status_type">Select type</label>
					<select class="form-control" id="status_type" name="status_type" required> 
						<option selected disabled value>-- Choose here --</option>
						<option value="OB">OB (Official Business)</option>
						<option value="AO">AO (Administrative Order)</option>
						<option value="OO">OO (Office Order)</option>
					</select>
				</div>


            <div class="form-group">
                <label for="ao_no">AO No. (leave blank if unapplicable)</label>
                <input type="number" class="form-control" id="ao_no" name="ao_no">
            </div>


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
				<label for="date">Date (YYYY-MM-DD)</label>
				<input class="date form-control" type="text" name="date" placeholder="YYYY-MM-DD" required>
				</div>

            

                <div class="form-group">
                <label for="title">Title / Purpose</label>
                <textarea rows="3" name="title" id="title" class="form-control" required></textarea>
                </div>

                <div class="form-group">
                <label for="details">Details / Destination</label>
                <textarea rows="3" name="details" id="details" class="form-control" required></textarea>
                </div>
               



                <!-- end FORM FOR ADD OB/AO -->

         </div>
         <div class="modal-footer">
      
        
         <button type="submit" class="btn btn-success col-md-4"><i class="feather icon-save"></i> Submit</button>
				</form>
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