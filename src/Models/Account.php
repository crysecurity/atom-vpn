<?php

namespace Cr4sec\AtomVPN\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Cr4sec\AtomVPN\AtomVPN;
use Cr4sec\AtomVPN\Exceptions\AtomVpnException;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class AtomVpnAccount
 * @package Cr4sec\AtomVPN\Models
 *
 * @property-read int $id
 * @property string $uuid
 * @property string $vpn_username
 * @property string $vpn_password
 * @property int $multi_login
 * @property int $session_limit
 * @property bool $enabled
 * @property Carbon $expires_at
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
class Account extends Model
{
    /** @var string  */
    protected $table = 'atom_vpn_accounts';

    /**
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(config('atom_vpn.user_model'));
    }

    /**
     * @return HasMany
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class, 'account_id', 'id');
    }

    /**
     * @param  AtomVPN  $atomVPN
     * @param  string  $uuid
     * @return Account
     * @throws AtomVpnException
     * @throws InvalidArgumentException
     */
    public static function create(AtomVPN $atomVPN, string $uuid): Account
    {
        $response = $atomVPN
            ->createAccount(compact('uuid'))
            ->collect();

        $username = $response->get('body')['vpnUsername'] ?? null;
        $password = $response->get('body')['vpnPassword'] ?? null;

        if ($username && $password) {
            $account = new self;
            $account->uuid = $uuid;
            $account->vpn_username = $username;
            $account->vpn_password = $password;
            $account->expires_at = Carbon::now()->addDays(30);
            $account->save();

            return $account;
        }

        throw new AtomVpnException('Empty fields username and password', 500);
    }

    /**
     * @return Account|null
     */
    public static function findFirstFreeAccount(): ?Account
    {
        return self::query()
            ->withCount(['sessions' => function ($query) {
                $query->open();
            }])
            ->having(
                'sessions_count',
                '<',
                config('atom_vpn.default_count_of_vpn_user_sessions')
            )
            ->first();
    }

    /**
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeExpiredAccounts(Builder $query): Builder
    {
        return $query->where('expires_at', '<', Carbon::now());
    }

    /**
     * @param  Server  $server
     * @return Session
     */
    public function reserveSession(Server $server): Session
    {
        $session = new Session;
        $session->server()->associate($server);
        $session->user()->associate(auth()->user());

        $this->sessions()->save($session);

        return $session;
    }
}
