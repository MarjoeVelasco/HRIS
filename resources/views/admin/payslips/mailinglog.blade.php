<table>
    <thead>
    <tr>
    <th>Payslip Mailing Log Report</th>
    </tr>

    <tr>
    <td> </td>
    </tr>

    <tr>
    <td> </td>
    </tr>

    <tr>
    <th> </th>
    <th> </th>
    <th>Email</th>
    <th>Transaction</th>
    <th>Pay Period</th>
    <th>Date</th>
    </tr>
    </thead>

    <tbody>
    
        @if($log_data)
            @foreach($log_data as $data)
            <tr>
            <td>{{$data->lastname}}</td>
            <td>{{$data->firstname}}</td>
            <td>{{$data->email}}</td>
            <td>{{$data->transaction}}</td>
            <td>{{$data->pay_period}}</td>
            <td>{{$data->created_at}}</td>  
            </tr> 
            @endforeach
        @endif
        
    </tbody>


</table>