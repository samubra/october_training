<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainingCourses extends Migration
{
    public function up()
    {
        Schema::create('samubra_training_courses', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('title');
            $table->smallInteger('course_type')->nullable();
            $table->integer('teacher_id')->nullable()->unsigned();
            $table->decimal('default_hours', 10, 1)->default(4.0);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_training_courses');
    }
}