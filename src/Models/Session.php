<?php

namespace Cr4sec\AtomVPN\Models;

use Carbon\Carbon;
use Database\Factories\SessionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

/**
 * Class Session
 * @package Cr4sec\AtomVPN\Models
 *
 * @property-read int $id
 * @property int $server_id
 * @property int $account_id
 * @property string $user_uuid
 * @property Carbon $started_at
 * @property Carbon $closed_at
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 *
 * @property-read Account $account
 * @property-read Server $server
 */
class Session extends Model
{
    use HasFactory;

    /** @var string  */
    protected $table = 'atom_vpn_server_sessions';

    protected static function newFactory(): SessionFactory
    {
        return SessionFactory::new();
    }

    /**
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * @return BelongsTo
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('atom_vpn.user_model'), 'user_uuid', 'uuid');
    }

    /**
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeReserved(Builder $query): Builder
    {
        return $query
            ->whereNull('started_at')
            ->whereNull('closed_at')
            ->where(
                'created_at',
                '>=',
                Carbon::now()->subSeconds(config('atom_vpn.seconds_to_reserve_sessions'))
            );
    }

    /**
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeOpen(Builder $query): Builder
    {
        return $query
            ->whereNotNull('started_at')
            ->whereNull('closed_at')
            ->where(
                'started_at',
                '>=',
                Carbon::now()->subHours(config('atom_vpn.session_lifetime_hours'))
            );
    }

    /**
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query
            ->whereNotNull('started_at')
            ->whereNull('closed_at')
            ->where(
                'started_at',
                '<',
                Carbon::now()->subHours(config('atom_vpn.session_lifetime_hours'))
            );
    }

    /**
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeClosed(Builder $query): Builder
    {
        return $query
            ->whereNotNull('started_at')
            ->whereNotNull('closed_at');
    }

    /**
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeEmpty(Builder $query): Builder
    {
        return $query
            ->whereNull('started_at')
            ->whereNull('closed_at')
            ->where(
                'created_at',
                '<',
                Carbon::now()->subSeconds(config('atom_vpn.seconds_to_reserve_sessions'))
            );
    }

    /**
     * @return bool
     */
    public function start(): bool
    {
        $this->started_at = Carbon::now();

        return $this->save();
    }

    /**
     * @return bool
     */
    public function close(): bool
    {
        $this->closed_at = Carbon::now();

        return $this->save();
    }
}
