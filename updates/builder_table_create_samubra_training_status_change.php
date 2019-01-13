<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainingStatusChange extends Migration
{
    public function up()
    {
        Schema::create('samubra_training_status_change', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('status_id')->nullable()->unsigned();
            $table->integer('entity_id')->nullable()->unsigned();
            $table->string('entity_type')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_training_status_change');
    }
}