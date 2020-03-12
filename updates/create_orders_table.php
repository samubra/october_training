<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateOrdersTable extends Migration
{

    public function up()
    {
        Schema::create('samubra_training_orders', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('no')->unique();
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->string('email')->nullable();
            //$table->text('items');
            $table->text('billing_info')->nullable();
            $table->text('shipping_info')->nullable();
            $table->decimal('vat', 7, 2);
            $table->decimal('total', 7, 2);
            $table->string('currency');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('samubra_training_orders');
    }

}