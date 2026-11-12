<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-900 via-purple-900 to-violet-900 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/20 p-8">
            <!-- Logo ou título -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Entrar</h1>
                <p class="text-gray-300">Acesse sua conta</p>
            </div>

            <!-- Formulário de Login -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Campo Email -->
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

                <!-- Campo Senha -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-200 mb-2">
                        Senha
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        autocomplete="current-password"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lembrar-me e Esqueci a senha -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="remember"
                            class="w-4 h-4 text-purple-600 bg-white/10 border-white/20 rounded focus:ring-purple-500"
                        >
                        <span class="ml-2 text-sm text-gray-300">Lembrar-me</span>
                    </label>

                    <a href="#" class="text-sm text-purple-400 hover:text-purple-300 transition-colors">
                        Esqueci minha senha
                    </a>
                </div>

                <!-- Botão de Login -->
                <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-purple-600 to-violet-600 hover:from-purple-700 hover:to-violet-700 text-white font-semibold py-3 px-4 rounded-lg shadow-lg transition duration-200"
                >
                    Entrar
                </button>
            </form>

            <!-- Link para registro -->
            <div class="mt-6 text-center">
                <p class="text-gray-300">
                    Não tem uma conta?
                    <a href="#" class="text-purple-400 hover:text-purple-300 font-medium transition-colors">
                        Registre-se
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
</html>[cite: 1, 2, 3, 4]