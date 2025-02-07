<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInitialTables extends Migration {
    public function up() {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamps();
        });

        Schema::create('urls', function (Blueprint $table) {
            $table->id();
            $table->string('url')->unique();
            $table->float('price')->nullable();
            $table->timestamps();
        });

        
        Schema::create('email_url', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_id')->constrained()->onDelete('cascade');
            $table->foreignId('url_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('email_url');
        Schema::dropIfExists('urls');
        Schema::dropIfExists('emails');
    }
}
