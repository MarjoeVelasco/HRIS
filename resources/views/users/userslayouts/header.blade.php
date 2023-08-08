
<style>
@media only screen and (max-width: 575px)
{


.pcoded-header .dropdown .dropdown-menu {

    margin-right: 0 !important;

}
}
</style>
<nav class="navbar navbar-expand-lg navbar-light bg-white ">
  
  <h2>
  <i class="feather"></i>

  <img src="{{url('/images/logo-header2.png')}}" height="60px" alt="ILS logo header"/>
</a>
</h2>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarNav">
  <ul class="navbar-nav mr-auto">
    
    
  </ul>
  <ul class="navbar-nav mr-auto">
    <li class="nav-item {{ Request::segment(1) === 'index' ? 'active' : null }}">
      <a class="nav-link" href="{{URL::to('/home')}}">Home <span class="sr-only">(current)</span></a>
    </li>

    <li class="nav-item {{ Request::segment(1) === 'myattendance' ? 'active' : null }}">
      <a class="nav-link" href="{{URL::to('/myattendance')}}">Attendance</a>
    </li>

    <li class="nav-item {{ Request::segment(1) === 'myleavesv2' ? 'active' : null }}">
      <a class="nav-link" href="{{URL::to('/myleaves')}}">Leaves</a>
    </li>

    <li class="nav-item {{ Request::segment(1) === 'my-payslip' ? 'active' : null }}">
      <a class="nav-link" href="{{URL::to('/my-payslip')}}">Payslips</a>
    </li>

    

    <!--
    @role('Supervisor')
    <li class="nav-item">
      <a class="nav-link" href="{{URL::to('/recommendation')}}">Leaves for Approval</a>
    </li>
    @endrole
    -->

    @hasanyrole('Admin|HR/FAD|Accounting/FAD|Division Chief')

    @hasanyrole('Admin|HR/FAD')  
    <li class="nav-item">
      <a class="nav-link" href="{{URL::to('/dashboard')}}">Admin Dashboard&nbsp;
        @if($leave_hr_approval_notif>0)
          <span class="text-white position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger">
            {{$leave_hr_approval_notif}}
          </span>
        @endif
      </a>
    </li>    
    @endrole

    @role('Division Chief')  
    <li class="nav-item">
      <a class="nav-link" href="{{URL::to('/division-dashboard')}}">Dashboard&nbsp;
        @if($leave_supervisor_approval_notif>0)
          <span class="text-white position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger">
            {{$leave_supervisor_approval_notif}}
          </span>
        @endif
      </a>
    </li>    
    @endrole


    @role('Accounting/FAD')  
    <li class="nav-item">
      <a class="nav-link" href="{{URL::to('/accounting-dashboard')}}">Accounting/Cash Dashboard</a>
    </li>    
    @endrole
    @endrole

    @role('Election Committee')
    <li class="nav-item">
      <a class="nav-link" href="{{ URL::to('/elections-dashboard') }}">Election Dashboard</a>
    </li>    
    @endrole
    

  </ul>
  <ul class="navbar-nav ml-auto  float-right">
            <li class="pcoded-header bg-white">
                <div class="dropdown drp-user">
                    <a href="javascript:" class="dropdown-toggle " data-toggle="dropdown">
                        <i class="icon feather icon-settings"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-notification">
                        <div class="pro-head">
                             <!-- <img src="{{ Auth::user()->image }}" class="img-radius" alt="User-Profile-Image"> -->
                             <i class="feather icon-user" style="float:right;"></i> <span>Hi! {{ Auth::user()->name }}</span>
                            <a class="dud-logout" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                
                                
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                      
                     
                       
                      <a href="profile">
                       <div style="padding:15px;">
                       <i class="feather icon-edit" style="float:right;"></i> Edit Profile
                      </div>
                      </a> 

                    <hr style="margin:0;">

                      <a href="settings">
                       <div style="padding:15px;">
                       <i class="feather icon-lock" style="float:right;"></i> Change Password
                      </div>
                      </a> 
                      
                      <hr style="margin:0;">

                      <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                       <div style="padding:15px;">
                       <i class="feather icon-log-out" style="float:right;"></i>  Log out 
                      </div>
                      </a> 
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                                    
                    </div>
                </div>
            </li>
        </ul>
</div>
</nav>

