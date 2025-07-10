<?php

namespace App\Console\Commands;

use App\Services\CService;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    protected $signature = 'app:test';

    protected $description = 'Command description';

    public function handle(): void
    {
        $api = new CService();

        dd($api->getVisits());
    }
}
