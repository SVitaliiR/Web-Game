<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Resources;

class ResetResources extends Command
{
    protected $signature = 'app:reset-resources';
    protected $description = 'Reset all resources';

    public function handle()
    {
        Resources::all()->each(function ($resource) {
            $resource->quantity = 0;
            $resource->save();
        });
    }
}
