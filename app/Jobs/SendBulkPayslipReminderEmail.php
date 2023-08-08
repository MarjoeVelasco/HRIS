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

class SendBulkPayslipReminderEmail implements ShouldQueue
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
    public function handle()
    {

        //get payslips
        $data = DB::table('payslips')
        ->select('id')
        ->where('pay_period',$this->details['period'])
        ->where('status',$this->details['status'])
        ->get();

        $new_status=$this->updatePayslipStatus($this->details['period'],$this->details['status']);        


        foreach ($data as $key => $value) {
        
            //get payslip details
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
        
        //get payslip period
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
            Mail::send('payslipemailreminder', $data, function ($message) use (
                    $to_name,$to_email,$period) {
                    $message
                        ->to($to_email, $to_name)
                        ->subject($period.' Payslip');
                    $message->from('noreply@ils.dole.gov.ph', 'TALMS');
                });

            //Add new paysliplog record
            $payslip = new PayslipLog();
            $payslip->user_id       =  $payslips->employee_id;
            $payslip->pay_period    =  $period;
            $payslip->transaction   =  "sent payslip notice";
            $payslip->save();        
        
        }

        //update status of payslip from draft to send
       
    }

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


        return $newstatus; 
    }
}
