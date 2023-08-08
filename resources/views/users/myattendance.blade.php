@extends('users.userslayouts.master')
@section('content')
@section('title', 'Attendance')

<div class="container d-flex justify-content-center mt-2">
    <div class="card col-md-12" style="padding-left:0;padding-right:0;">


    
    <h3 class="bg-info card-header text-center" style="color:white">
    <i class="feather icon-calendar"></i> Attendance
        </h3>

      <div class="card-body">
      

       
                   
                        
                          
{{ Form::open(array('url' => 'myattendance/show', 'method' => 'post','class'=>'form-inline')) }}
{!! csrf_field() !!}
<label class="sr-only" for="from">From</label>
<div class="input-group mb-2 mr-sm-2">
  <input class="form-control mb-2 mr-sm-2" id="from" type="date" name="from" required="">
  </div>

  <label class="sr-only" for="to">To</label>
  <div class="input-group mb-2 mr-sm-2">
  <input class="form-control mb-2 mr-sm-2" id="to" type="date" name="to" required="">
  </div>

<button class="btn btn-outline-primary mb-2" type="submit" name="action" value="retrieve"><span class="pcoded-micon"><i class="feather icon-file"></i></span><span class="pcoded-mtext"> Retrieve records</span></button>
<a href="/myattendance"><button type="button" class="btn btn-outline-primary mb-2"><span class="pcoded-micon"><i class="feather icon-file"></i></span><span class="pcoded-mtext"> Reset</span></button></a>
<button class="btn btn-outline-success mb-2" type="submit" name="action" value="export"><span class="pcoded-micon"><i class="feather icon-download"></i></span><span class="pcoded-mtext"> Export to excel</span></button>
{{ Form::close() }}
                             





                           
                        
<div class="table-responsive">               
        <table class="table table-hover table-bordered"  id="attendanceUserTable">
          <thead class="thead-dark">
            <tr>
              <th scope="col">Time in</th>
              <th scope="col">Time out</th>
              <th scope="col">Accomplishments</th>
              <th scope="col">Status</th>
              <th scope="col">Work Setting</th>
            </tr>
          </thead>
          <tbody>     
            @foreach($shows as $show)
              <tr>
                <td>{{date('F d, Y h:i a', strtotime($show->time_in))}}</td>
                <td>
                @if($show->time_out)
                {{date('F d, Y h:i a', strtotime($show->time_out))}}
                @else
                <span class="font-italic">No Entry</span>
                @endif
                </td>
                <td><textarea style="width: 100%;" rows="1" readonly>{{{$show->accomplishment}}}</textarea></td>
                
                @if($show->status=="present")
                  
                  <td><span class="rounded label label-success text-uppercase">{{$show->status}}</span></td>
                @elseif($show->status=="absent")
                  <td><span class="rounded label label-danger text-uppercase">{{$show->status}}</span></td>
                @else
                  <td><span class="rounded label label-primary text-uppercase">{{$show->status}}</span></td>
                @endif
                
                
                
                
                @if($show->wstatus)
                  @if($show->wstatus=="in office")
                    <td><span class="rounded label label-info"><i class="feather icon-monitor"></i> {{$show->wstatus}}</span></td>
                    @else
                    <td><span class="rounded label label-warning"><i class="feather icon-home"></i> {{$show->wstatus}}</span></td>
                  @endif
                @else
                
                <td><span class="font-italic">No Entry</span></td>
                @endif
              
              </tr>
              @endforeach
          </tbody>
        </table>
</div>
       {{Session::get('success')}}
      </div>
    

      @if ($shows instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $shows->render() }}
      @endif

    </div>
  </div>



  <script>
     // $('#attendanceUserTable').DataTable();

      $('#attendanceUserTable').DataTable({
      paging: false,
      bInfo : false,
      "order": [ 0, 'desc' ], 
   });
  </script>
  
  @stop