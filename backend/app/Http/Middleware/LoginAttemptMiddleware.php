<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginAttemptMiddleware
{
    protected $maxAttempts = 5; // Número máximo de tentativas
    protected $lockoutTime = 1; // Tempo em minutos antes que o usuário possa tentar novamente (1 minuto)

    public function handle(Request $request, Closure $next)
    {
        $email = $request->input('email');

        // Usando cache para controlar tentativas, apenas com base no email
        $key = 'login_attempts_' . $email;

        // Verifica se o usuário está bloqueado
        if (cache()->has($key . '_lockout')) {
            // Recupera o tempo restante de bloqueio
            return response()->json([
                'message' => "Seu email está bloqueado devido ao número excessivo de tentativas de login. Tente novamente em " . round(cache()->get($key . '_lockout') / 60) . " minuto(s)."
            ], 429); // Código 429 - Muitas solicitações
        }

        // Incrementa o número de tentativas
        $attempts = cache()->increment($key);

        // Se as tentativas excederem o máximo permitido
        if ($attempts > $this->maxAttempts) {
            // Define o tempo de bloqueio
            cache()->put($key . '_lockout', $this->lockoutTime * 60, $this->lockoutTime * 60);
            return response()->json([
                'message' => "Você excedeu o número máximo de tentativas de login. Tente novamente em " . $this->lockoutTime . " minuto(s)."
            ], 429); // Código 429 - Muitas solicitações
        }

        // Registro das tentativas de login
        Log::info("Tentativa de login para $email. Tentativas atuais: $attempts");

        return $next($request);
    }
}
