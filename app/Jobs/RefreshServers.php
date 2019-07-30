<?php

namespace App\Jobs;

use App\Http\Controllers\ServerController;
use App\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RefreshServers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {



    }

    public function handle()
    {
        //echo('Refreshing server IPs');
        foreach ( Server::all()->reverse() as $server) {
            ServerController::updateAddresses($server);
            //dd($server);
            //break;
        }

    }
}