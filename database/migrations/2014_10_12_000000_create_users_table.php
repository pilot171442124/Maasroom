<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('usercode',20)->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('userrole',20);/*Admin or Farmer*/
            $table->string('activestatus',20);/*Active or Inactive*/
            $table->string('phone',11)->unique();
            $table->string('address')->nullable();
            $table->string('nid')->nullable();
            $table->string('ImageURL',150)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });


        /*Default value insert*/
        $lastPostViewDate = date ( 'Y-m-d H:i:s' );
        $defaultPassword = Hash::make('Admin!strat0r');
        DB::table('users')->insert([
            ['usercode' => 'A00001','name'=>'Administrator','email' => 'administrator@esm.com','userrole' => 'Admin','activestatus' => 'Active','phone' => '01689763654','password' => $defaultPassword,'address'=>'Dhaka','nid'=>'8954124574']
        ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
