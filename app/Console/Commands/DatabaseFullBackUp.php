<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class DatabaseFullBackUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:full';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform weekly full backup of database';

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
        $filename = "full-backup-" . Carbon::now()->format('Y-m-d') . ".sql";
  
        $command = "mysqldump -u ". env('DB_USERNAME') ." -p". env('DB_PASSWORD') ." " . env('DB_DATABASE') . " --single-transaction > " . storage_path() . "/app/backup/" . $filename;
  
        $returnVar = NULL;
        $output  = NULL;
  
        exec($command, $output, $returnVar);
    }
}
