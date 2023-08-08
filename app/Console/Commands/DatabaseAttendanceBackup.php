<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;


class DatabaseAttendanceBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform daily backup of attendance table for security';

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
        $filename = "attendance-backup-" . Carbon::now()->format('Y-m-d') . ".sql";
  
        $command = "mysqldump -u ". env('DB_USERNAME') ." -p". env('DB_PASSWORD') ." " . env('DB_DATABASE') . " attendances --single-transaction > " . storage_path() . "/app/backup/" . $filename;
  
        $returnVar = NULL;
        $output  = NULL;
  
        exec($command, $output, $returnVar);
    }
}
