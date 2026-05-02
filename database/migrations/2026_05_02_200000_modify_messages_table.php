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
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'receiver_id')) {
                $table->dropForeign(['receiver_id']);
                $table->dropColumn('receiver_id');
            }
            
            if (Schema::hasColumn('messages', 'is_read')) {
                $table->dropColumn('is_read');
            }
            
            if (Schema::hasColumn('messages', 'sender_id')) {
                $table->renameColumn('sender_id', 'user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->renameColumn('user_id', 'sender_id');
            
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade')->after('sender_id');
            $table->boolean('is_read')->default(false)->after('content');
            
            $table->index('receiver_id');
            $table->index('is_read');
        });
    }
};
