
<style>
* {
  font-family: Arial, Helvetica, sans-serif;
  font-size:0.97em;
}

</style>
<p>
CSC Form No. 6<br>
Revised 1984
</p>
<center><h3>APPLICATION FOR LEAVE</h3></center>
<table class="table" style="border: 1px solid black;width:100%;" cellspacing="0">
@foreach($leaves as $leave)

<tr>
<td>&nbsp;&nbsp;1. OFFICE/AGENCY</td>
<td style="border-left: 1px solid black;">&nbsp;&nbsp;2. NAME </td>
<td><i> (Last Name)</i></td>
<td><i> (First Name)</i></td>
<td><i> (Middle Name)</i></td>
<td> </td>
</tr>
 
<tr>
<td style="padding:5px;border-bottom: 1px solid black;"><center><strong>INSTITUTE FOR LABOR STUDIES</strong></center></td>
<td colspan="5" style="padding:5px;border-left: 1px solid black;border-bottom: 1px solid black;text-transform:uppercase;"><center><strong>{{$leave->lastname}}, {{$leave->firstname}} {{substr($leave->middlename, 0, 1)}}.</strong></center></td>
</tr>

<tr>
<td>&nbsp;&nbsp;3. DATE OF FILING</td>
<td colspan="3" style="padding:5px;border-left: 1px solid black">&nbsp;&nbsp;4. POSITION</td>
<td colspan="2" style="border-left: 1px solid black" >&nbsp;&nbsp;5. SALARY(MONTHLY)</td>
</tr>

<tr>
<td style="padding:5px;border-right: 1px solid black;"><center><strong>{{ date('d F Y', strtotime($leave->created_at)) }}</strong></center></td>
<td colspan="3" style="border-right: 1px solid black;"><center><strong>{{$leave->position}}</strong></center></td>
<td colspan="2"><center><strong>SG {{$leave->sg}}</strong></center></td>
</tr>

<tr>
<td colspan="6" style="padding:15px;border-top: 3px solid black;"><center><b>6. DETAILS OF APPLICATION</b> <i> (to be filled out by applicant)</i></center></td>
</tr>

<tr>
<td colspan="2" style="padding:5px;border-right: 1px solid black;border-top: 1px solid black;">&nbsp;&nbsp;6.a. TYPE OF LEAVE</td>
<td colspan="4" style="padding:5px;border-top: 1px solid black;">&nbsp;&nbsp;6.b. WHERE LEAVE WILL BE SPENT</td>
</tr>

<tr>
@if($leave->leave_type=="vacation leave")
<td colspan="2" style="padding-top:5px;padding-bottom:5px;padding-left:50px;border-right: 1px solid black;"><input type="checkbox" id="radio_vacation" checked> Vacation</td>
@else
<td colspan="2" style="padding-top:5px;padding-bottom:5px;padding-left:50px;border-right: 1px solid black;"><input type="checkbox" id="radio_vacation"> Vacation</td>
@endif
<td colspan="4" style="padding:5px;">&nbsp;&nbsp;1. IN CASE OF VACATION LEAVE</td>
</tr>

<tr>
<td colspan="2" style="padding-top:5px;padding-bottom:5px;padding-left:70px;border-right: 1px solid black;">

@if($leave->leave_type=="vacation leave")
  @if(strpos($leave->details, 'To seek employment') !== false)
    <input type="checkbox" id="radio_to_seek_employment" checked> To seek employment<br>
    <input type="checkbox" id="radio_others_vl" > Others (Specify)________________
  @else
<input type="checkbox" id="radio_to_seek_employment"> To seek employment<br>
<input type="checkbox" id="radio_others_vl" checked> Others (Specify)<u>_{{$vacation_others}}_</u>
  @endif
@else
<input type="checkbox" id="radio_to_seek_employment" > To seek employment<br>
<input type="checkbox" id="radio_others_vl" > Others (Specify)________________
@endif
</td>

<td colspan="4" style="padding-top:5px;padding-bottom:5px;padding-left:50px;">
@if($leave->leave_type=="vacation leave")
  @if(strpos($leave->details, 'In the country') !== false)
  <input type="checkbox" id="radio_in_country" checked> In the country<br>  
  <input type="checkbox" id="radio_abroad"> Abroad (Specify)___________________
  @else
  <input type="checkbox" id="radio_in_country"> In the country<br>  
  <input type="checkbox" id="radio_abroad" checked> Abroad (Specify)<u>_{{$vacation_abroad}}_</u>
  @endif
@else
<input type="checkbox" id="radio_in_country"> In the country<br>
<input type="checkbox" id="radio_abroad"> Abroad (Specify)___________________
@endif

</td>
</tr>

<tr>
@if($leave->leave_type=="special leave privilege")
<td colspan="2" style="padding-top:5px;padding-left:50px;border-right: 1px solid black;"><input type="checkbox" id="radio_slp" checked> Special Leave Program</td>
@else
<td colspan="2" style="padding-top:5px;padding-left:50px;border-right: 1px solid black;"><input type="checkbox" id="radio_slp"> Special Leave Program</td>
@endif
<td colspan="4"></td>
</tr>

