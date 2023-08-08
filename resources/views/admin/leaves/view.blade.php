@extends('admin.adminlayouts.master')
@section('title', '| Leave Application')
@section('content')



<div class="table-responsive">
	<div class="col-xl-12">
		<div class="card d-flex justify-content-center col-md-12">
			<div class="card-header">
                <center>
				<h4>
					 <b>APPLICATION FOR LEAVE</b>
				</h4>
                </center>
			</div>
            @include('inc.messages')
            
			<div class="card-block table-border-style">
				<div class="table-responsive">
					<table class="table" style="border: 2px solid black;">
						<tbody>
                            @foreach($leaves as $leave)
                            @foreach($supervisors as $supervisor)
                            

                            <input type="hidden" id="value_leave_type" value="{{$leave->leave_type}}"/>
                            <input type="hidden" id="value_leave_details" value="{{$leave->details}}"/>
                            <input type="hidden" id="value_commutation" value="{{$leave->commutation}}"/>
                            <input type="hidden" id="value_supervisor_note" value="{{$leave->supervisor_note}}"/>
                            <input type="hidden" id="value_hr_note" value="{{$leave->note}}"/>
                            <input type="hidden" id="value_approver_id" value="{{$leave->approver_id}}"/>
                            <input type="hidden" id="value_signatory_id" value="{{$leave->signatory_id}}"/>

                            <tr>
                            <td>1. OFFICE/AGENCY</td>
                            <td style="border-left: 1px solid black;">2. NAME</td>
                            <td><i>(Last Name)</i></td>
                            <td><i>(First Name)</i></td>
                            <td><i>(Middle Name)</i></td>
                            <td> </td>
                            <tr>

                            <tr>
                            <td style="border-bottom: 1px solid black;"><b>INSTITUTE FOR LABOR STUDIES</b></td>
                            <td colspan="5" style="border-bottom: 1px solid black;border-left: 1px solid black;text-transform:uppercase;"><center><b>{{$leave->lastname}} ,{{$leave->firstname}} {{$leave->middlename}} </b></center></td>
                            </tr>

                            <tr>
                            <td style="border-right: 1px solid black;">3. DATE OF FILING</td>
                            <td colspan="4" style="border-right: 1px solid black;">4. POSITION</td>
                            <td>5. SALARY(MONTHLY)</td>
                            </tr>

                            <tr>
                            <td style="border-right: 1px solid black;">{{$leave->created_at}}</td>
                            <td colspan="4" style="border-right: 1px solid black;"><center>{{$leave->position}}</center></td>
                            <td><center>SG {{$leave->sg}}</center></td>
                            </tr>

                            <tr>
                            <td colspan="6" style="border-top: 3px solid black;"><center><b>6. DETAILS OF APPLICATION</b> <i> (to be filled out by applicant)</i></center></td>
                            </tr>

                            <tr>
                            <td colspan="3" style="border-right: 1px solid black;border-top: 3px solid black;">6.a. TYPE OF LEAVE</td>
                            <td colspan="3" style="border-top: 3px solid black;">6.b. WHERE LEAVE WILL BE SPENT</td>
                            </tr>

                            <tr>
                            <td colspan="3" style="border-right: 1px solid black;"><input type="radio" id="radio_vacation" disabled> Vacation</td>
                            <td colspan="3" >1. IN CASE OF VACATION LEAVE</td>
                            </tr>

                            <tr>
                            <td colspan="3" style="border-right: 1px solid black;"><input type="radio" id="radio_to_seek_employment" disabled> To seek employment<br><input type="radio" id="radio_others_vl" disabled> Others (Specify)_<u><span id="v_vacation_others"></span></u>_</td>
                            <td colspan="3"><input type="radio" id="radio_in_country" disabled> In the country<br><input type="radio" id="radio_abroad" disabled> Abroad (Specify)_<u><span id="v_vacation_case_others"></span></u>_</td>
                            </tr>

                            <tr>
                            <td colspan="3" style="border-right: 1px solid black;"><input type="radio" id="radio_slp" disabled> Special Leave Program</td>
                            <td colspan="3"></td>
                            </tr>

                            <tr>
                            <td colspan="3" style="border-right: 1px solid black;"><input type="radio" id="radio_sick" disabled> Sick</td>
                            <td colspan="3" >2. IN CASE OF SICK LEAVE</td>
                            </tr>
                            
                            <tr>
                            <td colspan="3" style="border-right: 1px solid black;"><input type="radio" id="radio_maternity" disabled> Maternity<br><input type="radio" id="radio_others" disabled> Others (Specify)_<u><span id="v_others"></span></u>_</td>
                            <td colspan="3"><input type="radio" id="radio_in_hospital" disabled> In hospital (Specify)_<u><span id="v_in_hospital"></span></u>_<br><input type="radio" id="radio_out_patient" disabled> Out Patient (Specify)_<u><span id="v_out_patient"></span></u>_</td>
                            </tr>

                            <tr>
                            <td colspan="3" style="border-right: 1px solid black;">6.c. NUMBER OF WORKING DAYS APPLIED FOR<br>
                           <u> ____{{$leave->no_days}}___day/s</u></td>
                            <td colspan="3">6.d. COMMUTATION<br>
                            <input type="radio" disabled id="radio_requested_commutation"> Requested <input type="radio" id="radio_not_requested_commutation" disabled> Not Requested</td>
                            </tr>
                           
                            <tr>
                            <td colspan="3" style="border-right: 1px solid black;">INCLUSIVE DATES<br>
                            _<u> ____{{$leave->inclusive_dates}}___</u></td>
                            <td colspan="3"></td>
                            </tr>

                            <tr>
                            <td colspan="6" style="border-bottom: 3px solid black;border-top: 3px solid black;"><center><b>7. DETAILS OF ACTION ON APPLICATION</b></center></td>
                            </tr>

                            <tr>
                            <td colspan="3" style="border-right: 1px solid black;">7.a. RECOMMENDATION for: (to be filled out by immediate supervisor)</td>
                            <td colspan="3" style="border-right: 1px solid black;">7.b. CERTIFICATE OF LEAVE CREDITS: (to be filled out by HR Unit)<br> 
                            As of: __<u>{{$leave->date_approved}}</u>__</td>
                            </tr>

                            <tr>
                            <td colspan="3" style="border-right: 1px solid black;"><input type="radio" id="radio_supervisor_approved" disabled> Approval<br><input type="radio" id="radio_supervisor_disapproved" disabled> Disapproval due to_<u><span id="v_supervisor_reason"></span></u>_</td>
                            
                            <td colspan="3">
                            <table style="border-top: 2px solid black;">
                            
                            <th style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">VACATION</th>
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">SICK</th>
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">SLP</th>
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">TOTAL</th>
                            </tr>

                            <tr>
                            <td style="border-bottom: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;"><span id="v_vl"></span></td>
                            <td style="border-bottom: 1px solid black;border-right: 1px solid black;"><span id="v_sl"></span></td>
                            <td style="border-bottom: 1px solid black;border-right: 1px solid black;"><span id="v_slp"></span></td>
                            <td style="border-bottom: 1px solid black;border-right: 1px solid black;"><span id="v_total"></span></td>
                            </tr>
                            <tr>
                            <td>Days</td>
                            <td>Days</td>
                            <td>Days</td>
                            <td>Days</td>
                            </tr>
                            </table>
                            </td>
                            </tr>

                            <tr>
                            <td colspan="2" style="border-bottom: 1px solid black;"><b>{{$supervisor->firstname}} {{$supervisor->middlename}} {{$supervisor->lastname}} {{$supervisor->extname}}</b><br><i>{{$supervisor->position}}</i></td>
                            <td colspan="1" style="border-right: 1px solid black;border-bottom: 1px solid black;"><center>_____________<br>date</center></td>

                            <td colspan="2" style="border-bottom: 1px solid black;"><b><span id="approver_name"></span></b><br><i><span id="approver_position"></span></i></td>
                            <td colspan="1" style="border-bottom: 1px solid black;"><center>_____________<br>date</center></td>
                            
                            </tr>

                            <tr>
                            <td colspan="3" style="border-right: 1px solid black;">7.c. APPROVED FOR (to be filled out by HR Unit)</td>
                            <td colspan="3">7.d. DISAPPROVED DUE TO (to be filled out by HR Unit)</td>
                            </tr>

                            <tr>
                            <td colspan="3" style="border-bottom: 2px solid black;border-right: 1px solid black;">_<u><span id="dwp"></span></u>_ Days with Pay<br>
                                __<u><span id="dwop"></span></u>__ Days without Pay<br>
                                __<u><span id="dothers"></span></u>__ Others (specify)<br></td>

                            <td colspan="3" style="border-bottom: 2px solid black;">_<u><p id="v_reason_disapproved"></p></u>_</td>    
                            </tr>

                            <tr>
                            <td colspan="6"><br><br><center><b><span id="signatory_name"></span></b><br><i><span id="signatory_position"></i></center></td>
                            </tr>

                            <tr>
                            <td colspan="6">Date: _______________</td>
                            </tr>

                            
                            @endforeach
                            @endforeach
							</tbody>
						</table>
                        
					</div>
				</div>
			</div>
		</div>
@endsection