@extends('admin.adminlayouts.master')
@section('title', '| Manage Leaves')
@section('content')
<style>
   .leave_type
   {
      display: block;
  width: 200px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
   }
</style>

<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">
         <h3 class="text-info">
         <i class="feather icon-clipboard"></i> Manage CTO Requests
            <section class="float-right">
               <a href="/managefiledleaves" class="btn btn-outline-success pull-right"><i class="feather icon-file-text"></i> Leave Requests</a>
            </section>
         </h3>
      </div>
      @include('inc.messages')
      <div class="card-block table-border-style">
         <div class="table-responsive">
            <table class="table table-hover table-striped" id="admin_leaves">
               <thead>
                  <tr>
                     <th>No.</th>
                     <th>Name</th>
                     <th>Leave type</th>
                     <th>Requested on</th>
                     <th>Status</th>
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody>
                    @foreach($leaves as $leave)
                     <tr>
                        <td>{{$leave->id}}</td>
                        <td>

                        @if($leave->status=="Cancelled")
                        <s>{{$leave->name}}</s>
                        @else
                        {{$leave->name}}
                        @endif

                        </td>
                        <td>
                        <span class="leave_type">
                        @if($leave->credits_id!=null)
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
                        <a href="{{ request()->getSchemeAndHttpHost() }}/{{$leave->attachment}}" download><i class="feather icon-paperclip"></i></a>
                        @endif
                        <!-- end -->

                        @endif 
                        
                        </span>
                        </td>

                        <td>   
                        @if($leave->status=="Cancelled")
                        <s>{{date('d-m-Y',strtotime($leave->created_at))}}</s>
                        @else
                        {{date('d-m-Y',strtotime($leave->created_at))}}
                        @endif
                        </td>
                        
                        <td>
                            @if($leave->status=="Cancelled")   
                           <span class="text-danger">{{$leave->status}}</span>
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

                        @if($leave->status=="Routed to HR" || $leave->status=="Routed to Supervisor" || $leave->status=="Routed to ED" || $leave->status=="Routed to DED")   
                        <a href="/route-cto/{{ $leave->id }}" class="btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Send Reminder"><span class="pcoded-micon"><i class="feather icon-clock"></i></span><span class="pcoded-mtext"></span></a>
                        @endif

                        @if($leave->status == "Routed to External")
                        <a href="/route-cto/{{ $leave->id }}/approve" class="btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Manual Approve"><span class="pcoded-micon"><i class="feather icon-clock"></i> Manual Approve</span><span class="pcoded-mtext"></span></a>
                        @endif

                        @if($leave->status=="Processing") 
                           <a href="/route-cto/{{ $leave->id }}" class="btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Route for approval"><span class="pcoded-micon"><i class="feather icon-mail"></i></span><span class="pcoded-mtext"></span></a>
                        @endif

                        @if($leave->status=="Routed to HR")   
                           @if($leave->hr_id==Auth::user()->id)
                           <a href="/review-cto/{{ $leave->id }}/{{$leave->hr_id}}" class="btn-sm btn-warning"><span class="pcoded-micon"><i class="feather icon-clipboard"></i></span><span class="pcoded-mtext"> Review</span></a>
                           @endif
                        @endif

                        
                        <a href="/download-cto/{{ $leave->id }}" class="btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Download Leave Form"><span class="pcoded-micon"><i class="feather icon-download"></i></span><span class="pcoded-mtext"></span></a>


                        @if($leave->status=="Cancelled" || $leave->status=="Processing" || $leave->status=="Pending")
                        <a href="/certify-cto/{{ $leave->id }}" class="btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Certify Leave Credits"><span class="pcoded-micon"><i class="feather icon-star"></i></span><span class="pcoded-mtext"></span></a>
                        @endif
                        

                        @if($leave->status=="Pending" || $leave->status=="Processing" || $leave->status=="Routed to HR" || $leave->status=="Routed to Supervisor" || $leave->status=="Routed to ED" || $leave->status=="Routed to DED" || $leave->status=="Routed to External")   
                        <a href="" id="cancelCto" data-toggle="modal" data-target='#cancel_modal_cto' data-id="{{ $leave->id }}" class="btn-sm btn-danger"><span class="pcoded-micon"><i class="feather icon-x"></i></span><span class="pcoded-mtext"> Cancel</span></a>
                        @endif


                        @if($leave->status=="Cancelled" || $leave->status=="Completed")
                        <a href="/archive-cto/{{ $leave->id }}" class="btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="Move to Archive"><span class="pcoded-micon"><i class="feather icon-inbox"></i></span><span class="pcoded-mtext"></span></a>
                        @endif
						
						@if($leave->status=="Completed")
						<a href="" id="cancelCto" data-toggle="modal" data-target='#cancel_modal_cto' data-id="{{ $leave->id }}" class="btn-sm btn-danger"><span class="pcoded-micon"><i class="feather icon-x"></i></span><span class="pcoded-mtext"> Cancel</span></a>
                        <a href="/archive-cto/{{ $leave->id }}" class="btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="Move to Archive"><span class="pcoded-micon"><i class="feather icon-inbox"></i></span><span class="pcoded-mtext"></span></a>
                        @endif


                       

                        </td>
                     </tr>
                     @endforeach
               </tbody>
            </table>

            @if ($leaves instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $leaves->render() }}
            @endif
            

           
         </div>
      </div>
   </div>
</div>
<!-- [ Hover-table ] start-->
<!-- [ Hover-table ] end -->

<div id="decline_modal" class="modal fade" role="dialog">
   <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Decline Leave Request</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            @csrf
         </div>
      </div>
   </div>
</div>

<div id="approve_modal" class="modal fade" role="dialog">
   <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Approve Leave Request</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            @csrf
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


<script>
    //  $('#admin_leaves').DataTable();

      $('#admin_leaves').DataTable({
      paging: false,
      bInfo : false,
      "order": [ 0, 'desc' ], 
   });

</script>

@endsection