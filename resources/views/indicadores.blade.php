<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Indicadores</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #0a0a0a; color: #ededec; font-family: system-ui, sans-serif; }
        .topo { max-width: 1120px; margin: 0 auto; padding: 1.25rem 1rem; display: flex; gap: .75rem; justify-content: flex-end; align-items: center; }
        .btn, button { background: transparent; color: #ededec; border: 1px solid #3e3e3a; border-radius: 6px; padding: .45rem 1rem; font-size: .9rem; cursor: pointer; text-decoration: none; }
        .btn:hover, button:hover { background: #1b1b18; }
        .btn-perigo { border-color: #7f1d1d; color: #f87171; }
        main { max-width: 1120px; margin: 1rem auto; padding: 0 1rem; }
        .card { background: #161615; border: 1px solid #3e3e3a; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; }
        h1, h2 { margin: 0 0 1rem; font-size: 1.2rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: .7rem .5rem; border-bottom: 1px solid #2a2a27; vertical-align: middle; }
        th { color: #a1a09a; font-weight: 500; font-size: .85rem; }
        input { background: #0a0a0a; color: #ededec; border: 1px solid #3e3e3a; border-radius: 6px; padding: .45rem .7rem; }
        form { display: inline; }
        .ok { background: #052e16; border: 1px solid #166534; color: #86efac; padding: .7rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
        .erro { background: #2a0a0a; border: 1px solid #7f1d1d; color: #fca5a5; padding: .7rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
        .vazio { color: #a1a09a; text-align: center; padding: 1.5rem 0; }
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
                @foreach ($errors->all() as $erro) <div>{{ $erro }}</div> @endforeach
            </div>
        @endif

        <section class="card">
            <h1>Indicadores</h1>

            <table>
                <thead>
                    <tr><th>Código</th><th>Nome</th><th>Valores</th><th>Último</th><th>Ações</th></tr>
                </thead>
                <tbody>
                @forelse ($indicadores as $i)
                    <tr>
                        <td>{{ $i->codigo }}</td>
                        <td>
                            <form method="POST" action="{{ route('indicadores.update', $i) }}">
                                @csrf @method('PUT')
                                <input name="nome" value="{{ $i->nome }}" required maxlength="100">
                                <button type="submit">Salvar</button>
                            </form>
                        </td>
                        <td>{{ $i->valores_count }}</td>
                        <td>{{ $i->valores_max_data ? \Carbon\Carbon::parse($i->valores_max_data)->format('d/m/Y') : '—' }}</td>
                        <td>
                            <form method="POST" action="{{ route('indicadores.sincronizar', $i) }}">
                                @csrf
                                <button type="submit">Sincronizar</button>
                            </form>
                            <form method="POST" action="{{ route('indicadores.destroy', $i) }}"
                                  onsubmit="return confirm('Excluir o indicador e todos os valores dele?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-perigo">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="vazio">Nenhum indicador cadastrado.</td></tr>
                @endforelse
                </tbody>
            </table>
        </section>

        <section class="card">
            <h2>Cadastrar indicador</h2>
            <p style="color:#a1a09a;margin-top:0">Use o código da série do BCB (ex.: 433 = IPCA, 189 = IGP-M).</p>
            <form method="POST" action="{{ route('indicadores.store') }}">
                @csrf
                <input name="codigo" type="number" min="1" placeholder="Código" value="{{ old('codigo') }}" required>
                <input name="nome" placeholder="Nome" value="{{ old('nome') }}" required maxlength="100">
                <button type="submit">Cadastrar</button>
            </form>
        </section>
    </main>
</body>
</html>