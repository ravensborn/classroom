<?php

use App\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default(UserRole::Student->value)->after('id');
            $table->string('username')->nullable()->unique()->after('name');
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete()->after('username');
            $table->tinyInteger('stage')->nullable()->after('department_id');
            $table->boolean('is_active')->default(true)->after('stage');
            $table->string('blocked_message')->nullable()->after('is_active');
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'username', 'department_id', 'stage', 'is_active', 'blocked_message']);
            $table->string('email')->nullable(false)->change();
        });
    }
};
