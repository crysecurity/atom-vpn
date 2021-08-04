<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Atom VPN preferences
    |--------------------------------------------------------------------------
    */

    'secret_key' => env('ATOM_VPN_SECRET_KEY'),
    'reseller_id' => env('ATOM_VPN_RESELLER_ID'),


    /*
    |--------------------------------------------------------------------------
    | Session preferences
    |--------------------------------------------------------------------------
    */

    /** Default number of available sessions per Atom VPN account */
    'default_count_of_vpn_user_sessions' => env('DEFAULT_VPN_USER_SESSIONS', 5),

    /** The time for how long the user's session is reserved. Measured in seconds */
    'seconds_to_reserve_sessions' => env('SECONDS_TO_RESERVE_SESSIONS', 60),

    /** The lifetime of the user's session. Measured in hours */
    'session_lifetime_hours' => env('SESSION_LIFETIME', 24),

    /** Boolean value. Closes open sessions before opening a new one */
    'close_open_sessions_before_opening_a_new_one' => true,

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    */

    /** User model */
    'user_model' => '\\App\\Models\\User',


    /*
    |--------------------------------------------------------------------------
    | Routing preferences
    |--------------------------------------------------------------------------
    */

    'route_prefix' => '',
];
