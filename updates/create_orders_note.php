<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateOrdersNote extends Migration
{

    public function up()
    {
        Schema::table('samubra_training_orders', function($table)
        {
            $table->text('note')->nullable()->after('shipping_info');
        });
    }

    public function down()
    {
        Schema::table('samubra_training_orders', function($table)
        {
            $table->dropColumn('note');
        });
    }
}