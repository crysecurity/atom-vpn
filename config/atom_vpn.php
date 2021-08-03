<?php

return [
    /**
     * Atom VPN preferences
     */
    'secret_key' => env('ATOM_VPN_SECRET_KEY'),
    'reseller_id' => env('ATOM_VPN_RESELLER_ID'),

    /**
     * Session preferences
     */
    'default_count_of_vpn_user_sessions' => env('DEFAULT_VPN_USER_SESSIONS', 5),
    'seconds_to_reserve_sessions' => env('SECONDS_TO_RESERVE_SESSIONS', 60),
    'session_lifetime_hours' => env('SESSION_LIFETIME', 24),

    /**
     * User model
     */
    'user_model' => '\\App\\Models\\User',

    /**
     * Routing preferences
     */
    'route_prefix' => '',
];
