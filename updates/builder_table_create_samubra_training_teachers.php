<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainingTeachers extends Migration
{
    public function up()
    {
        Schema::create('samubra_training_teachers', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('identity');
            $table->string('qualification_num')->nullable();
            $table->smallInteger('job_title')->nullable();
            $table->string('phone',12)->nullable();
            $table->string('company')->nullable();
            $table->smallInteger('edu_type')->nullable()->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_training_teachers');
    }
}