@extends('users.userslayouts.master')
@section('content')
@section('title', 'Leaves')

<div class="container d-flex justify-content-center mt-2">
  <!--table to showcase leave data-->
  <div class="card col-md-12" style="padding-left:0;padding-right:0;">

  <h3 class="bg-info card-header text-center" style="color:white">
    <i class="feather icon-clipboard"></i> Archive Leaves
        </h3>


    <div class="card-body">




      </h3>





      <div class="table-responsive">  
      @include('inc.messages')
      <table class="table table-hover table-bordered" id="leavesUserTable">
        <thead class="thead-dark">
         
          <tr>

            <th scope="col">No.</th>            
            <th scope="col">Leave type</th>
            <th scope="col">Days</th>
            <th scope="col">Requested</th>
            <th scope="col">status</th>
            <th scope="col">Action</th>
          </tr>
          
        </thead>
        <tbody>
           @foreach($leaves as $leave)
          <tr>
          <td>{{$leave->leave_id}}</td>
            <td>{{$leave->leave_type}}</td>
              
               <td>{{$leave->no_days}} day/s</td>
               <td> {{date('F d, Y h:i a', strtotime($leave->created_at))}}</td>

              
               <td>Archived</td>
          
               <td>
                
               
              @if($leave->leave_type=="cto")
              <a href="/exportmyleavecto/{{ $leave->leave_id }}" class="btn-sm btn-success"><span class="pcoded-micon"><i class="feather icon-download"></i></span><span class="pcoded-mtext"> Download</span></a>
              @else
              <a href="/exportmyleave/{{ $leave->leave_id }}" class="btn-sm btn-success"><span class="pcoded-micon"><i class="feather icon-download"></i></span><span class="pcoded-mtext"> Download</span></a>
              @endif
          </tr>
          @endforeach
          
        </tbody>
      </table>
    </div>


    @if ($leaves instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $leaves->links() }}
      @endif


     
    </div>
  </div>
</div>

<!-- delete modal -->
<div class="modal fade" id="delete_modal">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-body" id="delete_modal_body">
         </div>
      </div>
   </div>
</div>


<script>
    //  $('#leavesUserTable').DataTable();

      $('#leavesUserTable').DataTable({
      paging: false,
      bInfo : false,
   });
   
  </script>

@stop