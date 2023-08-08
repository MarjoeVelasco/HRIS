@extends('admin.adminlayouts.master')
@section('title', '| Leave Requests for Approval')
@section('content')
<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">
         <h3 class="text-info">
            <i class="fa fa-clipboard"></i> General Leave Requests
            <section class="float-right">
               <a href="/supervise-general-cto" class="btn btn-outline-primary"><i class="feather icon-clipboard "></i></span><span class="pcoded-mtext"> CTO Requests</span></a>
            </section>
         </h3>
      </div>
      @include('inc.messages')
      <div class="card-block table-border-style">
         <div class="table-responsive">
            
         <table class="table table-hover table-striped" id="admin_leaves">
               <thead>
                  <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Leave type</th>
                    <th scope="col">Days</th>
                    <th scope="col">Requested</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                    </tr>
               </thead>
               <tbody>

  @foreach($leaves as $leave)
    <tr>
    <td>{{$leave->id}}</td>
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
                        <a href="{{ request()->getSchemeAndHttpHost() }}/{{$leave->attachment}}" download><i class="feather icon-paperclip"></i></a>
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
                        @if($leave->status=="Cancelled")
                        <s>{{date('d-m-Y',strtotime($leave->created_at))}}</s>
                        @else
                        {{date('d-m-Y',strtotime($leave->created_at))}}
                        @endif
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

         @if($leave->status=="Routed to HR" || $leave->status=="Routed to Supervisor" || $leave->status=="Routed to ED" || $leave->status=="Routed to DED")   
         <span class="text-warning">{{$leave->status}}</span>
         @endif

         @if($leave->status=="Completed")   
         <span class="text-success font-weight-bold">{{$leave->status}}</span>
         @endif
        
        </td>
      
          <td>

          @if($leave->status!="Cancelled")
            @if($leave->status=="Routed to Supervisor")
            <a href="/review-leave-supervisor/{{ $leave->id }}/{{$leave->supervisor_id}}" class="btn-sm btn-warning"><span class="pcoded-micon"><i class="feather icon-clipboard"></i></span><span class="pcoded-mtext"> Review</span></a>
            @endif
           @endif  
          <a href="/download-leave/{{ $leave->id }}" class="btn-sm btn-success"><span class="pcoded-micon"><i class="feather icon-download"></i></span><span class="pcoded-mtext"> Download</span></a>
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
   </div>
</div>

<script>
    //  $('#admin_leaves').DataTable();

      $('#admin_leaves').DataTable({
      paging: false,
      bInfo : false,
      "order": [ 0, 'desc' ], 
      "language": {
      "emptyTable": "No pending leave requests"
    }
   });

</script>

@endsection