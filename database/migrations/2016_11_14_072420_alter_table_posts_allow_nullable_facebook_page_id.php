<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePostsAllowNullableFacebookPageId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `posts`
	CHANGE COLUMN `facebook_page_id` `facebook_page_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL AFTER `photo_path`;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `posts`
	CHANGE COLUMN `facebook_page_id` `facebook_page_id` BIGINT(20) UNSIGNED NOT NULL AFTER `photo_path`;');
    }
}
