<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddProjectCoursesCourseIdColumn extends Migration
{
    public function up()
    {
        Schema::table('samubra_training_project_courses', function($table)
        {
            $table->integer('course_id')->unsigned();
        });
    }

    public function down()
    {
        Schema::table('samubra_training_project_courses', function($table)
        {
            $table->dropColumn('course_id');
        });
    }
}