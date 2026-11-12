<?php

namespace App\Http\Controllers;

use App\Jobs\SincronizarIndicador;
use App\Models\Indicador;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'codigo' => ['required', 'integer', 'min:1', 'unique:indicadores,codigo'],
            'nome' => ['required', 'string', 'max:100'],
            'url_api' => ['nullable', 'url', 'max:500'],
        ]);

        $dados['url_api'] = $dados['url_api']
            ?? "https://api.bcb.gov.br/dados/serie/bcdata.sgs.{$dados['codigo']}/dados?formato=json&dataInicial=01/01/2025&dataFinal=01/11/2026";

        Indicador::create($dados);

        return back()->with('status', 'Indicador cadastrado.');
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
            SincronizarIndicador::dispatchSync($indicador);

            return back()->with(
                'status',
                "Sincronização do indicador {$indicador->nome} concluída com sucesso!"
            );

        } catch (\Throwable $e) {

            return redirect()->route('sincronizacao.erro')->with([
                'indicador' => $indicador->nome,
                'codigo' => $indicador->codigo,
                'mensagem' => 'Registro retornado da API com problema.',
                'detalhes' => $e->getMessage(),
            ]);
        }
    }
}
