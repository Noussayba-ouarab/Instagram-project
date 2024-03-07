<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('_postes', function (Blueprint $table) {
            //$table->id();
            $table->unsignedBigInteger('user_id');
            // $table->string('username');
            // $table->Date('date');
            // $table->string('commentaire');
            // $table->string('image')->nullable();
            // $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            //$table->bigInteger('user_id')->unsigned()->change();
  
            //$table->foreign('user_id')->references('id')->on('users');
        });


        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_postes');

        Schema::table('_postes', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['user_id']);
        });
    }
};
