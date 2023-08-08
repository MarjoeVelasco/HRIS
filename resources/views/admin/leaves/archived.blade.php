@extends('admin.adminlayouts.master')
@section('title', '| Archived Leaves')
@section('content')
<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">
         <h3 class="text-info">
         <i class="feather icon-folder"></i> Archived Leaves
            <section class="float-right">
            <a href="{{ route('manageleaves.index') }}" class="btn btn-outline-success pull-right"><i class="feather icon-clipboard"></i> Leaves</a>
               <a href="{{ route('manageattendance.index') }}" class="btn btn-outline-secondary pull-right"><i class="feather icon-calendar"></i> Attendance</a>
               <a href="{{ route('users.index') }}" class="btn btn-outline-primary"><i class="feather icon-users"></i> User</a>
            </section>
         </h3>
      </div>
      @include('inc.messages')
      <div class="card-block table-border-style">
         <div class="table-responsive">
            <table class="table table-hover" id="archiveLeavesTable">
               <thead>
                  <tr>
                     <th>No.</th>
                     <th>Employee Name</th>
                     <th>Leave type</th>
                     <th>Requested on</th>
                     <th>From</th>
                     <th>To</th>
                     <th>Status</th>
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($leaves as $leave)
                  <tr>
                     <td>{{$leave->leave_id}}</td>
                     <td>{{$leave->name}}</td>
                     <td>{{$leave->leave_type}} - {{$leave->details}}</td>
                     <td>{{date('d-m-Y',strtotime($leave->created_at))}}</td>
                     <td>{{date('d-m-Y',strtotime($leave->start_date))}}</td>
                     <td>{{date('d-m-Y',strtotime($leave->end_date))}}</td>
                     <td>{{$leave->status}}</td>
                     @csrf
                     <td>
                        
                     <div class="form-row">
                     @if($leave->leave_type=="cto")
                         <a href="/manageleaves/detailscto/{{ $leave->leave_id }}" style="margin-right:5px;" class="btn-sm btn-info"><i class="feather icon-eye"></i>View Details</a>
                     @else
                     <a href="/manageleaves/details/{{ $leave->leave_id }}" style="margin-right:5px;" class="btn-sm btn-info"><i class="feather icon-eye"></i>View Details</a>
                     @endif
                   <form method="post" action="/restore">
               			 @csrf
               			 		<input type="hidden" name="leave_id" value="{{ $leave->leave_id }}">
                                <button type="submit" style="padding:3px;border:none;cursor:pointer;border-radius:4px;margin-right:5px;" class="btn-xsm btn-danger rounded-pill" ><i class="feather icon-upload"></i> Restore</button>
						</form>
                  </div>
                     
                    

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


<script>
      $('#archiveLeavesTable').DataTable({
         paging: false,
      bInfo : false,
      });
</script>

@endsection