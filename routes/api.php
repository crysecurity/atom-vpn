<?php

use Illuminate\Support\Facades\Route;
use Cr4sec\AtomVPN\Http\Controllers\ServersController;

Route::group(['prefix' => config('atom_vpn.route_prefix')], static function () {
    Route::get('/servers', [ServersController::class, 'index']);
});
