
<style>
* {
  font-family: Arial, Helvetica, sans-serif;
  font-size:0.97em;
}
</style>

<table class="table" style="border: 1px solid black;width:100%;" cellspacing="0">
@foreach($leaves as $leave)
<tr>

<td style="border-right:solid 1px;padding:7px;" width="50">
<img src="{{ public_path('images/ils_icon.jpg') }}" height="50px">
</td>


<td style="border-right:solid 1px;padding:7px;">
Republic of the Philippines<br>
<strong>INSTITUTE FOR LABOR STUDIES</strong><br>
General Luna St., Intramuros, Manila<br>
</td>


<td style="border-right:solid 1px;padding:7px;">
<span style="font-size:0.89em;">HR Services: Internal Leave Credit (ILC) Form<br>
Reference No.:</span>
</td>


<td width="80">
<center>
Revision No.: 00<br>
Effectivity Date:
</center>
</td>

</tr>

<tr>
<td colspan="4" style="border-bottom:solid 3px;border-top:solid 1px;padding-right:7px;padding-left:7px;">
<span style="font-size:0.94em;text-align:justifiy;"><strong>INSTRUCTIONS:</strong> Upon completion of this form, submit to HR Unit for computation of your remaining ILC or Compensatory 
Overtime Credits (COC). ILC/COC may be availed in blocks of four (4) or eight (8) hours for a maximum of 5 consecutive days. </span>
</td>
</tr>

<tr> 

<td style="border: 1px solid black;padding:5px;">Date Filed:</td> 
<td style="border: 1px solid black;padding:5px;"><span style="font-size:1.2em;"><strong>{{date('F d, Y h:m a', strtotime($leave->created_at))}}</strong></span></td> 
<td colspan="2"rowspan="3" style="border: 1px solid black;padding:5px;">
<center>
<br><br>

        @if($leave->signature!=null)
        <div style="z-index:1;green;margin-bottom:-500px;">
            
            <span style="text-align:center;">        ___________________________<br><span style="font-size:0.80em;">Signature of Applicant</span><br>
        </div>
        <img src="{{ public_path($leave->signature) }}" style="height:50px;margin-top:-30px;"><br>  
        @else
        ___________________________<br>
        <span style="font-size:0.80em;">Signature of Applicant</span>
        @endif


</center>
</td> 
</tr> 

<tr> 
<td style="border: 1px solid black;padding:5px;">Name:</td> 
<td style="border: 1px solid black;padding:5px;"><span style="font-size:1.2em;"><strong>{{$employee_name}}</strong></span></td> 
</tr>

<tr> 
<td style="border: 1px solid black;padding:5px;">Designation:</td> 
<td style="border: 1px solid black;padding:5px;"><span style="font-size:1.2em;"><strong>{{$leave->position}}</strong></span></td> 
</tr>

<tr>
<td colspan="2" style="border: 1px solid black;padding:5px;">Number of Hours/Days applied for:</td> 
@if($leave->no_days=="0.5")
<td colspan="2" style="border: 1px solid black;padding:5px;"><span style="font-size:1.2em;"><strong>4 hours (or half day)</strong></span></td> 
@endif

@if($leave->no_days=="1")
<td colspan="2" style="border: 1px solid black;padding:5px;"><span style="font-size:1.2em;"><strong>8 hours (or 1 day)</strong></span></td> 
@endif

@if($leave->no_days=="1.5")
<td colspan="2" style="border: 1px solid black;padding:5px;"><span style="font-size:1.2em;"><strong>12 hours (or 1.5 days)</strong></span></td> 
@endif

@if($leave->no_days=="2")
<td colspan="2" style="border: 1px solid black;padding:5px;"><span style="font-size:1.2em;"><strong>16 hours (or 2 days)</strong></span></td> 
@endif

@if($leave->no_days=="2.5")
<td colspan="2" style="border: 1px solid black;padding:5px;"><span style="font-size:1.2em;"><strong>20 hours (or 2.5 day)</strong></span></td> 
@endif

@if($leave->no_days=="3")
<td colspan="2" style="border: 1px solid black;padding:5px;"><span style="font-size:1.2em;"><strong>24 hours (or 3 days)</strong></span></td> 
@endif

@if($leave->no_days=="3.5")
<td colspan="2" style="border: 1px solid black;padding:5px;"><span style="font-size:1.2em;"><strong>28 hours (or 3.5 days)</strong></span></td> 
@endif

