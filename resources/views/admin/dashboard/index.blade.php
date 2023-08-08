{{-- \resources\views\users\index.blade.php --}}
@extends('admin.adminlayouts.master')
@section('title', '| Dashboard')
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

#present_list_card .card-header h5:after {
    background-color: #28a745 !important;
}

#absent_list_card .card-header h5:after {
    background-color: #ffc107 !important;
}

#late_list_card .card-header h5:after {
    background-color: #343a40 !important;
}

#leave_list_card .card-header h5:after {
    background-color: #dc3545 !important;
}

#obao_list_card .card-header h5:after {
    background-color: #17a2b8 !important;
}
</style>

@include('inc.messages')
<div class="row">
	<div class="col-xl-12">
		<div class="card">
			<div class="card-header">
				<h3 class="text-info"><i class="feather icon-trending-up"></i> Admin Dashboard</h3>
			</div>
      
      
			<div class="card-block">
				
			<!-- Cards -->	
			<div class="row">

			<div class="col-md">
				<div class="card text-white bg-primary mb-3" style="border-radius:10px;">
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

			<div class="col-md">
				<div class="card text-white bg-success mb-3" style="border-radius:10px;">
					<div class="card-body" >
							<h5 class="card-title" style="color:white;">
								<center>PRESENT</center>
							</h5>
								<p class="card-text">
									<center>
										<h1 style="color:white;">{{$presentToday}}</h1>
									</center>
								</p>
					</div>
				</div>
			</div>

			<div class="col-md">
				<div class="card text-white bg-warning mb-3" style="border-radius:10px;">
					<div class="card-body" >
							<h5 class="card-title" style="color:white;">
								<center>ABSENT</center>
							</h5>
								<p class="card-text">
									<center>
										<h1 style="color:white;">{{($employees - $presentToday)-$leave}}</h1>
									</center>
								</p>
					</div>
				</div>
			</div>

			<div class="col-md">
				<div class="card text-white bg-dark mb-3" style="border-radius:10px;">
					<div class="card-body" >
							<h5 class="card-title" style="color:white;">
								<center>LATE</center>
							</h5>
								<p class="card-text">
									<center>
										<h1 style="color:white;">{{$late}}</h1>
									</center>
								</p>
					</div>
				</div>
			</div>

			<div class="col-md">
				<div class="card text-white bg-danger  mb-3" style="border-radius:10px;">
					<div class="card-body" >
							<h5 class="card-title" style="color:white;">
								<center>LEAVE</center>
							</h5>
								<p class="card-text">
									<center>
										<h1 style="color:white;">{{$leave}}</h1>
									</center>
								</p>
					</div>
				</div>
			</div>

			<div class="col-md">
				<div class="card text-white bg-info  mb-3" style="border-radius:10px;">
					<div class="card-body" >
							<h5 class="card-title" style="color:white;">
								<center>OB / AO</center>
							</h5>
								<p class="card-text">
									<center>
										<h1 style="color:white;">{{$obao}}</h1>
									</center>
								</p>
					</div>
				</div>
			</div>
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			</div>





				</div>	
			</div>
		</div>
		</div>

<div class="row">

	


		<div class="col-md">
		<div class="card" id="present_list_card">
			<div class="card-header">
				<h5 class="text-success" >Present Employees</h5>
			</div>

			<div class="card-block" style="padding:0;">
			
			<ul class="list-group list-group-flush" id="present_list">

  @foreach($presents as $present)        
  
  <li class="list-group-item">
  
  <img src="{{$present->image}}" height="45px" class="rounded-circle">

  {{$present->firstname}}</li>
   @endforeach
  </ul>
			
			
			</div>	
			
		</div>
		</div>

		<div class="col-md">
		<div class="card" id="absent_list_card">
			<div class="card-header">
				<h5 class="text-warning" >Absent Employees</h5>
			</div>
      
      
			<div class="card-block" style="padding:0;">
			

			<ul class="list-group list-group-flush" id="absent_list"> 

