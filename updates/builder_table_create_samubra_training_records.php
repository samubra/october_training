<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainingRecords extends Migration
{
    public function up()
    {
        Schema::create('samubra_training_records', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('num');
            $table->integer('record_status_id')->nullable()->unsigned();
            $table->string('record_phone')->change();
            $table->string('record_address')->change();
            $table->string('record_company')->change();
            $table->smallInteger('record_edu_type')->nullable();
            $table->smallInteger('healt_type')->nullable();
            $table->integer('certificate_id')->nullable()->unsigned();
            $table->integer('project_id')->nullable()->unsigned();
            $table->smallInteger('theory_score')->default(0);
            $table->smallInteger('operate_score')->nullable();
            $table->boolean('is_eligible')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_training_records');
    }
}