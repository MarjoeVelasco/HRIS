<?php

namespace App\Http\Controllers;
use App\Jobs\SendBulkQueueEmail;
use App\Jobs\SendBulkPayslipReminderEmail;

use Illuminate\Http\Request;
use DB;

class SendBulkMailController extends Controller
{
    public function massMailPayslip($period, $status)
    {

        
        $new_status=$this->updatePayslipStatus($period,$status);
        
        //pass data to details array
        $details = [
            'period' => $period,
            'status' => $new_status,
        ];
 
        //pass array to queue
        // send all mail in the queue.
        $job = (new SendBulkQueueEmail($details))
            ->delay(
                now()
                ->addSeconds(2) //add interval
            ); 
 
        dispatch($job);

        //$this->updatePayslipStatus($period,$status);
 
        return back()->with('success', 'Payslips are being sent, come back later and refresh the page');
    }

    public function massMailReminder($period, $status)
    {

        $new_status=$this->updatePayslipStatus($period,$status);
        
        //pass data to details array
        $details = [
            'period' => $period,
            'status' => $new_status,
        ];
 
        //pass array to queue
        // send all mail in the queue.
        $job = (new SendBulkPayslipReminderEmail($details))
            ->delay(
                now()
                ->addSeconds(2) //add interval
            ); 
 
        dispatch($job);

 
        return back()->with('success', 'Payslips are being sent, come back later and refresh the page');
    }

    public function updatePayslipStatus($period,$status)
    {
        try
        {
            $newstatus="sending";
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
