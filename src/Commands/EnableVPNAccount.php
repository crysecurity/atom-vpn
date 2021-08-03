<?php

namespace Cr4sec\AtomVPN\Commands;

use Illuminate\Console\Command;
use Cr4sec\AtomVPN\AtomVPN;
use Cr4sec\AtomVPN\Exceptions\AtomVpnException;
use Cr4sec\AtomVPN\Models\Account;
use Psr\SimpleCache\InvalidArgumentException;

class EnableVPNAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'atom-vpn:enable-vpn-account {vpn_usernames : vpn usernames separated by commas. Example partner124,partner34534}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enabling VPN accounts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param  AtomVPN  $atomVPN
     * @return int
     * @throws AtomVpnException
     * @throws InvalidArgumentException
     */
    public function handle(AtomVPN $atomVPN): int
    {
        $vpnUsernames = $this->argument('vpn_usernames');

        $vpnUsernames = array_values(array_filter(explode(',', $vpnUsernames)));

        foreach ($vpnUsernames as $vpnUsername) {
            $atomVPN->enableAccount($vpnUsername);
            Account::whereVpnUsername($vpnUsername)->delete();
        }

        return self::SUCCESS;
    }
}
