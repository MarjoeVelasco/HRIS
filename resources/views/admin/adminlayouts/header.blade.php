

<nav class="pcoded-navbar">
    <div class="navbar-wrapper">
        <div class="navbar-brand header-logo" style="margin-top:10px;">
            <a href="{{ route('users.index') }}" class="b-brand">
                
                @if(empty(Auth::user()->image))
						<img id="navbar-avatar" style="float:left;" src="/images/user/avatar-unisex.png" alt="{{ Auth::user()->name  }}'s image" width="30%" height="30%" class="rounded-circle">
						@else
						<img id="navbar-avatar" style="float:left;" src="{{ URL::to('/') }}/{{ Auth::user()->image }}" alt="{{ Auth::user()->name  }}'s image" width="30%" height="30%" class="rounded-circle">
						@endif <span class="b-title"> ILS</span>
            </a>
            <a class="mobile-menu" id="mobile-collapse" href="javascript:"><span></span></a>
        </div>
        <div class="navbar-content scroll-div">
            <ul class="nav pcoded-inner-navbar">
                <li class="nav-item pcoded-menu-caption">
                    <label>Navigation</label>
                </li>

                

                <li class="nav-item">
                    <a href="{{ URL::to('/') }}/home" class="nav-link "><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Back to Home</span></a>
                </li>

                @hasanyrole('Admin|HR/FAD')
                <li class="nav-item">
                    <a href="{{ URL::to('/') }}/dashboard" class="nav-link "><span class="pcoded-micon"><i class="feather icon-briefcase"></i></span><span class="pcoded-mtext">HR Dashboard</span></a>
                </li>
                @endrole

                @role('Division Chief')
                <li class="nav-item">
                    <a href="{{ URL::to('/') }}/division-dashboard" class="nav-link "><span class="pcoded-micon"><i class="feather icon-briefcase"></i></span><span class="pcoded-mtext">Division Dashboard</span></a>
                </li>
                @endrole


                @role('Accounting/FAD')
                <li class="nav-item">
                    <a href="{{ URL::to('/') }}/accounting-dashboard" class="nav-link "><span class="pcoded-micon"><i class="feather icon-briefcase"></i></span><span class="pcoded-mtext">Accounting Dashboard</span></a>
                </li>
                @endrole

                @role('Accounting/FAD')
                <!-- coming soon payroll
                <li class="nav-item pcoded-menu-caption">
                    <label>Payroll Management (Coming Soon) </label>
                </li>

                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-user"></i></span><span class="pcoded-mtext">Payroll (Coming Soon)</span></a>
                    <ul class="pcoded-submenu">
                        <li class=""><a href="" class="">Coming Soon</a></li>
                        
                    </ul>
                </li>
                -->

                <li class="nav-item pcoded-menu-caption">
                    <label>Payslip Management</label>
                </li>
                
                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="fa fa-credit-card"></i></span><span class="pcoded-mtext">Paylips</span></a>
                    <ul class="pcoded-submenu">
                        <li class=""><a href="{{ URL::to('/') }}/payslip-import" class="">Import Data</a></li>
                        <li class=""><a href="{{ URL::to('/') }}/payslip-general" class="">General</a></li>
                        <li class=""><a href="{{ URL::to('/') }}/payslip-mail" class="">Mailing</a></li>
                    </ul>
                </li>
                
                <li class="nav-item pcoded-menu-caption">
                    <label>Employee Management</label>
                </li>
                
                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-user"></i></span><span class="pcoded-mtext">Employees</span></a>
                    <ul class="pcoded-submenu">
                        <li class=""><a href="{{ URL::to('/') }}/payslip-general" class="">General</a></li>
                        <li class=""><a href="{{ URL::to('/') }}/payslip-import" class="">Import Data</a></li>
                       
                    </ul>
                </li>

                

                <li class="nav-item pcoded-menu-caption">
                    <label>Reports</label>
                </li>
                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-pie-chart"></i></span><span class="pcoded-mtext">Reports</span></a>
                    <ul class="pcoded-submenu">
                        <li class=""><a href="{{ URL::to('/') }}/payslip-report" class="">Payslip Report</a></li>
                        <li class=""><a href="{{ URL::to('/') }}/payslip-report-download" class="">Download Report</a></li>
                        <li class=""><a href="" class="">(More Reports Coming Soon)</a></li>
                       
                        
                    </ul>
                </li>

                @endrole



                @hasanyrole('Admin|HR/FAD')
                <li class="nav-item pcoded-menu-caption">
                    <label>User Management</label>
                </li>

                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-user"></i></span><span class="pcoded-mtext">Users</span></a>
                    <ul class="pcoded-submenu">
                        <li class=""><a href="{{ route('users.index') }}" class="">View Users</a></li>
                        <li class=""><a href="{{ route('users.create') }}" class="">Add User</a></li>
                    </ul>
                </li>
                <li class="nav-item pcoded-menu-caption">
                    <label>Roles and Permisions</label>
                </li>
                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-settings"></i></span><span class="pcoded-mtext">Roles</span></a>
                    <ul class="pcoded-submenu">
                        <li class=""><a href="{{ route('roles.index') }}" class="">View Roles</a></li>
                        <li class=""><a href="{{ route('roles.create') }}" class="">Add Role</a></li>
                    </ul>
                </li>
                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-shield"></i></span><span class="pcoded-mtext">Permissions</span></a>
                    <ul class="pcoded-submenu">
                        <li class=""><a href="{{ route('permissions.index') }}" class="">View Permissions</a></li>
                        <li class=""><a href="{{ route('permissions.create') }}" class="">Add Permission</a></li>
                    </ul>
                </li>

                <li class="nav-item pcoded-menu-caption">
                    <label>Attendance Management</label>
                </li>

                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-calendar"></i></span><span class="pcoded-mtext">Attendances</span></a>
                    <ul class="pcoded-submenu">
                        <li class=""><a href="{{ URL::to('/') }}/manageattendance" class="">General</a></li>
                        <li class=""><a href="{{ URL::to('/') }}/manageattendanceobao" class="">OB/AO</a></li>
                        <li class=""><a href="{{ URL::to('/') }}/manageattendanceothers" class="">Holidays & Weekends</a></li>
                        <li class=""><a href="{{ URL::to('/') }}/special-attendance" class="">Custom Time Out</a></li>
                    </ul>
                </li>

                <li class="nav-item pcoded-menu-caption">
                    <label>Leave Management</label>
                </li>

                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-calendar"></i></span>
                    <span class="pcoded-mtext">Leaves  &nbsp;
                            @if($leave_hr_approval_notif>0)
                                <span class="text-white position-absolute top-0 start-100 translate-middle badge rounded-circle bg-warning">
                                {{$leave_hr_approval_notif}}
                                </span>
                            @endif
                    </span>
                    </a>


                    <ul class="pcoded-submenu">
                        <li class="">
                            <a href="{{ URL::to('/') }}/managefiledleaves" class="">General &nbsp;
                            @if($hr_leave_approval_notif>0)
                                <span class="text-white position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger">
                                {{$hr_leave_approval_notif}}
                                </span>
                            @endif
                            </a>
                        </li>

                        <li class=""><a href="{{ URL::to('/') }}/managefiledcto" class="">CTO &nbsp;
                            @if($hr_cto_approval_notif>0)
                                <span class="text-white position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger">
                                {{$hr_cto_approval_notif}}
                                </span>
                            @endif
                            </a>
                        </li>

                        <li class=""><a href="{{ URL::to('/') }}/leave-card" class="">Credits</a></li>
                        <!--
                        <li class=""><a href="{{ URL::to('/') }}/manageattendanceothers" class="">Archive</a></li>
                        -->
                    </ul>
                </li>

               
                 <li class="nav-item">
                    <a href="{{ URL::to('/') }}/manageleaves" class="nav-link "><span class="pcoded-micon"><i class="feather icon-clipboard"></i></span><span class="pcoded-mtext">Leaves (will be archived)</span></a>
                </li>

                <li class="nav-item">
                    <a href="{{ URL::to('/') }}/manageobao" class="nav-link "><span class="pcoded-micon"><i class="feather icon-file-text"></i></span><span class="pcoded-mtext">OB/AO</span></a>
                </li>

                <li class="nav-item">
                    <a href="{{ URL::to('/') }}/manageholidays" class="nav-link "><span class="pcoded-micon"><i class="feather icon-star"></i></span><span class="pcoded-mtext">Holidays</span></a>
                </li>
               

                <li class="nav-item pcoded-menu-caption">
                    <label>Reports</label>
                </li>
                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-pie-chart"></i></span><span class="pcoded-mtext">Reports</span></a>
                    <ul class="pcoded-submenu">
                   
                        <li class=""><a href="{{ URL::to('/') }}/communication-utility" class="">Communication Utility Allowance Report</a></li>
                        <li class=""><a href="{{ URL::to('/') }}/attendancereport" class="">Attendance Report</a></li>
                        <li class=""><a href="{{ URL::to('/') }}/employeereport" class="">Employee Report</a></li>
                        <li class=""><a href="{{ URL::to('/') }}/leavereport" class="">Leave Report</a></li>
                        
                    </ul>
                </li>

                <li class="nav-item pcoded-menu-caption">
                    <label>Archives</label>
                </li>

                <li class="nav-item">
                    <a href="{{ URL::to('/') }}/archivedleave" class="nav-link "><span class="pcoded-micon"><i class="feather icon-folder"></i></span><span class="pcoded-mtext">Archived Leaves</span></a>
                </li>

                <li class="nav-item pcoded-menu-caption">
                    <label>System Settings</label>
                </li>

                <li class="nav-item">
                    <a href="{{ URL::to('/attendance-setting') }}" class="nav-link "><span class="pcoded-micon"><i class="feather icon-settings"></i></span><span class="pcoded-mtext">Attendance Settings</span></a>
                </li>
                <li class="nav-item">
                    <a href="{{ URL::to('/address') }}" class="nav-link "><span class="pcoded-micon"><i class="feather icon-settings"></i></span><span class="pcoded-mtext">Public IP Addresses</span></a>
                </li>

                
                @endrole

                @hasanyrole('Election Committee')

                <li class="nav-item">
                    <a href="{{ URL::to('/elections-dashboard') }}" class="nav-link "><span class="pcoded-micon"><i class="feather icon-briefcase"></i></span><span class="pcoded-mtext">Election Dashboard</span></a>
                </li>

                <li class="nav-item pcoded-menu-caption">
                    <label>ILSEA Elections</label>
                </li>
                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-inbox"></i></span><span class="pcoded-mtext">Forms</span></a>
                    <ul class="pcoded-submenu">
                   
                        <li class=""><a href="{{ route('forms.index') }}" class="">View Forms</a></li>
                        <li class=""><a href="{{ route('forms.create') }}" class="">Add Forms</a></li>
                                                
                    </ul>
                </li>

                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-bookmark"></i></span><span class="pcoded-mtext">Categories</span></a>
                    <ul class="pcoded-submenu">
                   
                        <li class=""><a href="{{ route('categories.index') }}" class="">View Categories</a></li>
                        <li class=""><a href="{{ route('categories.create') }}" class="">Add Categories</a></li>
                        
                    </ul>
                </li>

                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-users"></i></span><span class="pcoded-mtext">Candidates</span></a>
                    <ul class="pcoded-submenu">
                   
                        <li class=""><a href="{{ route('candidates.index') }}" class="">View Candidates</a></li>
                        <li class=""><a href="{{ route('candidates.create') }}" class="">Add Candidates</a></li>
                        
                    </ul>
                </li>

                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-tag"></i></span><span class="pcoded-mtext">Voters</span></a>
                    <ul class="pcoded-submenu">
                   
                        <li class=""><a href="{{ route('voters.index') }}" class="">View Voters</a></li>
                        <li class=""><a href="{{ route('voters.create') }}" class="">Add Voters</a></li>
                        
                    </ul>
                </li>

                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-clipboard"></i></span><span class="pcoded-mtext">Reports</span></a>
                    <ul class="pcoded-submenu">
                   
                        <li class=""><a href="{{ route('voters.index') }}" class="">Raw Data</a></li>
                        
                    </ul>
                </li>

                @endrole




                @role('Division Chief')

                <li class="nav-item pcoded-menu-caption">
                    <label>Leaves for Approval &nbsp;
                        @if($supervisor_leave_approval_notif>0)
                            <span class="text-white position-absolute top-0 start-100 translate-middle badge rounded-circle bg-warning">
                            {{$leave_supervisor_approval_notif}}
                            </span>
                        @endif
                    </label>
                </li>

                <li class="nav-item">
                    <a href="{{ URL::to('/') }}/supervise-general-leave" class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-calendar"></i>
                        </span>

                        <span class="pcoded-mtext">General &nbsp;
                            @if($supervisor_leave_approval_notif>0)
                                <span class="text-white position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger">
                                {{$supervisor_leave_approval_notif}}
                                </span>
                            @endif
                        </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ URL::to('/') }}/supervise-general-cto" class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-calendar"></i>
                        </span>
                        
                        <span class="pcoded-mtext">CTO &nbsp;
                            @if($supervisor_cto_approval_notif>0)
                                <span class="text-white position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger">
                                {{$supervisor_cto_approval_notif}}
                                </span>
                            @endif
                        </span>
                    </a>
                </li>

                

                
                <li class="nav-item pcoded-menu-caption">
                    <label>Reports</label>
                </li>
                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-pie-chart"></i></span><span class="pcoded-mtext">Reports</span></a>
                    <ul class="pcoded-submenu">
                
                        <li class=""><a href="{{ URL::to('/') }}/attendancereport" class="">Attendance Report</a></li>
                        <li class=""><a href="{{ URL::to('/') }}/employeereport" class="">Accomplishment Report</a></li>
                        
                    </ul>
                </li>


                @endrole

                <li class="nav-item pcoded-menu-caption">
                    <label>Others</label>
                </li>

                <li class="nav-item" style="margin-bottom:50px;">
                    <a href="{{ URL::to('/') }}/errorlog" class="nav-link "><span class="pcoded-micon"><i class="feather icon-alert-circle"></i></span><span class="pcoded-mtext">Error Log</span></a>
                </li>
                    
            </ul>
        </div>
    </div>
