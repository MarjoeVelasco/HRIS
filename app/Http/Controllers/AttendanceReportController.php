<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Attendance;
use App\User;
use DB;
use App\Exports\AttendanceExport;
use App\Exports\AttendanceExportMonthly;
use App\Exports\AttendanceExportOther;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;
class AttendanceReportController extends Controller {
    public function __construct() {

        $this->middleware(['auth', 'role:Admin|Division Chief']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request) {
                
        $division=DB::table('employees')
        ->select('division')
        ->where('employee_id',auth()->user()->id)
        ->first();

        
          return view('admin.attendancereport.index')
                ->with('division', $division->division);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*
    public function report(request $request) {
        $present = 0;
        $absents = 0;
        $leaves = 0;
        $percentage = 0;
        if (null != $request->input('from') && null != $request->input('to') && null != $request->input('id')) {
            $from = $request->input('from');
            $to = $request->input('to');
            $id = $request->get('id');
            $users = User::all();
            if ($id == "All") {
                $attendance = DB::table('users')->join('attendances', 'attendances.employee_id', '=', 'users.id')->select('attendances.attendance_id', 'attendances.employee_id', 'users.id', 'employees.lastname', 'employees.firstname', 'employees.middlename', 'employees.extname', 'employees.position', 'employees.item_number', 'employees.sg', 'employees.stepinc', 'employees.division', 'employees.unit', 'users.email', 'users.name', 'attendances.time_in', 'attendances.time_out', 'attendances.accomplishment')->whereDate('attendances.created_at', '>=', date('Y-m-d', strtotime($from)))->whereDate('attendances.created_at', '<=', date('Y-m-d', strtotime($to)))->get();
                $present = DB::table('attendances')->whereDate('created_at', '>=', date('Y-m-d', strtotime($from)))->whereDate('created_at', '<=', date('Y-m-d', strtotime($to)))->where('status', '=', 'present')->count();
                $absents = DB::table('attendances')->whereDate('created_at', '>=', date('Y-m-d', strtotime($from)))->whereDate('created_at', '<=', date('Y-m-d', strtotime($to)))->where('status', '=', 'absent')->count();
                $leaves = DB::table('attendances')->whereDate('created_at', '>=', date('Y-m-d', strtotime($from)))->whereDate('created_at', '<=', date('Y-m-d', strtotime($to)))->where('status', '=', 'leave')->count();
                $total = DB::table('attendances')->whereDate('created_at', '>=', date('Y-m-d', strtotime($from)))->whereDate('created_at', '<=', date('Y-m-d', strtotime($to)))->count();
                if ($total > 0) {
                    $percentage = ($present / $total) * 100;
                }
                return view('admin.attendancereport.index')->with('attendance', $attendance)->with('present', $present)->with('absents', $absents)->with('leaves', $leaves)->with('percentage', $percentage)->with('users', $users);
            } else {
                $attendance = DB::table('users')->join('attendances', 'attendances.employee_id', '=', 'users.id')->select('attendances.attendance_id', 'attendances.employee_id', 'users.id', 'employees.lastname', 'employees.firstname', 'employees.middlename', 'employees.extname', 'employees.position', 'employees.item_number', 'employees.sg', 'employees.stepinc', 'employees.division', 'employees.unit', 'users.email', 'users.name', 'attendances.time_in', 'attendances.time_out', 'attendances.accomplishment')->whereDate('attendances.created_at', '>=', date('Y-m-d', strtotime($from)))->whereDate('attendances.created_at', '<=', date('Y-m-d', strtotime($to)))->where('attendances.employee_id', '=', $id)->get();
                $present = DB::table('attendances')->whereDate('created_at', '>=', date('Y-m-d', strtotime($from)))->whereDate('created_at', '<=', date('Y-m-d', strtotime($to)))->where('status', '=', 'present')->where('employee_id', '=', $id)->count();
                $absents = DB::table('attendances')->whereDate('created_at', '>=', date('Y-m-d', strtotime($from)))->whereDate('created_at', '<=', date('Y-m-d', strtotime($to)))->where('status', '=', 'absent')->where('employee_id', '=', $id)->count();
                $leaves = DB::table('attendances')->whereDate('created_at', '>=', date('Y-m-d', strtotime($from)))->whereDate('created_at', '<=', date('Y-m-d', strtotime($to)))->where('status', '=', 'leave')->where('employee_id', '=', $id)->count();
                $total = DB::table('attendances')->whereDate('created_at', '>=', date('Y-m-d', strtotime($from)))->whereDate('created_at', '<=', date('Y-m-d', strtotime($to)))->where('employee_id', '=', $id)->count();
                if ($total > 0) {
                    $percentage = ($present / $total) * 100;
                }
                return view('admin.attendancereport.index')->with('attendance', $attendance)->with('present', $present)->with('absents', $absents)->with('leaves', $leaves)->with('percentage', $percentage)->with('users', $users);
            }
        } else {
            return view('admin.attendancereport.index');
        }
    }*/
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
        
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
        
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
        
    }
    /** 
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
        
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
        
    }
    public function export(request $request) {

        //get division
        $division = $request->get('division');
        $temp = explode("(", $division);
        $division_short = str_replace(")", "", $temp[1]);

        $today = date("Y-m-d H:i:s");
        //get month and year
        $month_year = $request->input('month_year');
        //get week
        $week = $request->get('week');
        //get User ID
        //$id = $request->input('id');
        if ($week == "all") {
            $date = date('Y-m-d', strtotime($month_year));
            $from = date('Y-m-01', strtotime($date));
            $to = date('Y-m-t', strtotime($date));
            $range = $request->input('range_weeks');
            return Excel::download(new AttendanceExportMonthly($division, $from, $to,$week,$month_year,$range), $month_year . '_' . $division_short . ' (monthly) attendance.xlsx');
        } else {
            $range = explode("-", $week);
            $from = date('Y-m-d', strtotime($month_year . "-" . $range[0]));
            $to = date('Y-m-d', strtotime($month_year . "-" . $range[1]));
            return Excel::download(new AttendanceExport($division, $from, $to,$week), $from.' to '.$to. '_' . $division_short . ' (weekly) attendance.xlsx');
        }
        //return (new AttendanceExport($id))->download('attendance.xlsx')
        //return Excel::download(new TestingExport($id,$from,$to), 'invoices.xlsx');
        
    }

    public function exportOther(request $request) {


        
        $division = $request->get('division');
        $temp = explode("(", $division);
        $division_short = str_replace(")", "", $temp[1]);

        $today = date("Y-m-d H:i:s");
        //get month and year
        $month_year = $request->input('month_year_other');
        //get week
       
        //get User ID
        //$id = $request->input('id_other');
     
            $date = date('Y-m-d', strtotime($month_year));
            $from = date('Y-m-01', strtotime($date));
            $to = date('Y-m-t', strtotime($date));
         
            return Excel::download(new AttendanceExportOther($division, $from, $to,$request->input('table_name')), $division_short.' '.$request->get('attendance_type').' attendance.xlsx');
      
        //return (new AttendanceExport($id))->download('attendance.xlsx')
        //return Excel::download(new TestingExport($id,$from,$to), 'invoices.xlsx');
        
    
    }
}
