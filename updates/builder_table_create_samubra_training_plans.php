<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainingPlans extends Migration
{
    public function up()
    {
        Schema::create('samubra_training_plans', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('title');
            $table->integer('category_id')->nullable()->unsigned();
            $table->integer('organization_id')->nullable()->unsigned();
            $table->boolean('is_retraining');
            $table->boolean('is_certificate');
            $table->decimal('operate_hours', 10, 1)->default(0);
            $table->decimal('theroy_hours', 10, 1)->default(0);
            $table->string('training_address')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_person')->nullable();
            $table->text('target')->nullable();
            $table->text('material')->nullable();
            $table->text('document')->nullable();
            $table->text('other')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_training_plans');
    }
}