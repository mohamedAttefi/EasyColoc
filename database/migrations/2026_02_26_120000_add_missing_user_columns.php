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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }

            if (!Schema::hasColumn('users', 'remember_token')) {
                $table->string('remember_token')->nullable();
            }

            if (!Schema::hasColumn('users', 'reputation')) {
                $table->integer('reputation')->default(0);
            }

            if (!Schema::hasColumn('users', 'is_super_admin')) {
                $table->boolean('is_super_admin')->default(false);
            }

            if (!Schema::hasColumn('users', 'is_banned')) {
                $table->boolean('is_banned')->default(false);
            }

            if (!Schema::hasColumn('users', 'banned_at')) {
                $table->timestamp('banned_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $columns = [];

        if (Schema::hasColumn('users', 'banned_at')) {
            $columns[] = 'banned_at';
        }

        if (Schema::hasColumn('users', 'is_banned')) {
            $columns[] = 'is_banned';
        }

        if (Schema::hasColumn('users', 'is_super_admin')) {
            $columns[] = 'is_super_admin';
        }

        if (Schema::hasColumn('users', 'reputation')) {
            $columns[] = 'reputation';
        }

        if (Schema::hasColumn('users', 'remember_token')) {
            $columns[] = 'remember_token';
        }

        if (Schema::hasColumn('users', 'email_verified_at')) {
            $columns[] = 'email_verified_at';
        }

        if (!empty($columns)) {
            Schema::table('users', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};
