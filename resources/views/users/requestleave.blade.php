@extends('users.userslayouts.master')
@section('content')

@section('title', 'Request Leave')

  @include('inc.messages')
  
<div class="d-flex justify-content-center col-md-12 mt-2">
	<div class="card col-md-10 manage-forms" style="padding-left:0;padding-right:0;">

	<h3 class="bg-info card-header text-center" style="color:white">
    <i class="feather icon-message-circle"></i> Leave Request
        </h3>

		<div class="card-body">
			

			<div class="form-group">
					<label for="leave_type">Select type of Leave</label>
					<select class="form-control" id="leave_type"> 
						<option selected disabled>-- Choose here --</option>
						<option value="special leave privilege">Special Leave Privilege</option>
						<option value="vacation leave">Vacation Leave</option>
						<option value="sick leave">Sick Leave</option>
						<option value="maternity leave">Maternity Leave (105 days)</option>
						<option value="cto">CTO</option>
						<option value="others">Others (Specify)</option>
					</select>
				</div>





				<!-- **START special leave privilege form** -->
				<form method="post" action="{{route('requestleave.store')}}" id="special_leave_privilege_form">
				@csrf
				<select name="employee_id" hidden="">
					<option>{{ Auth::user()->id }}</option>
				</select>
				<input type="hidden" name="status" value="pending">
				<input type="hidden" name="leave_title" id="slp_title">
				
				<div class="form-row">
				<div class="form-group col-md-6">
				<label>Inclusive Dates (YYYY-MM-DD)</label>
				<input class="date form-control" type="text" name="inclusive_dates" required>
				</div>

				
				<div class="form-group col-md-6">
					<label>Details</label>
					<input type="text" class="form-control" name="details" required>
				</div>
				</div>

				<div class="form-group">
					<label>Commutation</label>
					<div class="form-check">
					<input type="radio" class="form-check-input" name="commutation" value="Not Requested" required>
					<label>Not Requested</label>
					</div>
					<div class="form-check">
					<input type="radio" class="form-check-input" name="commutation" value="Requested">
					<label>Requested</label>
					</div>
				</div>
				


<div class="form-row">
 <!--list for users with Chief LEO / Supervisor role-->
  <div class="form-group col-md-4">
     <label>Please select immediate supervisor</label>
     <select class="form-control" name="supervisor_id" required> 
     						<option selected disabled value><i>-- Select name --</i></option>
     						@if(isset($users))
                             @foreach($users as $user)
                             <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                             @endforeach
                             @endif
     					</select>

						 <select class="form-control" required> 
         <option selected disabled value><i>-- Select designation --</i></option>
           @if(isset($users))
                       @foreach($users->unique('position') as $user)
                       <option value='{{$user->id}}'>{{$user->position}}</option>
                       @endforeach
                       @endif
         </select>
   </div>
 <!--END Chief LEO / Supervisor role-->

 <!--list for users with HR/FAD role-->
   <div class="form-group col-md-4">
   
     <label>Please select hr officer</label>
     <select class="form-control" name="approver_id" required> 
     					<option selected disabled value><i>-- Select name --</i></option>
     						@if(isset($hr))
                             @foreach($hr as $user)
                             <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                             @endforeach
                             @endif
     					</select>
						 <select class="form-control" required> 
					<option selected disabled value><i>-- Select designation --</i></option>
						@if(isset($hr))
                        @foreach($hr->unique('position') as $user)
                        <option value='{{$user->id}}'>{{$user->position}}</option>
                        @endforeach
                        @endif
					</select>     
   </div>