@if($leave->no_days=="4")
<td colspan="2" style="border: 1px solid black;padding:5px;"><span style="font-size:1.2em;"><strong>32 hours (or 4 days)</strong></span></td> 
@endif

@if($leave->no_days=="4.5")
<td colspan="2" style="border: 1px solid black;padding:5px;"><span style="font-size:1.2em;"><strong>36 hours (or 4.5 days)</strong></span></td> 
@endif

@if($leave->no_days=="5")
<td colspan="2" style="border: 1px solid black;padding:5px;"><span style="font-size:1.2em;"><strong>40 hours (or 5 days)</strong></span></td> 
@endif

</tr>

<tr>
<td colspan="2" style="border: 1px solid black;padding:5px;">Date of Availment for Compensatory Time-Off (CTO):</td> 
<td colspan="2" style="border: 1px solid black;padding:5px;"><span style="font-size:1.2em;"><strong>{{$leave->inclusive_dates}}</strong></span></td> 
</tr>




<tr>
<td colspan="2" style="border-top: 3px solid black;padding:5px;">
<span style="font-size:0.79em">Certified Availability of ILC/COC by: <i>(to be filled by HR unit)</i></span>
</td>

<td  colspan="2" style="border-top: 3px solid black;padding:5px;">
</td>

</tr>


<tr>
<td colspan="2">  

<center>
<br><br>

        @if($leave->hr_remarks=="Approved")
                @if($hr_signature!=null)
                        <div style="z-index:1;green;margin-bottom:-500px;"> 
                                <span style="font-size:1.2em;text-transform:uppercase;"><strong>{{$hr_name}}</strong></span><br>
                                <span>{{$hr_position}}</span>
                        </div>
                        <img src="{{ public_path($hr_signature) }}" style="height:50px;margin-top:-30px;"><br>  
                @else
                        <span style="font-size:1.2em;text-transform:uppercase;"><strong>{{$hr_name}}</strong></span><br>
                        <span>{{$hr_position}}</span>
                @endif
        @else
                <span style="font-size:1.2em;text-transform:uppercase;"><strong>{{$hr_name}}</strong></span><br>
                <span>{{$hr_position}}</span>        
        @endif


</center>

</td>


<td colspan="2">
<center><span style="font-size:0.9em"><strong>CERTIFICATION OF ILC/COC EARNED</strong></span></center>
<table style="border: 1px solid black;width:60%;margin:auto;" cellspacing="0">

@if($leave->credits_id==null)

<tr>                            
<td width="105" style="border: 1px solid black;padding:3px;">Certification as of:</td>
<td width="85" style="border: 1px solid black;padding:3px;"> </td>
</tr>

<tr>
<td style="border: 1px solid black;padding:3px;">Number of Hours Earned:</td>
<td style="border: 1px solid black;padding:3px;"> </td>
</tr>

<tr>
<td style="border: 1px solid black;padding:3px;">Date of Last Certification:</td>
<td style="border: 1px solid black;padding:3px;"> </td>
</tr>

@else
<tr>                            
<td width="105" style="border: 1px solid black;padding:3px;">Certification as of:</td>
<td width="85" style="border: 1px solid black;padding:3px;">{{$certification_as_of}}</td>
</tr>

<tr>
<td style="border: 1px solid black;padding:3px;">Number of Hours Earned:</td>
<td style="border: 1px solid black;padding:3px;">{{$number_of_hours}}</td>
</tr>

<tr>
<td style="border: 1px solid black;padding:3px;">Date of Last Certification:</td>
<td style="border: 1px solid black;padding:3px;">{{$last_certification}}</td>
</tr>
@endif







</table>
</td>


</tr>

<tr>
<td colspan="4" style="border-bottom: 3px solid black;">
&nbsp;&nbsp;Date Signed:
</td>
</tr>


<tr>
<td colspan="2" style="border-right:1px solid;">
<center><span style="font-size:0.79em;"><i>(to be filled out by the immediate supervisor)</i></span></center>
</td>
<td colspan="2">
<center><span style="font-size:0.79em"><i>(to be filled out by the approving authority)</i></span></center>
</td>
</tr>


<tr>

