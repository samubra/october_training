<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainingOrderItems extends Migration
{
    public function up()
    {
        Schema::create('samubra_training_order_items', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('samubra_training_orders')->onDelete('cascade');
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('samubra_training_projects')->onDelete('cascade');
            $table->integer('record_id')->unsigned();
            $table->foreign('record_id')->references('id')->on('samubra_training_records')->onDelete('cascade');
            $table->unsignedInteger('amount');
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('rating')->nullable();
            $table->text('review')->nullable();
            $table->timestamp('reviewed_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_training_order_items');
    }
}