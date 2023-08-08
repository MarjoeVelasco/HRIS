<?php

namespace App\Exports;


use DB;
use App\User;
use App\Attendance;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PayslipLogExportMailing implements FromView
{

    private $pay_period;
   
    public function __construct($pay_period)
    {
    $this->pay_period = $pay_period;
    }

    
    public function view(): View
    {
        $data="";

        if($this->pay_period!="all")
        {
            $data = DB::table('payslip_logs')
            ->join('employees','employees.employee_id','=','payslip_logs.user_id')
            ->join('users','users.id','=','payslip_logs.user_id')
            ->select('employees.lastname','payslip_logs.pay_period','employees.firstname','payslip_logs.transaction','users.email','payslip_logs.created_at')
            ->where('payslip_logs.pay_period',$this->pay_period)
            ->where(function ($query) {
                $query->where('payslip_logs.transaction', '=', 'sent payslip notice with attachment')
                      ->orWhere('payslip_logs.transaction', '=', 'sent payslip notice');
            })
            ->orderby('payslip_logs.created_at','desc')
            ->get();
        }
        else
        {
            $data = DB::table('payslip_logs')
            ->join('employees','employees.employee_id','=','payslip_logs.user_id')
            ->join('users','users.id','=','payslip_logs.user_id')
            ->select('employees.lastname','employees.firstname','payslip_logs.pay_period','payslip_logs.transaction','users.email','payslip_logs.created_at')
            ->where(function ($query) {
                $query->where('payslip_logs.transaction', '=', 'sent payslip notice with attachment')
                      ->orWhere('payslip_logs.transaction', '=', 'sent payslip notice');
            })
            ->orderby('payslip_logs.created_at','desc')
            ->get();
        }


        return view('admin.payslips.mailinglog', [
            'log_data'=>$data,
        ]);
        
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(15);
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(15);
                
            },
        ];
    }

}

?>
