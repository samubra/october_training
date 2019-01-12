<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainingProjects extends Migration
{
    public function up()
    {
        Schema::create('samubra_training_projects', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('slug');
            $table->string('title');
            $table->boolean('active');
            $table->integer('training_status_id')->unsigned();
            $table->date('training_begin_date')->nullable();
            $table->date('training_end_date')->nullable();
            $table->date('plan_exam_date')->nullable();
            $table->decimal('cost', 10, 2);
            $table->text('condition')->nullable();
            $table->integer('plan_id')->unsigned();
            $table->text('remark')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_training_projects');
    }
}