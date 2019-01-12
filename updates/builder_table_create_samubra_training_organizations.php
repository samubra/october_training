<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainingOrganizations extends Migration
{
    public function up()
    {
        Schema::create('samubra_training_organizations', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->smallInteger('complete_type');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_training_organizations');
    }
}