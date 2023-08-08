@extends('admin.adminlayouts.master')
@section('title', '| Payslips')
@section('content')


<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">
         <h3 class="text-info">
         <i class="fa fa-file"></i> General Payslips
            <section class="float-right">
            <a href="/payslip-import" class="btn btn-outline-primary"><i class="fa fa-plug"></i></span><span class="pcoded-mtext"> Import Data</span></a>
            <a href="/payslip-mail" class="btn btn-outline-warning"><i class="fa fa-paper-plane"></i></span><span class="pcoded-mtext"> Mass Mail</span></a>
            </section>
         </h3>
      </div>
      @include('inc.messages')
      <div class="card-block table-border-style">
         <div class="table-responsive">
            <table class="table table-hover table-striped" id="payslipTable">
               <thead>
                  <tr>
                     <th>No.</th>
                     <th>Name</th>
                     <th>Pay Period</th>
                     <th>Status</th>
                     <th>Created date</th>
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody>
               @foreach($payslips as $payslip)

                <tr>
                    <td>{{$payslip->id}}</td>
                    <td>{{Str::upper($payslip->lastname)}} {{$payslip->extname}}, {{$payslip->firstname}} {{substr($payslip->middlename, 0, 1)}}</td>
                    <td>{{$payslip->pay_period}}</td>
                    
                    @if($payslip->status=="published")
                    <td><span class="text-success font-weight-bold text-uppercase">{{$payslip->status}}</span></td> 
                    @elseif($payslip->status=="draft")
                    <td><span class="text-warning font-weight-bold text-uppercase">{{$payslip->status}}</span></td>
                    @else
                    <td><span class="text-danger font-weight-bold text-uppercase">{{$payslip->status}}</span></td>
                    @endif

                    <td>{{date('F d, Y h:i a', strtotime($payslip->created_at))}}</td>
                    <td>
                    <a href="/exportmypayslip/{{ $payslip->id }}" class="btn-sm btn-success"><span class="pcoded-micon"><i class="feather icon-download"></i></span><span class="pcoded-mtext"> Download</span></a>
                    <a href="" id="deletePayslip" data-toggle="modal" data-target='#delete_modal' data-id="{{ $payslip->id }}" class="btn-sm btn-danger"><i class="feather icon-trash-2 "></i></span><span class="pcoded-mtext"> Delete</span></a>   
                       

                    </td>
                </tr>

                @endforeach
               </tbody>
            </table>

            @if ($payslips instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $payslips->render() }}
            @endif

            
            
           
         </div>
      </div>
   </div>
</div>

<!-- Modal for deleting of holiday-->
<div class="modal fade" id="delete_modal">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-body" id="delete_modal_body">
         </div>
      </div>
   </div>
</div>


<script>
   $('#payslipTable').DataTable({
      paging: false,
      bInfo : false,
   });
</script>



@endsection