<!--END HR/FAD role-->

 <!--list for users with Director/Deputy Director role-->
   <div class="form-group col-md-4">
   
     <label>Please select signatory</label>

     <select class="form-control" name="signatory_id" required> 
 					<option selected disabled value><i>-- Select name --</i></option>
 						@if(isset($director))
                         @foreach($director as $user)
                         <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                         @endforeach
                         @endif
 					</select>

					 <select class="form-control" required> 
  					<option selected disabled value><i>-- Select designation --</i></option>
  						@if(isset($director))
                          @foreach($director->unique('position') as $user)
                          <option value='{{$user->id}}'>{{$user->position}}</option>
                          @endforeach
                          @endif
  					</select>
     
   </div>
    <!--END Director/Deputy Director role-->

 </div>


 
										

 				<button type="submit" class="btn btn-success"><i class="feather icon-save "></i><span class="pcoded-mtext"> Submit</span></button>
				</form>
				<!-- **END special leave privilege form** -->






				<!-- **START vacation leave form** -->
				<form method="post" action="{{route('requestleave.store')}}" id="vacation_leave_form">
				@csrf
				<select name="employee_id" hidden="">
					<option>{{ Auth::user()->id }}</option>
				</select>
				<input type="hidden" name="status" value="pending">
				<input type="hidden" name="leave_title" id="vacation_leave_title">

				<div class="form-row">
				<div class="form-group col-md-4">
				<label>Inclusive Dates (YYYY-MM-DD)</label>
				<input class="date form-control" type="text" name="inclusive_dates" required>
				</div>

				<div class="form-group col-md-4">
				<label>Reason for Vacation Leave</label>
					<select class="form-control" id="reason_vacation_leave" name="details"> 
						<option value="To seek employment">To seek employment</option>
						<option value="Others (Specify)">Others (Specify)</option>
					</select>
				</div>

				<div class="form-group col-md-4" id="reason_vacation_leave_others" style="display:none;">
					<label>Please specify</label>
					<input type="text" class="form-control" name="other_details" id="input_reason_vacation_leave_others">
				</div>


				</div>

				
				<div class="form-row">
				<div class="form-group col-md-4">
				<label>In case of Vacation Leave</label>
					<select class="form-control" id="case_vacation_leave" name="case"> 
						<option value="In the country">In the country</option>
						<option value="Abroad (Specify)">Abroad (Specify)</option>
					</select>
				</div>

				<div class="form-group col-md-4" id="case_vacation_leave_others" style="display:none;">
					<label>Abroad please specify</label>
					<input type="text" class="form-control" name="case_other" id="input_case_vacation_leave_others">
				</div>
				</div>
				

				<div class="form-group">
					<label>Commutation</label>
					<div class="form-check">
					<input type="radio" class="form-check-input" name="commutation" value="Not Requested" required>
					<label>Not Requested</label>
					</div>
					<div class="form-check">
					<input type="radio" class="form-check-input" name="commutation" value="Requested">
					<label>Requested</label>
					</div>
				</div>	

				<div class="form-row">
 <!--list for users with Chief LEO / Supervisor role-->
  <div class="form-group col-md-4">
     <label>Please select immediate supervisor</label>
     <select class="form-control" name="supervisor_id" required> 
     						<option selected disabled value><i>-- Select name --</i></option>
     						@if(isset($users))
                             @foreach($users as $user)
                             <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                             @endforeach
                             @endif
     					</select>

						 <select class="form-control" required> 
         <option selected disabled value><i>-- Select designation --</i></option>
           				@if(isset($users))
                       @foreach($users->unique('position') as $user)
                       <option value='{{$user->id}}'>{{$user->position}}</option>
                       @endforeach
                       @endif
         </select>
   </div>
 <!--END Chief LEO / Supervisor role-->

 <!--list for users with HR/FAD role-->
   <div class="form-group col-md-4">
   
     <label>Please select hr officer</label>
     <select class="form-control" name="approver_id" required> 
     					<option selected disabled value><i>-- Select name --</i></option>
     						@if(isset($hr))
                             @foreach($hr as $user)
                             <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                             @endforeach
                             @endif
     					</select>
						 <select class="form-control" required> 
					<option selected disabled value><i>-- Select designation --</i></option>
						@if(isset($hr))
                        @foreach($hr->unique('position') as $user)
                        <option value='{{$user->id}}'>{{$user->position}}</option>
                        @endforeach
                        @endif
					</select>     
   </div>
