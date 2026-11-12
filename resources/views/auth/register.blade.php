
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-l from-red-900 via-red-950 to-black min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md">

        <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/20 p-8">

            <!-- Título -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">
                    Criar conta
                </h1>

                <p class="text-gray-300">
                    Cadastre-se para acessar o sistema
                </p>
            </div>

            <!-- Formulário de Cadastro -->
            <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
                @csrf

                <!-- Nome -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-200 mb-2">
                        Nome
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400"
                        placeholder="Seu nome"
                    >

                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-200 mb-2">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400"
                        placeholder="seu@email.com"
                    >

                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Senha -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-200 mb-2">
                        Senha
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400"
                        placeholder="Mínimo de 8 caracteres"
                    >

                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirmar senha -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-200 mb-2">
                        Confirmar senha
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400"
                        placeholder="Repita sua senha"
                    >
                </div>

                <!-- Botão cadastrar -->
                <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-red-600 to-black-600 hover:from-red-700 hover:to-black-700 text-white font-semibold py-3 px-4 rounded-lg shadow-lg transition duration-200"
                >
                    Cadastrar
                </button>

            </form>

            <!-- Link para login -->
            <div class="mt-6 text-center">
                <p class="text-gray-300">
                    Já possui uma conta?

                    <a
                        href="{{ route('login') }}"
                        class="text-white-400 hover:text-red-300 font-medium transition-colors"
                    >
                        Entrar
                    </a>
                </p>
            </div>

        </div>

        <!-- Rodapé -->
        <div class="text-center mt-8">
            <p class="text-gray-400 text-sm">
                &copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.
            </p>
        </div>

    </div>

</body>
</html>