<td colspan="2" style="padding-top:20px;padding-left:50px;border-right:1px solid;">
@if($leave->supervisor_remarks=="waiting")
<input type="checkbox"> Recommended<br>
<input type="checkbox"> Not Recommended due to _______<br>
_______________________________
@else

        @if($leave->supervisor_remarks=="Approved")
        <input type="checkbox" checked> Recommended<br>
        <input type="checkbox"> Not Recommended due to _______<br>
        _______________________________ 
        @else
        <input type="checkbox"> Recommended<br>
        <input type="checkbox" checked> Not Recommended due to :<br>
        <u>{{$leave->supervisor_remarks}}</u> 
        @endif
@endif

</td>

<td colspan="2" style="padding-top:20px;padding-left:50px;">
@if($leave->signatory_remarks=="waiting")
<input type="checkbox"> Recommended<br>
<input type="checkbox"> Not Recommended due to _______<br>
_______________________________
@else

        @if($leave->signatory_remarks=="Approved")
        <input type="checkbox" checked> Recommended<br>
        <input type="checkbox"> Not Recommended due to _______<br>
        _______________________________ 
        @else
        <input type="checkbox"> Recommended<br>
        <input type="checkbox" checked> Not Recommended due to :<br>
        <u>{{$leave->signatory_remarks}}</u> 
        @endif
@endif




</td>

</tr>


<tr>

<td colspan="2" style="padding-top:20px;padding-left:50px;border-right:1px solid;">
<br>
</td>

<td colspan="2" style="padding-top:20px;padding-left:50px;">
<br>
</td>

</tr>



<tr>

<td colspan="2" style="padding-top:20px;border-right:1px solid;">

<center>
        @if($leave->supervisor_remarks=="Approved")
                @if($supervisor_signature!=null)
                        <div style="z-index:1;green;margin-bottom:-500px;"> 
                                <span style="font-size:1.2em;text-transform:uppercase;"><strong>{{$supervisor_name}}</strong></span><br>
                                <span>{{$supervisor_position}}</span>
                        </div>
                        <img src="{{ public_path($supervisor_signature) }}" style="height:50px;margin-top:-30px;"><br>  
                @else
                        <span style="font-size:1.2em;text-transform:uppercase;"><strong>{{$supervisor_name}}</strong></span><br>
                        <span>{{$supervisor_position}}</span>
                @endif
        @else
                <span style="font-size:1.2em;text-transform:uppercase;"><strong>{{$supervisor_name}}</strong></span><br>
                <span>{{$supervisor_position}}</span>        
        @endif
</center>
<br>
&nbsp;&nbsp;Date Signed:
</td>

<td colspan="2" style="padding-top:20px;">
<center>
        @if($leave->is_external==1)
                <span style="font-size:1.2em;text-transform:uppercase;"><strong>{{$leave->external_name}}</strong></span><br>
                <span>{{$leave->external_designation}}</span>

                @if($leave->sub_signatory_remarks=="Approved")
                <img src="{{ public_path($sub_signatory_signature) }}" style="height:15px;margin-top:-10px;margin-left:250px;">
            @endif
        @else
                @if($leave->signatory_remarks=="Approved")
                        @if($signatory_signature!=null)
                                <div style="z-index:1;green;margin-bottom:-500px;"> 
                                        <span style="font-size:1.2em;text-transform:uppercase;"><strong>{{$signatory_name}}</strong></span><br>
                                        <span>{{$signatory_position}}</span>
                                </div>
                                <img src="{{ public_path($signatory_signature) }}" style="height:50px;margin-top:-30px;"><br>  
                        @else
                                <span style="font-size:1.2em;text-transform:uppercase;"><strong>{{$signatory_name}}</strong></span><br>
                                <span>{{$signatory_position}}</span>
                        @endif
                @else
                        <span style="font-size:1.2em;text-transform:uppercase;"><strong>{{$signatory_name}}</strong></span><br>
                        <span>{{$signatory_position}}</span>        
                @endif

                @if($leave->sub_signatory_remarks=="Approved")
                <img src="{{ public_path($sub_signatory_signature) }}" style="height:15px;margin-top:-6px;margin-left:230px;">
                @endif
        @endif
       

</center>
<br>
&nbsp;&nbsp;Date Signed:
</td>

</tr>
@endforeach
</table>

<!-- ************************************ PAGE 2 ************************************ -->
<div style="page-break-before: always;">
<span style="font-weight: bold;font-size: 19px;">Document Log</span><br>
@foreach($leaves as $leave)
<textarea rows="3" style="font-size: 10px;height:500px;border: none;outline: none;" >
{{$leave->remarks}}
</textarea>
@endforeach
</div>
