<?php namespace Samubra\Training\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddUserIdentityColumn extends Migration
{
    public function up()
    {
        Schema::table('lovata_buddies_users', function($table)
        {
            $table->string('identity',20)->nullable;
            //$table->string('phone',12)->nullable;
        });
    }
    
    public function down()
    {
        Schema::table('lovata_buddies_users', function($table)
        {
            $table->dropColumn('identity');
           // $table->dropColumn('phone');
        });
    }
}