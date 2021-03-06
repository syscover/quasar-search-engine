<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class SearchEngineCreateTableIndex extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (! Schema::hasTable('search_engine_index'))
		{
			Schema::create('search_engine_index', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                
                $table->bigIncrements('id');
                $table->uuid('uuid');
                $table->uuid('permission_uuid')->nullable();
                $table->string('indexable_type')->nullable();
                $table->uuid('indexable_uuid')->nullable();
                $table->string('url', 511);
                $table->string('title', 511);
                $table->text('content_layer_1')->nullable();
                $table->text('content_layer_2')->nullable();
                $table->text('content_layer_3')->nullable();
                $table->timestamps();

                $table->index('uuid', 'search_engine_index_uuid_idx');
				
				$table->foreign('permission_uuid', 'search_engine_index_permission_uuid_fk')
					->references('uuid')
					->on('admin_permission')
					->onDelete('cascade')
					->onUpdate('cascade');
            });

            DB::statement('ALTER TABLE search_engine_index ADD FULLTEXT search_engine(url, title, content_layer_1, content_layer_2, content_layer_3)'); 
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('search_engine_index');
	}
}