<!--END HR/FAD role-->

 <!--list for users with Director/Deputy Director role-->
   <div class="form-group col-md-4">
   
     <label>Please select signatory</label>

     <select class="form-control" name="signatory_id" required> 
 					<option selected disabled value><i>-- Select name --</i></option>
 						@if(isset($director))
                         @foreach($director as $user)
                         <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                         @endforeach
                         @endif
 					</select>

					 <select class="form-control" required> 
  					<option selected disabled value><i>-- Select designation --</i></option>
  						@if(isset($director))
                          @foreach($director->unique('position') as $user)
                          <option value='{{$user->id}}'>{{$user->position}}</option>
                          @endforeach
                          @endif
  					</select>
     
   </div>
    <!--END Director/Deputy Director role-->
	
 </div>

  



 

 <button type="submit" class="btn btn-success"><i class="feather icon-save "></i><span class="pcoded-mtext"> Submit</span></button>
				</form>
			
				<!-- **END vacation leave form** -->







				<!-- **START sick leave form** -->
				<form method="post" action="{{route('requestleave.store')}}" id="sick_leave_form">
				@csrf
				<select name="employee_id" hidden="">
					<option>{{ Auth::user()->id }}</option>
				</select>
				<input type="hidden" name="status" value="pending">
				<input type="hidden" name="leave_title" id="sick_leave_title">
				
				<div class="form-row">
				<div class="form-group col-md-4">
				<label for="leave_type">Inclusive Dates (YYYY-MM-DD)</label>
				<input class="date form-control" type="text" name="inclusive_dates" required>
				</div>

				<div class="form-group col-md-4" id="type_sick_leave_case">
				<label>In case of vacation leave</label>
					<select class="form-control" id="case_sick_leave" name="details"> 
						<option value="In hospital (Specify)">In hospital (Specify)</option>
						<option value="Out patient (Specify)">Out patient (Specify)</option>
					</select>
				</div>

				<div class="form-group col-md-4">
					<label>Please specify</label>
					<input type="text" class="form-control" name="other_details">
				</div>
				</div>

				<div class="form-row">
 <!--list for users with Chief LEO / Supervisor role-->
  <div class="form-group col-md-4">
     <label>Please select immediate supervisor</label>
     <select class="form-control" name="supervisor_id" required> 
     						<option selected disabled value><i>-- Select name --</i></option>
     						@if(isset($users))
                             @foreach($users as $user)
                             <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                             @endforeach
                             @endif
     					</select>

						 <select class="form-control" required> 
         <option selected disabled value><i>-- Select designation --</i></option>
           @if(isset($users))
                       @foreach($users->unique('position') as $user)
                       <option value='{{$user->id}}'>{{$user->position}}</option>
                       @endforeach
                       @endif
         </select>
   </div>
 <!--END Chief LEO / Supervisor role-->

 <!--list for users with HR/FAD role-->
   <div class="form-group col-md-4">
   
     <label>Please select hr officer</label>
     <select class="form-control" name="approver_id" required> 
     					<option selected disabled value><i>-- Select name --</i></option>
     						@if(isset($hr))
                             @foreach($hr as $user)
                             <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                             @endforeach
                             @endif
     					</select>
						 <select class="form-control" required> 
					<option selected disabled value><i>-- Select designation --</i></option>
						@if(isset($hr))
                        @foreach($hr->unique('position') as $user)
                        <option value='{{$user->id}}'>{{$user->position}}</option>
                        @endforeach
                        @endif
					</select>     
   </div>
