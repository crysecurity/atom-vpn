<?php


namespace Cr4sec\AtomVPN\Observers;

use Cr4sec\AtomVPN\Models\Session;

class SessionObserver
{
    public function updating(Session $session)
    {
        if (
            config('atom_vpn.close_open_sessions_before_opening_a_new_one') &&
            $session->isDirty('started_at') &&
            $session->getOriginal('started_at') === null
        ) {
            $session
                ->user
                ->sessions()
                ->open()
                ->get()
                ->each(function (Session $session) {
                    $session->close();
                });
        }
    }
}
