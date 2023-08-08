<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Leaves;
use DB;
use Carbon\Carbon;
class LeaveRecommendController extends Controller
{
    //
    /* public function __construct()
    {
        $this->middleware(['auth', 'isSupervisor']);
    }*/
    public function index()
    {
        DB::beginTransaction();
        try {
            $leaves = DB::table('users')
                ->join('leaves', 'leaves.employee_id', '=', 'users.id')
                ->select(
                    'leaves.leave_id',
                    'users.name',
                    'leaves.leave_type',
                    'leaves.details',
                    'leaves.created_at',
                    'leaves.start_date',
                    'leaves.end_date',
                    'leaves.status',
                    'leaves.note'
                )
                ->where('leaves.supervisor_id', '=', auth()->user()->id)
                ->where('leaves.supervisor_note', '=', 'Waiting for approval')
                ->where('leaves.archive', '=', 0)
                ->orderBy('leaves.leave_id', 'desc')
                ->paginate(10);
            //->get();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $log = new ErrorLog();
            $log->message = $e->getMessage();
            $log->file = $e->getFile();
            $log->line = $e->getLine();
            $log->url = $_SERVER['REQUEST_URI'];
            $log->save();
            return back()->with(
                'error',
                'Execution Error. Record Not Saved! Please contact the administrator'
            );
        }
        return view('users.recommend')->with('leaves', $leaves);
    }
    public function show($id)
    {
        $leaves = DB::table('leaves')
            ->join(
                'employees',
                'leaves.employee_id',
                '=',
                'employees.employee_id'
            )
            ->select(
                'employees.extname',
                'employees.lastname',
                'employees.firstname',
                'employees.middlename',
                'employees.position',
                'employees.sg',
                'leaves.*'
            )
            ->where('leaves.leave_id', '=', $id)
            ->get();
        $supervisors = DB::table('leaves')
            ->join(
                'employees',
                'leaves.supervisor_id',
                '=',
                'employees.employee_id'
            )
            ->select(
                'employees.extname',
                'employees.lastname',
                'employees.firstname',
                'employees.middlename',
                'employees.position',
                'employees.sg',
                'leaves.*'
            )
            ->where('leaves.leave_id', '=', $id)
            ->get();
        return view('users.viewleave')
            ->with('leaves', $leaves)
            ->with('supervisors', $supervisors);
    }
    public function decline(Request $request)
    {
        //
        $leaves = DB::table('users')
            ->join('leaves', 'leaves.employee_id', '=', 'users.id')
            ->select(
                'leaves.leave_id',
                'users.name',
                'leaves.leave_type',
                'leaves.details',
                'leaves.created_at',
                'leaves.start_date',
                'leaves.end_date',
                'leaves.status',
                'leaves.note'
            )
            ->where('leaves.supervisor_id', '=', auth()->user()->id)
            ->where('leaves.supervisor_note', '=', 'Waiting for approval')
            ->where('leaves.archive', '=', 0)
            ->orderBy('leaves.leave_id', 'desc')
            ->paginate(10);
        DB::beginTransaction();
        try {
            $id = $request->input('id');
            $curDate = $request->input('curDate');
            $reason = $request->input('decline_reason');
            $Leave = Leaves::find($id);
            $Leave->status = "Disapproved by supervisor";
            $Leave->supervisor_note = "Disapproved-" . $reason;
            $Leave->date_recommended = $curDate;
            $Leave->save();
            DB::commit();
        } catch (\Exception $e) {
            //rollback all transactions made to database
            DB::rollback();
            $log = new ErrorLog();
            $log->message = $e->getMessage();
            $log->file = $e->getFile();
            $log->line = $e->getLine();
            $log->url = $_SERVER['REQUEST_URI'];
            $log->save();
            //redirect back to home with error
            return redirect('/users/create')->with(
                'error',
                'Execution Error. Record Not Saved!'
            );
        }
        // $Leaves=Leaves::where('leave_id',$id)->get();
        /*$startDate=$Leave->from;
        $endDate=$Leave->to;
        $empId=$Leave->employee_id;
        $leave_id=$Leave->leave_id;
        
        while($startDate<=$endDate)
        {       
                $fromDate=date('Y-m-d H:i:s',strtotime($startDate));
                DB::delete('delete from attendances where (employee_id = ? and created_at= ?)', [$leave_id, $fromDate]);
                $startDate  =date('Y-m-d',strtotime($startDate.'+1 day'));
                
        }*/
        return back()
            ->with('error', 'Leave Request Declined')
            ->with('leaves', $leaves);
    }
    public function approve(Request $request)
    {
        //
        $leaves = DB::table('users')
            ->join('leaves', 'leaves.employee_id', '=', 'users.id')
            ->select(
                'leaves.leave_id',
                'users.name',
                'leaves.leave_type',
                'leaves.details',
                'leaves.created_at',
                'leaves.start_date',
                'leaves.end_date',
                'leaves.status',
                'leaves.note'
            )
            ->where('leaves.supervisor_id', '=', auth()->user()->id)
            ->where('leaves.supervisor_note', '=', 'Waiting for approval')
            ->where('leaves.archive', '=', 0)
            ->orderBy('leaves.leave_id', 'desc')
            ->paginate(10);
        DB::beginTransaction();
        try {
            $id = $request->input('id');
            $curDate = $request->input('curDate');
            //$reason= $request->input('decline_reason');
            $Leave = Leaves::find($id);
            $Leave->status = "Approved by supervisor";
            $Leave->supervisor_note = "Approved";
            $Leave->date_recommended = $curDate;
            $Leave->save();
            DB::commit();
        } catch (\Exception $e) {
            //rollback all transactions made to database
            DB::rollback();
            $log = new ErrorLog();
            $log->message = $e->getMessage();
            $log->file = $e->getFile();
            $log->line = $e->getLine();
            $log->url = $_SERVER['REQUEST_URI'];
            $log->save();
            //redirect back to home with error
            return redirect('/users/create')->with(
                'error',
                'Execution Error. Record Not Saved!'
            );
        }
        // $Leaves=Leaves::where('leave_id',$id)->get();
        /*$startDate=$Leave->from;
        $endDate=$Leave->to;
        $empId=$Leave->employee_id;
        $leave_id=$Leave->leave_id;
        
        while($startDate<=$endDate)
        {       
                $fromDate=date('Y-m-d H:i:s',strtotime($startDate));
                DB::delete('delete from attendances where (employee_id = ? and created_at= ?)', [$leave_id, $fromDate]);
                $startDate  =date('Y-m-d',strtotime($startDate.'+1 day'));
                
        }*/
        return back()
            ->with('success', 'Leave Request Approved')
            ->with('leaves', $leaves);
    }
    public function view($id)
    {
        $users = DB::table('users')
            ->join('employees', 'users.id', '=', 'employees.employee_id')
            ->where('users.id', '=', $id)
            ->get();
        return response()->json(['data' => $users]);
    }
}
