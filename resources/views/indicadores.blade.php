<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Indicadores</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #0a0a0a;
            color: #ededec;
            font-family: system-ui, sans-serif;
        }

        .topo {
            max-width: 1120px;
            margin: 0 auto;
            padding: 1.25rem 1rem;
            display: flex;
            gap: .75rem;
            justify-content: flex-end;
            align-items: center;
        }

        .btn, button {
            background: transparent;
            color: #ededec;
            border: 1px solid #3e3e3a;
            border-radius: 6px;
            padding: .45rem 1rem;
            font-size: .9rem;
            cursor: pointer;
            text-decoration: none;
        }

        .btn:hover, button:hover {
            background: #1b1b18;
        }

        .btn-perigo {
            border-color: #7f1d1d;
            color: #f87171;
        }

        main {
            max-width: 1120px;
            margin: 1rem auto;
            padding: 0 1rem;
        }

        .card {
            background: #161615;
            border: 1px solid #3e3e3a;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        h1, h2 {
            margin: 0 0 1rem;
            font-size: 1.2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: left;
            padding: .7rem .5rem;
            border-bottom: 1px solid #2a2a27;
            vertical-align: middle;
        }

        th {
            color: #a1a09a;
            font-weight: 500;
            font-size: .85rem;
        }

        input, select {
            background: #0a0a0a;
            color: #ededec;
            border: 1px solid #3e3e3a;
            border-radius: 6px;
            padding: .45rem .7rem;
        }

        form {
            display: inline;
        }

        .form-consulta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: end;
        }

        .campo {
            display: flex;
            flex-direction: column;
            gap: .4rem;
        }

        .campo label {
            color: #a1a09a;
            font-size: .85rem;
        }

        .ok {
            background: #052e16;
            border: 1px solid #166534;
            color: #86efac;
            padding: .7rem 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        .erro {
            background: #2a0a0a;
            border: 1px solid #7f1d1d;
            color: #fca5a5;
            padding: .7rem 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        .vazio {
            color: #a1a09a;
            text-align: center;
            padding: 1.5rem 0;
        }

        .tabela-container {
            overflow-x: auto;
        }

        .grafico-container {
            position: relative;
            width: 100%;
            height: 350px;
            margin-top: 1.5rem;
        }

        @media (max-width: 700px) {
            .topo {
                flex-wrap: wrap;
            }

            .form-consulta {
                flex-direction: column;
                align-items: stretch;
            }

            .campo input, .campo select {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <header class="topo">
        <span>Olá, {{ auth()->user()->name }}!</span>

        <a class="btn" href="{{ url('/') }}">Dashboard</a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </header>

    <main>

        @if (session('status'))
            <div class="ok">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="erro">
                @foreach ($errors->all() as $erro)
                    <div>{{ $erro }}</div>
                @endforeach
            </div>
        @endif

        <section class="card">
            <h1>Indicadores</h1>

            <div class="tabela-container">
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Valores</th>
                            <th>Último</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($indicadores as $i)
                            <tr>
                                <td>{{ $i->codigo }}</td>

                                <td>
                                    <form method="POST" action="{{ route('indicadores.update', $i) }}">
                                        @csrf
                                        @method('PUT')

                                        <input
                                            name="nome"
                                            value="{{ $i->nome }}"
                                            required
                                            maxlength="100"
                                        >

                                        <button type="submit">Salvar</button>
                                    </form>
                                </td>

                                <td>{{ $i->valores_count }}</td>

                                <td>
                                    {{ $i->valores_max_data
                                        ? \Carbon\Carbon::parse($i->valores_max_data)->format('d/m/Y')
                                        : '—' }}
                                </td>

                                <td>
                                    <form method="POST" action="{{ route('indicadores.sincronizar', $i) }}">
                                        @csrf
                                        <button type="submit">Sincronizar</button>
                                    </form>

                                    <form
                                        method="POST"
                                        action="{{ route('indicadores.destroy', $i) }}"
                                        onsubmit="return confirm('Excluir o indicador e todos os valores dele?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn-perigo">
                                            Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="vazio">
                                    Nenhum indicador cadastrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="card">
            <h2>Cadastrar indicador</h2>

            <p style="color:#a1a09a;margin-top:0">
                Use o código da série do BCB (ex.: 433 = IPCA, 189 = IGP-M).
            </p>

            <form method="POST" action="{{ route('indicadores.store') }}">
                @csrf

                <input
                    name="codigo"
                    type="number"
                    min="1"
                    placeholder="Código"
                    value="{{ old('codigo') }}"
                    required
                >

                <input
                    name="nome"
                    placeholder="Nome"
                    value="{{ old('nome') }}"
                    required
                    maxlength="100"
                >

                <button type="submit">Cadastrar</button>
            </form>
        </section>

        <section class="card">
            <h2>Consultar indicadores</h2>

            <p style="color:#a1a09a;margin-top:0">
                Selecione o indicador e o período para consultar os valores armazenados no banco de dados.
            </p>

            <form
                method="GET"
                action="{{ route('indicadores.consultar') }}"
                class="form-consulta"
            >
                <div class="campo">
                    <label for="indicador_id">Indicador</label>

                    <select name="indicador_id" id="indicador_id" required>
                        <option value="">Selecione</option>

                        @foreach ($indicadores as $i)
                            <option
                                value="{{ $i->id }}"
                                @selected(isset($filtros) && $filtros['indicador_id'] == $i->id)
                            >
                                {{ $i->nome }} ({{ $i->codigo }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="campo">
                    <label for="data_inicial">Data inicial</label>

                    <input
                        type="date"
                        name="data_inicial"
                        id="data_inicial"
                        value="{{ $filtros['data_inicial'] ?? '' }}"
                        required
                    >
                </div>

                <div class="campo">
                    <label for="data_final">Data final</label>

                    <input
                        type="date"
                        name="data_final"
                        id="data_final"
                        value="{{ $filtros['data_final'] ?? '' }}"
                        required
                    >
                </div>

                <button type="submit">Consultar</button>
            </form>
        </section>

        @isset($valores)
            <section class="card">
                <h2>Resultado da consulta</h2>

                @if ($valores->isNotEmpty())

                    <div class="tabela-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($valores as $valor)
                                    <tr>
                                        <td>
                                            {{ \Carbon\Carbon::parse($valor->data)->format('d/m/Y') }}
                                        </td>

                                        <td>
                                            {{ number_format((float) $valor->valor, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h2 style="margin-top:2rem">Gráfico de evolução</h2>

                    <div class="grafico-container">
                        <canvas id="graficoIndicador"></canvas>
                    </div>

                @else
                    <p class="vazio">
                        Nenhum valor encontrado para o indicador e período selecionados.
                    </p>
                @endif
            </section>
        @endisset

    </main>

    @isset($valores)
        @if ($valores->isNotEmpty())
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
                const datas = @json($valores->pluck('data'));
                const valores = @json($valores->pluck('valor'));

                const valoresNumericos = valores.map(valor => Number(valor));

                const contexto = document
                    .getElementById('graficoIndicador')
                    .getContext('2d');

                new Chart(contexto, {
                    type: 'line',

                    data: {
                        labels: datas.map(data => {
                            const [ano, mes, dia] = data.split('-');
                            return `${dia}/${mes}/${ano}`;
                        }),

                        datasets: [{
                            label: 'Valor do indicador',
                            data: valoresNumericos,
                            borderColor: '#60a5fa',
                            backgroundColor: 'rgba(96, 165, 250, 0.12)',
                            borderWidth: 2,
                            pointRadius: 2,
                            tension: 0.2,
                            fill: true
                        }]
                    },

                    options: {
                        responsive: true,
                        maintainAspectRatio: false,

                        plugins: {
                            legend: {
                                labels: {
                                    color: '#ededec'
                                }
                            }
                        },

                        scales: {
                            x: {
                                ticks: {
                                    color: '#a1a09a'
                                },
                                grid: {
                                    color: '#2a2a27'
                                }
                            },

                            y: {
                                ticks: {
                                    color: '#a1a09a'
                                },
                                grid: {
                                    color: '#2a2a27'
                                }
                            }
                        }
                    }
                });
            </script>
        @endif
    @endisset

</body>
</html>
