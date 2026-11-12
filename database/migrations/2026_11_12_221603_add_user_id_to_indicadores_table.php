<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('indicadores', function (Blueprint $table) {
            $table->dropUnique('indicadores_codigo_unique');

            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->unique(['user_id', 'codigo']);
        });

        // Preserva os indicadores existentes para cada usuário.
        $usuarios = DB::table('users')->pluck('id');

        $indicadoresAntigos = DB::table('indicadores')
            ->whereNull('user_id')
            ->get();

        foreach ($indicadoresAntigos as $indicador) {
            foreach ($usuarios as $userId) {
                $novoId = DB::table('indicadores')->insertGetId([
                    'user_id' => $userId,
                    'codigo' => $indicador->codigo,
                    'nome' => $indicador->nome,
                    'url_api' => $indicador->url_api,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $valores = DB::table('valores')
                    ->where('indicador_id', $indicador->id)
                    ->get();

                foreach ($valores as $valor) {
                    DB::table('valores')->insert([
                        'indicador_id' => $novoId,
                        'data' => $valor->data,
                        'valor' => $valor->valor,
                    ]);
                }
            }

            // Remove o registro antigo depois de copiar os dados.
            if ($usuarios->isNotEmpty()) {
                DB::table('indicadores')
                    ->where('id', $indicador->id)
                    ->delete();
            }
        }
    }

    public function down(): void
    {
        throw new RuntimeException(
            'Migration irreversível automaticamente: os indicadores agora pertencem a usuários diferentes.'
        );
    }
};
