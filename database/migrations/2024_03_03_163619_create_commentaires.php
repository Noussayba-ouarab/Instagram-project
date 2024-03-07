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
        // Schema::create('commentaires', function (Blueprint $table) {
        //    $table->id();
        //     $table->unsignedBigInteger('user_id');
        //     $table->unsignedBigInteger('post_id');
        //     $table->text('content');
        //     $table->timestamps(); });

            // Foreign key constraints
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
             //$table->foreign('post_id')->references('id')->on('_posts')->onDelete('cascade');
            
            //  Schema::table('commentaires', function (Blueprint $table) {
            //     $table->foreign('post_id')->references('id')->on('_postes')->onDelete('cascade');
            // });
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commentaires');
    }
};