</nav>
<!-- [ Header ] start -->
<header class="navbar pcoded-header navbar-expand-lg navbar-light">
    <div class="m-header">
        <a class="mobile-menu" id="mobile-collapse1" href="javascript:"><span></span></a>
    </div>
    <a class="mobile-menu" id="mobile-header" href="javascript:">
        <i class="feather icon-more-horizontal"></i>
    </a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
        
            <li class="nav-item">
                <div class="main-search">
                    <div class="input-group">
                        <input type="text" id="m-search" class="form-control" placeholder="Search . . .">
                        <a href="javascript:" class="input-group-append search-close">
                            <i class="feather icon-x input-group-text"></i>
                        </a>
                        <span class="input-group-append search-btn btn btn-primary">
                            <i class="feather icon-search input-group-text"></i>
                        </span>
                    </div>
                </div>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li>
                <div class="dropdown drp-user">
                    <a href="javascript:" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon feather icon-settings"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-notification">
                        <div class="pro-head" >
                            <!-- <img src="assets/images/user/avatar-1.jpg" class="img-radius" alt="User-Profile-Image"> -->
                            
                            <span>Hi! {{ Auth::user()->name }}</span>
                            <a class="dud-logout" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                
                                <i class="feather icon-log-out"></i>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                       
                    </div>
                </div>
            </li>
        </ul>
    </div>
</header>
<!-- [ Header ] end -->