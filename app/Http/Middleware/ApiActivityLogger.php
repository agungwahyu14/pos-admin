<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiActivityLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Eksekusi request ke aplikasi dan ambil hasil balasan (response)
        $response = $next($request);

        // 2. Kita HANYA mencatat aktivitas yang sifatnya MERUBAH DATA (Mutasi)
        // Ini untuk mencegah spam log saat kasir sekadar melihat-lihat menu (GET)
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            
            $user = $request->user() ? $request->user()->name : 'Guest';
            $status = $response->isSuccessful() ? 'SUCCESS' : 'FAILED';
            $statusCode = $response->getStatusCode();
            
            // Ambil body response (biasanya JSON) dengan aman
            $responseData = null;
            $content = $response->getContent();
            if (!empty($content)) {
                $responseData = json_decode($content, true);
            }

            // Susun log detail
            $context = [
                'status' => $status,
                'http_code' => $statusCode,
                'user' => $user,
                'ip' => $request->ip(),
                'method' => $request->method(),
                'payload_dikirim' => $request->except(['password', 'password_confirmation', 'token']),
            ];

            // Jika sukses, masukkan hasil responsenya. Jika gagal, masukkan errornya.
            if ($response->isSuccessful()) {
                $context['hasil_perubahan'] = $responseData;
                Log::info("[API {$request->method()}] Activity Success: /{$request->path()}", $context);
            } else {
                $context['error_detail'] = $responseData;
                // Gunakan warning/error agar gampang dicari jika ada masalah
                if ($statusCode >= 500) {
                    Log::error("[API {$request->method()}] Activity Error: /{$request->path()}", $context);
                } else {
                    Log::warning("[API {$request->method()}] Activity Failed: /{$request->path()}", $context);
                }
            }
        }

        return $response;
    }
}
