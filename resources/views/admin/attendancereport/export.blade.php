<table>
    <thead>
    <tr>
    <th>Daily Time Record</th>
    </tr>

    <tr>
    <td>Start Date</td>
    <td>{{$start_date}}</td>
    </tr>

    <tr>
    <td>End Date</td>
    <td>{{$end_date}}</td>
    </tr>

    <tr>
    <td> </td>
    </tr>


    <tr>
        <th> </th>
        <th> </th>
        <th> </th>
        <th>IN</th>
        <th>OUT</th>
        <th>IN</th>
        <th>OUT</th>
        <th>IN</th>
        <th>OUT</th>
        <th>late</th>
        <th>undertime</th>
        <th>overtime</th>
        <th>no of hrs worked</th>
    </tr>

    <tr>
    <td> </td>
    </tr>

    <tr>

    <td><strong>{{$fullname}}</strong></td>

    </tr>

    </thead>

    @if($fullname !='No records found')    
    <tbody>
    @foreach($users as $user)
        <tr>
            <td colspan="3">{{ date('m/d/Y l', strtotime($user->time_in)) }}</td>

            @if($user->status=="CTO")

              @if($user->time_status=="cto-morning")

                <td colspan="2"><strong>** {{$user->time_status}} **</strong></td>
                <td> </td>
                <td> </td>      
                <td>{{ date('g:i a', strtotime($user->time_in)) }}</td>
                <td>{{ date('g:i a', strtotime($user->time_out)) }}</td>

                @endif

                @if($user->time_status=="cto-afternoon")

                <td>{{ date('g:i a', strtotime($user->time_in)) }}</td>
                <td>{{ date('g:i a', strtotime($user->time_out)) }}</td>
                <td> </td>
                <td> </td>  
                <td colspan="2"><strong>** {{$user->time_status}} **</strong></td>
                @endif


                <td>{{$user->late}}</td>
                <td>{{$user->undertime}}</td>
                <td>{{$user->overtime}}</td>
                <td>{{$user->hours_worked}}</td>

            @else

            @if($user->status =='present')         
            @if($user->time_status =='timed_in')             
                <td>{{ date('g:i a', strtotime($user->time_in)) }}</td>
                <td colspan="4"><strong>** NO TIME OUT **</strong></td> 
                @else
                <td>{{ date('g:i a', strtotime($user->time_in)) }}</td>  
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>      
                @endif
            @endif
            

            @if($user->status =='absent')  
            <td> -- </td>
            <td colspan="4"><strong> </strong></td>
            @endif

            @if($user->status =='on leave')         
            <td> -- </td>
            <td colspan="4"><strong>** ON LEAVE ({{$user->time_status}})**</strong></td>
            @endif          

            @if($user->status =='holiday')         
            <td> -- </td>
            <td colspan="4"><strong>** HOLIDAY **</strong></td>
            @endif        

            @if($user->status =='weekend')         
            <td> -- </td>
            <td colspan="4"><strong>** WEEKEND **</strong></td>
            @endif   

            @if($user->status =='no_in_out')         
            <td> -- </td>
            <td colspan="4"><strong> </strong></td>
            @endif

            @if($user->status =='AO' || $user->status =='OB')  
            <td> -- </td>
            <td colspan="4"><strong>** {{ $user->time_status }} **</strong></td>
            @endif

            
           

            @if($user->status =='present')         
            @if($user->time_status =='timed_in')             
                <td> -- </td> 
                <td>{{ $user->late }}</td>
                <td> -- </td> 
                <td> -- </td> 
                <td> -- </td>   
                @else
                <td>{{ date('g:i a', strtotime($user->time_out)) }}</td>
                <td>{{ $user->late }}</td>
                <td>{{ $user->undertime }}</td>
                <td>{{ $user->overtime }}</td>
                <td>{{ $user->hours_worked }}</td> 
                @endif 
            @else
            <td> -- </td> 
            <td>--</td> 
            <td> -- </td> 
            <td> -- </td> 
            <td> -- </td>   
                   
            @endif

            @endif

            

           
        </tr>
    @endforeach

    <tr>
    <td> </td>
    </tr>

    <!-- Week specific -->
    <tr>
    <td colspan="4">Required hours worked</td>
    <td>{{$expected_working_hours * 8}}:00:00</td>
    <td><strong>{{$report_type}}</strong></td>
    </tr>
    <tr>
    <td colspan="4">No. of hours worked</td>
    <td>{{$hours_worked}}</td>
    </tr>

    <tr>
    <td colspan="4">No. of hours late</td>
    <td>-{{$late}}</td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td colspan="4" style="border-bottom:2px solid black;text-align:center;"><b><u>{{$fullname_sign}}</u></b></td>

    </tr>

    <tr>
    <td colspan="4">No. of hours undertime</td>
    <td>-{{$undertime}}</td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td colspan="4" style="text-align:center;"><i>Employee Signature</i></td>
    </tr>

    <tr>
    <td colspan="4">No. of hours overtime</td>
    <td>{{$overtime}}</td>
    </tr>

    <tr>
    <td colspan="4">Total Undertime</td>
    <td>{{$total_undertime}}</td>
    </tr>
    <tr>
    <td colspan="4">Total late and Undertime</td>
    <td>{{$total_late_undertime}}</td>
    </tr>

    <tr>
    <td> </td>
    <td> </td>
    </tr>

    <tr>
    <td colspan="4">No. of days on leave</td>
    <td>{{$days_absent}}</td>
    </tr>
    <tr>
    <td colspan="4">No. of Official Business (OB)</td>
    <td>{{$total_OB}}</td>
    </tr>
    <tr>
    <td colspan="4">No. of Administrative Order (AO)</td>
    <td>{{$total_AO}}</td>
    </tr>

  
  
    </tbody>
    @endif
</table>