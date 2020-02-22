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

        Schema::table('rainlab_blog_posts', function($table)
        {
            $table->boolean('pinned')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('samubra_training_project_posts');
        Schema::table('rainlab_blog_posts', function($table){
            $table->dropColumn('pinned');
        });
    }
}