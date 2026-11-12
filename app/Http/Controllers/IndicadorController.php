<?php

namespace App\Http\Controllers;

use App\Models\Indicador;
use App\Models\Valor;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class IndicadorController extends Controller
{
    public function index(): View
    {
        $indicadores = Indicador::withCount('valores')
            ->withMax('valores', 'data')
            ->orderBy('codigo')
            ->get();

        return view('indicadores', compact('indicadores'));
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'codigo'  => ['required', 'integer', 'min:1', 'unique:indicadores,codigo'],
            'nome'    => ['required', 'string', 'max:100'],
            'url_api' => ['nullable', 'url', 'max:500'],
        ]);

        $dados['url_api'] = $dados['url_api']
            ?? "https://api.bcb.gov.br/dados/serie/bcdata.sgs.{$dados['codigo']}/dados?formato=json&dataInicial=01/01/2025&dataFinal=01/11/2026";

        Indicador::create($dados);

        return back()->with('status', 'Indicador cadastrado. Clique em Sincronizar para buscar os valores.');
    }

    public function update(Request $request, Indicador $indicador): RedirectResponse
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'url_api' => ['nullable', 'url', 'max:500'],
        ]);

        $indicador->update($dados);

        return back()->with('status', 'Indicador atualizado.');
    }

    public function destroy(Indicador $indicador): RedirectResponse
    {
        $indicador->delete();

        return back()->with('status', 'Indicador e valores excluídos.');
    }

    public function sincronizar(Indicador $indicador): RedirectResponse
    {
        try {
            // withoutVerifying(): só para desenvolvimento (erro de SSL no Windows).
            $resposta = Http::withoutVerifying()->timeout(30)->get($indicador->url_api);
        } catch (\Throwable $e) {
            return back()->withErrors(['api' => 'Não foi possível acessar a API: ' . $e->getMessage()]);
        }

        if ($resposta->failed() || !is_array($resposta->json())) {
            return back()->withErrors(['api' => "A API do BCB não respondeu corretamente (HTTP {$resposta->status()})."]);
        }

        $linhas = collect($resposta->json())->map(fn ($l) => [
            'indicador_id' => $indicador->id,
            'data'         => Carbon::createFromFormat('d/m/Y', $l['data'])->toDateString(),
            'valor'        => (float) $l['valor'],
        ])->all();

        Valor::upsert($linhas, ['indicador_id', 'data'], ['valor']);

        return back()->with('status', count($linhas) . " valores sincronizados para {$indicador->nome}.");
    }
}