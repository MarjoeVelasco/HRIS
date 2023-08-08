@extends('admin.adminlayouts.master')
@section('title', '| Accounting Dashboard')
@section('content')

<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">
         <h3 class="text-info">
            <i class="fa fa-plug"></i> Import Data
            <section class="float-right">
               <a href="/payslip-general" class="btn btn-outline-primary"><i class="fa fa-file"></i></span><span class="pcoded-mtext"> Payslips</span></a>
               <a href="/payslip-mail" class="btn btn-outline-warning"><i class="fa fa-paper-plane"></i></span><span class="pcoded-mtext"> Mass Mail</span></a>
              
            </section>
         </h3>
      </div>
      @include('inc.messages')
      <div class="card-block table-border-style">
         <div class="table-responsive">
         

         <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group col-md-6 col-sm-6 ">
                <label for="example-date-input">Select excel file</label>
                <input type="file" name="file" class="form-control">
                </div>
                <div class="form-group col-md-6 col-sm-6 ">
                                        <label for="example-date-input">Pay period</label>

                                        
                                        <input class="date form-control" type="text" name="month_year" id="month_year" placeholder="MMMM-YY" required>
               </div>

               <div class="form-group col-md-6 col-sm-6 ">
                <button class="btn btn-success"><i class="feather icon-save "></i></span><span class="pcoded-mtext">Import User Data</button>
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