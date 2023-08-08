@extends('admin.adminlayouts.master')
@section('title', '| Attendance Reports')
@section('content')

<style>
    h3{
        text-align: center;
    }
</style>


<div class="card p-3">
    <h2 class="text-center text-info"> <i class="feather icon-file-text"></i> Attendance Report</h2>
</div>

<div class="row">

    <div class="col-md-4">
        <div class="table-responsive">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    
                    <h3 class="text-info"><i class="feather icon-file-text"></i>General</h3>
                    
                </div>

                

                <div class="card-block table-border-style">         
                    <form method="get" action="/export">
                        @csrf
                       
                       <input type="hidden" id="weeks" name="range_weeks" value=""/>

                        
                        <div class="form-group">
                            <label for="example-date-input">Month and Year</label>
                            <input class="date form-control" type="text" name="month_year" id="month_year" placeholder="MM-YYYY" required>
                        </div>
                      
                        <div class="form-group ">
                            <label for="example-date-input">Week</label>
                            <select name="week" class="form-control" id="week">
                            <option value="all">All</option>
                            </select>
                        </div>  

                        <div class="form-group">   
                            <label for="example-date-input">Division</label>
                            <select name="division" class="form-control" required>

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
                       
                        <br>
                        
                        
                        <div class="form-group col-md-3">
                            <button class="btn btn-outline-primary" type="submit"><i class="feather icon-download "></i><span class="pcoded-mtext"> Generate Report</span></button>
                       </div>
                       
                    </form>         
                </div>
            </div>
        </div>
        </div>
    </div>


    <div class="col-md-4">
        <div class="table-responsive">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-info"><i class="feather icon-file-text"></i> Official Business & AO</h3> 
                    </div>

                    <div class="card-block table-border-style">
                                   
                        <form method="get" action="/exportOther">
                            @csrf
                           
                            <input type="hidden" value="(OB and AO)" name="attendance_type">
                            <input type="hidden" value="obao" name="table_name">

                            <div class="form-group">
                                <label for="example-date-input">Month and Year</label>
                                <input class="date form-control" type="text" name="month_year_other" id="month_year_obao" placeholder="MM-YYYY" required>
                            </div>
                          
                            <div class="form-group">   
                                <label for="example-date-input">Division</label>
                                <select name="division" class="form-control" required>
                                
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
                           
                            <br>
                            <br>
                            <br>
                            <div class="form-group">
                                <button class="btn btn-outline-primary" type="submit"><i class="feather icon-download "></i><span class="pcoded-mtext"> Generate Report</span></button>
                           </div>
                           <br>
                           <br>
                        </form>         
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="col-md-4">
        <div class="table-responsive">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-info"><i class="feather icon-file-text"></i> Holidays & Weekends</h3>
                    
                </div>

                

                <div class="card-block table-border-style">
                   
                    <form method="get" action="/exportOther">
                        @csrf
                       
                        <input type="hidden" value="(holidays weekends)" name="attendance_type">
                        <input type="hidden" value="other" name="table_name">

                        <div class="form-group">
                            <label for="example-date-input">Month and Year</label>
                            <input class="date form-control" type="text" name="month_year_other" id="month_year_other" placeholder="MM-YYYY" required>
                        </div>

                        <div class="form-group">   
                            <label for="example-date-input">Division</label>
                            <select name="division" class="form-control" required>
                     

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

                        <br>
                        <br>
                        <br>

                        <div class="form-group">
                            <button class="btn btn-outline-primary" type="submit"><i class="feather icon-download "></i><span class="pcoded-mtext"> Generate Report</span></button>
                       </div> 
                       <br>
                       <br>
                    </form>         
                </div>
            </div>
        </div>
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