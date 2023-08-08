<style>
*{
    font-family: Arial, Helvetica, sans-serif;
}

table td, table td * {
    vertical-align: top;
}

</style>

@foreach($leaves as $leave)

<table width="100%" CELLSPACING=0>



<tr>
    <td colspan="3">
        <p style="font-size: 10px;">
            <strong><i>Civil Service Form No. 6<br>
            Revised 2020</i></strong></p>
    </td>

    <td colspan="3">
        <p style="font-size: 13px;text-align: right;">
            <strong>ANNEX A</strong></p>
    </td>
</tr>

<tr>
    <td colspan="1">
        <p style="text-align: left;">
            <img src="{{ public_path('images/ils_icon.jpg') }}" width="70"></p>
    </td>

    <td colspan="4">
        <p style="text-align: center;margin-left:110px;">
            <span style="font-size: 12px;font-weight: bold;">
            Republic of the Philippines<br></span>
            <span style="font-size: 14px;font-weight: bold;">
            Institute for Labor Studies</span><br>
            <span style="font-size: 10px;">6/F BF Condominium Building, A. Soriano Ave. <br>cor. Solana St., Intramuros 1002 Manila, Philippines</span>
        </p>
    </td>

    <td colspan="1">
        <center><p style="font-size: 11px;border: dashed #808080 1px;padding:7px;width: fit-content;">
        {{date('F d, Y', strtotime($leave->created_at))}}</p></center>
    </td>
</tr>

<tr>
    <td colspan="6">
        <center><span style="font-weight: bold;font-size: 19px;">APPLICATION FOR LEAVE<br><br></span></center>
    </td>
</tr>

<!-- LEAVE FORM HEADER END-->

<!-- FORM DATA START -->

<!-- FORM FIELDS ROW 1 START-->
<tr>

    <td colspan="2" style="border-left:solid #000000 1.5px;border-top:solid #000000 1.5px;">
        <span style="font-size: 10px;">1. OFFICE/DEPARTMENT</span>
    </td>

    <td style="border-top:solid #000000 1.5px;">
        <span style="font-size: 10px;">2. NAME :</span>    
    </td>

    <td style="border-top:solid #000000 1.5px;">
        <span style="font-size: 10px;">(Last)</span>
    </td>

    <td style="border-top:solid #000000 1.5px;">
        <span style="font-size: 10px;">(First)</span>
    </td>

    <td style="border-right:solid #000000 1.5px;border-top:solid #000000 1.5px;">
        <span style="font-size: 10px;">(Middle)</span>
    </td>
</tr>
<!-- FORMS FIELDS ROW 1 END-->


<!--FORM VALUES ROW 1 START-->
<tr>
    <td colspan="3" style="border-left:solid #000000 1.5px;border-bottom:solid #000000 1.5px;">
        <span style="font-size: 12px;text-transform: uppercase;"><strong>{{$division_short}}</strong></span>
    </td>

    <td style="border-bottom:solid #000000 1.5px;">
        <span style="font-size: 12px;text-transform: uppercase;"><strong>{{$leave->lastname}}</strong></span>
    </td>

    <td style="border-bottom:solid #000000 1.5px;">
        <span style="font-size: 12px;text-transform: uppercase;"><strong>{{$leave->firstname}}</strong></span>
    </td>

    <td style="border-right:solid #000000 1.5px;border-bottom:solid #000000 1.5px;">
        <span style="font-size: 12px;text-transform: uppercase;"><strong>{{substr($leave->middlename, 0, 1)}}.</strong></span>
    </td>
</tr>
<!--FORM VALUES ROW 1 END-->


<!-- FORM FIELDS AND VALUES ROW 2 START-->
<tr>

    <td colspan="3" style="padding-top:11px;padding-bottom:5px;border-left:solid #000000 1.5px;">
        <span style="font-size: 10px;">3. DATE OF FILING : </span>
           <span style="font-size: 12px;text-transform: uppercase;"><strong><u>{{date('F d, Y', strtotime($leave->created_at))}}</u></strong></span>
    </td>

    <td colspan="2" style="padding-top:11px;padding-bottom:5px;">
        <span style="font-size: 10px;">4. POSITION : </span>
            <span style="font-size: 11px;text-transform: uppercase;"><strong><u>{{$leave->position}}</u></strong></span>
    </td>

    <td style="padding-top:11px;padding-bottom:5px;border-right:solid #000000 1.5px;">
        <span style="font-size: 10px;">5. SALARY : </span>
            <span style="font-size: 11px;text-transform: uppercase;"><strong><u>SG {{$leave->sg}}</u></strong></span>
    </td>

   

</tr>
<!-- FORMS FIELDS AND VALUES ROW 2 END-->


<!-- FORM FIELDS ROW 3 START-->
<tr>
    <td colspan="6" style="border-right:solid #000000 1.5px;border-left:solid #000000 1.5px;border-top:double #000000 4px;border-bottom:double #000000 4px;">
        <p style="text-align:center;font-size: 12px;text-transform: uppercase;"><strong>6. DETAILS OF APPLICATION</strong></p>
    </td>
