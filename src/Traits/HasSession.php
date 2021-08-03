<?php

namespace Cr4sec\AtomVPN\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Cr4sec\AtomVPN\Models\Session;

trait HasSession
{
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class, 'user_uuid', 'uuid');
    }
}
