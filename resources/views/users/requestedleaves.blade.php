@extends('users.userslayouts.master')
@section('content')
@section('title', 'Leaves')

<div class="container d-flex justify-content-center mt-2">
  <!--table to showcase leave data-->
  <div class="card col-md-12" style="padding-left:0;padding-right:0;">

  <h3 class="bg-info card-header text-center" style="color:white">
    <i class="feather icon-clipboard"></i> Leaves
        </h3>


    <div class="card-body">




      </h3>


      @include('inc.messages')




      <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#general_leave">General Leave</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" id="ilc_tab_a" href="#ilc_cto">CTO</a>
    </li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div id="general_leave" class="container tab-pane active">



<span>As of <b>{{$date_certification}}</b></span><br>
<span class="text-primary">Remaining Vacation Leave : <b>{{$balance_vl}}</b></span><br>
<span class="text-success">Remaining Sick Leave : <b>{{$balance_sl}}</b></span>

<div class="table-responsive">  
<table class="table table-hover table-bordered" id="leavesUserTable">
  <thead class="thead-dark">
   
    <tr>
      <th scope="col">No.</th>
      <th scope="col">Requested</th>            
      <th scope="col">Leave type</th>
      <th scope="col">Days</th>
      <th scope="col">Status</th>
      <th scope="col">Remarks</th>
      <th scope="col">Action</th>
    </tr>
    
  </thead>
  <tbody>

  @foreach($leaves as $leave)
    <tr>
    <td>{{$leave->id}}</td>
    <td>{{date('F d, Y', strtotime($leave->created_at))}}</td>
      <td> @if($leave->credits_id!=null)
              @if($leave->status=="Completed")   
                    <span class="badge bg-success rounded-circle text-light"><i class="feather icon-check"></i></span>
              @else
                    <span class="badge bg-primary rounded-circle text-light"><i class="feather icon-check"></i></span>
              @endif
            @endif
                  
                  @if($leave->status=="Cancelled")
                  <s>{{$leave->leave_type}}</s>
                  @else
                  {{$leave->leave_type}}

                  
                  <!-- if leave has attachment start -->
                  @if($leave->attachment!=null)
                    <a href="{{$leave->attachment}}" download><i class="feather icon-paperclip"></i></a>
                  @endif
                  <!-- end -->

                  @endif


                </td>
        
         <td>
         @if($leave->status=="Cancelled") 
         <s>{{$leave->no_days}} day/s</s>
         @else
         {{$leave->no_days}} day/s
         @endif
         
        </td>
         <td>
         <a href="" class="showLeaveStatus" data-toggle="modal" data-target='#leave_modal' data-id="{{ $leave->id }}"><span class="btn-sm btn-secondary"><i class="feather icon-eye"></i> View</span></a>
         </td>

         <td>
         @if($leave->status=="Cancelled")   
         <span class="text-danger"><b>{{$leave->status}}</b></span>
         @endif

         @if($leave->status=="Processing")   
         <span class="text-primary">{{$leave->status}}</span>
         @endif

         @if($leave->status=="Pending")   
         <span>{{$leave->status}}</span>
         @endif

         @if($leave->status=="Routed to HR" || $leave->status=="Routed to Supervisor" || $leave->status=="Routed to ED" || $leave->status=="Routed to DED" || $leave->status=="Routed to External")   
         <span class="text-warning">{{$leave->status}}</span>
         @endif

         @if($leave->status=="Completed")   
         <span class="text-success font-weight-bold">{{$leave->status}}</span>
         @endif
        
        </td>
      
          <td>

          @if($leave->status!="Cancelled")
            @if($leave->status=="Completed")

              @if($leave->is_rated==0)
                <span class="pcoded-micon btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="Fill out survey to unlock" style="cursor: not-allowed !important;"><i class="feather icon-lock"></i><span class="pcoded-mtext"> Download</span></span>
                <a href="/unlock/leaves/{{ $leave->id }}" class="btn-sm btn-primary" id="leave_survey_btn"><span class="pcoded-micon"><i class="feather icon-clipboard"></i></span><span class="pcoded-mtext"> Take Survey</span></a>
              @else
                <a href="/download-leave/{{ $leave->id }}" class="btn-sm btn-success"><span class="pcoded-micon"><i class="feather icon-download"></i></span><span class="pcoded-mtext"> Download</span></a>
              @endif


            @else
            <a href="/download-leave/{{ $leave->id }}" class="btn-sm btn-success"><span class="pcoded-micon"><i class="feather icon-download"></i></span><span class="pcoded-mtext"> Download</span></a>
            <a href="" id="cancelLeave" data-toggle="modal" data-target='#cancel_modal' data-id="{{ $leave->id }}" class="btn-sm btn-danger"><span class="pcoded-micon"><i class="feather icon-x"></i></span><span class="pcoded-mtext"> Cancel</span></a>            
            @endif
          @else
          <a href="/download-leave/{{ $leave->id }}" class="btn-sm btn-success"><span class="pcoded-micon"><i class="feather icon-download"></i></span><span class="pcoded-mtext"> Download</span></a>
          <a href="/disable-leave/{{ $leave->id }}" class="btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="Remove" ><i class="feather icon-trash"></i></a>
          @endif  

          </td>

    </tr>
    @endforeach
    
    
  </tbody>
