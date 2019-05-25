<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSamubraTrainingRecords extends Migration
{
    public function up()
    {
        Schema::table('samubra_training_records', function($table)
        {
            $table->string('record_name')->nullable();
            $table->string('record_phone',20)->nullable();
            $table->string('record_address')->nullable();
            $table->string('record_company')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('samubra_training_records', function($table)
        {
            $table->dropColumn(['record_name','record_phone','record_address','record_company']);
        });
    }
}