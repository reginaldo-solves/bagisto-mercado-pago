<?php

use Illuminate\Support\Facades\Route;
use Webkul\MercadoPago\Http\Controllers\MercadoPagoController;

// Rotas de retorno do Mercado Pago
Route::middleware(['web'])->group(function () {
    // Sucesso no pagamento
    Route::get('mercadopago/success', [MercadoPagoController::class, 'success'])
        ->name('mercadopago.success');
    
    // Pagamento pendente
    Route::get('mercadopago/pending', [MercadoPagoController::class, 'pending'])
        ->name('mercadopago.pending');
    
    // Falha no pagamento
    Route::get('mercadopago/failure', [MercadoPagoController::class, 'failure'])
        ->name('mercadopago.failure');
});

// Webhook para notificações do Mercado Pago
Route::post('mercadopago/webhook', [MercadoPagoController::class, 'handleWebhook'])
    ->name('mercadopago.webhook')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
