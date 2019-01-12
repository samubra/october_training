<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainingProjectCourses extends Migration
{
    public function up()
    {
        Schema::create('samubra_training_project_courses', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('plan_course_id')->unsigned();
            $table->integer('project_id')->unsigned();
            $table->integer('teacher_id')->nullable()->unsigned();
            $table->dateTime('course_time_start')->nullable();
            $table->dateTime('course_time_end')->nullable();
            $table->decimal('hours', 10, 1);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_training_project_courses');
    }
}