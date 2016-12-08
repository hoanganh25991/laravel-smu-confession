<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUserRolesAllowRoleNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `user_roles`
	CHANGE COLUMN `role` `role` VARCHAR(255) NULL COLLATE \'utf8_unicode_ci\' AFTER `provider_id`;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `user_roles`
	CHANGE COLUMN `role` `role` VARCHAR(255) NOT NULL COLLATE \'utf8_unicode_ci\' AFTER `provider_id`;');
    }
}
