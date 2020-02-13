<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddUserIdentityColumn extends Migration
{
    public function up()
    {
        Schema::table('users', function($table)
        {
            $table->string('identity',20)->nullable();
            $table->string('phone',12)->nullable();
            $table->string('company',60)->nullable();
            $table->text('introduce')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function($table)
        {
            $table->dropColumn('identity');
            $table->dropColumn('phone');
            $table->dropColumn('company');
            $table->dropColumn('introduce');
        });
    }
}