</tr>
<!-- FORMS FIELDS ROW 3 END-->


<!-- ROW 4 START-->
<tr>
    <td colspan="4" style="padding-top:5px;border-right:solid #000000 1.5px;border-left:solid #000000 1.5px;">
        <span style="font-size: 10px;">6.A TYPE OF LEAVE TO BE AVAILED OF</span>
    </td>

    <td colspan="2" style="padding-top:5px;border-right:solid #000000 1.5px;">
        <span style="font-size: 10px;">6.B DETAILS OF LEAVE</span>
    </td>

</tr>
<!-- ROW 4 END-->

<!-- ROW 5 START-->
<tr>
    <td colspan="4" style="border-right:solid #000000 1.5px;border-left:solid #000000 1.5px;">
        <div style="padding-left:6px;">
            <p style="font-size: 11px;margin:5px 5px 5px 0px;"><strong>[{{$vl}}]</strong> Vacation Leave 
                <span style="font-size:8px;font-family: 'Arial Narrow', Arial, sans-serif;">(Sec. 51, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span>
            </p>
            <p style="font-size: 11px;margin:5px 5px 5px 0px;"><strong>[{{$mfl}}]</strong> Mandatory/Forced Leave
                <span style="font-size:8px;font-family: 'Arial Narrow', Arial, sans-serif;">(Sec. 25, Rule XVI, Omnibus Rules Implementing E.O. No. 292) </span>
            </p>
            
            <p style="font-size: 11px;margin:5px 5px 5px 0px;"><strong>[{{$sl}}]</strong> Sick Leave 
                <span style="font-size:8px;font-family: 'Arial Narrow', Arial, sans-serif;">(Sec. 43, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span>
            </p>

            <p style="font-size: 11px;margin:5px 5px 5px 0px;"><strong>[{{$ml}}]</strong> Maternity Leave 
                <span style="font-size:8px;font-family: 'Arial Narrow', Arial, sans-serif;">(R.A. No. 11210 / IRR issued by CSC, DOLE and SSS)</span>
            </p>

            <p style="font-size: 11px;margin:5px 5px 5px 0px;"><strong>[{{$pl}}]</strong> Paternity Leave 
                <span style="font-size:8px;font-family: 'Arial Narrow', Arial, sans-serif;">(R.A. No. 8187 / CSC MC No. 71, s. 1998, as amended)</span>
            </p>

            <p style="font-size: 11px;margin:5px 5px 5px 0px;"><strong>[{{$slp}}]</strong> Special Privilege Leave 
                <span style="font-size:8px;font-family: 'Arial Narrow', Arial, sans-serif;">(Sec. 21, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span>
            </p>

            <p style="font-size: 11px;margin:5px 5px 5px 0px;"><strong>[{{$slp2}}]</strong> Solo Parent Leave 
                <span style="font-size:8px;font-family: 'Arial Narrow', Arial, sans-serif;">(RA No. 8972 / CSC MC No. 8, s. 2004)</span>
            </p>

            <p style="font-size: 11px;margin:5px 5px 5px 0px;"><strong>[{{$sl2}}]</strong> Study Leave 
                <span style="font-size:8px;font-family: 'Arial Narrow', Arial, sans-serif;">(Sec. 68, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span>
            </p>

            <p style="font-size: 11px;margin:5px 5px 5px 0px;"><strong>[{{$vawcl}}]</strong> 10-Day VAWC Leave 
                <span style="font-size:8px;font-family: 'Arial Narrow', Arial, sans-serif;">(RA No. 9262 / CSC MC No. 15, s. 2005)</span>
            </p>

            <p style="font-size: 11px;margin:5px 5px 5px 0px;"><strong>[{{$rl}}]</strong> Rehabilitation Privilege 
                <span style="font-size:8px;font-family: 'Arial Narrow', Arial, sans-serif;">(Sec. 55, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span>
            </p>

            <p style="font-size: 11px;margin:5px 5px 5px 0px;"><strong>[{{$slbw}}]</strong> Special Leave Benefits for Women 
                <span style="font-size:8px;font-family: 'Arial Narrow', Arial, sans-serif;">(RA No. 9710 / CSC MC No. 25, s. 2010)</span>
            </p>

            <p style="font-size: 11px;margin:5px 5px 5px 0px;"><strong>[{{$sel}}]</strong> Special Emergency (Calamity) Leave
                <span style="font-size:8px;font-family: 'Arial Narrow', Arial, sans-serif;">(CSC MC No. 2, s. 2012, as amended)</span>
            </p>

            <p style="font-size: 11px;margin:5px 5px 5px 0px;"><strong>[{{$al}}]</strong> Adoption Leave
                <span style="font-size:8px;font-family: 'Arial Narrow', Arial, sans-serif;">(R.A. No. 8552)</span>
            </p>


        </div>
    </td>

    <td colspan="2" style="border-right:solid #000000 1.5px;">
        <div style="padding-left:6px;">
            <p style="font-size: 11px;margin:5px;"><i>In case of Vacation/Special Privilege Leave:</i></p>
            @if($vl=="X")
                @if($leave_details=="within_the_philippines")    
                <span style="font-size: 11px;margin:5px;"><strong>[X]</strong> Within the Philippines :  <u style="font-size:8px;">{{ json_decode($leave->leave_attributes)->slp_vacay_details_input }}</u></p>
                <p style="font-size: 11px;margin:5px;"><strong>[ ]</strong> Abroad (Specify) : ________________</p>
                @else
                <p style="font-size: 11px;margin:5px;"><strong>[ ]</strong> Within the Philippines : __________</p>
                <p style="font-size: 11px;margin:5px;"><strong>[X]</strong> Abroad (Specify) : <u>{{ json_decode($leave->leave_attributes)->slp_vacay_details_input }}</u></p>
                @endif
            @else
                <p style="font-size: 11px;margin:5px;"><strong>[ ]</strong> Within the Philippines : __________</p>
                <p style="font-size: 11px;margin:5px;"><strong>[ ]</strong> Abroad (Specify) : ________________</p>
            @endif

            <p style="font-size: 11px;margin:5px;"><i>In case of Sick Leave:</i></p>
            @if($sl=="X")
                @if($leave_details=="in_hospital")
                    <p style="font-size: 11px;margin:5px;"><strong>[X]</strong> In Hospital (Specify Illness) : <u>{{ json_decode($leave->leave_attributes)->sick_details_input }}</u></p>
                    <p style="font-size: 11px;margin:5px;"><strong>[ ]</strong> Out Hospital (Specify Illness) : ________________</p>
                    <p style="font-size: 11px;margin:5px;"> ______________________________________________</p>
                @else
                    <p style="font-size: 11px;margin:5px;"><strong>[ ]</strong> In Hospital (Specify Illness) : __________</p>
                    <p style="font-size: 11px;margin:5px;"><strong>[X]</strong> Out Hospital (Specify Illness) : <u>{{ json_decode($leave->leave_attributes)->sick_details_input }}</u></p>
                    <p style="font-size: 11px;margin:5px;"> ______________________________________________</p>
                @endif
            
            @else
                <p style="font-size: 11px;margin:5px;"><strong>[ ]</strong> In Hospital (Specify Illness) : __________</p>
                <p style="font-size: 11px;margin:5px;"><strong>[ ]</strong> Out Hospital (Specify Illness) : ________________</p>
                <p style="font-size: 11px;margin:5px;"> ______________________________________________</p>
            @endif            

            <p style="font-size: 11px;margin:5px;"><i>In case of Special Leave Benefits for Women:</i></p>
            @if($slbw=="X")
            <p style="font-size: 11px;margin:5px;">(Specify Illness) : <u>{{ json_decode($leave->leave_attributes)->slbfw_details_input }}</u></p>
            @else
            <p style="font-size: 11px;margin:5px;">(Specify Illness) : __________</p>
            @endif
            <p style="font-size: 11px;margin:5px;"> ______________________________________________</p>

            <p style="font-size: 11px;margin:5px;"><i>In case of Study Leave:</i></p>
            @if($sl2=="X")
                @if($leave_details=="completion_masters")
                    <p style="font-size: 11px;margin:5px;"><strong>[X]</strong> Completion of Master's Degree</p>
                    <p style="font-size: 11px;margin:5px;"><strong>[ ]</strong> BAR/Board Examination Review</p>
                @else
                    <p style="font-size: 11px;margin:5px;"><strong>[ ]</strong> Completion of Master's Degree</p>
                    <p style="font-size: 11px;margin:5px;"><strong>[X]</strong> BAR/Board Examination Review</p>
                @endif
            @else
                <p style="font-size: 11px;margin:5px;"><strong>[ ]</strong> Completion of Master's Degree</p>
                <p style="font-size: 11px;margin:5px;"><strong>[ ]</strong> BAR/Board Examination Review</p>
            @endif

        </div>
    </td>
</tr>
<!-- ROW 5 END-->



<!-- ROW 6 START-->
<tr>
    <td colspan="4" style="border-right:solid #000000 1.5px;border-left:solid #000000 1.5px;">
        <p> </p>
        <p style="font-size: 11px;margin:10px;"><i>Others:</i></p>
        @if($ol=="")
        <p style="font-size: 11px;margin:10px;">____________________________</p>
        @else
        <p style="font-size: 11px;margin:10px;"><u>{{$ol_specify}}</u></p>
        @endif
    </td>

    <td colspan="2" style="border-right:solid #000000 1.5px;">
        <p style="font-size: 11px;margin:10px;"><i>Others purpose:</i></p>

        @if($ol_details)
            @if($ol_details=="terminal_leave")
            <p style="font-size: 11px;margin:5px 5px 5px 10px;"><strong>[ ]</strong> Monetization of Leave Credits</p>
            <p style="font-size: 11px;margin:5px 5px 5px 10px;"><strong>[X]</strong> Terminal Leave</p>
            @endif
            
            @if($ol_details=="monetization_leave")
            <p style="font-size: 11px;margin:5px 5px 5px 10px;"><strong>[X]</strong> Monetization of Leave Credits</p>
            <p style="font-size: 11px;margin:5px 5px 5px 10px;"><strong>[ ]</strong> Terminal Leave</p>
            @endif

        @else
            <p style="font-size: 11px;margin:5px 5px 5px 10px;"><strong>[ ]</strong> Monetization of Leave Credits</p>
            <p style="font-size: 11px;margin:5px 5px 5px 10px;"><strong>[ ]</strong> Terminal Leave</p>        
        @endif

    </td>
</tr>

<!-- ROW 6 END-->

<!-- ROW 7 START -->

<tr>
    <td colspan="4" style="padding-top:5px; padding-bottom:5px;border-top:solid #000000 1.5px;border-left:solid #000000 1.5px;border-right:solid #000000 1.5px;">
        <span style="font-size: 10px;">6.C NUMBER OF WORKING DAYS APPLIED FOR</span>
    </td>

    <td colspan="2" style="padding-top:5px;padding-bottom:5px;border-top:solid #000000 1.5px;border-right:solid #000000 1.5px;">
        <span style="font-size: 10px;">6.D COMMUTATION</span>
    </td>
</tr>

<!-- ROW 7 END -->


<!-- ROW 8 START-->
<tr>
    <td colspan="4" style="padding-top:5px; padding-bottom:5px;border-left:solid #000000 1.5px;border-right:solid #000000 1.5px;">
        <span style="font-size: 10px;margin-left:20px;"><u style="margin-left:20px;">{{$leave->no_days}} day/s</u></span><br>
        <span style="font-size: 10px;margin-left:20px;">INCLUSIVE DATES</span>
    </td>

    <td colspan="2" style="padding-top:5px;padding-bottom:5px;border-right:solid #000000 1.5px;">
    @if($leave->commutation==0)
        <span style="font-size: 11px;margin-left: 20px;"><strong>[X]</strong> Not Requested</span><br>
        <span style="font-size: 11px;margin-left: 20px;"><strong>[ ]</strong> Requested</span><br>
    @else
        <span style="font-size: 11px;margin-left: 20px;"><strong>[ ]</strong> Not Requested</span><br>
        <span style="font-size: 11px;margin-left: 20px;"><strong>[X]</strong> Requested</span><br>
    @endif
    </td>
</tr>
<!-- ROW 8 END -->

<!-- ROW 9 START-->
<tr>
    <td colspan="4" style="padding-top:5px; padding-bottom:5px;border-left:solid #000000 1.5px;border-right:solid #000000 1.5px;">
    @if($ol_details!="monetization_leave")
        <span style="font-size: 10px;"><u style="margin-left:20px;">{{$leave->inclusive_dates}}</u></span>
    @else
        <span style="font-size: 10px;margin-left:20px;">________________</span>
    @endif
    </td>

    <td colspan="2" style="padding-top:5px;padding-bottom:5px;border-right:solid #000000 1.5px;">
        <center>
        @if($leave->signature!=null)
        <div style="z-index:1;green;margin-bottom:-500px;">
            
            <span style="text-align:center;"><span style="margin-left:10px;font-size: 12px;text-transform: uppercase;"><strong><u>{{$employee_name}}</u></strong></span><br>
        </div>
        <img src="{{ public_path($leave->signature) }}" style="height:50px;margin-top:-30px;"><br>  
        @else

        <span style="text-align:center;"><span style="margin-left:10px;font-size: 12px;text-transform: uppercase;"><strong><u>{{$employee_name}}</u></strong></span><br>
        @endif

 
        <span style="font-size: 10px;">(Signature of Applicant)</span></span>
        </center>
    </td>
</tr>
<!-- ROW 9 END -->

<!-- ROW 10 START-->
<tr>
    <td colspan="6" style="border-right:solid #000000 1.5px;border-left:solid #000000 1.5px;border-top:double #000000 4px;border-bottom:double #000000 4px;">
        <p style="text-align:center;font-size: 12px;text-transform: uppercase;"><strong>7. DETAILS OF ACTION ON APPLICATION</strong></p>
    </td>
</tr>
<!-- ROW 10 END-->

<!-- ROW 11 START -->

<tr>
    <td colspan="4" style="padding-top:5px; padding-bottom:5px;border-left:solid #000000 1.5px;border-right:solid #000000 1.5px;">
        <span style="font-size: 10px;">7.A CERTIFICATION OF LEAVE CREDITS</span>
    </td>

    <td colspan="2" style="padding-top:5px;padding-bottom:5px;border-right:solid #000000 1.5px;">
        <span style="font-size: 10px;">7.B RECOMMENDATION</span>
    </td>
</tr>

<!-- ROW 11 END -->


<!-- ROW 12 START -->

<tr>
    <td colspan="4" style="border-left:solid #000000 1.5px;border-right:solid #000000 1.5px;">
    @if($leave->credits_id==null)
    <span style="font-size: 10px;margin-left: 100px;">As of: ___________________</span>
    @else
    <span style="font-size: 10px;margin-left: 100px;">As of: <u>{{$date_certification}}</u></span>
    @endif

    </td>

    <td colspan="2" style="border-right:solid #000000 1.5px;">
    @if($leave->supervisor_remarks=="Approved")
        @if($mfl=='X')
                @if($leave->leave_remarks!="n/a")
                <span style="font-size: 11px;margin-left:10px;"><strong>[ ]</strong> For approval</span>
                @else
                <span style="font-size: 11px;margin-left:10px;"><strong>[X]</strong> For approval</span>
                @endif
        @else        
            <span style="font-size: 11px;margin-left:10px;"><strong>[X]</strong> For approval</span>
        @endif        
    @else
        <span style="font-size: 11px;margin-left:10px;"><strong>[ ]</strong> For approval</span>
    @endif

    </td>
</tr>

<!-- ROW 12 END -->

<!-- ROW 13 START -->

<tr>
    <td colspan="4" style="border-left:solid #000000 1.5px;border-right:solid #000000 1.5px;">
        
        <table CELLSPACING=0 style="text-align: center;width: 350px;margin-left:28px;">
        <tr>
            <td style="font-size: 11px;border:solid #000000 1.5px;"> </td>
            <td style="font-size: 11px;border-top:solid #000000 1.5px;border-bottom:solid #000000 1.5px;border-right:solid #000000 1.5px;">Vacation Leave</td>
            <td style="font-size: 11px;border-top:solid #000000 1.5px;border-bottom:solid #000000 1.5px;border-right:solid #000000 1.5px;">Sick Leave</td>
        </tr>

        <tr>
            <td style="font-size: 11px;border-left:solid #000000 1.5px;border-right:solid #000000 1.5px;"><i>Total Earned</i></td>
            <td style="font-size: 11px;border-right:solid #000000 1.5px;">{{$total_vl}}</td>
            <td style="font-size: 11px;border-right:solid #000000 1.5px;">{{$total_sl}}</td>
        </tr>

        <tr>
            <td style="font-size: 11px;border-top:solid #000000 1.5px;border-left:solid #000000 1.5px;border-right:solid #000000 1.5px;"><i>Less this application</i></td>
            <td style="font-size: 11px;border-top:solid #000000 1.5px;border-right:solid #000000 1.5px;">{{$less_vl}}</td>
            <td style="font-size: 11px;border-top:solid #000000 1.5px;border-right:solid #000000 1.5px;">{{$less_sl}}</td>
        </tr>

        <tr>
            <td style="font-size: 11px;border:solid #000000 1.5px;"><i>Balance</i></td>
            <td style="font-size: 11px;border-bottom:solid #000000 1.5px;border-top:solid #000000 1.5px;border-right:solid #000000 1.5px;">{{$balance_vl}}</td>
            <td style="font-size: 11px;border-bottom:solid #000000 1.5px;border-top:solid #000000 1.5px;border-right:solid #000000 1.5px;">{{$balance_sl}}</td>
        </tr>

        </table>

    </td>

    <td colspan="2" style="border-right:solid #000000 1.5px;">
    @if($leave->supervisor_remarks=="waiting")
        <span style="font-size: 11px;margin-left:10px;"><strong>[ ]</strong> For disapproval due to _________________</span>    
    @else
        @if($leave->supervisor_remarks=="Approved")
            @if($mfl=='X')
                @if($leave->leave_remarks!="n/a")
                <span style="font-size: 11px;margin-left:10px;"><strong>[X]</strong> For disapproval due to <u>{{$leave->leave_remarks}}</u></span>            
                @else
                <span style="font-size: 11px;margin-left:10px;"><strong>[ ]</strong> For disapproval due to _________________</span>                
                @endif

            @else
            <span style="font-size: 11px;margin-left:10px;"><strong>[ ]</strong> For disapproval due to _________________</span>
            @endif
        @else
        <span style="font-size: 11px;margin-left:10px;"><strong>[X]</strong> For disapproval due to </span>
        @endif
    @endif

        
    </td>

</tr>
<!-- ROW 13 END -->

<!-- start reason disapproval division chief -->
@if($leave->supervisor_remarks!="waiting")
        @if($leave->leave_remarks=="n/a")
            <u style="position:absolute;font-size: 11px;width:300px;top:850px;left:420px;">{{$leave->supervisor_remarks}}</u>
        @endif
@endif
<!-- end-->

<!-- ROW 14 START-->
<tr>
    <td colspan="4" style="padding-top:20px; padding-bottom:5px;border-left:solid #000000 1.5px;border-right:solid #000000 1.5px;">
        <center>
        <br>
        @if($leave->hr_remarks=="Approved")
                @if($hr_signature!=null)
                    <div style="z-index:1;green;margin-bottom:-500px;"> 
                        <span style="text-align:center;"><span style="margin-left:10px;font-size: 12px;text-transform: uppercase;"><strong><u>{{$hr_name}}</u></strong></span><br>
                    </div>
                    <img src="{{ public_path($hr_signature) }}" style="height:50px;margin-top:-30px;"><br>  
                    <span style="font-size: 10px;">{{$hr_position}}</span>
                @else
                    <span style="text-align:center;"><span style="margin-left:10px;font-size: 12px;text-transform: uppercase;"><strong><u>{{$hr_name}}</u></strong></span><br>
                    <span style="font-size: 10px;">{{$hr_position}}</span>
                @endif
        @else
            <span style="text-align:center;"><span style="margin-left:10px;font-size: 12px;text-transform: uppercase;"><strong><u>{{$hr_name}}</u></strong></span><br>
            <span style="font-size: 10px;">{{$hr_position}}</span>
        @endif
        
        

        </center>
    </td>

    <td colspan="2" style="padding-top:20px;padding-bottom:5px;border-right:solid #000000 1.5px;">
        <center>    
        <br>

        @if($leave->supervisor_remarks=="Approved")
            @if($supervisor_signature!=null)
                <div style="z-index:1;green;margin-bottom:-500px;"> 
                    <span style="text-align:center;"><span style="margin-left:10px;font-size: 12px;text-transform: uppercase;"><strong><u>{{$supervisor_name}}</u></strong></span><br>
                </div>
                <img src="{{ public_path($supervisor_signature) }}" style="height:50px;margin-top:-30px;"><br>  
                <span style="font-size: 10px;">{{$supervisor_position}}</span>
            @else
                <span style="text-align:center;"><span style="margin-left:10px;font-size: 12px;text-transform: uppercase;"><strong><u>{{$supervisor_name}}</u></strong></span><br>
                <span style="font-size: 10px;">{{$supervisor_position}}</span>
            @endif
        @else
            <span style="text-align:center;"><span style="margin-left:10px;font-size: 12px;text-transform: uppercase;"><strong><u>{{$supervisor_name}}</u></strong></span><br>
            <span style="font-size: 10px;">{{$supervisor_position}}</span>
        @endif
        </center>
    </td>
</tr>
<!-- ROW 14 END-->

<!-- ROW 15 START -->
<tr>
    <td colspan="4" style="padding-top:5px; padding-bottom:5px;border-top:double #000000 4px;border-left:solid #000000 1.5px;">
        <span style="font-size: 10px;">7.C APPROVED FOR:</span>
    </td>

    <td colspan="2" style="padding-top:5px;padding-bottom:5px;border-top:double #000000 4px;border-right:solid #000000 1.5px;">
        <span style="font-size: 10px;">7.D DISAPPROVED DUE TO:</span>
    </td>
</tr>
<!-- ROW 15 END-->

<!-- ROW 16 START -->
<tr>
    <td colspan="4" style="padding-bottom:5px;border-left:solid #000000 1.5px;">
        <span style="font-size: 10px;margin-left:10px;">_______ days with pay </span>
    </td>

    <td colspan="2" style="padding-bottom:5px;border-right:solid #000000 1.5px;">
        <span style="font-size: 10px;margin-left:10px;">_____________________</span>
    </td>
</tr>
<!-- ROW 16 END-->

<!-- ROW 16 START -->
<tr>
    <td colspan="4" style="padding-bottom:5px;border-left:solid #000000 1.5px;">
        <span style="font-size: 10px;margin-left:10px;">_______ days without pay </span>
    </td>

    <td colspan="2" style="padding-bottom:5px;border-right:solid #000000 1.5px;">
        <span style="font-size: 10px;margin-left:10px;">_____________________</span>
    </td>
</tr>
<!-- ROW 16 END-->

<!-- ROW 16 START -->
<tr>
    <td colspan="4" style="padding-bottom:5px;border-left:solid #000000 1.5px;">
        <span style="font-size: 10px;margin-left:10px;">_______ others (Specify) </span>
    </td>

    <td colspan="2" style="padding-bottom:5px;border-right:solid #000000 1.5px;">
        <span style="font-size: 10px;margin-left:10px;">_____________________</span>
    </td>
</tr>
<!-- ROW 16 END-->

<!-- ROW 17 START -->
<tr>
    <td colspan="6" style="padding-bottom:5px;border-left:solid #000000 1.5px;border-right:solid #000000 1.5px;border-bottom:solid #000000 1.5px;">
        <center>
        <br><br><br>

        @if($leave->is_external==1)
            <span style="text-align:center;"><span style="margin-left:10px;font-size: 12px;text-transform: uppercase;"><strong><u>{{$leave->external_name}}</u></strong></span><br>
            <span style="font-size: 10px;">{{$leave->external_designation}}</span></center>

            @if($leave->sub_signatory_remarks=="Approved")
                <img src="{{ public_path($sub_signatory_signature) }}" style="height:15px;margin-top:-20px;margin-left:220px;">
            @endif

        @else
            @if($leave->signatory_remarks=="Approved")
                @if($signatory_signature!=null)
                    <div style="z-index:1;margin-bottom:-500px;"> 
                    <span style="text-align:center;"><span style="margin-left:10px;font-size: 12px;text-transform: uppercase;"><strong><u>{{$signatory_name}}</u></strong></span><br>
                    </div>
                    <img src="{{ public_path($signatory_signature) }}" style="height:50px;margin-top:-30px;"><br>  
                    <span style="font-size: 10px;">{{$signatory_position}}</span></center>
                @else
                <span style="text-align:center;"><span style="margin-left:10px;font-size: 12px;text-transform: uppercase;"><strong><u>{{$signatory_name}}</u></strong></span><br>
                <span style="font-size: 10px;">{{$signatory_position}}</span></center>
                @endif
            @else
            <span style="text-align:center;"><span style="margin-left:10px;font-size: 12px;text-transform: uppercase;"><strong><u>{{$signatory_name}}</u></strong></span><br>
            <span style="font-size: 10px;">{{$signatory_position}}</span></center>
            @endif

            @if($leave->sub_signatory_remarks=="Approved")
                <img src="{{ public_path($sub_signatory_signature) }}" style="height:15px;margin-top:-20px;margin-left:250px;">
            @endif
        @endif
       

    </td>
</tr>
<!-- ROW 17 END-->

@endforeach
</table>

<!-- ************************************ PAGE 2 ************************************ -->
<div style="page-break-before: always;">

<table width="100%" CELLSPACING=0>
    <tbody>
    <tr>
        <td colspan="2" style="border:solid #000000 1px;">
            <p style="text-align:center;font-size: 14px;"><b>INSTRUCTIONS AND REQUIREMENTS</b</p>
        </td>
    </tr>

    <tr>
        <td style="font-size:11px;padding-right:40px;">
        <p style="text-align:justify;">Application for any type of leave shall be made on this Form and <strong style="text-decoration: underline;">to be
        accomplished at least in duplicate</strong> with documentary requirements, as
        follows:</p>

        <strong>1. Vacation leave*</strong>
        <p style="text-align:justify;margin-top:0px;padding-left:12px;"> 
            It shall be filed five (5) days in advance, whenever possible, of the
            effective date of such leave. Vacation leave within in the Philippines or
            abroad shall be indicated in the form for purposes of securing travel
            authority and completing clearance from money and work
            accountabilities.
        </p>
         
        <strong>2. Mandatory/Forced leave</strong>
        <p style="text-align:justify;margin-top:0px;padding-left:12px;">
            Annual five-day vacation leave shall be forfeited if not taken during the
            year. In case the scheduled leave has been cancelled in the exigency
            of the service by the head of agency, it shall no longer be deducted from
            the accumulated vacation leave. Availment of one (1) day or more
            Vacation Leave (VL) shall be considered for complying the
            mandatory/forced leave subject to the conditions under Section 25, Rule
            XVI of the Omnibus Rules Implementing E.O. No. 292.
        </p>
        
        <strong>3. Sick leave*</strong>
        <li style="padding-left:25px;text-align:justify;">It shall be filed immediately upon employee's return from such leave.</li>
        <li style="padding-left:25px;text-align:justify;">If filed in advance or exceeding five (5) days, application shall be
        accompanied by a <u>medical certificate</u>. In case medical consultation was not availed of, an <u>affidavit</u> should be executed by an applicant.</li>   
        <br><br>
             
        <strong>4. Maternity leave* – 105 days</strong>
        <li style="padding-left:25px;"> Proof of pregnancy e.g. ultrasound, doctor’s certificate on the
        expected date of delivery</li>
        <li style="padding-left:25px;">Accomplished Notice of Allocation of Maternity Leave Credits (CS
        Form No. 6a), if needed</li>
        <li style="padding-left:25px;">Seconded female employees shall enjoy maternity leave with full pay
        in the recipient agency.</li>
        <br><br>

        <strong>5. Paternity leave – 7 days</strong>
        <p style="text-align:justify;margin-top:0px;padding-left:12px;">
            Proof of child’s delivery e.g. birth certificate, medical certificate and
            marriage contract
        </p>
        
        <strong>6. Special Privilege leave – 3 days</strong>
        <p style="text-align:justify;margin-top:0px;padding-left:12px;">
            It shall be filed/approved for at least one (1) week prior to availment,
            except on emergency cases. Special privilege leave within the
            Philippines or abroad shall be indicated in the form for purposes of
            securing travel authority and completing clearance from money and work
            accountabilities.
        </p>

        <strong>7. Solo Parent leave – 7 days</strong>
        <p style="text-align:justify;margin-top:0px;padding-left:12px;">
            It shall be filed in advance or whenever possible five (5) days before
            going on such leave with updated Solo Parent Identification Card.
            </p>
        

        <strong>8. Study leave* – up to 6 months</strong>
        <li style="padding-left:25px;">Shall meet the agency’s internal requirements, if any;</li>
        <li style="padding-left:25px;">Contract between the agency head or authorized representative and the employee concerned.</li> 
        <br><br>

        <strong>9. VAWC leave – 10 days</strong>
        <li style="padding-left:25px;">It shall be filed in advance or immediately upon the woman
            employee’s return from such leave.</li>
        <li style="padding-left:25px;">It shall be accompanied by any of the following supporting documents:</li>
        <p style="text-align:justify;margin-top:0px;margin-bottom:0px;padding-left:24px;">a. Barangay Protection Order (BPO) obtained from the barangay;</p>
        <p style="text-align:justify;margin-top:0px;margin-bottom:0px;padding-left:24px;">b. Temporary/Permanent Protection Order (TPO/PPO) obtained from the court;</p>      
        <p style="text-align:justify;margin-top:0px;margin-bottom:0px;padding-left:24px;">c. If the protection order is not yet issued by the barangay or the court, a certification issued by the Punong Barangay/Kagawad or Prosecutor or the Clerk of Court that the application for the BPO,</p>
        <br>
        <br>
        _________________________
        </td>

        <td style="font-size:11px;">
        <p style="text-align:justify;margin-bottom:0px;padding-left:32px;">TPO or PPO has been filed with the said office shall be sufficient
        to support the application for the ten-day leave; or.</p>
        <p style="text-align:justify;margin-bottom:0px;margin-top:0px;padding-left:20px;">d. In the absence of the BPO/TPO/PPO or the certification, a</p>
        <p style="text-align:justify;margin-top:0px;padding-left:32px;">
        police report specifying the details of the occurrence of violence on the
        victim and a medical certificate may be considered, at the
        discretion of the immediate supervisor of the woman employee concerned.</p>
        
        <strong>10. Rehabilitation leave* – up to 6 months</strong>
        <li style="text-align:justify;padding-left:30px;">Application shall be made within one (1) week from the time of the
            accident except when a longer period is warranted.</li> 
        <li style="text-align:justify;padding-left:30px;">Letter request supported by relevant reports such as the police
            report, if any,</li> 
        <li style="text-align:justify;padding-left:30px;">Medical certificate on the nature of the injuries, the course of
            treatment involved, and the need to undergo rest, recuperation, and
            rehabilitation, as the case may be.</li>
        <li style="text-align:justify;padding-left:30px;">Written concurrence of a government physician should be obtained
            relative to the recommendation for rehabilitation if the attending
            physician is a private practitioner, particularly on the duration of the
            period of rehabilitation.</li>
            <br><br>

            <strong>11. Special leave benefits for women* – up to 2 months</strong>
            <li style="text-align:justify;padding-left:30px;">The application may be filed in advance, that is, at least five (5) days
            prior to the scheduled date of the gynecological surgery that will be
            undergone by the employee. In case of emergency, the application
            for special leave shall be filed immediately upon employee’s return
            but during confinement the agency shall be notified of said surgery.</li>

            <li style="text-align:justify;padding-left:30px;">The application shall be accompanied by a medical certificate filled
            out by the proper medical authorities, e.g. the attending surgeon
            accompanied by a clinical summary reflecting the gynecological
            disorder which shall be addressed or was addressed by the said
            surgery; the histopathological report; the operative technique used
            for the surgery; the duration of the surgery including the peri-
            operative period (period of confinement around surgery); as well as
            the employees estimated period of recuperation for the same.</li>
            <br><br>

            <strong>12. Special Emergency (Calamity) leave – up to 5 days</strong>
            <li style="text-align:justify;padding-left:30px;">The special emergency leave can be applied for a maximum of five
            (5) straight working days or staggered basis within thirty (30) days
            from the actual occurrence of the natural calamity/disaster. Said
            privilege shall be enjoyed once a year, not in every instance of
            calamity or disaster.</li>
            <li style="text-align:justify;padding-left:30px;"> The head of office shall take full responsibility for the grant of special
            emergency leave and verification of the employee’s eligibility to be
            granted thereof. Said verification shall include: validation of place of
            residence based on latest available records of the affected
            employee; verification that the place of residence is covered in the
            declaration of calamity area by the proper government agency; and
            such other proofs as may be necessary.</li> 
            <br><br>
            <strong>13. Monetization of leave credits</strong>
            <p style="text-align:justify;margin-top:0px;padding-left:20px;">Application for monetization of fifty percent (50%) or more of the
                accumulated leave credits shall be accompanied by letter request to
                the head of the agency stating the valid and justifiable reasons.</p>
            
            <strong>14. Terminal leave*</strong>
            <p style="text-align:justify;margin-top:0px;padding-left:20px;">Proof of employee’s resignation or retirement or separation from the
                service.</p>
            <strong>15. Adoption Leave</strong>
            <li style="text-align:justify;padding-left:30px;">Application for adoption leave shall be filed with an authenticated
            copy of the Pre-Adoptive Placement Authority issued by the
            Department of Social Welfare and Development (DSWD).</p>
        </td>
    </tr>

    <tr>
        <td colspan="2">
            <span style="font-size: 10.5px;">
            * For leave of absence for thirty (30) calendar days or more and terminal leave, application shall be accompanied by a <span style="text-decoration: underline;">clearance from money, property and work-related accountabilities</span> (pursuant to CSC Memorandum Circular No. 2, s. 1985)
            </span>     
        </td>
    </tr>
</tbody>
    

</table>
</div>

<!-- ************************************ PAGE 3 ************************************ -->
<div style="page-break-before: always;">
<span style="font-weight: bold;font-size: 19px;">Document Log</span><br>
@foreach($leaves as $leave)
<textarea rows="3" style="font-size: 10px;height:500px;border: none;outline: none;" >
{{$leave->remarks}}
</textarea>
@endforeach
</div>