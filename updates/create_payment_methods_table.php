<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreatePaymentMethodsTable extends Migration
{

    public function up()
    {
        Schema::create('samubra_training_payment_methods', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('active')->default(0);
            $table->string('name');
            $table->string('code')->index();
            $table->integer('weight')->nullable()->index();
            $table->text('description')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });

        if (Schema::hasColumn('samubra_training_orders', 'payment_method_id') && Schema::hasColumn('samubra_training_orders', 'payment_method_name') && Schema::hasColumn('samubra_training_orders', 'payment_method_title')) {
            return;
        }

        Schema::table('samubra_training_orders', function($table)
        {
            $table->integer('payment_method_id')->nullable()->after('currency');
            $table->string('transaction_id')->nullable()->after('payment_method_id');
            $table->text('payment_data')->nullable()->after('transaction_id');
            $table->text('payment_response')->nullable()->after('payment_data');
            $table->timestamp('date_completed')->nullable()->after('updated_at');
            $table->timestamp('date_paid')->nullable()->after('transaction_id');

        });

        if (Schema::hasColumn('samubra_training_projects', 'deleted_at')) {
            return;
        }

        //
        // Add additional fields to products
        //
        Schema::table('samubra_training_projects', function($table)
        {
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('samubra_training_payment_methods');
    }

}