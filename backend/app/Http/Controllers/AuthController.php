<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6|confirmed',
        ]);

        // Verifica se o usuário já está autenticado
        if (Auth::check()) {
            return response()->json([
                'message' => 'Você já está autenticado. Não é possível criar um novo registro.',
            ], 403);
        }

        // Cria um novo usuário
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
        ]);

        return response()->json([
            'user' => $user, 
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }


    // função de login
    public function login(Request $request)
    {
        // Validação dos dados de entrada
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        // Verifica se o usuário já está autenticado
        if (Auth::check()) {
            return response()->json([
                'message' => 'Você já está autenticado.',
            ], 403); // Código 403 - Proibido
        }

        // Tenta autenticar o usuário
        if (!Auth::attempt($request->only('email', 'password'))) {
            // Mensagem de erro genérica para não expor informações
            return response()->json([
                'message' => "Credenciais inválidas."
            ], 401); // Código 401 - Não autorizado
        }

        // Se a autenticação for bem-sucedida, cria um novo token
        $user = Auth::user();
        $token = $user->createToken('API Token')->plainTextToken;

        // Retorna o token em resposta
        return response()->json(['token' => $token]);
    }

    // Função de Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    
        return response()->json(['message' => 'Logout realizado com sucesso!']);
    }
    
}

