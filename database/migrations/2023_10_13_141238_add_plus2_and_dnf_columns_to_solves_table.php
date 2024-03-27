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
        Schema::table('solves', function (Blueprint $table) {
            $table->boolean('dnf')->after('time')->default(false);
            $table->boolean('plus2')->after('time')->default(false);
        });
    }
};