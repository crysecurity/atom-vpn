<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Cr4sec\AtomVPN\Models\Account;

class CreateAtomVpnAccountsTable extends Migration
{
    /** @var string  */
    private $tableName = 'atom_vpn_accounts';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, static function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('vpn_username');
            $table->string('vpn_password');

            $table
                ->unsignedInteger('multi_login')
                ->default(config('atom_vpn.default_count_of_vpn_user_sessions'));

            $table->unsignedInteger('session_limit')->default(300);

            $table
                ->boolean('enabled')
                ->default(true);

            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
}
