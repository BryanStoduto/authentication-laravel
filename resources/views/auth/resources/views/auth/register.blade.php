<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Register</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">

        <h1 class="text-2xl font-bold text-center mb-2">
            Criar conta
        </h1>

        <p class="text-gray-500 text-center mb-6">
            Cadastre-se para acessar o sistema.
        </p>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <!-- Nome -->
            <div class="mb-4">
                <label for="name" class="block mb-1 font-medium">
                    Nome
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    class="w-full border border-gray-300 rounded-lg p-3"
                    placeholder="Digite seu nome"
                >

                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- E-mail -->
            <div class="mb-4">
                <label for="email" class="block mb-1 font-medium">
                    E-mail
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full border border-gray-300 rounded-lg p-3"
                    placeholder="Digite seu e-mail"
                >

                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Senha -->
            <div class="mb-4">
                <label for="password" class="block mb-1 font-medium">
                    Senha
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    minlength="8"
                    class="w-full border border-gray-300 rounded-lg p-3"
                    placeholder="Mínimo de 8 caracteres"
                >

                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirmar senha -->
            <div class="mb-6">
                <label for="password_confirmation" class="block mb-1 font-medium">
                    Confirmar senha
                </label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    minlength="8"
                    class="w-full border border-gray-300 rounded-lg p-3"
                    placeholder="Repita sua senha"
                >
            </div>

            <!-- Botão -->
            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold p-3 rounded-lg"
            >
                Criar conta
            </button>

        </form>

        <!-- Voltar para login -->
        <div class="text-center mt-6">
            <span class="text-gray-600">
                Já possui uma conta?
            </span>

            <a
                href="{{ route('login') }}"
                class="text-blue-600 hover:underline font-semibold"
            >
                Fazer login
            </a>
        </div>

    </div>

</body>
</html>
