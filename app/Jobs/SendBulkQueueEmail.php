<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DB;
use PDF;
use Mail;
use App\PayslipLog;

class SendBulkQueueEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    public $timeout = 7200; // 2 hours
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    //mail function
    public function handle()
    {
        //usleep(10000);
        //get payslips
        $data = DB::table('payslips')
        ->select('id')
        ->where('pay_period',$this->details['period'])
        ->where('status',$this->details['status'])
        ->get();

        $this->updatePayslipStatus($this->details['period'],$this->details['status']);

        foreach ($data as $key => $value) {

            //select general data
            $payslips = DB::table('payslips')
                        ->select('employee_id','ref_no','pay_period')
                        ->where('id',$value->id)
                        ->first();

            //select employee details            
            $employees = DB::table('employees')
                        ->select('lastname','firstname','middlename','extname','birthdate')
                        ->where('employee_id',$payslips->employee_id)
                        ->first();

            //get user email
            $users = DB::table('users')
                        ->select('email')
                        ->where('id',$payslips->employee_id)
                        ->first();

            //select compensations details
            $compensations = DB::table('compensations')
                            ->select('basic_pay','representation',
                                    'transportation','rep_trans_sum',
                                    'gross_pay','pera',
                                    'salary_differential','myb_adjustments',
                                    'lates_undertime','pera_under_diff',
                                    'gross_income')
                            ->where('payslip_id',$value->id)
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
                            ->where('payslip_id',$value->id)
                            ->first();

            //select netpays details        
            $netpays = DB::table('net_pays')
                            ->select('netpay7','netpay15',
                                    'netpay22','netpay30',
                                    'total_netpay')
                            ->where('payslip_id',$value->id)
                            ->first();

            //set PDF password
            //get first letter of firstname
            $first_initial = substr($employees->firstname, 0, 1);
            //get first letter of lastname
            $second_initial = substr($employees->lastname, 0, 1);
            //default if no birthdate
            $third_initial ='00000000';
            //check if has birthdate
            if($employees->birthdate!=NULL)
            {
                //if yes, get birthdate and remove -
                $third_initial =str_replace('-','',$employees->birthdate);
            }
            //concatenate 
            $payslip_encryption=$first_initial.''.$second_initial.''.$third_initial;

            //load pdf
            $pdf = PDF::loadView('users.exportpayslip',compact('payslips','employees','compensations','deductions','netpays'))
                ->setPaper('legal', 'portrait');
            //set password    
            $pdf->setEncryption($payslip_encryption);

            //get payslip pay period
            $period=$payslips->pay_period;
            //get current year
            $current_year=date("Y");

            //send mail
            $to_name = $employees->firstname;
                $to_email = $users->email;
                $data = [
                    'email' => $to_email,
                    'name' => $to_name,
                    'year' => $current_year,
                    'period'=>$period,
                ];
                Mail::send('payslipemail', $data, function ($message) use (
                    $to_name,$to_email,$pdf,$period) {
                    $message
                        ->to($to_email, $to_name)
                        ->subject($period.' Payslip');
                    $message->from('noreply@ils.dole.gov.ph', 'TALMS')
                            ->attachData($pdf->output(), $period.'_payslip.pdf');
                });

            //Add new paysliplog record
            $payslip = new PayslipLog();
            $payslip->user_id       =  $payslips->employee_id;
            $payslip->pay_period    =  $period;
            $payslip->transaction   =  "sent payslip notice with attachment";
            $payslip->save();
        }

        //update status from draft to sent
       
        

        


    }

    //function to update payslip status
    public function updatePayslipStatus($period,$status)
    {
        try
        {
            $newstatus="published";
            
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


    }
}
