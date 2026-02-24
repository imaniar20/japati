<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DispatchJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispatch:job {job-namespace : Job namespace, use quotes to prevent path sanitized, ex: "App\Jobs\MyJob" } {--async : Asynchronous dispatching}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch job from console';

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
     * @return mixed
     */
    public function handle()
    {
        $namespace = $this->argument('job-namespace');

        $isAsync = $this->option('async');

        if ($isAsync) {
            $namespace::dispatch();
        } else {
            $namespace::dispatchSync();
        }
    }
}
