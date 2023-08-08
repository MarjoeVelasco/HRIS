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

use App\Obao;
use Illuminate\Http\Request;

class OBAOController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'isAdmin']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obao = DB::table('obaos')
        ->join('employees', 'employees.employee_id', '=', 'obaos.employee_id')
            ->select('obaos.id','employees.lastname','employees.firstname','obaos.status','obaos.inclusive_dates','obaos.title','obaos.details','obaos.note')
            ->paginate(20);

            $oed = DB::table('users')
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.middlename',
                'employees.lastname',
                'employees.extname',
                'employees.position'
            )
            ->where(
                'employees.division',
                'Office of the Executive Director (OED)'
            )
            ->get();
        $erd = DB::table('users')
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.middlename',
                'employees.lastname',
                'employees.extname',
                'employees.position'
            )
            ->where('employees.division', 'Employment Research Division (ERD)')
            ->get();
        $lssrd = DB::table('users')
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.middlename',
                'employees.lastname',
                'employees.extname',
                'employees.position'
            )
            ->where(
                'employees.division',
                'Labor and Social Relations Research Division (LSRRD)'
            )
            ->get();
        $wwrd = DB::table('users')
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.middlename',
                'employees.lastname',
                'employees.extname',
                'employees.position'
            )
            ->where(
                'employees.division',
                'Workers Welfare Research Division (WWRD)'
            )
            ->get();
        $apd = DB::table('users')
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.middlename',
                'employees.lastname',
                'employees.extname',
                'employees.position'
            )
            ->where(
                'employees.division',
                'Advocacy and Pulications Division (APD)'
            )
            ->get();
        $fad = DB::table('users')
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.middlename',
                'employees.lastname',
                'employees.extname',
                'employees.position'
            )
            ->where(
                'employees.division',
                'Finance and Administrative Division (FAD)'
            )
            ->get();

       
        return view(
            'admin.obao.index',
            compact('obao', 'oed', 'erd', 'lssrd', 'wwrd', 'apd', 'fad')
        );
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
        
        $status_type=$request->get('status_type');
        $ao_no="";

        switch($status_type)
        {
            case "OB":
                $ao_no=" ";
            break;

            case "AO":
                $ao_no=$request->input('ao_no');
            break;
			
			case "OO":
                $ao_no=$request->input('ao_no');
            break;
        }

        DB::beginTransaction();
        try {

            //create OB/AO
            Obao::create([
                'employee_id' => $request->get('employee'),
                'status' => $status_type,
                'inclusive_dates' => $request->input('date'),
                'title' => $request->input('title'),
                'details' => $request->input('details'),
                'note' => $ao_no,
            ]);



            $inclusive_dates = explode(',', $request->input('date'));

            for ($i = 0; $i <= sizeof($inclusive_dates) - 1; $i++) {
                $insertedDate = date('Y-m-d',strtotime($inclusive_dates[$i]));
                Attendance::create([
					'employee_id' => $request->get('employee'),
					'status' => $status_type,
					'time_status' => $status_type.'.'.$ao_no.'-'.$request->input('title'),
					'time_in' =>  $insertedDate,
					'time_out' => $insertedDate,
					'undertime' => '00:00:00',
					'overtime' => '00:00:00',
					'hours_worked' => '00:00:00',
					'late' => '00:00:00',
                ]);
            }


            DB::commit();
        }
        catch (\Exception $e) {
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

        return back()->with('success', 'OB/AO Added!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OBAO  $oBAO
     * @return \Illuminate\Http\Response
     */
    public function show(OBAO $oBAO)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OBAO  $oBAO
     * @return \Illuminate\Http\Response
     */
    public function edit(OBAO $oBAO)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OBAO  $oBAO
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        DB::beginTransaction();
        try {

            $obao = Obao::select('employee_id','status','inclusive_dates','title','note')
            ->where('id',$request->input('obao_id'))
            ->first();

            
            $inclusive_dates = explode(',', $obao->inclusive_dates);

            for ($i = 0; $i <= sizeof($inclusive_dates) - 1; $i++) {
                $insertedDate = date(
                    'Y-m-d',
                    strtotime($inclusive_dates[$i])
                );

                    DB::table('attendances')
                    ->where('employee_id', $obao->employee_id)
                    ->where('status', $obao->status)
                    ->where('time_status', $obao->status.'.'.$obao->note.'-'.$obao->title)
                    ->whereDate('time_in', $insertedDate)
                    ->delete();
            }

        $status_type=$request->get('status_type');
        $ao_no="";

        switch($status_type)
        {
            case "OB":
                $ao_no=" ";
            break;

            case "AO":
                $ao_no=$request->input('ao_no');
            break;

            case "OO":
                $ao_no=$request->input('ao_no');
            break;
        }


        $obao_update=Obao::find($request->input('obao_id'));
        $obao_update->employee_id = $request->get('employee');
        $obao_update->status = $request->get('status_type');
        $obao_update->inclusive_dates = $request->get('date');
        $obao_update->title = $request->get('title');
        $obao_update->details = $request->get('details');
        $obao_update->note = $ao_no;
        $obao_update->save();

        /*
        ->update([
            "employee_id" => $request->get('employee'),
            "status" => $request->get('status_type'),
            "inclusive_dates" => $request->input('date'),
          
            "title" => $request->input('title'),
            "details" => $request->input('details'),
            "note" => $ao_no,
        ]);
        */


        $inclusive_dates = explode(',', $request->input('date'));

            for ($i = 0; $i <= sizeof($inclusive_dates) - 1; $i++) {

                $insertedDate = date('Y-m-d',strtotime($inclusive_dates[$i]));
                
                //record to general attendance
                Attendance::create([
                'employee_id' => $request->get('employee'),
                'status' => $request->get('status_type'),
                'time_status' => $status_type.'.'.$ao_no.'-'.$request->input('title'),
                'time_in' =>  $insertedDate,
                'time_out' => $insertedDate,
                'undertime' => '00:00:00',
                'overtime' => '00:00:00',
                'hours_worked' => '00:00:00',
                'late' => '00:00:00',
                ]);
                
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
        return back()->with('success', 'OB/AO updated');


        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OBAO  $oBAO
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        DB::beginTransaction();
        try {
          

            $obao = Obao::select('employee_id','status','inclusive_dates','title','note')
            ->where('id',$request->input('delete_OBAO_id'))
            ->first();

            $inclusive_dates = explode(',', $obao->inclusive_dates);

            for ($i = 0; $i <= sizeof($inclusive_dates) - 1; $i++) {

                $insertedDate = date('Y-m-d',strtotime($inclusive_dates[$i]));

                Attendance::where('employee_id', $obao->employee_id)
                ->where('status',$obao->status)
                ->where('time_status',$obao->status.'.'.$obao->note.'-'.$obao->title)
                ->whereDate('time_in',date("Y-m-d", strtotime($insertedDate)))
                ->delete();
            }

            //log deletion of OBAO
            activity()
            ->event('Delete')
            ->useLog('OBAO')
            ->withProperties(['id' => $request->input('delete_OBAO_id'),
                                  'holiday_name' => $obao->title,
                                  'inclusive_dates' => $obao->inclusive_dates,
                                  'deleted_at' => date('Y-m-d H:i:s')])
            ->log('An OBAO has been deleted');

            Obao::where('id',$request->input('delete_OBAO_id'))
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
                'Execution Error! Please contact the administrator'
            );
        }
          
        return back()->with('success', 'OB/AO deleted!');

        //
    }

    public function viewOBAO($id)
    {
        $obao = Obao::join('employees', 'employees.employee_id', '=', 'obaos.employee_id')
        ->where('obaos.id', '=', $id)
        ->select('obaos.id','obaos.employee_id','employees.lastname','employees.middlename','employees.firstname','obaos.status','obaos.inclusive_dates','obaos.title','obaos.details','obaos.note')
        ->get();

        return response()->json(['data' => $obao]);
    }
}
