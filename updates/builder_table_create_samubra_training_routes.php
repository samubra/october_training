<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainingRoutes extends Migration
{
    public function up()
    {
        Schema::create('samubra_training_routes', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('slug');
            $table->integer('entity_id')->unsigned();
            $table->tinyInteger('type');
            $table->index('entity_id');//add index
            $table->unique('slug');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_training_routes');
    }
}