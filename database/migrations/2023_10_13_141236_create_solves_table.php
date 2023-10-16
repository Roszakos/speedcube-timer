<?php

use App\Models\Session;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('solves', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Session::class, 'session_id');
            $table->string('puzzle');
            $table->string('scramble');
            $table->integer('time');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solves');
    }
};