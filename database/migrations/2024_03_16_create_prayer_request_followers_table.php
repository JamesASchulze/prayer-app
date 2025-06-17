<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('prayer_request_followers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prayer_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['prayer_request_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('prayer_request_followers');
    }
}; 