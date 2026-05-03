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
        Schema::table('user_interests', function (Blueprint $table) {
            // Eliminar la columna interest_name si existe
            if (Schema::hasColumn('user_interests', 'interest_name')) {
                $table->dropColumn('interest_name');
            }
            
            // Agregar la columna tag_id si no existe
            if (!Schema::hasColumn('user_interests', 'tag_id')) {
                $table->foreignId('tag_id')->nullable()->after('user_id')->constrained()->onDelete('cascade');
            }
            
            // Agregar unique constraint si no existe
            $table->unique(['user_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_interests', function (Blueprint $table) {
            $table->dropForeign(['tag_id']);
            $table->dropUnique(['user_id', 'tag_id']);
            $table->dropColumn('tag_id');
            $table->string('interest_name', 100)->after('user_id');
        });
    }
};
