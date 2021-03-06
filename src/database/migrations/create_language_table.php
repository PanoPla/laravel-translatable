<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguageTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = \Illuminate\Support\Facades\Config::get('translatable.language_table', 'language');
        Schema::create($table, function (Blueprint $table) {
            $table->increments('id');

            $table->string('code', 10);
            $table->string('name', 40);

            $table->index('code');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('language');
    }

}
