<table>
    <thead>
    <tr>
    <th>Accomplishment Report</th>
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
    <th> </th>
    </tr>

    <tr>
    <th></th>
    <th></th>
    <th><strong>ACCOMPLISHMENT</strong></th>
    <th><strong>STATUS</strong></th>
    <th><strong>WORK SETTING</strong></th>
    </tr>

   

    

    <tr>
    <th><strong>{{$name}}</strong></th>
    </tr>

    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
        <td>{{ date('m/d/Y l', strtotime($user->time_in)) }}</td>
        <td> </td>
        <td>{{$user->accomplishment}}</td>
        <td>{{$user->status}}</td>
        <td>{{$user->wstatus}}</td>
        </tr>
    @endforeach

 
  
    </tbody>
</table>