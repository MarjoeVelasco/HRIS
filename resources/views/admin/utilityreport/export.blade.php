<table>


<tbody>

<tr>
<td colspan="5" style="font-weight: bold;text-align:center;">Annex A</td>
</tr>

<tr>
</tr>

<tr>
<td colspan="5" style="font-weight: bold;text-align:center;">Institute for Labor Studies - DOLE</td>
</tr>

<tr>
<td colspan="5" style="font-weight: bold;text-align:center;">ACCOMPLISHMENT REPORT AND CERTIFICATION</td>
</tr>

<tr>
<td colspan="5" style="font-weight: bold;text-align:center;">ON SERVICE RENDERED UNDER THE WORK FROM HOME ARRANGEMENT</td>
</tr>

<tr>
<td colspan="5" style="font-weight: bold;text-align:center;">({{date('F Y', strtotime($month_year))}})</td>
</tr>


<tr>
</tr>

<tr>
<td colspan="1" style="border:2px solid #000000;font-weight:bold;">Office/Division</td>
<td colspan="4" style="border:2px solid #000000;font-weight:bold;font-size:10px">
    @if($division=="APD")
        Advocacy and Publications Division
    @endif
    
    @if($division=="FAD")
        Finance and Administrative Division
    @endif

    @if($division=="ERD")
        Employee Research Division
    @endif

    @if($division=="WWRD")
        Worker's Welfare Research Divsion
    @endif

    @if($division=="LSRRD")
        Labor and Social Relations Research Division
    @endif
    
    @if($division=="OED")
        Office of the Executive Director
    @endif



</td>
</tr>

<tr>
<td colspan="1" style="border:2px solid #000000;font-weight:bold;">Employee Name</td>
<td colspan="4" style="border:2px solid #000000;font-weight:bold;">{{$fullname}}</td>
</tr>

<tr>
<td colspan="1" style="border:2px solid #000000;font-weight:bold;">Employee No.</td>
<td colspan="4" style="border:2px solid #000000;font-weight:bold;"> </td>
</tr>

<tr>
</tr>

<tr>
<td colspan="2" style="border:2px solid #000000;font-weight:bold;">Task/Activity/Accomplishment</td>
<td colspan="3" style="border:2px solid #000000;font-weight:bold;text-align:center;">Dates</td>
</tr>

<!-- DATA -->


    @foreach($users as $user)
        <tr>
        <td style="border-left:2px solid #000000;border-bottom:2px solid #000000;">
        <br>
            {{$user->accomplishment}}        
        <br>
        </td>
        <td colspan="1" style="border-bottom:2px solid #000000;"> </td>
        @if($user->accomplishment)

            @if($user->wstatus)
            <td colspan="3" style="border:2px solid #000000;">{{ date('m/d/Y l', strtotime($user->time_in)) }} <br> [{{$user->wstatus}}]</td>
            @else
            <td colspan="3" style="border:2px solid #000000;">{{ date('m/d/Y l', strtotime($user->time_in)) }}</td>
            @endif
        
        @else

            @if($user->wstatus)
            <td colspan="3" style="height:30px;border:2px solid #000000;">{{ date('m/d/Y l', strtotime($user->time_in)) }} <br> [{{$user->wstatus}}]</td>
            @else
            <td colspan="3" style="height:30px;border:2px solid #000000;">{{ date('m/d/Y l', strtotime($user->time_in)) }}</td>
            @endif
        
        @endif
        
        </tr>
        @endforeach




<!-- END -->

<!--No of WFH -->
<tr>
<td colspan="2" style="font-weight:bold;border:2px solid #000000;">TOTAL no. of WFH</td>
<td colspan="3" style="font-weight:bold;border:2px solid #000000;">{{$wfh_days}} DAYS</td>    
</tr>
<!--end-->

<!--No of WFH -->
<tr>
<td colspan="2" style="font-weight:bold;border:2px solid #000000;">TOTAL no. of In Office</td>
<td colspan="3" style="font-weight:bold;border:2px solid #000000;">{{$inoffice_days}} DAYS</td>    
</tr>
<!--end-->


<tr>
</tr>

<tr>
</tr>

<!-- purpose -->
<tr>
<td colspan="5" style="border-left:2px solid #000000;border-top:2px solid #000000;border-right:2px solid #000000;font-weight: bold;">Purpose:</td>
</tr>

<tr>
<td colspan="5" style="border-left:2px solid #000000;border-right:2px solid #000000;"> </td>
</tr>

<tr>
<td colspan="5" style="border-left:2px solid #000000;border-right:2px solid #000000;">To claim for reimbursement of internet/mobile data subscription actual incurred by the undersigned</td>
</tr>

<tr>
<td colspan="5" style="border-left:2px solid #000000;border-right:2px solid #000000;">in the performance of official and authorized duties under work from home arrangement for the </td>
</tr>

<tr>
<td colspan="5" style="border-left:2px solid #000000;border-right:2px solid #000000;border-bottom:2px solid #000000;">period of  {{date('F d-', strtotime($from))}} {{date('d, Y', strtotime($to))}}</td>
</tr>
<!-- end -->

<tr>
</tr>
<!-- purpose part 2-->
<tr>
<td colspan="5" style="border-left:2px solid #000000;border-top:2px solid #000000;border-right:2px solid #000000;">The above expenses are incurred as they are necessary for the above-cited purpose, and that we are</td>
</tr>

<tr>
<td colspan="5" style="border-left:2px solid #000000;border-right:2px solid #000000;">fully aware that willful falsification of statement is punishable by law.</td>
</tr>

<tr>
<td colspan="5" style="border-left:2px solid #000000;border-right:2px solid #000000;"> </td>
</tr>

<tr>
<td colspan="5" style="border-left:2px solid #000000;border-right:2px solid #000000;">The personnel concerned is not excluded from claiming reimbursement of internet/mobile data</td>
</tr>

<tr>
<td colspan="5" style="border-left:2px solid #000000;border-right:2px solid #000000;border-bottom:2px solid #000000;">subscription expense, as provided in the pertinent guidelines for the purpose.</td>
</tr>
<!-- end -->

<tr>
</tr>

<!-- signature box -->
<tr>
<td colspan="1" style="border:2px solid #000000;">Certified Correct:</td>
<td colspan="4" style="border:2px solid #000000;">Verified and Recommended:</td>
</tr>

<tr>
<td colspan="1" style="height:50px;border-left:2px solid #000000;border-right:2px solid #000000;"> </td>
<td colspan="4" style="height:50px;border-left:2px solid #000000;border-right:2px solid #000000;"> </td>
</tr>

<tr>
<td colspan="1" style="border:2px solid #000000;font-weight:bold;text-align:center;">{{$firstname}} {{$middlename}}. {{$lastname}}</td>
<td colspan="4" style="border:2px solid #000000;font-weight:bold;text-align:center;">AHMMA CHARISMA LOBRIN-SATUMBA</td>
</tr>

<tr>
<td colspan="1" style="border:2px solid #000000;font-weight:bold;font-style:italic;text-align:center;">{{$position}}</td>
<td colspan="4" style="border:2px solid #000000;font-weight:bold;font-style:italic;text-align:center;">Executive Director III</td>
</tr>

<tr>
<td colspan="1" style="border:2px solid #000000;font-weight:bold;">Date: </td>
<td colspan="4" style="border:2px solid #000000;"> </td>
</tr>


<!-- end -->


</tbody>



</table>