<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainingProjectPosts extends Migration
{
    public function up()
    {
        Schema::create('samubra_training_project_posts', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('post_id')->unsigned();
            $table->integer('project_id')->unsigned();
            $table->primary(['project_id', 'post_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('samubra_training_project_posts');
    }
}