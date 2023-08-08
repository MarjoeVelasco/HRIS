
<style>
* {
  font-family: Arial, Helvetica, sans-serif;
  font-size:0.97em;
}
</style>

<table class="table" style="border: 1px solid black;width:100%;" cellspacing="0">
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
<td style="border: 1px solid black;padding:5px;"><span style="font-size:1.2em;"><strong>{{$date_filed}}</strong></span></td> 
<td colspan="2"rowspan="3" style="border: 1px solid black;padding:5px;">
<center>
<br><br>___________________________<br>
<span style="font-size:0.80em;">Signature of Applicant</span>

</center>
</td> 
</tr> 

<tr> 
<td style="border: 1px solid black;padding:5px;">Name:</td> 
<td style="border: 1px solid black;padding:5px;"><span style="font-size:1.2em;"><strong>{{$name}}</strong></span></td> 
</tr>

<tr> 
<td style="border: 1px solid black;padding:5px;">Designation:</td> 
<td style="border: 1px solid black;padding:5px;"><span style="font-size:1.2em;"><strong>{{$position}}</strong></span></td> 
</tr>

<tr>
<td colspan="2" style="border: 1px solid black;padding:5px;">Number of Hours/Days applied for:</td> 
<td colspan="2" style="border: 1px solid black;padding:5px;"><span style="font-size:1.2em;"><strong>{{$no_days}} days</strong></span></td> 
</tr>

<tr>
<td colspan="2" style="border: 1px solid black;padding:5px;">Date of Availment for Compensatory Time-Off (CTO):</td> 
<td colspan="2" style="border: 1px solid black;padding:5px;"><span style="font-size:1.2em;"><strong>{{$inclusive_dates}}</strong></span></td> 
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
<span style="font-size:1.2em;text-transform:uppercase;"><strong>{{$hr_name}}</strong></span><br>
<span>{{$hr_position}}</span>
</center>

</td>


<td colspan="2">
<center><span style="font-size:0.9em"><strong>CERTIFICATION OF ILC/COC EARNED</strong></span></center>
<table style="border: 1px solid black;width:60%;margin:auto;" cellspacing="0">

@if($note=="Waiting for approval")
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
<td width="85" style="border: 1px solid black;padding:3px;">{{$cert_as_of}}</td>
</tr>

<tr>
<td style="border: 1px solid black;padding:3px;">Number of Hours Earned:</td>
<td style="border: 1px solid black;padding:3px;">{{$hours_earned}}</td>
</tr>

<tr>
<td style="border: 1px solid black;padding:3px;">Date of Last Certification:</td>
<td style="border: 1px solid black;padding:3px;">{{$date_last_cert}}</td>
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
<input type="checkbox"> Recommended<br>
<input type="checkbox"> Not Recommended due to _______<br>
_______________________________
</td>

<td colspan="2" style="padding-top:20px;padding-left:50px;">
<input type="checkbox"> Recommended<br>
<input type="checkbox"> Not Recommended due to _______<br>
_______________________________
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
<center><strong><span style="font-size:1.2em;text-transform:uppercase;">{{$supervisor_name}}</span></strong><br>
{{$supervisor_position}}
</center>
<br>
&nbsp;&nbsp;Date Signed:
</td>

<td colspan="2" style="padding-top:20px;">
<center><strong><span style="font-size:1.2em;text-transform:uppercase;">{{$signatory_name}}</span></strong><br>
{{$signatory_position}}
</center>
<br>
&nbsp;&nbsp;Date Signed:
</td>

</tr>





















</table>