</table>
</div>
@if ($leaves instanceof \Illuminate\Pagination\LengthAwarePaginator)
      {{ $leaves->links() }}
@endif

      
    </div>
    <div id="ilc_cto" class="container tab-pane fade">
    <!-- CTO requests start -->

    <span>As of <b>{{$certification_as_of}}</b></span><br>
<span class="text-info">Number of Hours Earned : <b>{{$hours_earned}}</b></span><br><br>

<div class="table-responsive">  
<table class="table table-hover table-bordered" id="ctoUserTable">
  <thead class="thead-dark">
   
    <tr>
      <th scope="col">No.</th>
      <th scope="col">Requested</th>
      <th scope="col">Leave type</th>
      <th scope="col">Days</th>
      <th scope="col">Status</th>
      <th scope="col">Remarks</th>
      <th scope="col">Action</th>
    </tr>
    
  </thead>
  <tbody>

  @foreach($leaves_cto as $leave)
    <tr>
    <td>{{$leave->id}}</td>
    <td>{{date('F d, Y', strtotime($leave->created_at))}}</td>
      <td> @if($leave->credits_id!=null)
                  @if($leave->status=="Completed")   
                    <span class="badge bg-success rounded-circle text-light"><i class="feather icon-check"></i></span>
                  @else
                    <span class="badge bg-primary rounded-circle text-light"><i class="feather icon-check"></i></span>
                  @endif
            @endif
                  
                  @if($leave->status=="Cancelled")
                  <s>{{$leave->leave_type}}</s>
                  @else
                  {{$leave->leave_type}}

                  <!-- if leave has attachment start -->
                  @if($leave->attachment!=null)
                    <a href="{{$leave->attachment}}" download><i class="feather icon-paperclip"></i></a>
                  @endif
                  <!-- end -->

                  @endif

                  

                </td>
        
         <td>
         @if($leave->status=="Cancelled") 
         <s>{{$leave->no_days}} day/s</s>
         @else
         {{$leave->no_days}} day/s
         @endif
         
        </td>
         <td>
         <a href="" class="showCtoStatus" data-toggle="modal" data-target='#leave_modal' data-id="{{ $leave->id }}"><span class="btn-sm btn-secondary"><i class="feather icon-eye"></i> View</span></a>
        </td>

         <td>
         @if($leave->status=="Cancelled")   
         <span class="text-danger"><b>{{$leave->status}}</b></span>
         @endif

         @if($leave->status=="Processing")   
         <span class="text-primary">{{$leave->status}}</span>
         @endif

         @if($leave->status=="Pending")   
         <span>{{$leave->status}}</span>
         @endif

         @if($leave->status=="Routed to HR" || $leave->status=="Routed to Supervisor" || $leave->status=="Routed to ED" || $leave->status=="Routed to DED" || $leave->status=="Routed to External")   
         <span class="text-warning">{{$leave->status}}</span>
         @endif

         @if($leave->status=="Completed")   
         <span class="text-success font-weight-bold">{{$leave->status}}</span>
         @endif
   
      

        
        </td>
      
          <td>

          @if($leave->status!="Cancelled")
            @if($leave->status=="Completed")
           
              @if($leave->is_rated==0)
                <span class="pcoded-micon btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="Fill out survey to unlock" style="cursor: not-allowed !important;"><i class="feather icon-lock"></i><span class="pcoded-mtext"> Download</span></span>
                <a href="/unlock/cto/{{ $leave->id }}" class="btn-sm btn-primary" id="leave_survey_btn"><span class="pcoded-micon"><i class="feather icon-clipboard"></i></span><span class="pcoded-mtext"> Take Survey</span></a>
              @else
                <a href="/download-cto/{{ $leave->id }}" class="btn-sm btn-success"><span class="pcoded-micon"><i class="feather icon-download"></i></span><span class="pcoded-mtext"> Download</span></a>
              @endif
                
            @else
            <a href="/download-cto/{{ $leave->id }}" class="btn-sm btn-success"><span class="pcoded-micon"><i class="feather icon-download"></i></span><span class="pcoded-mtext"> Download</span></a>
            <a href="" id="cancelCto" data-toggle="modal" data-target='#cancel_modal_cto' data-id="{{ $leave->id }}" class="btn-sm btn-danger"><span class="pcoded-micon"><i class="feather icon-x"></i></span><span class="pcoded-mtext"> Cancel</span></a>            
            @endif
          @else
          <a href="/download-cto/{{ $leave->id }}" class="btn-sm btn-success"><span class="pcoded-micon"><i class="feather icon-download"></i></span><span class="pcoded-mtext"> Download</span></a>
          <a href="/disable-cto/{{ $leave->id }}" class="btn-sm btn-secondary"><span class="pcoded-micon"><i class="feather icon-trash"></i></span><span class="pcoded-mtext"> Remove</span></a>
          @endif  
          </td>

    </tr>
    @endforeach
    
    
  </tbody>
