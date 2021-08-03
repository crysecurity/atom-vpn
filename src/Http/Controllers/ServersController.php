<?php

namespace Cr4sec\AtomVPN\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Cr4sec\AtomVPN\Models\Server;

class ServersController extends Controller
{
    public function index(): Collection
    {
        return Server::all();
    }
}
