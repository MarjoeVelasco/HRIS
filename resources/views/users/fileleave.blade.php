@extends('users.userslayouts.master')
@section('content')
@section('title', 'File Leave')
@include('inc.messages')
<style>
input[type=radio] {
  transform: scale(1.8);
}
</style>

<div class="d-flex justify-content-center col-md-12 mt-2">
   <div class="card col-md-10 manage-forms" style="padding-left:0;padding-right:0;">
      <h3 class="bg-info card-header text-center" style="color:white">
         <i class="feather icon-message-circle"></i> Leave Request
      </h3>
      <div class="card-body">
        
      
        
         <!-- **START special leave privilege form** -->
         <form method="post" action="{{action('FiledLeavesController@store')}}" enctype="multipart/form-data" id="special_leave_privilege_form">
            @csrf


            <input type="hidden" name="employee_id" value='{{ Auth::user()->id }}'>
            
            <div class="form-group">
					<label for="leave_type">SELECT TYPE OF LEAVE TO BE AVAILED <span class="text-danger">*</span></label>
					<select name="leave_type" class="form-control" id="file_leave_type" required> 
						<option selected disabled>-- Choose here --</option>
						<option value="vacation leave">Vacation Leave</option>
                  <option value="mandatory forced leave">Mandatory/Forced Leave</option>
						<option value="sick leave">Sick Leave</option>
						<option value="maternity leave">Maternity Leave - 105 days</option>
                  <option value="paternity leave">Paternity Leave - 7 days</option>
                  <option value="special privilege leave">Special Privilege Leave</option>
                  <option value="solo parent leave">Solo Parent Leave</option>
                  <option value="study leave">Study Leave</option>
                  <option value="vawc leave">10-Day VAWC Leave</option>
                  <option value="rehabilitation leave">Rehabilitation Leave</option>
                  <option value="special leave benefits for women">Special Leave Benefits for Women</option>
                  <option value="special emergency (calamity) leave">Special Emergency (Calamity) Leave</option>
                  <option value="adoption leave">Adoption Leave</option>
						<option value="cto">CTO</option>
						<option value="others">Others (Specify)</option>
					</select>
				</div>
            
            <div class="form-row">
               <div class="form-group col-md-12">
                  <span>DETAILS OF LEAVE</span>
               </div>
            </div>

            <!-- In case of vacation/slp start -->
            <div id="slp_vacay_details">
               <div class="form-row">
                  <div class="form-group col-md-6">
                        <div class="form-group">
                           <label><i>In case of Vacation/Special Privilege Leave:</i></label> <span class="text-danger">*</span>
                           <div class="form-check">
                              <input type="radio" class="form-check-input" name="leave_details" value="within_the_philippines" checked required>
                              <label>Within the Philippines (Specify)</label>
                           </div>
                           <div class="form-check">
                              <input type="radio" class="form-check-input" name="leave_details" value="abroad">
                              <label>Abroad (Specify)</label>
                           </div>
                        </div>
                     </div>

                  <div class="form-group col-md-6">
                     <label>Specify</label>
                     <input maxlength="80" type="text" class="form-control" name="slp_vacay_details_input" id="slp_vacay_details_input" placeholder="Specify details here">
                  </div>
               </div>
            </div>
            <!-- In case of vacation/slp end -->

            <!-- In case of sick leave start -->
            <div id="sick_details">
               <div class="form-row">
                  <div class="form-group col-md-6">
                        <div class="form-group">
                           <label><i>In case of Sick Leave:</i></label> <span class="text-danger">*</span>
                           <div class="form-check">
                              <input type="radio" class="form-check-input" name="leave_details" value="in_hospital">
                              <label>In Hospital</label>
                           </div>
                     
                           <div class="form-check">
                              <input type="radio" class="form-check-input" name="leave_details" value="out_patient" checked required>
                              <label>Out Patient</label>
                           </div>
                        </div>
                     </div>

                  <div class="form-group col-md-6">
                     <label>Specify Illness</label>
                     <input maxlength="30" type="text" class="form-control" name="sick_details_input" id="sick_details_input" placeholder="Specify details here">
                  </div>
               </div>
            </div>
            <!-- In case of sick leave  end -->

            <!-- In case of study leave start -->
            <div id="study_details">
               <div class="form-row">
                  <div class="form-group col-md-12">
                        <div class="form-group">
                           <label><i>In case of Study Leave:</i></label> <span class="text-danger">*</span>
                           <div class="form-check">
                              <input type="radio" class="form-check-input" name="leave_details" value="completion_masters" checked required>
                              <label>Completion of Master's Degree</label>
                           </div>
                     
                           <div class="form-check">
                              <input type="radio" class="form-check-input" name="leave_details" value="exam_review">
                              <label>BAR/Board Examination Review</label>
                           </div>
                        </div>
                     </div>
               </div>
            </div>
            <!-- In case of study leave  end -->

            <!-- In case of others leave start -->
            <div id="other_details">
               <div class="form-row">
                  <div class="form-group col-md-12">
                     <label>Specify type of leave</label> <span class="text-danger">*</span>
                     <input type="text" placeholder="Others (Specify here)"class="form-control" name="other_details_input">
                  </div>

                  <div class="form-group col-md-12">
                        <div class="form-group">
                           <label><i>Other purpose:</i></label> <span class="text-danger">*</span>
                           <div class="form-check">
                              <input type="radio" class="form-check-input" name="other_leave_details" value="monetization_leave">
                              <label>Monetization of Leave Credits</label>
                           </div>
                     
                           <div class="form-check">
                              <input type="radio" class="form-check-input" name="other_leave_details" value="terminal_leave">
                              <label>Terminal Leave</label>
                           </div>
                        </div>
                  </div>
               </div>
            </div>
            <!-- In case of others leave  end -->

            <!-- In case of SLBFW start -->
            <div id="slbfw_details">
               <div class="form-row">
                  <div class="form-group col-md-12">
                        <label><i>In case of Special Leave Benefits for Women:</i></label> <span class="text-danger">*</span>
                        <input maxlength="40" placeholder="Specify Illness" type="text" class="form-control" name="slbfw_details_input" id="slbfw_details_input" placeholder="Specify details here">
                  </div>
               </div>
            </div>
            <!-- In case of SLBFW leave  end -->

            <!-- In case of maternity/paternity/study/SLBW leave start 
            <div id="start_end_date_container">
               <div class="form-row">

                  <div class="form-group col-md-6">
                     <label>Start Date</label>
                     <input type="date" class="form-control" name="start_date_input">
                  </div>

                  <div class="form-group col-md-6">
                     <label>End Date</label>
                     <input type="date" class="form-control" name="end_date_input">
                  </div>

               </div>
            </div>
            -->
            
            <!-- In case of maternity/paternity/study/SLBW leave end -->

            <div class="form-row" id="details_of_leave_container">
               <div class="form-group col-md-6" id="input_general_inclusive_dates">
                  <label>Inclusive Dates (YYYY-MM-DD)</label> <span class="text-danger">*</span>
                  <input class="date form-control" type="text" name="inclusive_dates">
               </div>

               <div class="form-group col-md-6"  id="input_monetization_no_days">
                  <label>Number of Days</label> <span class="text-danger">*</span>
					   <select name="no_days_monetization" class="form-control" id="file_leave_type"> 
						<option selected value=1>1 day/s</option>
                  <option value=2>2 day/s</option>
                  <option value=3>3 day/s</option>
                  <option value=4>4 day/s</option>
                  <option value=5>5 day/s</option>
                  <option value=6>6 day/s</option>
                  <option value=7>7 day/s</option>
                  <option value=8>8 day/s</option>
                  <option value=9>9 day/s</option>
                  <option value=10>10 day/s</option>
                  <option value=11>11 day/s</option>
                  <option value=12>12 day/s</option>
                  <option value=13>13 day/s</option>
                  <option value=14>14 day/s</option>
                  <option value=15>15 day/s</option>
                  

					   </select>

               </div>

               <div class="form-group col-md-6">
                  <div class="form-group">
                     <label>Commutation</label> <span class="text-danger">*</span>
                     <div class="form-check">
                        <input type="radio" class="form-check-input" name="commutation" value=0 checked required>
                        <label>Not Requested</label>
                     </div>
               
                     <div class="form-check">
                        <input type="radio" class="form-check-input" name="commutation" value=1 >
                        <label>Requested</label>
                     </div>
                  </div>
               </div>

            </div>

            <!-- CTO start -->
            <div id="cto_container">
            <div class="form-row">

               <div class="form-group col-md-6" id="type_cto">
               <label for="type_vacation_leave">Number of Hours/Days applying for</label> <span class="text-danger">*</span>
                  <select class="form-control" name="hours_days_cto" id="hours_days_cto"> 
                     <option selected disabled value><i>-- Select hours/days --</i></option>
                     <option value="0.5">4 Hours (or half day)</option>
                     <option value="1">8 Hours (or 1 day)</option>
                     <option value="1.5">12 Hours (or 1.5 days)</option>
                     <option value="2">16 Hours (or 2 days)</option>
                     <option value="2.5">20 Hours (or 2.5 days)</option>
                     <option value="3">24 Hours (or 3 days)</option>
                     <option value="3.5">28 Hours (or 3.5 days)</option>
                     <option value="4">32 Hours (or 4 days)</option>
                     <option value="4.5">36 Hours (or 4.5 days)</option>
                     <option value="5">40 Hours (or 5 days)</option>
                  </select>
               </div>

               <div class="form-group col-md-6" id="start_date_cto_group">  
				      <label for="leave_type">Start of Leave (YYYY-MM-DD)</label> <span class="text-danger">*</span>
				      <input class="date_cto form-control" type="text" name="start_date_cto" id="start_date_cto">
			      </div>
            
               </div>


            <div class="form-row">

               <div class="form-group col-md-12">
                  <label for="basic-url">Inclusive Dates (YYYY-MM-DD)</label> <span class="text-danger">*</span>
                  <div class="input-group mb-3">
                     <div class="input-group-prepend">
                        <button class="btn btn-success" id="generate_dates_cto_btn" type="button" disabled><i class="feather icon-save "></i>Generate Inclusive Dates</button>
                     </div>
                     <input class="form-control" aria-label="" aria-describedby="basic-addon1" type="text" name="inclusive_dates_cto" id="inclusive_dates_cto" readonly>
                  </div>
               </div>

				</div>
            

            <div class="form-row">
               <div class="form-group col-md-12">
                  <center><p id="half_day_message">You've selected a non whole day, please select which date it is and whether the leave is for morning (10-12am) or afternoon (1-4pm).</p></center>
               </div>
            </div>


            <div class="form-row">

               <div class="form-group col-md-6 half_day_group">
               <label for="type_vacation_leave">Date for half day</label> <span class="text-danger">*</span>
                  <select class="form-control" id="select_half_day" name="date_half_day"> 
                  </select>
               </div>

               <div class="form-group col-md-6 half_day_group" >
                  <label>Time of the day</label> <span class="text-danger">*</span>
                  <select class="form-control" id="time_day_half_cto" name="time_day_half_cto"> 
                     <option value="morning">Morning (10-12pm)</option>
                     <option value="afternoon">Afternoon (1-4pm)</option>
                  </select>
               </div>

				</div>

            
            </div>

            <div class="form-row">
               <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6" >
                  <label>Reason for late filing (if applicable)</label>
                  <textarea rows="1.5"  name="reason_late" placeholder="Indicate reason for late filing (if applicable)" class="form-control"></textarea>
               </div>

               <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6" >
                  <label>Attachment (if applicable)</label>
                  <input type="file" name="attachment" class="form-control">
               </div>
            </div>
            
            <!-- CTO end-->

            <div class="form-row">
               <!--list for users with Chief LEO / Supervisor role-->
               <div class="form-group col-md-4">
                  <label>Immediate supervisor</label> <span class="text-danger">*</span>
                  <select class="form-control" name="supervisor_id" required>
                     <option selected disabled value><i>-- Select name --</i></option>
                     @if(isset($users))
                     @foreach($users as $user)
                     <optgroup label='{{$user->position}}'>
                     <option value='{{$user->id}}'>{{$user->firstname}} {{$user->lastname}} {{$user->extname}}</option>
                     </outgroup>
                     @endforeach
                     @endif
                  </select>
               </div>
               <!--END Chief LEO / Supervisor role-->

               <!--list for users with HR/FAD role-->
               <div class="form-group col-md-4">
                  <label>HR officer</label> <span class="text-danger">*</span>
                  <select class="form-control" name="approver_id" required>
                     <option selected disabled value><i>-- Select name --</i></option>
                     @if(isset($hr))
                     @foreach($hr as $user)
                     <optgroup label='{{$user->position}}'>
                     <option value='{{$user->id}}'>{{$user->firstname}} {{$user->lastname}} {{$user->extname}}</option>
                     </outgroup>
                     @endforeach
                     @endif
                  </select>
               </div>
               <!--END HR/FAD role-->

               <!--list for users with Director/Deputy Director role-->
               <div class="form-group col-md-4">
                  <label>Signatory</label> <span class="text-danger">*</span>
                  <select class="form-control" name="signatory_id" required>
                     <option selected disabled value><i>-- Select name --</i></option>
                     @if(isset($director))
                     @foreach($director as $user)
                     <optgroup label='{{$user->position}}'>
                     <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                     </outgroup>
                     @endforeach
                     @endif
                  </select>
               </div>
               <!--END Director/Deputy Director role-->
            </div>

            <div class="form-row">  
               <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                  <label>Remarks</label>
                  <textarea rows="1.5"  name="internal_notes" placeholder="Internal notes (Optional)" class="form-control"></textarea>
               </div>
            </div>


            <!-- For external routing -->
            <hr>
            <div class="form-row">
               <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                  <div class="form-check">
                     <input class="form-check-input" style="scale: 1.7;"type="checkbox" value="1" name="is_external_checkbox" id="is_external_checkbox">
                     <label class="form-check-label" for="flexCheckDefault"> Route to external approving signatory? <i>(applicable to Division Chiefs)</i></label>

                  </div>
               </div>
            </div>

            <div class="form-row" id="external_approver_div" style="display:none;">
               <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6" >
                  <label>Name of approver <span class="text-danger">*</span></label>
                  <input type="text" name="external_name" placeholder="ex. Atty. Benjo Santos M. Benavidez" class="form-control">
               </div>

               <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6" >
                  <label>Designation of approver <span class="text-danger">*</span></label>
                  <input type="text" name="external_designation" placeholder="Undersecretary" class="form-control">
               </div>
            </div>
            <!-- end of for extenal routing-->

            <button type="submit" class="btn btn-success"><i class="feather icon-save "></i><span class="pcoded-mtext"> Submit</span></button>
         </form>
         <!-- **END special leave privilege form** -->
      </div>
   </div>
