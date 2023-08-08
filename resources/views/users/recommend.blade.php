@extends('users.userslayouts.master')
@section('content')



<div class="table-responsive">
	<div class="col-xl-12">
		<div class="card">
			<div class="card-header">
				<h3>
					Leave requests awaiting your approval (Supervisor)
				</h3>
			</div>
            @include('inc.messages')
            
			<div class="card-block table-border-style">
				<div class="table-responsive">
					<table class="table table-hover" id="approveLeaveSupervisorTable">
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
										

										
										<button id="approve_btn" data-toggle="modal" data-target='#approve_modal' data-id="{{ $leave->leave_id }}" class="btn btn-danger">Approve
										</button>
										<button id="decline_btn" data-toggle="modal" data-target='#decline_modal' data-id="{{ $leave->leave_id }}" class="btn btn-danger">Decline
										</button>

										<a href="/recommendation/details/{{ $leave->leave_id }}" class="btn btn-info">View Full Details</a>
									</td>
								</tr>
                            @endforeach
                            
                        
							</tbody>
						</table>
                        {{$leaves->render()}}
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
      $('#approveLeaveSupervisorTable').DataTable();
  </script>

@endsection