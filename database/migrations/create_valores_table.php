<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // A tabela já pode ter sido criada pela migration de indicadores.
        if (!Schema::hasTable('valores')) {
            Schema::create('valores', function (Blueprint $table) {
                $table->id();

                $table->foreignId('indicador_id')
                    ->constrained('indicadores')
                    ->cascadeOnDelete();

                $table->date('data');

                $table->decimal('valor', 14, 6);

                $table->unique(['indicador_id', 'data']);
            });
        }
    }

    public function down(): void
    {
        // Não remover a tabela, pois ela também é criada
        // pela migration de indicadores.
    }
};
