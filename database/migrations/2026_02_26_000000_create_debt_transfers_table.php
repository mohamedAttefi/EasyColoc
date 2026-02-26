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
        Schema::create('debt_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('colocation_id')->constrained('colocations');
            $table->foreignId('from_user_id')->constrained('users');
            $table->foreignId('to_user_id')->constrained('users');
            $table->foreignId('origin_user_id')->nullable()->constrained('users');
            $table->decimal('amount', 10, 2);
            $table->string('reason')->default('member_removed_with_debt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debt_transfers');
    }
};
