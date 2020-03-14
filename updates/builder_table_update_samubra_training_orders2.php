<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSamubraTrainingOrders2 extends Migration
{
    public function up()
    {
        Schema::table('samubra_training_orders', function($table)
        {
            $table->text('cart_items')->nullable();
            $table->text('address')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('samubra_training_orders', function($table)
        {
            $table->dropColumn('cart_items');
            $table->text('address')->nullable(false)->change();
        });
    }
}