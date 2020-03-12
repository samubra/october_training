<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateOrdersStatus extends Migration
{

    public function up()
    {
        Schema::table('samubra_training_orders', function($table)
        {
            $table->string('status')->default('pending')->after('id');
        });
    }

    public function down()
    {
        Schema::table('samubra_training_orders', function($table)
        {
            $table->dropColumn('status');
        });
    }
}