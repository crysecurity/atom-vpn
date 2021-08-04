<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtomVpnServerSessionsTable extends Migration
{
    /** @var string  */
    private $tableName = 'atom_vpn_server_sessions';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, static function (Blueprint $table) {
            $userModel = config('atom_vpn.user_model');

            $table->id();
            $table->foreignId('server_id');
            $table->foreignId('account_id');
            $table->string('user_uuid');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table
                ->foreign('server_id')
                ->references('id')
                ->on('atom_vpn_servers');

            $table
                ->foreign('account_id')
                ->references('id')
                ->on('atom_vpn_accounts')
                ->cascadeOnDelete();

            $table
                ->foreign('user_uuid')
                ->references('uuid')
                ->on((new $userModel)->getTable());
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