</table>
</div>
@if ($leaves_cto instanceof \Illuminate\Pagination\LengthAwarePaginator)
      {{ $leaves_cto->links() }}
@endif

    <!-- CTO requests end -->
    </div>
    
  </div>
<br>
<i class="feather icon-paperclip text-primary"></i> - has attachment <i class="text">(download)</i><br>
<span class="badge bg-primary rounded-circle text-light"><i class="feather icon-check"></i></span> - Leave/CTO request <span class="text-primary">seen</span><br>
<span class="badge bg-success rounded-circle text-light"><i class="feather icon-check"></i></span> - Leave/CTO request <span class="text-success">completed</span>
<p></p>
<p class="text-muted"><b>Note:</b> Filed leaves prior to the <b>November 13, 2022</b> TALMS update has been moved to a different archive. Click <a href="{{URL::to('/archived-leaves')}}" target="_none"><span class="text-primary"><u>here</u></span></a> for access.</p>

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

<!-- delete modal -->
<div class="modal fade" id="cancel_modal_cto">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-body" id="cancel_modal_body_cto">
         </div>
      </div>
   </div>
</div>

<!-- delete modal -->
<div class="modal fade" id="cancel_modal">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-body" id="cancel_modal_body">
         </div>
      </div>
   </div>
</div>

<!-- show leave status modal -->
<div class="modal fade" id="leave_modal">
   <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
         <div class="modal-body" id="leave_modal_body">
         </div>
      </div>
      </form>
   </div>
</div>

<!-- CSAT modal -->
<div class="modal fade" id="csat_survey">
   <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
         <div class="modal-body">
         <iframe src="https://forms.office.com/Pages/ResponsePage.aspx?id=guZUuEHcEUWp_nB9SS795tvJpJ1962NGjBMrki0c_zRUNE1OMDNOM1hQVjBNU1NKQkNUUVVVTUxLTyQlQCN0PWcu" title="W3Schools Free Online Web Tutorials" height="500px" width="100%"></iframe>

         </div>
      </div>
      </form>
   </div>
</div>


<script>
    //  $('#leavesUserTable').DataTable();

      $('#leavesUserTable').DataTable({
      paging: false,
      bInfo : false,
      "order": [ 0, 'desc' ], 
   });

   $('#ctoUserTable').DataTable({
      paging: false,
      bInfo : false,
      "order": [ 0, 'desc' ], 
   });
   
  </script>

@stop