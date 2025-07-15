<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Resources;

class ResetResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-resources';

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
        Resources::all()->each(function ($resource) {
            $resource->quantity = 0;
            $resource->save();
        });
    }
}
