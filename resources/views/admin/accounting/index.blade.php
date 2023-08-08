@extends('admin.adminlayouts.master')
@section('title', '| Accounting Dashboard')
@section('content')

<style>
.list-group{
    max-height: 300px;
	height:300px;
    margin-bottom: 10px;
    overflow:scroll;
	overflow-x: hidden; /* Hide horizontal scrollbar */
    -webkit-overflow-scrolling: touch;
}

#downloads_list_card .card-header h5:after {
    background-color: #ffc107 !important;
}


#payslips_list_card .card-header h5:after {
    background-color: #343a40 !important;
}


</style>

<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">
         <h3 class="text-info">
            <i class="feather icon-trending-up"></i> Dashboard
         </h3>
      </div>

      @include('inc.messages')
      
      <div class="card-block table-border-style">

         <div class="row">
         
            <div class="col-lg-4 col-md-12">

               <div class="col">
                  <div class="card text-white bg-dark mb-3" style="border-radius:10px;">
                     <div class="card-body" >
                        <h5 class="card-title" style="color:white;">
                           <center>EMPLOYEES</center>
                        </h5>
                        <p class="card-text">
                        <center>
                           <h1 style="color:white;">{{$employees}}</h1>
                        </center>
                        </p>
                     </div>
                  </div>
               </div>

               <div class="col">
                  <div class="card text-white bg-success mb-3" style="border-radius:10px;">
                     <div class="card-body" >
                        <h5 class="card-title" style="color:white;">
                           <center>DIVISIONS</center>
                        </h5>
                        <p class="card-text">
                        <center>
                           <h1 style="color:white;">6</h1>
                        </center>
                        </p>
                     </div>
                  </div>
               </div>

            </div>

            <div class="col-lg-4 col-md-12">

               <div class="col">
                  <div class="card" id="downloads_list_card">
                        <div class="card-header">
                        <h5 class="text-warning" >Downloads</h5>
                        </div>
                        <ul class="list-group list-group-flush">

                           @if($downloads)

                           @foreach($downloads as $data)
                           <li class="list-group-item">
                              <div class="row">
                                 <div class="col-lg-4 col-md-2">
                                    <img src="{{$data->image}}" height="50px" class="rounded-circle">
                                 </div>

                                 <div class="col-lg-8 col-md-10">
                                    <b>{{$data->firstname}}</b>
                                    <span style="font-size:0.8em"> has downloaded their payslip.</span><br> 
                                    <span style="font-size:0.75em" class="text-primary">{{date('F d, Y h:i a', strtotime($data->created_at))}}</span>

                                 </div>

                              </div>
                           </li>
                           @endforeach                           
                           
                           @endif
                        </ul>
                  </div>

               </div>

             
            </div>

            <div class="col-lg-4 col-md-12">

               <div class="col">

                  <div class="card" id="payslips_list_card">
                     <div class="card-header">
                        <h5 class="text-dark" >Payslips sent</h5>
                     </div>
                     <ul class="list-group list-group-flush">
                        
                        @if($payslips)

                           @foreach($payslips as $data)
                           <li class="list-group-item">
                              <div class="row">
                                 <div class="col-lg-4 col-md-2">
                                    <img src="{{$data->image}}" height="50px" class="rounded-circle">
                                 </div>

                                 <div class="col-lg-8 col-md-10">
                                    <b>{{$data->firstname}}</b>

                                       @if($data->transaction=="sent payslip notice")
                                       <span style="font-size:0.8em">has received a payslip reminder</span>                                        
                                       @else
                                       <span style="font-size:0.8em">has received a payslip reminder <i>with attachment</i> </span>
                                       @endif
                                    <br> 
                                    <span style="font-size:0.75em" class="text-primary">{{date('F d, Y h:i a', strtotime($data->created_at))}}</span>

                                 </div>

                              </div>
                           </li>
                           @endforeach                           
                           
                           @endif

                     </ul>
                  </div>

               </div>

            </div>
         </div>

         <div class="row">

            <div  class="col text-center">
               <label class="bg-dark label label-success">Monthly Deductions <b>{{date("Y")}}</b></label>
                  <div id="deduction-bar-chart" ></div>
            </div>

         </div>
         <br>
         <div class="row">

            <div  class="col-lg-6 col-md-12 text-center">
               <label class="bg-dark label label-success">GSIS Quarterly Deduction Breakdown <b>{{date("Y")}}</b></label>
                  <div id="gsis-bar-chart" ></div>
            </div>

            <div  class="col-lg-6 col-md-12 text-center">
               <label class="bg-dark label label-success">Pagibig Quarterly Deduction Breakdown <b>{{date("Y")}}</b></label>
                  <div id="pagibig-bar-chart" ></div>
            </div>

           
         </div>
         <br>
         <div class="row">

            <div  class="col-lg-6 col-md-12 text-center">
               <label class="bg-dark label label-success">Philhealth Quarterly Deduction Breakdown <b>{{date("Y")}}</b></label>
                  <div id="philhealth-bar-chart" ></div>
            </div>

            <div  class="col-lg-6 col-md-12 text-center">
               <label class="bg-dark label label-success">ILSEA Quarterly Deduction Breakdown <b>{{date("Y")}}</b></label>
                  <div id="ILSEA-bar-chart" ></div>
            </div>

           
         </div>
         <br>
         <div class="row">

            <div  class="col text-center">
               <label class="bg-dark label label-success">Withholding Tax <b>{{date("Y")}}</b></label>
                  <div id="tax-line-chart" ></div>
            </div>           
         </div>














      </div>
   </div>
</div>
@endsection