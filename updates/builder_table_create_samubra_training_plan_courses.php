<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainingPlanCourses extends Migration
{
    public function up()
    {
        Schema::create('samubra_training_plan_courses', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('plan_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->decimal('hours', 10, 1)->default(4.0);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_training_plan_courses');
    }
}