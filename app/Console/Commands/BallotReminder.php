<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\User;
use App\Voters;
use App\ErrorLog;
use App\Mail\SendMail;

class BallotReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'election:ballot {form_id} {form_title}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'disseminate ballot number to all voters';

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
    public function handle() {
        //get all emails from election committee
        $committee_emails = User::role('Election Committee')->select('email')->get();
        //get active form
        $form_id = $this->argument('form_id');
        $form_title = $this->argument('form_title');

    }
}
