<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Attendance;
use App\Employee;
use DB;
use App\Exports\EmployeeExport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;
class EmployeeReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin|Division Chief']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        $division=DB::table('employees')
        ->select('division')
        ->where('employee_id',auth()->user()->id)
        ->first();

        //$employees = Employee::all();
        return view('admin.employeereport.index')
        ->with('division', $division->division);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function report(request $request)
    {
        $present = 0;
        $absents = 0;
        $leaves = 0;
        $percentage = 0;
        if (null != $request->input('from') && null != $request->input('to')) {
            $from = $request->input('from');
            $to = $request->input('to');
            $attendance = DB::table('users')
                ->join(
                    'attendances',
                    'attendances.employee_id',
                    '=',
                    'users.id'
                )
                ->select(
                    'attendances.attendance_id',
                    'attendances.employee_id',
                    'users.id',
                    'employees.lastname',
                    'employees.firstname',
                    'employees.middlename',
                    'employees.extname',
                    'employees.position',
                    'employees.item_number',
                    'employees.sg',
                    'employees.stepinc',
                    'employees.division',
                    'employees.unit'
                )
                ->whereDate(
                    'attendances.created_at',
                    '>=',
                    date('Y-m-d', strtotime($from))
                )
                ->whereDate(
                    'attendances.created_at',
                    '<=',
                    date('Y-m-d', strtotime($to))
                )
                ->get();
            $present = DB::table('attendances')
                ->whereDate('created_at', '>=', date('Y-m-d', strtotime($from)))
                ->whereDate('created_at', '<=', date('Y-m-d', strtotime($to)))
                ->where('status', '=', 'present')
                ->count();
            $absents = DB::table('attendances')
                ->whereDate('created_at', '>=', date('Y-m-d', strtotime($from)))
                ->whereDate('created_at', '<=', date('Y-m-d', strtotime($to)))
                ->where('status', '=', 'absent')
                ->count();
            $leaves = DB::table('attendances')
                ->whereDate('created_at', '>=', date('Y-m-d', strtotime($from)))
                ->whereDate('created_at', '<=', date('Y-m-d', strtotime($to)))
                ->where('status', '=', 'leave')
                ->count();
            $total = DB::table('attendances')
                ->whereDate('created_at', '>=', date('Y-m-d', strtotime($from)))
                ->whereDate('created_at', '<=', date('Y-m-d', strtotime($to)))
                ->count();
            if ($total > 0) {
                $percentage = ($present / $total) * 100;
            }
            return view('admin.employeereport.index')
                ->with('attendance', $attendance)
                ->with('present', $present)
                ->with('absents', $absents)
                ->with('leaves', $leaves)
                ->with('percentage', $percentage);
        } else {
            return view('admin.employeereport.index');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function export(request $request)
    {
        $today = date("Y-m-d H:i:s");
        //get month year
        $month_year = $request->input('month_year');
        //get week
        $week = $request->get('week');
        $division = $request->get('division');
        $temp = explode("(", $division);
        $division_short = str_replace(")", "", $temp[1]);
        $date = date('Y-m-d', strtotime($month_year));
        $from = date('Y-m-01', strtotime($date));
        $to = date('Y-m-t', strtotime($date));
        return Excel::download(
            new EmployeeExport($division, $from, $to),
            $today . '_' . $division_short . '_accomplishments.xlsx'
        );
        //return (new AttendanceExport($id))->download('attendance.xlsx');
    }
}
