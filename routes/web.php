<?php

use Illuminate\Support\Facades\Route;
use Webkul\MercadoPago\Http\Controllers\MercadoPagoController;
use Webkul\MercadoPago\Http\Controllers\FrontendController;

// Rotas do Frontend
Route::middleware(['web', 'customer'])->group(function () {
    // Formulário de pagamento
    Route::get('mercadopago/payment', [FrontendController::class, 'showPaymentForm'])
        ->name('mercadopago.payment.form');
    
    // Processar pagamento
    Route::post('mercadopago/payment/process', [FrontendController::class, 'processPayment'])
        ->name('mercadopago.payment.process');
    
    // Obter métodos de pagamento
    Route::post('mercadopago/payment/methods', [FrontendController::class, 'getPaymentMethods'])
        ->name('mercadopago.payment.methods');
    
    // Criar plano recorrente
    Route::post('mercadopago/recurring/plan', [FrontendController::class, 'createRecurringPlan'])
        ->name('mercadopago.recurring.plan');
    
    // Assinar plano
    Route::post('mercadopago/recurring/subscribe', [FrontendController::class, 'subscribe'])
        ->name('mercadopago.recurring.subscribe');
});

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
