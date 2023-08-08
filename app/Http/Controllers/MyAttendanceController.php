<?php
namespace App\Http\Controllers;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\ObaoAttendances;
use App\Attendance;
use App\Other_attendances;
use DB;
use App\Exports\MyAttendanceExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;
class MyAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attendance = Attendance::where('employee_id', auth()->user()->id)
                ->orderBy('time_in', 'desc')
                //->get();     
                ->leftjoin('work_settings', 'attendances.attendance_id', '=', 'work_settings.attendance_id')
                ->select('attendances.time_in','attendances.time_out','attendances.accomplishment','attendances.status','work_settings.status as wstatus')
              //  ->get();
                ->paginate(10);
       
        

        $obao = ObaoAttendances::where('employee_id', auth()->user()->id)
                ->orderBy('time_in', 'desc')
                ->get();
        
        $other = Other_attendances::where('employee_id', auth()->user()->id)
                ->orderBy('time_in', 'desc')
                ->get();

        $tempResult=$attendance->merge($obao);
        $finalResult=$tempResult->merge($other);
        $shows=$finalResult->sortByDesc('time_in')->paginate(10);

        return view('users.myattendance')->with('shows', $attendance);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function show(Request $request)
    {
        switch ($request->input('action')) {
            case 'retrieve':
                $shows = Attendance::whereBetween(DB::raw('DATE(time_in)'), [
                    $request->input('from'),
                    $request->input('to'),
                ])
                    ->where('employee_id', auth()->user()->id)
                    ->get();
                return view('users.myattendance')->with('shows', $shows);
                break;
            case 'export':
                $name = DB::table('employees')
                    ->select('lastname', 'firstname', 'middlename')
                    ->where('employee_id', auth()->user()->id)
                    ->first();
                $fullname =
                    $name->lastname .
                    "_" .
                    $name->firstname .
                    "_" .
                    $name->middlename;
                $from = $request->input('from');
                $to = $request->input('to');
                $id = Auth::id();
                return Excel::download(
                    new MyAttendanceExport($id, $from, $to),
                    $fullname . '_Attendance_Report.xlsx'
                );
                break;
        }
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
}
