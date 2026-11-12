
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('valores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('indicador_id')
                ->constrained('indicadores')
                ->cascadeOnDelete();

            $table->date('data');

            $table->decimal('valor', 14, 6);
            // Impede datas duplicadas para o mesmo indicador
            $table->unique(['indicador_id', 'data']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('valores');
    }
};
