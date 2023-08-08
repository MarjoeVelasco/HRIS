@extends('users.userslayouts.master')
@section('content')
@section('title', 'Home')


<style>

.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}



@media all and (max-width:555px) {
        .calculator tr {    display: table;  width:100%;    }               
        .calculator td {    display: table-row; }           
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>



<div class="container d-flex justify-content-center  mt-2">
   <div class="card">

   <h3 class="bg-info card-header text-center" style="color:white">
    <i class="feather icon-user"></i> Profile
        </h3>

      <div class="card-body">
        


         <div class="table calculator">
         <div class="row">
         <div class="col">
                     @if(empty(Auth::user()->image))
                     <img src="/images/user/avatar-unisex.png" alt="{{ Auth::user()->name  }}'s image" width="150px" height="150px" class="rounded-circle rounded mx-auto d-block"><br>
                     @else
                     <img src="{{ Auth::user()->image }}" alt="{{ Auth::user()->name  }}'s image" width="150px" height="150px" class="rounded-circle rounded mx-auto d-block"><br>
                     @endif


                     
                     <!-- Start live time -->
                     
                     <div class="row justify-content-center">
                           <span id="live_time_span" class="font-weight-bold align-middle px-2">00:00:00</span>
                     </div>
                     <br>

                     <!-- End live time -->


                     <!-- Start Work Environment -->
                     <div class="row justify-content-center">
                        <img src="{{url('/images/icons/loading4.gif')}}" height="60px" id="loading_container">
                     </div>

                     <div  id="work_environment_container">
                        <div class="row justify-content-center">
                              <span id="wfh_span" class="font-weight-bold align-middle px-2">WFH</span>                       
                              <label class="switch">
                              <input type="checkbox" id="work_place_checkbox">
                              <span class="slider round"></span>
                              </label>
                              <span style="visibility:hidden;" id="in_office_span" class="font-weight-bold align-middle px-2">IN OFFICE</span> 
                        </div>
                     </div>

                     <!-- End Work Environment -->

                    
         </div>

         <div class="col">
         <table class="table">
            <tbody>
               <tr>
                 
                  @foreach($users as $user)
                  <th>Name</th>
                  <td>{{ $user->firstname }} {{ $user->middlename }} {{ $user->lastname }} {{ $user->extname }}</td>
                  <th>Position</th>
                  <td>{{ $user->position }}</td>
                  @endforeach
                  </tr>
               <tr>   
               <th>Time In</th>
                  <td><span id="user_time_in">
                     @if($time_in!="No Entry")
                     {{date('F d, Y h:i a', strtotime($time_in))}}
                     @else
                     No Entry
                     @endif
                     <span></td>

                  <th>Time Out</th>
                  <td>
                  @if($time_out!="No Entry")   
                  {{date('F d, Y h:i a', strtotime($time_out))}}
                  @else
                  No Entry
                  @endif
               </td>
               </tr>

               <tr>   
               <th>Hours Worked </th>
               
               @if($hours_worked)
                  @if($hours_worked=="00:00:00")
                  <td>No time out</td>
                  @else
                  <td>{{$hours_worked}}</td>
                  @endif
               @endif
               

               <th>Late </th>
               @if($late)
                  @if($late=="00:00:00")
                     <td>On time</td>
                  @else
                     <td>{{$late}}</td>
                  @endif
               @endif
               </tr>

              

             
               
            </tbody>
         </table>
               <form method="post" action="{{route('index.store')}}">
                        @csrf
                        <select name="employee_id" hidden="">
                           <option>{{ Auth::user()->id }}</option>
                        </select>
                        <input type="hidden" name="time_status" value="timed_in"/>
                        <input type="hidden" name="time_in" value="{{ date('Y-m-d H:i:s') }}"/>
                        <input type="hidden" id="work_place_input" name="work_place" value="{{$work_envi}}"/>
                       
                        @if(!$statusMark)
                        <button  type="submit" class="btn btn-primary " name="status" value="present"><span class="pcoded-micon"><i class="feather icon-clock"></i></span><span class="pcoded-mtext"> Time out</span></button>
                        @else
                        <button  type="submit" class="btn btn-primary " id="time_in_btn" name="status" value="present"><span class="pcoded-micon"><i class="feather icon-clock"></i></span><span class="pcoded-mtext"> Time in</span></button>
                        @endif
                        <a href="myattendance" class="btn btn-dark"><span class="pcoded-micon"><i class="feather icon-calendar"></i></span><span class="pcoded-mtext"> Attendance</span></a>
                        <!--
                        <a href="requestleave" class="btn btn-warning"><span class="pcoded-micon"><i class="feather icon-message-circle"></i></span><span class="pcoded-mtext"> Request Leave</span></a> 
                        -->
                        <a href="file-leave" class="btn btn-warning"><span class="pcoded-micon"><i class="feather icon-message-circle"></i></span><span class="pcoded-mtext"> Request Leave</span></a>
                        <a href="{{URL::to('/my-payslip')}}" class="btn btn-success"><span class="pcoded-micon"><i class="feather icon-message-circle"></i></span><span class="pcoded-mtext"> Payslips</span></a>
                        @if($vote)
                           <a href="{{URL::to('/ilsea')}}" class="btn text-white" style="background:#A020F0"><span class="pcoded-micon"><i class="feather icon-clipboard"></i></span><span class="pcoded-mtext"> Vote</span></a>
                        @endif
               </form>

         </div>

         </div>
         </div>




         @if($message=Session::get('success'))
         <div class="alert alert-success alert-dismissible fade show" role="alert">
            <p>{{$message}}</p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         @endif
         @if($message=Session::get('timeout'))

         @if(Session::get('double_timeout'))
         <script>
            $(function() {
                $('#modalConfirmationDoubleEntry').modal('show');
            });
            
         </script>
        @else
        <script>
            $(function() {
                $('#modalAccomplishment').modal('show');
            });
            
         </script>
          @endif
         @endif




 <!-- Modal -->
 <div id="modalAccomplishment" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-info" role="document">
               <!-- Modal content-->
               <div class="modal-content">
                  <div class="modal-header panel-heading bg-info">
                     <h5 class="modal-title" style="color:white;">Accomplishments of the day</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <div class="modal-body">
                     <form method="PUT" action="/accomplishment">
                     {!! csrf_field() !!}
                        <p>Before timing out, please enter your accomplishments first to continue.</p>
                        
                        <br>
                        <textarea rows="10" name="accomplishment" class="form-control" required></textarea>
                        <input type="hidden" name="time_status" value="timed_out"/>  
                        <input type="hidden" name="time_out" value="{{ date('Y-m-d H:i:s') }}"/>
                        <input type="hidden" name="time_in" id="time_in_val" value=" "/>
                        <input type="hidden" name="work_env" id="work_env_value" value="wfh"/>

                  </div>
                  <div class="modal-footer">
                  <input type="hidden" id="public_ip_timeout_text" name="public_ip_timeout" value="wfh">
                  <button type="submit" name="save" value='{{$message}}' class="btn btn-primary"><i class="feather icon-save"></i>Submit</button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  </form>
                  </div>
               </div>
            </div>
         </div>
         


         <!-- Modal -->
         <div id="modalConfirmationDoubleEntry" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-info" role="document">
               <!-- Modal content-->
               <div class="modal-content">
                
                  <div class="modal-body">
                  <center><h1 style="color:orange;"><i class="feather icon-alert-triangle"></i></h1><center>
        <br><center><h3>Existing Time out Entry Detected!</h3><center>
        <br><center><p>You have already timed out. Are you sure you want to perform this again?</p><center>
        <br><center><button class="btn btn-warning" id="btn_multipleTimeOut">Confirm</button><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><center></form>
  
                  </div>
                 
               </div>
            </div>
         </div>





         @if($message=Session::get('found'))
         <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <p>{{$message}}</p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         @endif
      </div>
   </div>
</div>







<!-- Modal in office-->
<div id="network_detection_modal_in_office" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-info" role="document">
               <!-- Modal content-->
               <div class="modal-content">
                  <div class="modal-header panel-heading bg-info">
                     <center><h3 class="modal-title text-white"><i class="feather icon-monitor"></i> IN OFFICE </h2></center>
                  </div>
                  <div class="modal-body">
                   <p class="text-center">Hi, you are in office today, and using the ILS Network. <br>You may now time in! Press OK to proceed.<br>Wear your mask!<br><br>Marked as <span class="text-primary fw-bold">IN OFFICE</span><p>
                   <center><button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button></center>
                  </div>



               </div>
            </div>
         </div>


<!-- Modal wfh-->
<div id="network_detection_modal_wfh" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-info" role="document">
               <!-- Modal content-->
               <div class="modal-content">
                  <div class="modal-header panel-heading bg-info">
                     <center><h3 class="modal-title text-white"><i class="feather icon-home"></i> WFH </h2></center>
                     
                  </div>
                  <div class="modal-body">
                   <p class="text-center">Hi, you are working from home today! Press OK to proceed<br>Stay Safe!<br><br>Marked as <span class="text-primary fw-bold">WFH</span><p>
                   <center><button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button></center>
                  </div>



               </div>
            </div>
         </div>


@stop