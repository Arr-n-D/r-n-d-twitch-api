<?php

namespace App\Console\Commands;

use App\Jobs\ProcessNewTwitchFollowers;
use Illuminate\Console\Command;

class TestFollowersJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-followers-job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ProcessNewTwitchFollowers::dispatch();
    }
}
