<?php

namespace App\Http\Controllers;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use App\Attendance;
use App\Employee;
use App\User;
use App\Holidays;
use App\Payslip;
use App\Compensations;
use App\Deductions;
use App\NetPays;
use DB;
use PDF;
use DateTime;
use Carbon\Carbon;
use App\ErrorLog;
use App\Imports\PayslipImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Obao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PayslipController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'isAccounting']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }

    public function index(request $request)
    {
        return view('admin.payslips.import');
    }

    public function import(request $request) 
    {


        $month_year = $request->input('month_year');

        Excel::import(new PayslipImport($month_year),request()->file('file'));
           
        return back()->with('success','File import successful');
        
        
        $temp_employees = Employee::select('lastname','firstname','middlename','extname','employee_id')
        ->get();

        //define empty collection
        $employees = collect([]);

        foreach($temp_employees as $emp)
        {

            $lastname=$emp->lastname;
            $extname=$emp->extname;
            $firstname=$emp->firstname;
            $middlename=$emp->middlename;
            
            if($emp->extname!=NULL || $emp->extname!="")
            {
                $lastname=$emp->lastname.' '.$extname;
            }

            if($emp->middlename!=NULL || $emp->middlename!="")
            {
                $initial = substr($emp->middlename, 0, 1);
                $firstname=$emp->firstname.' '.$initial.'.';
            }

            $fullname = $lastname.', '.$firstname;

            $item= (object) array(
                'name'=>strtoupper($fullname), 
                'employee_id'=>$emp->employee_id,
                );

            $employees->push($item);
        }
        
        
    }

    public function general(request $request)
    {

        $payslips = DB::table('payslips')
                    ->join('employees','employees.employee_id','=','payslips.employee_id')
                    ->select('employees.lastname','employees.firstname','employees.middlename','employees.extname','payslips.id','payslips.pay_period','payslips.status','payslips.created_at')
                    ->orderby('payslips.id', 'desc')
                    ->paginate(10);

        return view('admin.payslips.general')->with('payslips', $payslips);
    }

    public function destroy(request $request)
    {
        DB::beginTransaction();
        try {

            $payslip_log=Payslip::find($request->input('delete_payslip_id'));
            //dd($request->input('delete_payslip_id'));
            $this->logCustomActivity('Deleted','Payslips',$payslip_log->id,$payslip_log->employee_id,$payslip_log->ref_no,$payslip_log->pay_period);

            NetPays::where('payslip_id',$request->input('delete_payslip_id'))
            ->delete();
            Deductions::where('payslip_id',$request->input('delete_payslip_id'))
            ->delete();
            Compensations::where('payslip_id',$request->input('delete_payslip_id'))
            ->delete();
            
            
            //delete payslip
            Payslip::where('id',$request->input('delete_payslip_id'))
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
                'Error has occured! Please contact the administrator'
            );
        }
          
        return back()->with('success', 'Payslip details deleted!');

    }

    public function destroyMany(request $request)
    {

        

        DB::beginTransaction();
        try {

            $payslip_ids = Payslip::select('id')
                       ->where('pay_period',$request->input('delete_payslip_pay_period'))
                       ->get();

            foreach($payslip_ids as $payslip)
            {
                NetPays::where('payslip_id',$payslip->id)
                ->delete();
                Deductions::where('payslip_id',$payslip->id)
                ->delete();
                Compensations::where('payslip_id',$payslip->id)
                ->delete();
            }

            $this->logCustomActivity('Deleted','Payslips','all','all','all',$request->input('delete_payslip_pay_period'));

            Payslip::where('pay_period',$request->input('delete_payslip_pay_period'))
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
                'Error has occured! Please contact the administrator'
            );
        }
          
        return back()->with('success', 'Payslip details deleted!');

    }

    public function mail(request $request)
    {

        $payslips = DB::table('payslips')
        ->select('pay_period','status')
        ->distinct()
        ->get()
        ->paginate(10);
          
        return view('admin.payslips.mail')->with('payslips', $payslips);

    }

    public function changeStatus($period, $status)
    {
        $new_status="";
        DB::beginTransaction();
        try {

            $new_status=$this->updatePayslipStatus($period,$status);
           
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
                'Error has occured! Please contact the administrator'
            );
        }
          
        return back()->with('success', 'Payslips for the period '.$period.' has been saved as '.$new_status);
    }


    public function updatePayslipStatus($period,$status)
    {
        try
        {
            $newstatus="";
            if($status=="draft")
            {
                $newstatus="published";
            }
            else
            {
                $newstatus="draft";
            }
            DB::update(
            'update payslips set status = ? where pay_period = ? and status = ?',
            [$newstatus,$period,$status]);

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
        }


        return $newstatus; 
    }

    public function logCustomActivity($operation,$model_name,$payslip_id,$employee_id,$ref_no,$pay_period)
    {
        activity()
        ->event($operation)
        ->useLog($model_name)
        ->withProperties(['payslip_id'  => $payslip_id,
                       'employee_id'    => $employee_id,
                      'ref_no'          => $ref_no,
                      'pay_period'      => $pay_period,
                      $operation.'_at'      => date('Y-m-d H:i:s')])
        ->log('A payslip has been deleted');
    }
   
            
        
    

    



}
