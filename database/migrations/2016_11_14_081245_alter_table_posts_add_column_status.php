<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePostsAddColumnStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('status')->after('photo_path')->default('pending');
            DB::statement('ALTER TABLE `posts`
	CHANGE COLUMN `photo_path` `photo_path` VARCHAR(255) NULL COLLATE \'utf8_unicode_ci\';');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('status');
            DB::statement('ALTER TABLE `posts`
	CHANGE COLUMN `photo_path` `photo_path` VARCHAR(255) NOT NULL COLLATE \'utf8_unicode_ci\';');
        });
    }
}
