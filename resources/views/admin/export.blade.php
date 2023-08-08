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

    <td>{{$fullname}}</td>

    </tr>

    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td colspan="3">{{ date('m/d/Y l', strtotime($user->time_in)) }}</td>


            @if($user->status =='present')         
            <td>{{ date('g:i a', strtotime($user->time_in)) }}</td>  
            <td> </td>
            <td> </td>
            <td> </td>
            <td> </td>       
            @else
            <td> -- </td>
            <td colspan="4"><strong>** ABSENT **</strong></td>
            @endif
            
           

            @if($user->status =='present')         
            <td>{{ date('g:i a', strtotime($user->time_out)) }}</td>
            <td>{{ $user->late }}</td>
            <td>{{ $user->undertime }}</td>
            <td>{{ $user->overtime }}</td>
            <td>{{ $user->hours_worked }}</td>         
            @else
            <td> -- </td> 
            <td>--</td> 
            <td> -- </td> 
            <td> -- </td> 
            <td> -- </td>   
                   
            @endif

           
        </tr>
    @endforeach

    <tr>
    <td> </td>
    </tr>


    <tr>
    <td colspan="4">Required hours worked</td>
    <td>{{$expected_working_hours * 8}}:00:00</td>
    </tr>
    <tr>
    <td colspan="4">No. of hours worked</td>
    <td>{{$hours_worked}}</td>
    </tr>

    <tr>
    <td colspan="4">No. of hours late for the month</td>
    <td>{{$late}}</td>
    </tr>

    <tr>
    <td colspan="4">No. of hours undertime for the month</td>
    <td>{{$undertime}}</td>
    </tr>

    <tr>
    <td colspan="4">No. of hours overtime for the month </td>
    <td>{{$overtime}}</td>
    </tr>

    <tr>
    <td colspan="4">No. of days absent</td>
    <td>{{$days_absent}}</td>
    </tr>

    <tr>
    <td> </td>
    <td> </td>
    </tr>
    <tr>
    <td> </td>
    <td> </td>
    </tr>


    <tr>
    <td colspan="4">Total late and Undertime</td>
    <td>{{$total_late_undertime}}</td>
    </tr>

<!--
    <tr>
    <td colspan="4">Adjusted total late and Undertime</td>
    <td>{{$adjustment}}</td>
    </tr>

    <tr>
    <td> </td>
    <td> </td>
    </tr>

    <tr>
    <td colspan="3">Hour</td>
    <td>{{$total_hour}}</td>
    <td>{{$deduction_total_hour}}</td>
    </tr>

    <tr>
    <td colspan="3">Minutes</td>
    <td>{{$total_minutes}}</td>
    <td>{{$deduction_total_minutes}}</td>
    </tr>

    <tr>
    <td colspan="3">Initial Deduction</td>
    <td> </td>
    <td>{{$deduction_total_hour + $deduction_total_minutes}}</td>
    </tr>

    <tr>
    <td colspan="3">Adjusted Deduction</td>
    <td> </td>
    <td>{{$adjusted_deduction_total_hour + $adjusted_deduction_total_minutes}}</td>
    </tr>
  -->
  
    </tbody>
</table>