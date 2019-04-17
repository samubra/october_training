<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSamubraTrainingPlanCourses extends Migration
{
    public function up()
    {
        Schema::table('samubra_training_plan_courses', function($table)
        {
            $table->json('teaching_form')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('samubra_training_plan_courses', function($table)
        {
            $table->dropColumn('teaching_form');
        });
    }
}