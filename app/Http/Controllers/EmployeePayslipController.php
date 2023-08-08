<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Employee;
use App\User;
use App\Leaves;
use App\Attendance;
use App\Payslip;
use App\Compensations;
use App\Deductions;
use App\NetPays;
use App\PayslipLog;
use Carbon\Carbon;
use DB;
use PDF;
class EmployeePayslipController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }
    public function index()
    {

        $payslips = DB::table('users')
                    ->join('payslips','users.id','=','payslips.employee_id')
                    ->select('payslips.id','payslips.pay_period','payslips.status','payslips.created_at')
                    ->where('users.id',auth()->user()->id)
                    ->where('payslips.status','published')
                    ->orderby('payslips.id', 'desc')
                    ->paginate(10);

        return view('users.mypayslips')->with('payslips', $payslips);
    }

    public function export($id)
    {
        //select payslip general details
        $payslips = DB::table('payslips')
                    ->select('employee_id','ref_no','pay_period')
                    ->where('id','=',$id)
                    ->first();
        
        //select employee details            
        $employees = DB::table('employees')
                    ->select('lastname','firstname','middlename','extname')
                    ->where('employee_id',$payslips->employee_id)
                    ->first();

        //select compensations details
        $compensations = DB::table('compensations')
                        ->select('basic_pay','representation',
                                'transportation','rep_trans_sum',
                                'gross_pay','pera',
                                'salary_differential','myb_adjustments',
                                'lates_undertime','pera_under_diff',
                                'gross_income')
                        ->where('payslip_id',$id)
                        ->first();

        //select deductions details
         $deductions = DB::table('deductions')
                        ->select('gsis_insurance','gsis_policy_loan',
                                'tax','philhealth_contri',
                                'philhealth_diff','gsis_conso',
                                'gsis_emergency','gsis_computer',
                                'gsis_ins_diff','pagibig_contri',
                                'pagibig_mp','pagibig_cal',
                                'pagibig_mp2','gsis_educ',
                                'gfal','union_dues',
                                'paluwagan_shares','ilsea_loan',
                                'paluwagan_loan','total_deduction')
                        ->where('payslip_id',$id)
                        ->first();

        //select netpays details        
        $netpays = DB::table('net_pays')
                        ->select('netpay7','netpay15',
                                'netpay22','netpay30',
                                'total_netpay')
                        ->where('payslip_id',$id)
                        ->first();

        $pdf = PDF::loadView(
            'users.exportpayslip',
            compact(
                'payslips',
                'employees',
                'compensations',
                'deductions',
                'netpays',
            )
        )
        ->setPaper('legal', 'portrait');

        
        $payslip = new PayslipLog();
        $payslip->user_id       =  $payslips->employee_id;
        $payslip->pay_period    =  $payslips->pay_period;
        $payslip->transaction   =  "accessed";
        $payslip->save();    
        
        return $pdf
            ->setPaper('legal', 'portrait')
            ->download($employees->lastname.'_'.$payslips->pay_period.'_payslip.pdf');

        
    }

}
