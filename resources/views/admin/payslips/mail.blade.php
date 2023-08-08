@extends('admin.adminlayouts.master')
@section('title', '| Payslips')
@section('content')


<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">
         <h3 class="text-info">
         <i class="fa fa-paper-plane"></i> Mailing
            <section class="float-right">
            <a href="/payslip-import" class="btn btn-outline-primary"><i class="fa fa-plug"></i></span><span class="pcoded-mtext"> Import Data</span></a>
            <a href="/payslip-mail" class="btn btn-outline-success"><i class="fa fa-file"></i></span><span class="pcoded-mtext"> Payslips</span></a>

            </section>
         </h3>
      </div>
      @include('inc.messages')
      <div class="card-block table-border-style">
         <div class="table-responsive">
            <table class="table table-hover table-striped" id="payslipTable">
               <thead>
                  <tr>
                  
                     <th>Pay Period</th>
                     <th>Status</th>
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody>

               @foreach($payslips as $payslip)

                <tr>
                    
                    <td>{{$payslip->pay_period}}</td>
                    @if($payslip->status=="published")
                    <td><span class="text-success font-weight-bold text-uppercase">{{$payslip->status}}</span></td> 
                    @elseif($payslip->status=="draft")
                    <td><span class="text-warning font-weight-bold text-uppercase">{{$payslip->status}}</span></td>
                    @else
                    <td><span class="text-danger font-weight-bold text-uppercase">{{$payslip->status}}</span></td>
                    @endif
                    <td>

                    @if($payslip->status=="published")
                    <a href="/update-payslip-status/{{ $payslip->pay_period }}/{{$payslip->status}}" class="btn-sm btn-warning"><span class="pcoded-micon"><i class="fa fa-ban"></i></span><span class="pcoded-mtext"> Unpublish</span></a>
                    <a href="" id="deletePayslip" data-toggle="modal" data-target='#delete_modal' data-id="{{ $payslip->pay_period }}" class="btn-sm btn-danger"><i class="feather icon-trash-2 "></i></span><span class="pcoded-mtext"> Delete</span></a>
                    @elseif($payslip->status=="draft")
                    <a href="/update-payslip-status/{{ $payslip->pay_period }}/{{$payslip->status}}" class="btn-sm btn-success"><span class="pcoded-micon"><i class="fa fa-share-alt"></i></span><span class="pcoded-mtext"> Publish</span></a>
                    <a href="/send-bulk-mail/{{ $payslip->pay_period }}/{{$payslip->status}}" class="btn-sm btn-info"><span class="pcoded-micon"><i class="fa fa-paper-plane"></i></span><span class="pcoded-mtext"> Mass mail</span></a>
                    <a href="" id="deletePayslip" data-toggle="modal" data-target='#delete_modal' data-id="{{ $payslip->pay_period }}" class="btn-sm btn-danger"><i class="feather icon-trash-2 "></i></span><span class="pcoded-mtext"> Delete</span></a>
                    @else
                    <a href="" id="deletePayslip" data-toggle="modal" data-target='#delete_modal' data-id="{{ $payslip->pay_period }}" class="btn-sm btn-danger"><i class="feather icon-trash-2 "></i></span><span class="pcoded-mtext"> Delete</span></a>
                    @endif
                    
                    </td>
                </tr>

                @endforeach
             
               </tbody>
            </table>

            @if ($payslips instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $payslips->render() }}
            @endif           
         </div>
         <br>
         <p>
            <span class="text-warning font-weight-bold text-uppercase">draft</span> - not yet visible to employees<br>
            <span class="text-danger font-weight-bold text-uppercase">sending</span> - payslips are being sent<br>
            <span class="text-success font-weight-bold text-uppercase">published</span> - visible to employees which they can freely download
         </p>

         <p class="text-info">Note: <b>Mass mailing</b> a payslip will be marked as published.</p>

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