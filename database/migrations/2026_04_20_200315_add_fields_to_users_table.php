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
            $table->string('username', 50)->unique()->after('id');
            $table->string('name', 100)->change();
            $table->string('surname', 100)->after('name');
            $table->text('bio')->nullable()->after('email');
            $table->string('profile_image')->nullable()->after('bio');
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null')->after('profile_image');
            $table->foreignId('role_id')->nullable()->constrained()->onDelete('set null')->after('city_id');
            $table->boolean('is_active')->default(true)->after('role_id');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropForeign(['role_id']);
            $table->dropColumn(['username', 'surname', 'bio', 'profile_image', 'city_id', 'role_id', 'is_active']);
            $table->dropSoftDeletes();
        });
    }
};
