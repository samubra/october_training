<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSamubraTrainingOrders extends Migration
{
    public function up()
    {
        Schema::table('samubra_training_orders', function($table)
        {
            $table->text('address');
        });
    }
    
    public function down()
    {
        Schema::table('samubra_training_orders', function($table)
        {
            $table->dropColumn('address');
        });
    }
}
