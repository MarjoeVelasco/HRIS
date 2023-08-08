<?php
namespace App\Http\Controllers;
use App\ErrorLog;
use Illuminate\Http\Request;
use DB;
use App\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ErrorlogExport;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class ErrorLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function __construct()
    {
         $this->middleware(['auth', 'role:Admin|Accounting/FAD|Division Chief']);
    }
    
    public function index()
    {
        $error_logs = DB::table('error_logs')
            ->select('id', 'message', 'file', 'line', 'url', 'created_at')
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.errors.errorlog')->with('error_logs', $error_logs);
        //->with('leaves', $leaves);
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
     * @param  \App\Models\ErrorLog  $errorLog
     * @return \Illuminate\Http\Response
     */
    public function show(ErrorLog $errorLog)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ErrorLog  $errorLog
     * @return \Illuminate\Http\Response
     */
    public function edit(ErrorLog $errorLog)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ErrorLog  $errorLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ErrorLog $errorLog)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ErrorLog  $errorLog
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        ErrorLog::truncate();
        return back()->with('success', 'Error log cleared');
    }
    public function export()
    {
        $today = date("Y-m-d H:i:s");
        return Excel::download(new ErrorLogExport(), $today . ' error_log.csv');
        //return (new AttendanceExport($id))->download('attendance.xlsx');
    }

    public function testingmail()
    {
        
      $res = User::whereHas("roles", function ($q) {
            $q->where("name", "Election Committee");
            })->select('email')
            ->get();
        
        $email_party = array();

        foreach($res as $a) {

            array_push($email_party, $a->email);
        }

        $to_name="ILS Family";
        $to_email='ilsdole@ils.dole.gov.ph';
       
        $data = array('email' => 'mvelasco@ils.dole.gov.ph', 'name' => 'Marjoe Velasco');

            Mail::send('voteclosed', $data, function ($message) use ($email_party,$to_name, $to_email) {
            $message->to($to_email, $to_name)->cc($email_party)->subject('ELECTIONS ARE NOW CLOSED!');
            $message->from('noreply@ils.dole.gov.ph', 'TALMS');        
            });
        
        

        return back();
    }
}
