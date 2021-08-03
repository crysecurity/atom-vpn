<?php

namespace Cr4sec\AtomVPN\Observers;

use Cr4sec\AtomVPN\Models\Account;

class AccountObserver
{
    public function deleting(Account $account)
    {
        $account->sessions()->delete();
    }
}
