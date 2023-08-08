<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\ErrorLog;

use Illuminate\Support\Facades\Mail;



class TimeReminder extends Command 
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:timereminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails to users for time in and time out';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currentDate = date('Y-m-d');
        $working_day_flag = $this->isWorkingDay($currentDate);
        if($working_day_flag)
        {
            try
            {
                $currentYear = date('Y');
        
                //get all verified user names and email
                $users=DB::table('users')
                ->join('employees','employees.employee_id','=','users.id')
                ->select('employees.firstname','users.email')
                ->whereNotNull('users.email_verified_at')
                ->where('users.is_disabled','=',0)
                ->get();
        
                //loop through collection and send email
                foreach ($users as $user) {
        
                    //script for sending emails
                    $to_name = $user->firstname;
                    $to_email = $user->email;
                    $data = array('email' => $to_email, 'name' => $to_name, 'year' => $currentYear);


                    Mail::send('reminderemail', $data, function ($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)->subject('Reminder to Time in and out');
                    $message->from('noreply@ils.dole.gov.ph', 'TALMS');
                    });

                    //end

                    $this->info('Email reminder to '.$to_email.' has been sent');    
                }
            }

            catch(\Exception $e) {
                
                $this->info('An error has occured '.$e);
            }

        }

        $this->info('Task schedule executed');
        

       

    }

    public function isWorkingDay($date) {
        //convert date to day name i.e Saturday
        $check_weekend = date('l', strtotime($date));
        //convert to lowercase
        $converted = strtolower($check_weekend);
        if (($converted == "saturday") || ($converted == "sunday")) {
            //day is a weekend
            return false;
        } else {
            $holidays = DB::table('holidays')->select('id')->where('inclusive_dates', 'like', '%' . $date . '%')->get();
            //check if holiday
            if ($holidays->isNotEmpty()) {
                //day is holiday
                return false;
            } else {
                return true;
            }
        }
    }

}
