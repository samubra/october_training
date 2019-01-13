<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainingStatus extends Migration
{
    public function up()
    {
        Schema::create('samubra_training_status', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('color', 10);
            $table->string('type');
            $table->tinyInteger('sort');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_training_status');
    }
}