<?php

namespace Cr4sec\AtomVPN\Commands;

use Illuminate\Console\Command;
use Cr4sec\AtomVPN\AtomVPN;
use Cr4sec\AtomVPN\Exceptions\AtomVpnException;
use Cr4sec\AtomVPN\Models\Account;
use Psr\SimpleCache\InvalidArgumentException;

class DeleteVPNAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'atom-vpn:delete-vpn-account {vpn_usernames : vpn usernames separated by commas. Example partner124,partner34534}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deleting VPN accounts';

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
     * @throws InvalidArgumentException
     */
    public function handle(AtomVPN $atomVPN): int
    {
        $vpnUsernames = $this->argument('vpn_usernames');

        $vpnUsernames = array_values(
            array_filter(
                explode(',', $vpnUsernames)
            )
        );

        foreach ($vpnUsernames as $vpnUsername) {
            try {
                $atomVPN->deleteAccount($vpnUsername);

                if (!Account::whereVpnUsername($vpnUsername)->delete()) {
                    $this->error(
                        "The VPN account $vpnUsername was not found in the database or it was not possible to delete it"
                    );
                }
            } catch (AtomVpnException $exception) {
                $this->error($exception->getMessage());
            }
        }

        return self::SUCCESS;
    }
}
