<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainingCertificates extends Migration
{
    public function up()
    {
        Schema::create('samubra_training_certificates', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('num')->unquie();
            $table->string('id_num');
            $table->smallInteger('id_type');
            $table->string('name');
            $table->string('phone',20)->nullable();
            $table->string('address')->nullable();
            $table->string('company')->nullable();
            $table->smallInteger('edu_type')->nullable();
            $table->integer('category_id')->nullable()->unsigned();
            $table->integer('organization_id')->nullable()->unsigned();
            $table->date('first_get_date')->nullable();
            $table->date('print_date')->nullable();
            $table->date('review_date')->nullable();
            $table->date('invalid_date')->nullable();
            $table->boolean('active')->default(0);
            $table->integer('user_id')->nullable()->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->softDeletes();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_training_certificates');
    }
}