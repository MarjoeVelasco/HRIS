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


            @if($user->status =='present')         
            <td>{{ date('g:i a', strtotime($user->time_in)) }}</td>  
            <td> </td>
            <td> </td>
            <td> </td>
            <td> </td>      
            @endif
            

            @if($user->status =='absent')  
            <td> -- </td>
            <td colspan="4"><strong> </strong></td>
            @endif

            @if($user->status =='on leave')         
            <td> -- </td>
            <td colspan="4"><strong>** ON LEAVE **</strong></td>
            @endif          

            @if($user->status =='holiday')         
            <td> -- </td>
            <td colspan="4"><strong>** HOLIDAY **</strong></td>
            @endif        
            
           

            @if($user->status =='present')         
            <td>{{ date('g:i a', strtotime($user->time_out)) }}</td>
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
    <td> </td>
    </tr>

   
    </tbody>

    @endif
</table>