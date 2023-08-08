<?php

namespace App\Http\Controllers;
use App\Employee;
use App\User;
use App\Holidays;
use App\Payslip;
use App\Compensations;
use App\Deductions;
use App\NetPays;
use DB;
use App\Exports\PayslipLogExportMailing;
use App\Exports\PayslipLogExportDownloading;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

class PayslipLogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isAccounting']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }

    public function index(request $request)
    {
        $payslips = DB::table('payslips')
        ->select('pay_period')
        ->orderby('pay_period','asc')
        ->distinct()
        ->get();
        

        return view('admin.payslips.report')->with('payslips', $payslips);
    }

    public function indexDownload(request $request)
    {
        $payslips = DB::table('payslips')
        ->select('pay_period')
        ->orderby('pay_period','asc')
        ->distinct()
        ->get();
        

        return view('admin.payslips.downloadreport')->with('payslips', $payslips);
    }

    public function payslipMailing(request $request)
    {
        $pay_period = $request->get('pay_period');
        
        return Excel::download(new PayslipLogExportMailing($pay_period), $pay_period . '_Mailing_Report.xlsx');

    }

    public function payslipDownloading(request $request)
    {
        $pay_period = $request->get('pay_period');
        
        return Excel::download(new PayslipLogExportDownloading($pay_period), $pay_period . '_Download_Report.xlsx');

    }
}
