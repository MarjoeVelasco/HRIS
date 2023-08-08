@extends('users.userslayouts.master')
@section('content')
@section('title', 'Leaves')

<div class="container d-flex justify-content-center mt-2">
  <!--table to showcase leave data-->
  <div class="card col-md-12" style="padding-left:0;padding-right:0;">

  <h3 class="bg-info card-header text-center" style="color:white">
    <i class="feather icon-clipboard"></i> Payslips
        </h3>


    <div class="card-body">

      </h3>


      <div class="table-responsive">  
      <table class="table table-hover table-bordered" id="EmployeePayslipTable">
        <thead class="thead-dark">
         
          <tr>

            <th scope="col">No.</th>            
            <th scope="col">Pay Period</th>
            <th scope="col">Published / Sent</th>
            <th scope="col">Actions</th>
          </tr>
          
        </thead>
        <tbody>
           @foreach($payslips as $payslip)

           <tr>
            <td>{{$payslip->id}}</td>
            <td>{{$payslip->pay_period}}</td>
            <td>{{date('F d, Y h:i a', strtotime($payslip->created_at))}}</td>
            <td>
                <a href="/exportmypayslip/{{ $payslip->id }}" class="btn-sm btn-success"><span class="pcoded-micon"><i class="feather icon-download"></i></span><span class="pcoded-mtext"> Download</span></a>
            </td>
          </tr>
               


          @endforeach
          
        </tbody>
      </table>
    </div>


    @if ($payslips instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $payslips->links() }}
      @endif



     
    </div>
  </div>
</div>


<script>
    //  $('#leavesUserTable').DataTable();

      $('#EmployeePayslipTable').DataTable({
      paging: false,
      bInfo : false,
   });
   
  </script>




@stop