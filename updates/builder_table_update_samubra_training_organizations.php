<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSamubraTrainingOrganizations extends Migration
{
    public function up()
    {
        Schema::table('samubra_training_organizations', function($table)
        {
            $table->smallInteger('review_cycle')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('samubra_training_organizations', function($table)
        {
            $table->dropColumn('review_cycle');
        });
    }
}
