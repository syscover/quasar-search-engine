<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class SearchEngineCreateTableWord extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (! Schema::hasTable('search_engine_word'))
		{
			Schema::create('search_engine_word', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                
                $table->bigIncrements('id');
                $table->uuid('uuid');
                $table->string('word');
                $table->string('metaphone');

                $table->index('uuid', 'search_engine_word_uuid_idx');
                $table->index('metaphone', 'search_engine_word_metaphone_idx');
            });
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('search_engine_word');
	}
}