<!--END HR/FAD role-->

 <!--list for users with Director/Deputy Director role-->
   <div class="form-group col-md-4">
   
     <label>Please select signatory</label>

     <select class="form-control" name="signatory_id" required> 
 					<option selected disabled value><i>-- Select name --</i></option>
 						@if(isset($director))
                         @foreach($director as $user)
                         <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                         @endforeach
                         @endif
 					</select>

					 <select class="form-control" required> 
  					<option selected disabled value><i>-- Select designation --</i></option>
  						@if(isset($director))
                          @foreach($director->unique('position') as $user)
                          <option value='{{$user->id}}'>{{$user->position}}</option>
                          @endforeach
                          @endif
  					</select>
     
   </div>
    <!--END Director/Deputy Director role-->
	
 </div>



  



 

 				<button type="submit" class="btn btn-success"><i class="feather icon-save "></i><span class="pcoded-mtext"> Submit</span></button>
				</form>
				<!-- **END sick leave form** -->







				<!-- **START maternity leave form** -->
				<form method="post" action="{{route('requestleave.store')}}" id="maternity_leave_form">
				@csrf
				<select name="employee_id" hidden="">
					<option>{{ Auth::user()->id }}</option>
				</select>
				<input type="hidden" name="status" value="pending">
				<input type="hidden" name="leave_title" id="maternity_leave_title">

				<div class="form-row">			
				<div class="form-group col-md-6" id="start_date">
					<label for="fromDate">Start Date</label>
					<input type="date" class="form-control" id="fromDate" name="inclusive_dates" required>
				</div>
				<div class="form-group col-md-6" id="end_date">
					<label for="toDate">End Date</label>
					<input type="date" class="form-control" id="toDate" name="end_date" readonly>
				</div>
				</div>





				<div class="form-row">
 <!--list for users with Chief LEO / Supervisor role-->
  <div class="form-group col-md-4">
     <label>Please select immediate supervisor</label>
     <select class="form-control" name="supervisor_id" required> 
     						<option selected disabled value><i>-- Select name --</i></option>
     						@if(isset($users))
                             @foreach($users as $user)
                             <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                             @endforeach
                             @endif
     					</select>

						 <select class="form-control" required> 
         <option selected disabled value><i>-- Select designation --</i></option>
           @if(isset($users))
                       @foreach($users->unique('position') as $user)
                       <option value='{{$user->id}}'>{{$user->position}}</option>
                       @endforeach
                       @endif
         </select>
   </div>
 <!--END Chief LEO / Supervisor role-->

 <!--list for users with HR/FAD role-->
   <div class="form-group col-md-4">
   
     <label>Please select hr officer</label>
     <select class="form-control" name="approver_id" required> 
     					<option selected disabled value><i>-- Select name --</i></option>
     						@if(isset($hr))
                             @foreach($hr as $user)
                             <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                             @endforeach
                             @endif
     					</select>
						 <select class="form-control" required> 
					<option selected disabled value><i>-- Select designation --</i></option>
						@if(isset($hr))
                        @foreach($hr->unique('position') as $user)
                        <option value='{{$user->id}}'>{{$user->position}}</option>
                        @endforeach
                        @endif
					</select>     
   </div>
