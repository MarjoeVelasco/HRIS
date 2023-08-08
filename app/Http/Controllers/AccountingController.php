<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Employee;
use App\User;
use App\Leaves;
use App\Attendance;
use Carbon\Carbon;
use DB;
class AccountingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isAccounting']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }
    
    public function index(request $request)
    {
        //get number of employees
        $employees = User::where('users.is_disabled','=',0)
        ->count();

        //get users who downloaded their payslip
        //get image, names, date
        $downloads = DB::table('payslip_logs')
            ->join('employees','employees.employee_id','=','payslip_logs.user_id')
            ->join('users','users.id','=','payslip_logs.user_id')
            ->select('employees.firstname','users.image','payslip_logs.created_at')
            ->where('payslip_logs.transaction', '=', 'accessed')
            ->orderby('payslip_logs.created_at','desc')
            ->limit(20)
            ->get(); 

        //get payslip sent
        $payslips = DB::table('payslip_logs')
            ->join('employees','employees.employee_id','=','payslip_logs.user_id')
            ->join('users','users.id','=','payslip_logs.user_id')
            ->select('employees.firstname','users.image','payslip_logs.transaction','payslip_logs.created_at')
            ->where(function ($query) {
                $query->where('payslip_logs.transaction', '=', 'sent payslip notice with attachment')
                      ->orWhere('payslip_logs.transaction', '=', 'sent payslip notice');
            })
            ->orderby('payslip_logs.created_at','desc')
            ->limit(20)
            ->get();

        return view('admin.accounting.index')
        ->with('employees',$employees)
        ->with('downloads',$downloads)
        ->with('payslips',$payslips);
    }

    public function barChartDeductions()
    {
        $currentYear = date('y');

        $labels = ['January-'.$currentYear,'February-'.$currentYear,
                'March-'.$currentYear,'April-'.$currentYear,
                'May-'.$currentYear,'June-'.$currentYear,
                'July-'.$currentYear,'August-'.$currentYear,
                'September-'.$currentYear,'October-'.$currentYear,
                'November-'.$currentYear,'December-'.$currentYear,];

        $gsis=[];
        $philhealth=[];
        $pagibig=[];
        $tax=[];
        $ilsea=[];

        for($i=0;$i<=11;$i++)
        {
            $gsis[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$labels[$i])
            ->value(DB::raw("SUM(gsis_insurance + gsis_policy_loan + gsis_conso + gsis_emergency + gsis_computer + gsis_ins_diff + gsis_educ + gfal)"));
            
            $philhealth[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$labels[$i])
            ->value(DB::raw("SUM(philhealth_contri + philhealth_diff)"));

            $pagibig[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$labels[$i])
            ->value(DB::raw("SUM(pagibig_contri + pagibig_mp + pagibig_cal + pagibig_mp2)"));

            $tax[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$labels[$i])
            ->value(DB::raw("SUM(tax)"));

            $ilsea[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$labels[$i])
            ->value(DB::raw("SUM(paluwagan_loan + ilsea_loan + paluwagan_shares + union_dues)"));
            
        }


        return response()->json([
            'label' => $labels,
            'gsis' => $gsis,
            'pagibig' => $pagibig,
            'philhealth' => $philhealth,
            'tax' => $tax,
            'ilsea' => $ilsea,
        ]);
    }

    public function barChartGsis()
    {
        //get quarter
        $currentYear = date('y');
        $current = date('F-y');
        $label = $this->getQuarter($current,$currentYear);

        $gsis_insurance=[];
        $gsis_policy_loan=[];
        $gsis_conso=[];
        $gsis_emergency=[];
        $gsis_computer=[];
        $gsis_ins_diff=[];
        $gsis_educ=[];
        $gfal=[];

        for($i=0;$i<=2;$i++)
        {
            $gsis_insurance[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$label[$i])
            ->value(DB::raw("SUM(gsis_insurance)"));
            
            $gsis_policy_loan[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$label[$i])
            ->value(DB::raw("SUM(gsis_policy_loan)"));

            $gsis_conso[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$label[$i])
            ->value(DB::raw("SUM(gsis_conso)"));

            $gsis_emergency[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$label[$i])
            ->value(DB::raw("SUM(gsis_emergency)"));

            $gsis_computer[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$label[$i])
            ->value(DB::raw("SUM(gsis_computer)"));

            $gsis_ins_diff[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$label[$i])
            ->value(DB::raw("SUM(gsis_ins_diff)"));

            $gsis_educ[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$label[$i])
            ->value(DB::raw("SUM(gsis_educ)"));

            $gfal[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$label[$i])
            ->value(DB::raw("SUM(gfal)"));
        }

        return response()->json([
            'label' => $label,
            'gsis_insurance' => $gsis_insurance,
            'gsis_policy_loan' => $gsis_policy_loan,
            'gsis_conso' => $gsis_conso,
            'gsis_emergency' => $gsis_emergency,
            'gsis_computer' => $gsis_computer,
            'gsis_ins_diff' => $gsis_ins_diff,
            'gsis_educ' => $gsis_educ,
            'gfal' => $gfal,

        ]);

    }

    public function barChartPagibig()
    {
        //get quarter
        $currentYear = date('y');
        $current = date('F-y');
        $label = $this->getQuarter($current,$currentYear);

        $pagibig_contri=[];
        $pagibig_mp=[];
        $pagibig_cal=[];
        $pagibig_mp2=[];

        for($i=0;$i<=2;$i++)
        {
            $pagibig_contri[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$label[$i])
            ->value(DB::raw("SUM(pagibig_contri)"));
            
            $pagibig_mp[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$label[$i])
            ->value(DB::raw("SUM(pagibig_mp)"));

            $pagibig_cal[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$label[$i])
            ->value(DB::raw("SUM(pagibig_cal)"));

            $pagibig_mp2[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$label[$i])
            ->value(DB::raw("SUM(pagibig_mp2)"));
        }


        return response()->json([
            'label' => $label,
            'pagibig_contri' => $pagibig_contri,
            'pagibig_mp' => $pagibig_mp,
            'pagibig_cal' => $pagibig_cal,
            'pagibig_mp2' => $pagibig_mp2,
            

        ]);

    }

    public function barChartPhilhealth()
    {
        //get quarter
        $currentYear = date('y');
        $current = date('F-y');
        $label = $this->getQuarter($current,$currentYear);

        $philhealth_contri=[];
        $philhealth_diff=[];

        for($i=0;$i<=2;$i++)
        {
            $philhealth_contri[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$label[$i])
            ->value(DB::raw("SUM(philhealth_contri)"));
            
            $philhealth_diff[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$label[$i])
            ->value(DB::raw("SUM(philhealth_diff)"));
        }


        return response()->json([
            'label' => $label,
            'philhealth_contri' => $philhealth_contri,
            'philhealth_diff' => $philhealth_diff,
           

        ]);

    }

    public function barChartIlsea()
    {
        //get quarter
        $currentYear = date('y');
        $current = date('F-y');
        $label = $this->getQuarter($current,$currentYear);

        $union_dues=[];
        $paluwagan_shares=[];
        $ilsea_loan=[];
        $paluwagan_loan=[];

        for($i=0;$i<=2;$i++)
        {
            $union_dues[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$label[$i])
            ->value(DB::raw("SUM(union_dues)"));
            
            $paluwagan_shares[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$label[$i])
            ->value(DB::raw("SUM(paluwagan_shares)"));

            $ilsea_loan[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$label[$i])
            ->value(DB::raw("SUM(ilsea_loan)"));
            
            $paluwagan_loan[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$label[$i])
            ->value(DB::raw("SUM(paluwagan_loan)"));
        }


        return response()->json([
            'label' => $label,
            'union_dues' => $union_dues,
            'paluwagan_shares' => $paluwagan_shares,
            'ilsea_loan' => $ilsea_loan,
            'paluwagan_loan' => $paluwagan_loan,
           

        ]);

    }

    public function lineChartTax()
    {
        $currentYear = date('y');

        $tax=[];
        
        $labels = ['January-'.$currentYear,'February-'.$currentYear,
                'March-'.$currentYear,'April-'.$currentYear,
                'May-'.$currentYear,'June-'.$currentYear,
                'July-'.$currentYear,'August-'.$currentYear,
                'September-'.$currentYear,'October-'.$currentYear,
                'November-'.$currentYear,'December-'.$currentYear,];

        for($i=0;$i<=11;$i++)
        {
            $tax[$i]=DB::table('deductions')
            ->join('payslips','payslips.id','=','deductions.payslip_id')
            ->where('payslips.pay_period','=',$labels[$i])
            ->value(DB::raw("SUM(tax)"));
            
        }


        return response()->json([
            'labels' => $labels,
            'tax' => $tax,
        ]);

    }





    public function getQuarter($current,$currentYear)
    {
        $label=[];

        $q1 = ['January-'.$currentYear,'February-'.$currentYear,
                'March-'.$currentYear];
        $q2 = ['April-'.$currentYear,
                'May-'.$currentYear,'June-'.$currentYear];
        $q3 = ['July-'.$currentYear,
                'August-'.$currentYear,'September-'.$currentYear];
        $q4 = ['October-'.$currentYear,
                'November-'.$currentYear,'December-'.$currentYear];

         //get which quarter
         if (in_array($current,$q1)) {
            $label = $q1;}

        else if (in_array($current,$q2)) {
            $label = $q2;}

        else if (in_array($current,$q3)) {
            $label = $q3;}

        else if (in_array($current,$q4)) {
            $label = $q4;}
        

        return $label;
    }
}
