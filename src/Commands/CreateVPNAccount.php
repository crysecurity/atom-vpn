<?php

namespace Cr4sec\AtomVPN\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Cr4sec\AtomVPN\AtomVPN;
use Cr4sec\AtomVPN\Exceptions\AtomVpnException;
use Cr4sec\AtomVPN\Models\Account;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class CreateVPNAccount
 * @package Cr4sec\AtomVPN\Commands
 */
class CreateVPNAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'atom-vpn:create-vpn-account';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating a VPN account';

    /**
     * Execute the console command.
     *
     * @param  AtomVPN  $atomVPN
     * @return int
     * @throws InvalidArgumentException
     */
    public function handle(AtomVPN $atomVPN): int
    {
        try {
            Account::create($atomVPN, Str::uuid()->toString());
        } catch (AtomVpnException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
