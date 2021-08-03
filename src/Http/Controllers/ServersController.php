<?php

namespace Cr4sec\AtomVPN\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Cr4sec\AtomVPN\Models\Server;

class ServersController extends Controller
{
    /**
     * @return Collection
     */
    public function index(): Collection
    {
        return Server::all();
    }

    /**
     * @param  Server  $server
     * @return Server
     */
    public function show(Server $server)
    {
        return $server;
    }
}
