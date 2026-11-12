<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indicadores', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('codigo')->unique();
            $table->string('nome');
            $table->string('url_api', 500);
            $table->timestamps();
        });

        Schema::create('valores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indicador_id')->constrained('indicadores')->cascadeOnDelete();
            $table->date('data');
            $table->decimal('valor', 14, 6);
            $table->unique(['indicador_id', 'data']);
        });

        DB::table('indicadores')->insert([
            [
                'codigo'     => 433,
                'nome'       => 'IPCA',
                'url_api'    => 'https://api.bcb.gov.br/dados/serie/bcdata.sgs.433/dados?formato=json&dataInicial=01/01/2025&dataFinal=01/11/2026',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo'     => 189,
                'nome'       => 'IGP-M',
                'url_api'    => 'https://api.bcb.gov.br/dados/serie/bcdata.sgs.189/dados?formato=json&dataInicial=01/01/2025&dataFinal=01/11/2026',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('valores');
        Schema::dropIfExists('indicadores');
    }
};