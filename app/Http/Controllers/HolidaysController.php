<?php
namespace App\Http\Controllers;
use App\Attendance;
use App\Employee;
use App\User;
use App\Holidays;
use DB;
use DateTime;
use Carbon\Carbon;
use App\ErrorLog;
use Illuminate\Http\Request;
class HolidaysController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth', 'isAdmin']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }
    public function index()
    {
        $holidays = DB::table('holidays')
            ->select('id', 'holiday_name', 'remarks','inclusive_dates','created_at','updated_at')
            ->paginate(20);
        return view('admin.holidays.index')->with('holidays', $holidays);
    }

    public function create() {

        $users = DB::table('users')
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select('users.id','employees.firstname','employees.lastname','employees.division',)
            ->where('users.is_disabled',0)
            ->get();

        return view('admin.holidays.create')->with('users', $users);
    }

    public function store(Request $request)
    {
        $users_checked = $request->input('users', []);
        $members = (count($users_checked) > 0) ? implode(',', $users_checked) : 'all';



        DB::beginTransaction();
        try {
            Holidays::create([
                'holiday_name' => $request->input('holiday_name'),
                'inclusive_dates' => $request->input('inclusive_dates'),
                'remarks' => $request->input('remarks'),
                'users' => $members
            ]);

            $inclusive_dates = explode(',', $request->input('inclusive_dates'));

            // Get the list of users for holiday attendance
            $users = ($members === 'all') ? User::select('id')->get() : User::whereIn('id', $users_checked)->get();

            // Add holiday to all attendances
            foreach ($inclusive_dates as $date) {
                foreach ($users as $user) {
                    Attendance::create([
                        'employee_id' => $user->id,
                        'status' => 'holiday',
                        'time_status' => $request->input('holiday_name'),
                        'time_in' => $date,
                        'time_out' => $date,
                        'undertime' => '00:00:00',
                        'overtime' => '00:00:00',
                        'hours_worked' => '00:00:00',
                        'late' => '00:00:00',
                    ]);
                }
            }

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
        return redirect("/manageholidays")->with('success', 'Holiday Added!');
    }

    public function destroy(Request $request)
    {
       
        DB::beginTransaction();
        try {
            //delete holiday record in attendance

            $holiday = Holidays::select('id','remarks','holiday_name','inclusive_dates','users')
            ->where('id',$request->input('delete_holiday_id'))
            ->get();

            $holiday_name="";
            $members = "";
            foreach ($holiday as $holiday_day) {
            $inclusive_dates = explode(',', $holiday_day->inclusive_dates);
            $holiday_name=$holiday_day->holiday_name;
            }

    
            $users = User::select('id')
            ->get();
           

            for($i=0;$i<count($inclusive_dates);$i++)
            {
                foreach ($users as $user) {
                    Attendance::where('employee_id', $user->id)
                    ->where('status','holiday')
                    ->where('time_status',$holiday_name)
                    ->whereDate('time_in',date("Y-m-d", strtotime($inclusive_dates[$i])))
                    ->delete();

                    //log deletion of attendance = holiday
                    activity()
                    ->event('Delete')
                    ->useLog('Attendances')
                    ->withProperties(['employee_id' => $user->id,
                                   'status' => 'holiday',
                                  'time_status' => $holiday_name,
                                  'date' => date("Y-m-d", strtotime($inclusive_dates[$i])),
                                  'deleted_at' => date('Y-m-d H:i:s')])
                    ->log('An attendance has been deleted');
                    
                 }
            }

            //log deletion of holiday
            foreach ($holiday as $holiday_day) {
               
                $holiday_name=$holiday_day->holiday_name;
                activity()
                ->event('Delete')
                ->useLog('Holidays')
                ->withProperties(['id' => $holiday_day->id,
                                  'holiday_name' => $holiday_day->holiday_name,
                                  'inclusive_dates' => $holiday_day->inclusive_dates,
                                  'deleted_at' => date('Y-m-d H:i:s')])
                ->log('A holiday has been deleted');
            }

            Holidays::where('id',$request->input('delete_holiday_id'))
            ->delete();

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
          
        return back()->with('success', 'Holiday deleted!');
    }

    public function viewHoliday($id)
    {
        $holiday = DB::table('holidays')
            ->select('id', 'holiday_name', 'inclusive_dates', 'remarks')
            ->where('id', '=', $id)
            ->get();
        return response()->json(['data' => $holiday]);
    }
    public function edit(Request $request)
    {
        DB::beginTransaction();
        try {

            $holiday_old = Holidays::select('holiday_name','inclusive_dates')
            ->where('id',$request->input('holiday_id'))
            ->first();

        
            $holiday = Holidays::find($request->input('holiday_id'));
            $holiday->holiday_name = $request->input('edit_holiday_name');
            $holiday->inclusive_dates = $request->input('edit_inclusive_dates');
            $holiday->remarks = $request->input('edit_remarks');
            $holiday->save();


            $inclusive_dates_old = explode(',', $holiday_old->inclusive_dates);
            $holiday_name_old=$holiday_old->holiday_name;
         
            

            $users = User::select('id')
            ->get();

            for($i=0;$i<count($inclusive_dates_old);$i++)
            {

                foreach ($users as $user) {

                    Attendance::where('employee_id', $user->id)
                    ->where('status','holiday')
                    ->where('time_status',$holiday_name_old)
                    ->whereDate('time_in',date("Y-m-d", strtotime($inclusive_dates_old[$i])))
                    ->delete();                  
                 }

            }

            
            $holiday = Holidays::find($request->input('holiday_id'));
            $holiday->holiday_name = $request->input('edit_holiday_name');
            $holiday->inclusive_dates = $request->input('edit_inclusive_dates');
            $holiday->remarks = $request->input('edit_remarks');
            $holiday->save();


            $inclusive_dates_new = explode(',', $request->input('edit_inclusive_dates'));
            $holiday_name_new=$request->input('edit_holiday_name');

            for($i=0;$i<count($inclusive_dates_new);$i++)
            {

                foreach ($users as $user) {

                    Attendance::create([
                        'employee_id' => $user->id,
                        'status' => 'holiday',
                        'time_status' => $holiday_name_new,
                        'time_in' =>  date("Y-m-d", strtotime($inclusive_dates_new[$i])),
                        'time_out' => date("Y-m-d", strtotime($inclusive_dates_new[$i])),
                        'undertime' => '00:00:00',
                        'overtime' => '00:00:00',
                        'hours_worked' => '00:00:00',
                        'late' => '00:00:00',
                    ]);
                   
                 }

            }






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
        return back()->with('success', 'Holiday updated');
    }
}
