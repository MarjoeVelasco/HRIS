<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Employee;
use App\User;
use App\Leaves;
use App\Attendance;
use Carbon\Carbon;
use DB;
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
        //$this->middleware(['isSupervisor']); 
    }

    public function index(request $request)
    {
        //$employees = Employee::all();
        $currentDate = date('Y-m-d');
        $presentToday = 0;
        $employees = User::where('users.is_disabled','=',0)
        ->count();
        //$present = Attendance::get();
        $presents = DB::table('attendances')
            ->join(
                'employees',
                'employees.employee_id',
                '=',
                'attendances.employee_id'
            )
            ->join('users', 'users.id', '=', 'employees.employee_id')
            ->select(
                'users.image',
                'employees.firstname',
                'employees.lastname',
                'attendances.status',
                'attendances.time_in',
                'attendances.attendance_id'
            )
            ->where('attendances.status', '=', 'present')
            //->whereRaw('CAST(attendances.created_at as date = ?',[$currentDate])
            ->whereDate('attendances.time_in', '=', $currentDate)
            ->get();
        /*$absents = DB::table('attendances')
        ->join('employees', 'employees.employee_id', '=', 'attendances.employee_id')
        ->join('users', 'users.id', '=', 'employees.employee_id')
        ->select('users.image', 'employees.firstname', 'employees.lastname', 'attendances.status', 'attendances.time_in','attendances.attendance_id')
        ->where('attendances.status','!=','present')
        //->whereRaw('CAST(attendances.created_at as date = ?',[$currentDate])
        ->whereDate('attendances.time_in','=',$currentDate)
        ->get();*/
        $absents = DB::table('users')
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select('users.image', 'employees.firstname', 'employees.lastname')
            ->whereNotExists(function ($query) {
                $query
                    ->select(DB::raw(1))
                    ->from('attendances')
                    ->whereRaw('users.id = attendances.employee_id')
                    ->where('attendances.status', '=', 'present')
                    ->whereDate('attendances.time_in', '=', date('Y-m-d'));
            })
            ->where('users.is_disabled','=',0)
            ->get();
        $lates = DB::table('attendances')
            ->join(
                'employees',
                'employees.employee_id',
                '=',
                'attendances.employee_id'
            )
            ->join('users', 'users.id', '=', 'employees.employee_id')
            ->select(
                'users.image',
                'employees.firstname',
                'employees.lastname',
                'attendances.status',
                'attendances.time_in',
                'attendances.attendance_id'
            )
            ->where('attendances.status', '=', 'present')
            ->where('attendances.late', '!=', '00:00:00')
            ->whereDate('attendances.time_in', '=', $currentDate)
            ->get();

        $leaves = DB::table('attendances')
            ->join(
                'employees',
                'employees.employee_id',
                '=',
                'attendances.employee_id'
            )
            ->join('users', 'users.id', '=', 'employees.employee_id')
            ->select(
                'users.image',
                'employees.firstname',
                'employees.lastname',
                'attendances.status',
                'attendances.time_in',
                'attendances.attendance_id'
            )
            ->where('attendances.status', '=', 'on leave')
            ->whereDate('attendances.time_in', '=', $currentDate)
            ->get();

        $obaos = DB::table('attendances')
            ->join(
                'employees',
                'employees.employee_id',
                '=',
                'attendances.employee_id'
            )
            ->join('users', 'users.id', '=', 'employees.employee_id')
            ->select(
                'users.image',
                'employees.firstname',
                'employees.lastname',
                'attendances.status',
                'attendances.time_in',
                'attendances.attendance_id'
            )
            ->whereDate('attendances.time_in', '=', $currentDate)
            ->where(function($q) {
                $q->where('attendances.status', 'OB')
                  ->orWhere('attendances.status', 'AO');
            })
            ->get();   
            
        $in_office = DB::table('attendances')
            ->join('employees','employees.employee_id','=','attendances.employee_id')
            ->join('users', 'users.id', '=', 'employees.employee_id')
            ->join('work_settings', 'attendances.attendance_id', '=', 'work_settings.attendance_id')
            ->whereDate('attendances.time_in', '=', $currentDate)
            ->where('work_settings.status','in office')
            ->select('users.image','employees.firstname','employees.lastname','attendances.status','attendances.time_in','attendances.attendance_id')
            ->get();

        $wfh = DB::table('attendances')
            ->join('employees','employees.employee_id','=','attendances.employee_id')
            ->join('users', 'users.id', '=', 'employees.employee_id')
            ->join('work_settings', 'attendances.attendance_id', '=', 'work_settings.attendance_id')
            ->whereDate('attendances.time_in', '=', $currentDate)
            ->where('work_settings.status','work from home')
            ->select('users.image','employees.firstname','employees.lastname','attendances.status','attendances.time_in','attendances.attendance_id')
            ->get();


    
        $late = DB::table('attendances')
            ->where('attendances.status', '=', 'present')
            ->whereDate('attendances.time_in', '=', $currentDate)
            ->where('attendances.late', '!=', '00:00:00')
            ->count();
        $leave = DB::table('attendances')
            ->where('attendances.status', '=', 'on leave')
            ->whereDate('attendances.time_in', '=', $currentDate)
            ->count();

        $obao = DB::table('attendances')
            ->whereDate('attendances.time_in', '=', $currentDate)
            ->where(function($q) {
                $q->where('attendances.status', 'OB')
                  ->orWhere('attendances.status', 'AO');
            })
            ->count();

      
        

        /* $presents=$presents->filter(function($item,$currentDate)
        {
            $convertedDate = date('Y-m-d', strtotime(data_get($item, 'created_at')));
            return  $convertedDate == $currentDate;
        });*/
        foreach ($presents as $at) {
            if (
                $currentDate == date('Y-m-d', strtotime($at->time_in)) &&
                $at->status == "present"
            ) {
                $presentToday++;
            } else {
            }
        }
        return view(
            'admin.dashboard.index',
            compact('employees', 'presentToday', 'late', 'leave','obao')
        )
            ->with('presents', $presents)
            ->with('lates', $lates)
            ->with('absents', $absents)
            ->with('leaves', $leaves)
            ->with('in_office', $in_office)
            ->with('wfh', $wfh)
            ->with('obaos', $obaos);
    }

    public function piechartPercentageToday()
    {
        $currentDate = date('Y-m-d');
        $presents = DB::table('attendances')
            ->select('attendance_id')
            ->where('status', '=', 'present')
            ->whereDate('time_in', '=', $currentDate)
            ->count();

        $employees = Employee::count();
        return response()->json([
            'present' => $presents,
            'employee' => $employees,
        ]);
    }

    public function piechartPercentageMonth()
    {
        $currentDate = date('m');
        $presents = DB::table('attendances')
            ->select('attendance_id')
            ->where('status', '=', 'present')
            ->whereMonth('attendances.time_in', '=', $currentDate)
            ->count();

        $employees = Employee::count();

        $holiday = DB::table('holidays')
            ->select('inclusive_dates')
            ->get();
      
        return response()->json([
            'present' => $presents,
            'employee' => $employees,
            'holiday' => $holiday,
        ]);
    }

    public function piechartPercentageYear()
    {
        $currentDate = date('Y');
        $presents = DB::table('attendances')
            ->select('attendance_id')
            ->where('status', '=', 'present')
            ->whereYear('attendances.time_in', '=', $currentDate)
            ->count();
            $employees = Employee::count();

            $holiday = DB::table('holidays')
                ->select('inclusive_dates')
                ->get();
          
            return response()->json([
                'present' => $presents,
                'employee' => $employees,
                'holiday' => $holiday,
            ]);
    }
}