@foreach($absents as $present)        

<li class="list-group-item">

<img src="{{$present->image}}" height="45px" class="rounded-circle">
{{$present->firstname}} 
</li>
 @endforeach
</ul>

			</div>	
			
		</div>
		</div>

		<div class="col-md">
		<div class="card" id="late_list_card">
			<div class="card-header">
				<h5 class="text-dark" >Late Employees</h5>
			</div>
      
      
			<div class="card-block" style="padding:0;">
		
			<ul class="list-group list-group-flush " id="late_list">

@foreach($lates as $late)        



<li class="list-group-item">
<img src="{{$late->image}}" height="45px" class="rounded-circle">
{{$late->firstname}} 
</li>
@endforeach
  </ul>

			</div>	
			
		</div>
		</div>

		<div class="col-md">
		<div class="card" id="leave_list_card">
			<div class="card-header">
				<h5 class="text-danger" >On Leave Employees</h5>
			</div>
      
      
			<div class="card-block" style="padding:0;">
			

			<ul class="list-group list-group-flush " id="late_list">

  @foreach($leaves as $leave)        
  
  <li class="list-group-item">
  <img src="{{$leave->image}}" height="45px" class="rounded-circle">
  {{$leave->firstname}} 
  </li>


   @endforeach
  </ul>




			</div>	
			
		</div>
		</div>

		<div class="col-md">
		<div class="card" id="obao_list_card">
			<div class="card-header">
				<h5 class="text-info" >OB/AO Employees</h5>
			</div>
      
      
			<div class="card-block" style="padding:0;">
			

			<ul class="list-group list-group-flush " id="late_list">

  @foreach($obaos as $obao)        
  
  <li class="list-group-item">
  <img src="{{$obao->image}}" height="45px" class="rounded-circle">
  {{$obao->firstname}} 
  </li>


   @endforeach
  </ul>




			</div>	
			
		</div>
		</div>






</div>


<!-- for in office and wfh -->
<div class="row">


		<div class="col-md">
		<div class="card" id="leave_list_card">
			<div class="card-header">
				<h5 class="text-danger" >Work from home</h5>
			</div>
      
      
			<div class="card-block" style="padding:0;">
			

			<ul class="list-group list-group-flush " id="late_list">

  @foreach($wfh as $data)        
  
  <li class="list-group-item">
  <img src="{{$data->image}}" height="45px" class="rounded-circle">
  {{$data->firstname}} 
  </li>


   @endforeach
  </ul>




			</div>	
			
		</div>
		</div>

		<div class="col-md">
		<div class="card" id="obao_list_card">
			<div class="card-header">
				<h5 class="text-info" >In Office</h5>
			</div>
      
      
			<div class="card-block" style="padding:0;">
			

			<ul class="list-group list-group-flush " id="late_list">

  @foreach($in_office as $data)        
  
  <li class="list-group-item">
  <img src="{{$data->image}}" height="45px" class="rounded-circle">
  {{$data->firstname}} 
  </li>


   @endforeach
  </ul>




			</div>	
			
		</div>
		</div>

</div>

<!-- end for in office and wfh-->

<div class="row">

<div class="col-md-4">
		<div class="card">
			<div class="card-header">
				<h5 class="text-info">Attendance Percentage Today</h5>
			</div>
      
      
			<div class="card">
			<div id="pie-chart-today"></div>
			</div>	
			
		</div>
</div>

<div class="col-md-4">
		<div class="card">
			<div class="card-header">
				<h5 class="text-info">Attendance Percentage this Month</h5>
			</div>
      
      
			<div class="card">
			<div id="pie-chart-month"></div>
			</div>	
			
		</div>
</div> 

<div class="col-md-4">
		<div class="card">
			<div class="card-header">
				<h5 class="text-info">Attendance Percentage this Year</h5>
			</div>
      
      
			<div class="card">
			<div id="pie-chart-year"></div>
			</div>	
			
		</div>
</div>




</div>


		

@endsection