</div>


<!-- Disclaimer modal start -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel"><i class="feather icon-shield"></i> Data Privacy Consent</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         
        </button>
      </div>
      <div class="modal-body">
        <p class="text-justify">By filing leave requests through this platform, you are giving consent to affix your electronic signature along with your employee information such as name, position, salary grade, email, and leave details to your Civil Service Form No. 6 (Leave Request Form) and Internal Leave Credit form.</p>
 
        <p class="text-justify">Rest assured that any information divulged will be treated with utmost confidentiality 
 within the terms and scope of the <a target="_blank" href="https://www.privacy.gov.ph/data-privacy-act/">Data Privacy Act of 2012</a> and will be used solely
 in processing your leave application.</p>

      </div>
      <div class="modal-footer">
         <div class="row d-flex justify-content-center">
            <button type="button" class="btn btn-success" data-dismiss="modal"><i class="feather icon-thumbs-up"></i> Yes -- I Consent</button>
            <a href="/home"><button type="button" class="btn btn-danger"><i class="feather icon-thumbs-down"></i> NO, I DO NOT CONSENT</button></a>
         </div>   
      </div>
    </div>
  </div>
</div>
<!-- Disclaimer modal end-->

<div class="modal fade" id="leave_instruction_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="exampleModalLongTitle"><i class="feather icon-alert-circle"></i> INSTRUCTIONS AND REQUIREMENTS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="leave_type_desc">
         </div>

<button type="button" class="btn btn-secondary btn-sm float-right" data-dismiss="modal">Close</button>
      </div>
      
    </div>
  </div>
</div>

@stop