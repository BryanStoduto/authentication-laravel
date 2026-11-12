<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Indicador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Exibe a tela de login
    public function login()
    {
        return view('auth.login');
    }

    // Realiza a autenticação
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(
            $request->only('email', 'password'),
            $request->boolean('remember')
        )) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ])->onlyInput('email');
    }

    // Exibe a tela de cadastro
    public function register()
    {
        return view('auth.register');
    }

    // Cadastra um novo usuário
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = DB::transaction(function () use ($validated) {

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Indicadores padrão da nova conta
            $indicadores = [
                [
                    'codigo' => 189,
                    'nome' => 'IGP-M',
                ],
                [
                    'codigo' => 433,
                    'nome' => 'IPCA',
                ],
            ];

            foreach ($indicadores as $indicador) {

                Indicador::create([
                    'user_id' => $user->id,
                    'codigo' => $indicador['codigo'],
                    'nome' => $indicador['nome'],
                    'url_api' => "https://api.bcb.gov.br/dados/serie/bcdata.sgs.{$indicador['codigo']}/dados?formato=json&dataInicial=01/01/2025&dataFinal=01/11/2026",
                ]);
            }

            return $user;
        });

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('indicadores.index')
            ->with('success', 'Cadastro realizado com sucesso!');
    }

    // Realiza o logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
