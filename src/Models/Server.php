<?php

namespace Cr4sec\AtomVPN\Models;

use Carbon\Carbon;
use \Database\Factories\ServerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AtomVpnServer
 * @package Cr4sec\AtomVPN\Models
 *
 * @property-read int $id
 * @property bool $free
 * @property string $country
 * @property string $icon
 * @property string $hostname
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
class Server extends Model
{
    use HasFactory;

    /**
     * @return ServerFactory
     */
    protected static function newFactory(): ServerFactory
    {
        return ServerFactory::new();
    }

    protected $table = 'atom_vpn_servers';

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'free' => 'bool'
    ];

    /**
     * @param $value
     * @return string
     */
    public function getIconAttribute($value): string
    {
        return env('APP_URL') . $value;
    }
}
