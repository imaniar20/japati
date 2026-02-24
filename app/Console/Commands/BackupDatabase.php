<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database postgres';

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
        $config = config('database.connections')[config('database.default')];
        $destination = storage_path('app/backups/database/');

        File::ensureDirectoryExists($destination);

        $destination .= '/'.$config['database'].'_'.now()->format('Ymd-His').'.sql';

        $process = Process::fromShellCommandline(
            command: "pg_dump -U {$config['username']} -h {$config['host']} {$config['database']} >> $destination",
            env: ['PGPASSWORD' => $config['password']]
        );

        try {
            $this->info('Mulai backup...');
            $process->mustRun();
            $this->info('Backup selesai');
        } catch (ProcessFailedException $exception) {
            logger()->error('Backup exception', compact('exception'));
            $this->error('Backup gagal!');
        }
    }
}