<!--END HR/FAD role-->

 <!--list for users with Director/Deputy Director role-->
   <div class="form-group col-md-4">
   
     <label>Please select signatory</label>

     <select class="form-control" name="signatory_id" required> 
 					<option selected disabled value><i>-- Select name --</i></option>
 						@if(isset($director))
                         @foreach($director as $user)
                         <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                         @endforeach
                         @endif
 					</select>

					 <select class="form-control" required> 
  					<option selected disabled value><i>-- Select designation --</i></option>
  						@if(isset($director))
                          @foreach($director->unique('position') as $user)
                          <option value='{{$user->id}}'>{{$user->position}}</option>
                          @endforeach
                          @endif
  					</select>
     
   </div>
    <!--END Director/Deputy Director role-->
	
 </div>


  



 

 				<button type="submit" class="btn btn-success"><i class="feather icon-save "></i><span class="pcoded-mtext"> Submit</span></button>
				</form>
				<!-- **END maternity leave form** -->







				<!-- **START cto leave form** -->
				<form method="get" action="{{route('requestleave.create')}}" id="cto_leave_form">
				@csrf
				<select name="employee_id" hidden="" id="cto_employee_id">
					<option>{{ Auth::user()->id }}</option>
				</select>

					
				<input type="hidden" name="status" value="pending">
				<input type="hidden" name="leave_title" id="cto_leave_title">
				

				<div class="form-row">

				

				<div class="form-group col-md-6" id="type_cto">
				<label for="type_vacation_leave">Number of Hours/Days applying for</label>
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
				
				<label for="leave_type">Start of Leave (YYYY-MM-DD)</label>
				<input class="date_cto form-control" type="text" name="start_date_cto" id="start_date_cto" required>
	

				</div>


				</div>

				<div class="form-row">
				<div class="form-group col-md-3">
				<label></label>
				<button type="button" class="btn btn-success" id="generate_dates_cto_btn" disabled><i class="feather icon-save "></i></span><span class="pcoded-mtext"> Generate Inclusive Dates</span></button>
				</div>

				<div class="form-group col-md-9">
				<label for="leave_type">Inclusive Dates (YYYY-MM-DD)</label>
				<input class="form-control" type="text" name="inclusive_dates_cto" id="inclusive_dates_cto" required readonly>
				</div>

				<div class="form-row">
				<div class="form-group col-md-12">
				<center><p id="half_day_message">You've selected a non whole day, please select which date it is and whether the leave is for morning (10-12am) or afternoon (1-4pm).</p></center>
				</div>
				</div>
				</div>

				<div class="form-row">

				<div class="form-group col-md-6 half_day_group">
				<label for="type_vacation_leave">Date for half day</label>
					<select class="form-control" id="select_half_day" name="date_half_day" required> 

					</select>
				</div>

				<div class="form-group col-md-6 half_day_group" >
					<label>Time of the day</label>
					
					<select class="form-control" id="time_day_half_cto" name="time_day_half_cto" required> 
					<option value="morning">Morning (10-12pm)</option>
					<option value="afternoon">Afternoon (1-4pm)</option>
					</select>
					
				</div>

				</div>

				

				

				<div class="form-row">
 <!--list for users with Chief LEO / Supervisor role-->
  <div class="form-group col-md-4">
     <label>Please select immediate supervisor</label>
     <select class="form-control" name="supervisor_id" required> 
     						<option selected disabled value><i>-- Select name --</i></option>
     						@if(isset($users))
                             @foreach($users as $user)
                             <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                             @endforeach
                             @endif
     					</select>

						 <select class="form-control" required> 
         <option selected disabled value><i>-- Select designation --</i></option>
           @if(isset($users))
                       @foreach($users->unique('position') as $user)
                       <option value='{{$user->id}}'>{{$user->position}}</option>
                       @endforeach
                       @endif
         </select>
   </div>
 <!--END Chief LEO / Supervisor role-->

 <!--list for users with HR/FAD role-->
   <div class="form-group col-md-4">
   
     <label>Please select hr officer</label>
     <select class="form-control" name="approver_id" required> 
     					<option selected disabled value><i>-- Select name --</i></option>
     						@if(isset($hr))
                             @foreach($hr as $user)
                             <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                             @endforeach
                             @endif
     					</select>
						 <select class="form-control" required> 
					<option selected disabled value><i>-- Select designation --</i></option>
						@if(isset($hr))
                        @foreach($hr->unique('position') as $user)
                        <option value='{{$user->id}}'>{{$user->position}}</option>
                        @endforeach
                        @endif
					</select>     
   </div>
