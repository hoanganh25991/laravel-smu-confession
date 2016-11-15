<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePostsFacebookIdString extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function () {
            DB::statement('ALTER TABLE `posts`
	CHANGE COLUMN `facebook_page_id` `facebook_page_id` VARCHAR(255) NULL DEFAULT NULL;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function () {
            DB::statement('ALTER TABLE `posts`
	CHANGE COLUMN `facebook_page_id` `facebook_page_id` BIGINT NULL DEFAULT NULL;');
        });
    }
}
