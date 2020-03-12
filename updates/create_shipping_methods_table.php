<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateShippingMethodsTable extends Migration
{

    public function up()
    {
        Schema::create('samubra_training_shipping_methods', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('active')->default(0);
            $table->string('name');
            $table->string('code')->index();
            $table->decimal('price', 15, 2)->nullable();
            $table->integer('weight')->nullable()->index();
            $table->text('description')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });

        if (Schema::hasColumn('samubra_training_orders', 'shipping_total') && Schema::hasColumn('samubra_training_orders', 'shipping_method_id')) {
            return;
        }

        Schema::table('samubra_training_orders', function($table)
        {
            $table->integer('shipping_method_id')->nullable()->after('shipping_info');
            $table->decimal('shipping_total', 15, 2)->nullable()->after('shipping_method_id');
            $table->decimal('shipping_tax', 15, 2)->nullable()->after('shipping_total');
        });
    }

    public function down()
    {
        Schema::dropIfExists('samubra_training_shipping_methods');
    }

}