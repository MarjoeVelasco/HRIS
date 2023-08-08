<?php
namespace App\Http\Controllers;
use App\Leaves;
use App\Employee;
use App\ErrorLog;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use DB;
class LeaveController extends Controller
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
        $position_chief = "Division Chief";
        $director = User::whereHas("roles", function ($q) {
            $q->where("name", "Director");
        })
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.middlename',
                'employees.lastname',
                'employees.extname',
                'employees.position'
            )
            ->get();
        $chiefs = User::whereHas("roles", function ($q) {
            $q->where("name", "Chief LEO/Supervisor");
        })
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.middlename',
                'employees.lastname',
                'employees.extname',
                'employees.position'
            )
            ->get();
        $hr = User::whereHas("roles", function ($q) {
            $q->where("name", "HR/FAD");
        })
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.middlename',
                'employees.lastname',
                'employees.extname',
                'employees.position'
            )
            ->get();



            //print($chiefs);
            
            
        return view('users.requestleave')
            ->with('users', $chiefs)
            ->with('hr', $hr)
            ->with('director', $director);
            
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $leave_type = $request->input('leave_title');
        $inclusive_dates = $request->input('inclusive_dates_cto');
        $date_collection = explode(",", $inclusive_dates);
        $start_date = current($date_collection);
        $end_date = end($date_collection);
        $no_days = $request->get('hours_days_cto');
        $details="whole day";
        
        if (strpos($no_days, '.') !== false) {
            $details=$request->get('date_half_day').",".$request->get('time_day_half_cto');
        }
        
        DB::beginTransaction();
        try {
            leaves::create([
                'employee_id' => $request->input('employee_id'),
                'supervisor_id' => $request->get('supervisor_id'),
                'approver_id' => $request->get('approver_id'),
                'signatory_id' => $request->get('signatory_id'),
                'leave_type' => $request->get('leave_title'),
                'details' => $details,
                'inclusive_dates' => $inclusive_dates,
                'no_days' => $no_days,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'commutation' => 'Not Applicable',
                'status' => 'pending',
                'archive' => 0,
            ]);
            

            //Notify HR that a leave has been filed
            //Get all HR/FAD users
            $hr = User::whereHas("roles", function ($q) {
                $q->where("name", "HR/FAD");
            })
                ->join('employees', 'employees.employee_id', '=', 'users.id')
                ->select(
                    'users.email',
                    'employees.firstname',
                )
                ->whereNotNull('users.email_verified_at')
                ->where('users.is_disabled','=',0)
                ->get();

                $filer = DB::table('employees')
                ->select(
                    'firstname',
                    'lastname',
                    'middlename',
                )
                ->where('employee_id', '=', $request->input('employee_id'))
                ->get()
                ->first();
                
                $employee_name_mail=$filer->firstname." ".$filer->middlename." ".$filer->lastname;
                $currentYear = date('Y');
                foreach ($hr as $user) {
                   
                    //script for sending emails
                    $this->mailAdmin($user->email,$user->firstname,$currentYear,$employee_name_mail,$no_days,$start_date,$end_date,$request->get('leave_title'));
                    //end
                    
                }
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
            return back()->with(
                'error',
                'Execution Error. Record Not Saved! Please contact the administrator '
            );
        }
        return back()->with('success', 'Leave application submitted ');

        
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $leave_type = $request->input('leave_title');
        $inclusive_dates = "";
        $start_date = "";
        $end_date = "";
        $no_days = "";
        $commutation = "";
        if ($leave_type == "maternity leave") {
            $start_date = $request->input('inclusive_dates');
            $end_date = $request->input('end_date');
            $no_days = 105;
            $inclusive_dates = $start_date . " - " . $end_date;
        } else {
            $inclusive_dates = $request->input('inclusive_dates');
            $date_collection = explode(",", $inclusive_dates);
            $start_date = current($date_collection);
            $end_date = end($date_collection);
            $no_days = count($date_collection);
            $commutation = $request->get('commutation');
        }
        if ($leave_type == "maternity leave" || $leave_type == "sick leave") {
            $commutation = "Not Applicable";
        }

        $details = $request->input('details');
        $other_details = $request->input('other_details');
        if (empty($details)) {
            $details = "Not Applicable";
        } elseif (!empty($details)) {
            $details = $details . "/" . $other_details;
        }
        if ($leave_type == "vacation leave") {
            $details .=
                "-" . $request->get('case') . "/" . $request->get('case_other');
        }
        DB::beginTransaction();
        try {
            leaves::create([
                'employee_id' => $request->input('employee_id'),
                'supervisor_id' => $request->get('supervisor_id'),
                'approver_id' => $request->get('approver_id'),
                'signatory_id' => $request->get('signatory_id'),
                'leave_type' => $request->get('leave_title'),
                'details' => $details,
                'inclusive_dates' => $inclusive_dates,
                'no_days' => $no_days,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'commutation' => $commutation,
                'status' => $request->input('status'),
                'archive' => 0,
            ]);
            

            //Notify HR that a leave has been filed
            //Get all HR/FAD users
            $hr = User::whereHas("roles", function ($q) {
                $q->where("name", "HR/FAD");
            })
                ->join('employees', 'employees.employee_id', '=', 'users.id')
                ->select(
                    'users.email',
                    'employees.firstname',
                )
                ->whereNotNull('users.email_verified_at')
                ->where('users.is_disabled','=',0)
                ->get();

                $filer = DB::table('employees')
                ->select(
                    'firstname',
                    'lastname',
                    'middlename',
                )
                ->where('employee_id', '=', $request->input('employee_id'))
                ->get()
                ->first();
                
                $employee_name_mail=$filer->firstname." ".$filer->middlename." ".$filer->lastname;
                $currentYear = date('Y');
                foreach ($hr as $user) {
                    
                    //script for sending emails
                    $this->mailAdmin($user->email,$user->firstname,$currentYear,$employee_name_mail,$no_days,$start_date,$end_date,$request->get('leave_title'));
                    //end
                    
                }

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
            return back()->with(
                'error',
                'Execution Error. Record Not Saved! Please contact the administrator '
            );
        }
        return back()->with('success', 'Leave application submitted ');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Leaves  $leaves
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Leaves  $leaves
     * @return \Illuminate\Http\Response
     */
    public function edit(Leaves $leaves)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Leaves  $leaves
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Leaves $leaves)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Leaves  $leaves
     * @return \Illuminate\Http\Response
     */
    public function destroy(Leaves $leaves)
    {
        //
    }

    public function mailAdmin($email,$name,$currentYear,$employee_name,$no_days,$start_date,$end_date,$leave_type)
    {
            $to_name = $name;
            $to_email = $email;
            $data = [
                'email' => $to_email,
                'name' => $to_name,
                'employee_name' => $employee_name,
                'no_days' => $no_days,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'year' => $currentYear,
                'leave_type' => $leave_type,
            ];
            Mail::send('leavenotify', $data, function ($message) use (
                $to_name,$to_email) {
                $message
                    ->to($to_email, $to_name)
                    ->subject('Leave Application Notification');
                $message->from('noreply@ils.dole.gov.ph', 'TALMS');
            });
        
    }

}
