@extends('admin.adminlayouts.master')
@section('title', '| Accounting Dashboard')
@section('content')

<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">
         <h3 class="text-info">
            <i class="fa fa-envelope-open"></i> Payslip Mailing Report
            <section class="float-right">
               <a href="/payslip-general" class="btn btn-outline-primary"><i class="fa fa-file"></i></span><span class="pcoded-mtext"> Payslips</span></a>
               <a href="/payslip-mail" class="btn btn-outline-warning"><i class="fa fa-paper-plane"></i></span><span class="pcoded-mtext"> Mass Mail</span></a>
              
            </section>
         </h3>
      </div>
      @include('inc.messages')
      <div class="card-block table-border-style">
         <div class="table-responsive">
         

         <form action="{{ route('export-payslip-mailing') }}" method="POST">
                @csrf
                
                <div class="form-group col-md-6 col-sm-6">
                    <label for="exampleFormControlSelect1">Pay period</label>
                    <select name="pay_period" class="form-control" id="exampleFormControlSelect1">
                    <option value="all">All</option>
                    @if($payslips)
                    @foreach($payslips as $payslip)
                    <option value="{{ $payslip->pay_period }}">{{ $payslip->pay_period }}</option>
                    @endforeach
                    @endif
                    </select>
                </div>

               <div class="form-group col-md-6 col-sm-6 ">
                <button class="btn btn-success"><i class="fa fa-download"></i></span><span class="pcoded-mtext">Generate Report</button>
                </div>

            </form>







         </div>
      </div>
   </div>
</div>

<script>
$('.date').datepicker({  
    format: "MM-yy",
    viewMode: "months", 
    minViewMode: "months",
    selectYears: true,
}); 

</script>

@endsection