<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
/*  Lista de codigos para consultar e possivels erros:
    200 OK: A requisição foi bem-sucedida.
    201 Created: O recurso foi criado com sucesso.
    204 No Content: A requisição foi bem-sucedida, mas não há conteúdo a retornar.
    400 Bad Request: A requisição não pôde ser entendida ou estava malformada.
    401 Unauthorized: A requisição requer autenticação do usuário.
    403 Forbidden: O servidor entendeu a requisição, mas se recusa a autorizá-la.
    404 Not Found: O recurso solicitado não foi encontrado.
    409 Conflict: Conflito com o estado atual do recurso (por exemplo, quando um e-mail já está em uso).
*/
class AuthController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6|confirmed',
        ], [
            'email.unique' => 'Este e-mail já está registrado.',
        ]);

        if (Auth::check()) {
            return response()->json([
                'message' => 'Você já está autenticado. Não é possível criar um novo registro.',
            ], 403);
        }

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

    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        if (Auth::check()) {
            return response()->json([
                'message' => 'Você já está autenticado.',
            ], 403);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => "Credenciais inválidas."
            ], 401);
        }

        $user = Auth::user();
        
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    
        return response()->json(['message' => 'Logout realizado com sucesso!']);
    }
    
}

