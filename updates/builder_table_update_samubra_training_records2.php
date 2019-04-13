<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSamubraTrainingRecords2 extends Migration
{
    public function up()
    {
        Schema::table('samubra_training_records', function($table)
        {
            $table->string('record_id_num');
            $table->smallInteger('record_id_type');
        });
    }
    
    public function down()
    {
        Schema::table('samubra_training_records', function($table)
        {
            $table->dropColumn('record_id_num');
            $table->dropColumn('record_id_type');
        });
    }
}