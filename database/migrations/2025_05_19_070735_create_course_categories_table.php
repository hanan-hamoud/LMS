<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   
        public function up()
        {
            Schema::create('course_categories', function (Blueprint $table) {
                $table->id();
                $table->json('name')->unique();
                $table->string('slug')->unique();
                $table->boolean('status')->default(true);
                $table->timestamps();
                $table->softDeletes();
            });
        }
        
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_categories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }

    

};