<tr>
@if($leave->leave_type=="sick leave")
<td colspan="2" style="padding-top:5px;padding-left:50px;border-right: 1px solid black;"><input type="checkbox" id="radio_sick" checked> Sick</td>
@else
<td colspan="2" style="padding-top:5px;padding-left:50px;border-right: 1px solid black;"><input type="checkbox" id="radio_sick"> Sick</td>
@endif
<td colspan="4" style="padding:5px;">&nbsp;&nbsp;2. IN CASE OF SICK LEAVE</td>
</tr>

<tr>

@if($leave->leave_type=="maternity leave")
<td colspan="2" style="padding-top:5px;padding-bottom:5px;padding-left:50px;border-right: 1px solid black;"><input type="checkbox" id="radio_maternity" checked> Maternity<br>
@else
<td colspan="2" style="padding-top:5px;padding-bottom:5px;padding-left:50px;border-right: 1px solid black;"><input type="checkbox" id="radio_maternity"> Maternity<br>
@endif


@if($leave->leave_type=="others")
<input type="checkbox" id="radio_others" checked> Others (Specify)<u>____{{$leave->details}}___</u></td>
@else
<input type="checkbox" id="radio_others"> Others (Specify)___________________<u><span id="v_others"></span></u>_</td>
@endif



<td colspan="4" style="padding-top:5px;padding-bottom:5px;padding-left:50px;">

@if($leave->leave_type=="sick leave")
  @if(strpos($leave->details, 'In hospital') !== false)
  <input type="checkbox" id="radio_in_hospital" checked> In hospital (Specify)<u>_{{$sick_in}}_</u><br>
  <input type="checkbox" id="radio_out_patient"> Out Patient (Specify)___________________
  @else
  <input type="checkbox" id="radio_in_hospital"> In hospital (Specify)___________________<br>
  <input type="checkbox" id="radio_out_patient" checked> Out Patient (Specify)<u>_{{$sick_out}}_</u>
  @endif
@else
<input type="checkbox" id="radio_in_hospital"> In hospital (Specify)___________________<br>
<input type="checkbox" id="radio_out_patient"> Out Patient (Specify)___________________
@endif
</td>
</tr>

<tr>
<td colspan="2" style="padding:5px;border-right: 1px solid black;">&nbsp;&nbsp;6.c. NUMBER OF WORKING DAYS APPLIED FOR</td>
<td colspan="4" style="padding:5px;">&nbsp;&nbsp;6.d. COMMUTATION</td>
</tr>

<tr>
<td colspan="2" style="padding:5px;border-right: 1px solid black;"><center><u>______<strong>{{$leave->no_days}} day/s only</strong>_______</u></center></td>
<td colspan="4" style="padding-top:5px;padding-bottom:5px;padding-left:50px;">
@if($leave->commutation=="Requested")
<input type="checkbox" id="radio_in_hospital" checked> Requested
@else
<input type="checkbox" id="radio_in_hospital"> Requested
@endif

@if($leave->commutation=="Not Requested")
<input type="checkbox" id="radio_in_hospital" checked> Not Requested
@else
<input type="checkbox" id="radio_in_hospital"> Not Requested
@endif

 
</td>
</tr>

<tr>
<td colspan="2" style="padding:5px;border-right: 1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;INCLUSIVE DATES: <br>
<center><u>_______<strong>{{$leave->inclusive_dates}}</strong>__________</u></center></td>
<td colspan="4"><center>___________________________</center><br><center><i>(Signature of Applicant)</i></center></td>
</tr>

<tr>
    <td> </td>
</tr>
<tr>
    <td> </td>
</tr>
<tr>
    <td> </td>
</tr>

<tr>
<td colspan="6" style="padding:15px;border-top: 3px solid black;"><center><b>7. DETAILS OF ACTION ON APPLICATION</b></center></td>
</tr>

<tr>
<td colspan="2" style="padding:5px;border-top: 1px solid black;border-right: 1px solid black;">&nbsp;&nbsp;7.a. RECOMMENDATION for: <i>(to be filled out by immediate supervisor)</i></td>
<td colspan="4" style="padding:5px;border-top: 1px solid black;">&nbsp;&nbsp;7.b. CERTIFICATE OF LEAVE CREDITS: <i>(to be filled out by HR Unit)</i>
As of: ______________</td>
</tr>

<tr>
<td colspan="2" style="padding-top:5px;padding-left:50px;border-right: 1px solid black;"><input type="checkbox" id="radio_supervisor_approved" disabled> Approval</td>                           
<td colspan="4"></td>
</tr>

<tr>