<!--END HR/FAD role-->

 <!--list for users with Director/Deputy Director role-->
   <div class="form-group col-md-4">
   
     <label>Please select signatory</label>

     <select class="form-control" name="signatory_id" required> 
 					<option selected disabled value><i>-- Select name --</i></option>
 						@if(isset($director))
                         @foreach($director as $user)
                         <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                         @endforeach
                         @endif
 					</select>

					 <select class="form-control" required> 
  					<option selected disabled value><i>-- Select designation --</i></option>
  						@if(isset($director))
                          @foreach($director->unique('position') as $user)
                          <option value='{{$user->id}}'>{{$user->position}}</option>
                          @endforeach
                          @endif
  					</select>
     
   </div>
    <!--END Director/Deputy Director role-->
	
 </div>

  



 

				<button type="submit" class="btn btn-success"><i class="feather icon-save "></i><span class="pcoded-mtext"> Submit</span></button>
				</form>
				<!-- **END cto leave form** -->







				<!-- **START others leave form** -->
				<form method="post" action="{{route('requestleave.store')}}" id="others_leave_form">
				@csrf
				<select name="employee_id" hidden="">
					<option>{{ Auth::user()->id }}</option>
				</select>
				<input type="hidden" name="status" value="pending">
				<input type="hidden" name="leave_title" id="others_leave_title">
				
				<div class="form-row">
				<div class="form-group col-md-6">
				<label for="leave_type">Inclusive Dates (YYYY-MM-DD)</label>
				<input class="date form-control" type="text" name="inclusive_dates" required>
				</div>

				<div class="form-group col-md-6">
					<label>Please specify (Others)</label>
					<input type="text" class="form-control" name="details">
				</div>
				</div>


				<div class="form-group">
					<label>Commutation</label>
					<div class="form-check">
					<input type="radio" class="form-check-input" name="commutation" value="Not Requested" required>
					<label>Not Requested</label>
					</div>
					<div class="form-check">
					<input type="radio" class="form-check-input" name="commutation" value="Requested">
					<label>Requested</label>
					</div>
				</div>

				<div class="form-row">
 <!--list for users with Chief LEO / Supervisor role-->
  <div class="form-group col-md-4">
     <label>Please select immediate supervisor</label>
     <select class="form-control" name="supervisor_id" required> 
     						<option selected disabled value><i>-- Select name --</i></option>
     						@if(isset($users))
                             @foreach($users as $user)
                             <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                             @endforeach
                             @endif
     					</select>

						 <select class="form-control" required> 
         <option selected disabled value><i>-- Select designation --</i></option>
           @if(isset($users))
                       @foreach($users->unique('position') as $user)
                       <option value='{{$user->id}}'>{{$user->position}}</option>
                       @endforeach
                       @endif
         </select>
   </div>
 <!--END Chief LEO / Supervisor role-->

 <!--list for users with HR/FAD role-->
   <div class="form-group col-md-4">
   
     <label>Please select hr officer</label>
     <select class="form-control" name="approver_id" required> 
     					<option selected disabled value><i>-- Select name --</i></option>
     						@if(isset($hr))
                             @foreach($hr as $user)
                             <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                             @endforeach
                             @endif
     					</select>
						 <select class="form-control" required> 
					<option selected disabled value><i>-- Select designation --</i></option>
						@if(isset($hr))
                        @foreach($hr->unique('position') as $user)
                        <option value='{{$user->id}}'>{{$user->position}}</option>
                        @endforeach
                        @endif
					</select>     
   </div>
<!--END HR/FAD role-->

 <!--list for users with Director/Deputy Director role-->
   <div class="form-group col-md-4">
   
     <label>Please select signatory</label>

     <select class="form-control" name="signatory_id" required> 
 					<option selected disabled value><i>-- Select name --</i></option>
 						@if(isset($director))
                         @foreach($director as $user)
                         <option value='{{$user->id}}'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}} {{$user->extname}}</option>
                         @endforeach
                         @endif
 					</select>

					 <select class="form-control" required> 
  					<option selected disabled value><i>-- Select designation --</i></option>
  						@if(isset($director))
                          @foreach($director->unique('position') as $user)
                          <option value='{{$user->id}}'>{{$user->position}}</option>
                          @endforeach
                          @endif
  					</select>
     
   </div>
    <!--END Director/Deputy Director role-->
	
 </div>



  



 

 <button type="submit" class="btn btn-success"><i class="feather icon-save "></i><span class="pcoded-mtext"> Submit</span></button>
				</form>
				<!-- **END others leave form** -->
				

		</div>
	</div>
</div>


@stop

