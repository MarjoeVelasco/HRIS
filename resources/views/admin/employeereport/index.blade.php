@extends('admin.adminlayouts.master')
@section('title', '| Employee Accomplishment Reports')
@section('content')
<div class="table-responsive">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h3 class="text-info"><i class="feather icon-file-text"></i> Employee Accomplishment Report
                <section class="float-right">
                   
                </section>
                </h3>
                
            </div>
            <div class="card-block table-border-style">
               
                  
                           
                                <form method="get" action="/exportemployees">
                                    @csrf
                                   
                                    
                                    <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="example-date-input">Month and Year</label>
                                        <input class="date form-control" type="text" name="month_year" id="month_year" placeholder="MM-YYYY" required>
                                    </div>

                                  
                                    <div class="form-group col-md-3">   
                                        <label for="example-date-input">Division</label>
                                        <select name="division" class="form-control">
                                        
                                        @role('Division Chief')
                                        <option selected value='{{$division}}'>{{$division}}</option>
                                        @endrole

                                        @hasanyrole('Admin|HR/FAD')
                                        <option selected disabled value>-- Select Division Here --</option>
                                        <option value='Office of the Executive Director (OED)'>Office of the Executive Director (OED)</option>
                                        <option value='Employment Research Division (ERD)'>Employment Research Division (ERD)</option>
                                        <option value='Labor and Social Relations Research Division (LSRRD)'>Labor and Social Relations Research Division (LSRRD)</option>
                                        <option value='Workers Welfare Research Division (WWRD)'>Workers Welfare Research Division (WWRD)</option>
                                        <option value='Advocacy and Pulications Division (APD)'>Advocacy and Publications Division (APD)</option>
                                        <option value='Finance and Administrative Division (FAD)'>Finance and Administrative Division (FAD)</option>
                                        @endrole

                                        </select>
                                    </div> 
                                   </div>

                                   <div class="form-row"> 
                                   <div class="form-group col-md-3">
                                        <button class="btn btn-outline-primary" type="submit"><i class="feather icon-download "></i></span><span class="pcoded-mtext"> Generate Report</span></button>
                                   </div>
                                   </div>
                                
                            </form>
                           
                       
               
            </div>
        </div>
    </div>

    
    <script>
$('.date').datepicker({  
    format: "yyyy-mm",
    viewMode: "months", 
    minViewMode: "months",
    selectYears: true,
}); 

    </script>

    @endsection