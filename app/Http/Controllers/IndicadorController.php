<?php

namespace App\Http\Controllers;

use App\Jobs\SincronizarIndicador;
use App\Models\Indicador;
use App\Models\Valor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class IndicadorController extends Controller
{
    // Lista somente os indicadores do usuário autenticado
    public function index(): View
    {
        $indicadores = Indicador::where('user_id', auth()->id())
            ->withCount('valores')
            ->withMax('valores', 'data')
            ->orderBy('codigo')
            ->get();

        return view('indicadores', compact('indicadores'));
    }

    // Cadastra um indicador
    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'codigo' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('indicadores', 'codigo')
                    ->where('user_id', auth()->id()),
            ],
            'nome' => ['required', 'string', 'max:100'],
            'url_api' => ['nullable', 'url', 'max:500'],
        ]);

        $dados['url_api'] = $dados['url_api']
            ?? "https://api.bcb.gov.br/dados/serie/bcdata.sgs.{$dados['codigo']}/dados?formato=json&dataInicial=01/01/2025&dataFinal=01/11/2026";

        $dados['user_id'] = auth()->id();

        Indicador::create($dados);

        return back()->with('status', 'Indicador cadastrado.');
    }

    // Atualiza somente indicadores pertencentes ao usuário
    public function update(Request $request, Indicador $indicador): RedirectResponse
    {
        $indicador = $this->indicadorDoUsuario($indicador);

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'url_api' => ['nullable', 'url', 'max:500'],
        ]);

        $indicador->update($dados);

        return back()->with('status', 'Indicador atualizado.');
    }

    // Exclui somente indicadores pertencentes ao usuário
    public function destroy(Indicador $indicador): RedirectResponse
    {
        $indicador = $this->indicadorDoUsuario($indicador);

        $indicador->delete();

        return back()->with('status', 'Indicador e valores excluídos.');
    }

    // Sincroniza somente indicadores pertencentes ao usuário
    public function sincronizar(Indicador $indicador): RedirectResponse
    {
        $indicador = $this->indicadorDoUsuario($indicador);

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

    // Consulta valores de um indicador do usuário
    public function consultar(Request $request): View
    {
        $dados = $request->validate([
            'indicador_id' => [
                'required',
                Rule::exists('indicadores', 'id')
                    ->where('user_id', auth()->id()),
            ],
            'data_inicial' => ['required', 'date'],
            'data_final' => ['required', 'date', 'after_or_equal:data_inicial'],
        ]);

        $indicadores = Indicador::where('user_id', auth()->id())
            ->withCount('valores')
            ->withMax('valores', 'data')
            ->orderBy('codigo')
            ->get();

        $valores = Valor::where('indicador_id', $dados['indicador_id'])
            ->whereBetween('data', [
                $dados['data_inicial'],
                $dados['data_final']
            ])
            ->orderBy('data')
            ->get();

        return view('indicadores', [
            'indicadores' => $indicadores,
            'valores' => $valores,
            'filtros' => $dados,
        ]);
    }

    // Verifica se o indicador pertence ao usuário autenticado
    private function indicadorDoUsuario(Indicador $indicador): Indicador
    {
        return Indicador::where('user_id', auth()->id())
            ->findOrFail($indicador->id);
    }
}
