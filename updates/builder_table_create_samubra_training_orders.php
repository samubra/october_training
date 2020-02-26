<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainingOrders extends Migration
{
    public function up()
    {
        Schema::create('samubra_training_orders', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('no')->unique();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('address');
            $table->decimal('total_amount', 10, 2);
            $table->text('remark')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_no')->nullable();
            $table->string('refund_status')->default(\Samubra\Training\Models\Order::REFUND_STATUS_PENDING);
            $table->string('refund_no')->unique()->nullable();
            $table->boolean('closed')->default(false);
            $table->boolean('reviewed')->default(false);
            $table->string('ship_status')->default(\Samubra\Training\Models\Order::SHIP_STATUS_PENDING);
            $table->text('ship_data')->nullable();
            $table->text('extra')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('samubra_training_orders');
    }
}