<?php

namespace Cr4sec\AtomVPN;

use Illuminate\Support\ServiceProvider;
use Cr4sec\AtomVPN\Commands\ClearingUnusedSessions;
use Cr4sec\AtomVPN\Commands\CreateVPNAccount;
use Cr4sec\AtomVPN\Commands\DeleteVPNAccount;
use Cr4sec\AtomVPN\Commands\DisableVPNAccount;
use Cr4sec\AtomVPN\Commands\EnableVPNAccount;
use Cr4sec\AtomVPN\Models\Account;
use Cr4sec\AtomVPN\Observers\AccountObserver;

class AtomVPNServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/atom_vpn.php' => config_path('atom_vpn.php'),
            ], 'atom_vpn-config');

            $this->commands([
                CreateVPNAccount::class,
                DeleteVPNAccount::class,
                DisableVPNAccount::class,
                EnableVPNAccount::class,
                ClearingUnusedSessions::class
            ]);
        }

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }

    public function register()
    {
        $this->app->singleton(AtomVPN::class, function () {
            return new AtomVPN;
        });
    }
}