<td colspan="2" style="padding-top:5px;padding-bottom:5px;padding-left:50px;border-right: 1px solid black;"><input type="checkbox" id="radio_supervisor_approved" disabled> Disapproval due to __________<br>____________________________</td>                           
<td colspan="4">
<table style="width:80%;margin:auto;" cellspacing="0">
<tr>                            
<th style="border-top:1px solid black;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">VACATION</th>
<th style="border-top:1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">SICK</th>
<th style="border-top:1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">SLP</th>
<th style="border-top:1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">TOTAL</th>
</tr>

<tr>

@if($leave->status=="Approved by HR" || $leave->status=="Disapproved by HR" )
<td style="border-bottom: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;"><center>{{$vacation_leave_days}}</center></td>
<td style="border-bottom: 1px solid black;border-right: 1px solid black;"><center>{{$sick_leave_days}}</center></td>
<td style="border-bottom: 1px solid black;border-right: 1px solid black;"><center>{{$slp_days}}</center></td>
<td style="border-bottom: 1px solid black;border-right: 1px solid black;"><center>{{$total_days}}</center></td>
@else
<td style="border-bottom: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;">_</td>
<td style="border-bottom: 1px solid black;border-right: 1px solid black;">_</td>
<td style="border-bottom: 1px solid black;border-right: 1px solid black;">_</td>
<td style="border-bottom: 1px solid black;border-right: 1px solid black;">_</td>
@endif

</tr>

<tr>
<td><center>Days</center></td>
<td><center>Days</center></td>
<td><center>Days</center></td>
<td><center>Days</center></td>
</tr>

</table>
</td>
</tr>

<tr>
@foreach($supervisor as $user)
<td colspan="1" style="font-size:11px;padding-left:5px;padding-right:5px;padding-bottom:5px;padding-top:30px;border-bottom: 1px solid black;text-transform:uppercase;"><center><b>{{$user->prefix}} {{$user->firstname}} {{substr($user->middlename, 0, 1)}}. {{$user->lastname}} {{$user->extname}}</b><br><span style="font-size:10px;"><i>{{$user->position}}</i></span></center></td>
@endforeach
<td colspan="1" style="font-size:11px;padding-left:5px;padding-right:5px;padding-bottom:5px;padding-top:30px;border-right: 1px solid black;border-bottom: 1px solid black;"><center>_________<br>date</center></td>

@foreach($hr as $user)
<td colspan="2" style="font-size:11px;padding-left:5px;padding-right:5px;padding-bottom:5px;padding-top:30px;border-bottom: 1px solid black;text-transform:uppercase;"><center><b><span id="approver_name">{{$user->prefix}} {{$user->firstname}} {{substr($user->middlename, 0, 1)}}. {{$user->lastname}} {{$user->extname}}</span></b><br><i><span style="font-size:10px;" id="approver_position">{{$user->position}}</span></i></center></td>
@endforeach
<td colspan="2" style="font-size:11px;padding-left:5px;padding-right:5px;padding-bottom:5px;padding-top:30px;border-bottom: 1px solid black;"><center>___________<br>date</center></td>                     

</tr>

<tr>
<td colspan="2" style="padding:5px;border-right: 1px solid black;">&nbsp;&nbsp;7.c. APPROVED FOR <i>(to be filled out by HR Unit)</i></td>
<td colspan="4" style="padding:5px;">&nbsp;&nbsp;7.d. DISAPPROVED DUE TO <i>(to be filled out by HR Unit)</i></td>
</tr>

<tr>
<td colspan="2" style="padding:5px;border-bottom: 2px solid black;border-right: 1px solid black;">


@if($leave->status=="Approved by HR")
<center>
<u>{{$days_w_pay}} </u> Days with Pay<br>
<u>{{$days_wo_pay}} </u> Days without Pay<br>
<u>{{$others_pay}} </u> Others (specify)<br></td></center>
@else
<center>
___________ Days with Pay<br>
____________ Days without Pay<br>
___________ Others (specify)<br></td></center>
@endif


<td colspan="4" style="border-bottom: 2px solid black;">
@if($leave->status=="Disapproved by HR")
<center>_<u>{{$disapproved_reasons}}</u>_</center>
@else
<center>___________________________________<br>
___________________________________<br>
___________________________________</center>
@endif
</td>    
</tr>


<tr>
@foreach($signatory as $user)

@if($user->middlename=="")
<td colspan="6" style="text-transform:uppercase;"><br><br><center><b><span id="signatory_name">{{$user->prefix}} {{$user->firstname}} {{$user->lastname}} {{$user->extname}}</span></b><br><i><span id="signatory_position">{{$user->position}}</i></center></td>
@else
<td colspan="6" style="text-transform:uppercase;"><br><br><center><b><span id="signatory_name">{{$user->prefix}} {{$user->firstname}} {{substr($user->middlename, 0, 1)}}. {{$user->lastname}} {{$user->extname}}</span></b><br><i><span id="signatory_position">{{$user->position}}</i></center></td>
@endif

@endforeach
</tr>

<tr>
<td colspan="6" style="padding:5px">&nbsp;&nbsp;&nbsp;&nbsp;Date: _______________</td>
</tr>


@endforeach

</table>
                        
					