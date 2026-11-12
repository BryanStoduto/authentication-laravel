<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Indicador;
use App\Models\Valor;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SincronizarIndicador implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Indicador $indicador
    ) {}

    public function handle(): void
    {
        Log::info("Iniciando sincronização para o indicador: {$this->indicador->nome} (URL: {$this->indicador->url_api})");

        $resposta = Http::connectTimeout(5)
            ->timeout(15)
            ->withoutVerifying() // Ignora problemas de certificado SSL em ambiente local
            ->get($this->indicador->url_api);

        if ($resposta->failed()) {
            throw new \RuntimeException("A API do Banco Central retornou um erro (Status: {$resposta->status()}). Verifique o código do indicador.");
        }

        $resposta->throw();

        $dados = $resposta->json();

        if (!is_array($dados)) {
            throw new \RuntimeException(
                'Resposta inválida da API do Banco Central.'
            );
        }

        $linhas = collect($dados)->map(function ($linha) {
            return [
                'indicador_id' => $this->indicador->id,
                'data' => Carbon::createFromFormat(
                    'd/m/Y',
                    $linha['data']
                )->toDateString(),
                'valor' => (float) $linha['valor'],
            ];
        })->all();

        if (!empty($linhas)) {
            Valor::upsert(
                $linhas,
                ['indicador_id', 'data'],
                ['valor']
            );
            Log::info("Sincronização concluída com sucesso para {$this->indicador->nome}. Total de registos processados: " . count($linhas));
        } else {
            Log::warning("A API não retornou dados para o indicador {$this->indicador->nome} no período especificado.");
        }
    }
}