# Data Model: Método de Pagamento Mercado Pago

**Created**: 2025-01-03  
**Feature**: Método de Pagamento Mercado Pago  
**Status**: Draft
**Environment**: Docker (bagisto-docker) - Container PHP 8.2 + MySQL 8.0

## Entity Overview

Este documento define as entidades de dados necessárias para implementação do método de pagamento Mercado Pago no Bagisto, seguindo padrões simplificados de pacotes de pagamento.

## Core Entities

### 1. Mercado Pago Payment Data

Dados de transação armazenados conforme padrão Bagisto, usando sistema de configuração core.

**Storage Approach**:
- Configurações: `core_config` table (padrão Bagisto)
- Transações: Usar sistema de transações do Bagisto com metadata
- Webhooks: Log em tabela simples para auditoria

**Configuration Structure**:
```php
// Config/payment.php
return [
    'title' => 'Mercado Pago',
    'description' => 'Pagamento via Mercado Pago',
    'class' => 'Reginaldo\MercadoPago\Payment\MercadoPago',
    'settings' => [
        'access_token' => [
            'type' => 'password',
            'label' => 'Access Token',
            'comment' => 'Token de acesso do Mercado Pago',
        ],
        'enable_pix' => [
            'type' => 'boolean',
            'label' => 'Habilitar Pix',
            'default' => true,
        ],
        'enable_credit_card' => [
            'type' => 'boolean', 
            'label' => 'Habilitar Cartão',
            'default' => true,
        ],
        'enable_boleto' => [
            'type' => 'boolean',
            'label' => 'Habilitar Boleto', 
            'default' => true,
        ],
        'sandbox_mode' => [
            'type' => 'boolean',
            'label' => 'Modo Sandbox',
            'default' => false,
        ],
    ]
];
```

### 2. Webhook Logging

Tabela simples para auditoria de webhooks recebidos.

**Migration**:
```php
Schema::create('mercado_pago_webhooks', function (Blueprint $table) {
    $table->id();
    $table->string('payment_id')->nullable();
    $table->string('event_type');
    $table->json('payload');
    $table->boolean('processed')->default(false);
    $table->timestamps();
    
    $table->index(['processed', 'created_at']);
});
```

**Model**:
```php
class MercadoPagoWebhook extends Model
{
    protected $fillable = [
        'payment_id',
        'event_type', 
        'payload',
        'processed',
    ];
    
    protected $casts = [
        'payload' => 'array',
        'processed' => 'boolean',
    ];
}
```

## Integration with Bagisto Core

### Payment Class Structure

```php
namespace Reginaldo\MercadoPago\Payment;

use Webkul\Payment\Payment\Payment;

class MercadoPago extends Payment
{
    protected $code = 'mercado_pago';
    
    public function getRedirectUrl()
    {
        // Implementar redirecionamento para pagamento
    }
    
    public function redirect()
    {
        // Processar redirecionamento
    }
    
    public function cancel()
    {
        // Cancelar pagamento
    }
    
    public function createPayment($order)
    {
        // Criar pagamento no Mercado Pago
        // Retornar dados específicos do método (QR Code, URL Boleto, etc.)
    }
}
```

### Order Integration

Usar sistema de transações existente do Bagisto com metadata:

```php
// Na criação de pagamento
$transaction = $order->payment()->create([
    'type' => 'mercado_pago',
    'status' => 'pending',
    'transaction_id' => $mercadoPagoId,
    'data' => [
        'payment_method' => 'pix', // ou 'credit_card', 'boleto'
        'qr_code' => $qrCode, // para pix
        'boleto_url' => $boletoUrl, // para boleto
        // outros dados específicos
    ],
]);
```

## Database Schema

### Simplified Migration

```php
// database/migrations/create_mercado_pago_webhooks_table.php
Schema::create('mercado_pago_webhooks', function (Blueprint $table) {
    $table->id();
    $table->string('payment_id')->nullable();
    $table->string('event_type');
    $table->json('payload');
    $table->boolean('processed')->default(false);
    $table->timestamps();
    
    $table->index(['processed', 'created_at']);
    $table->index('payment_id');
});
```

## Data Flow

### Payment Creation Flow
1. Customer selects payment → Payment class called
2. Create Mercado Pago payment via SDK
3. Store transaction in Bagisto system with metadata
4. Return payment details (QR Code, Boleto URL, etc.)

### Webhook Processing Flow  
1. Receive webhook → Log in mercado_pago_webhooks table
2. Find related transaction in Bagisto system
3. Update transaction status
4. Trigger Bagisto order status events

## Configuration Management

### Admin Interface

Usar sistema de configuração do Bagisto:

```php
// Configurações acessíveis via:
// Admin > Configuration > Payment Methods > Mercado Pago

// Valores armazenados em core_config:
// mercadopago.settings.access_token
// mercadopago.settings.enable_pix
// mercadopago.settings.enable_credit_card  
// mercadopago.settings.enable_boleto
// mercadopago.settings.sandbox_mode
```

### Channel Support

Configurações por canal usando sistema do Bagisto:

```php
// Automaticamente disponível por canal através do core_config
// Cada canal pode ter suas próprias configurações
```

## Security Considerations

### Data Protection
- Access Token encrypted no core_config
- Dados sensíveis apenas em metadata da transação
- Webhook logging sem dados sensíveis adicionais

### API Security
- Validar webhook signatures
- Rate limiting em webhook endpoint
- Usar HTTPS em produção

## Performance Considerations

### Simplified Approach
- Menos tabelas = melhor performance
- Usar otimizações existentes do Bagisto
- Cache de configurações nativo

### Indexing
- Índice em webhooks para processamento
- Usar índices existentes do Bagisto para transações

## Testing Strategy

### Simplified Tests
- Test Payment class methods
- Test webhook processing
- Test admin configuration
- Integration tests com Bagisto core

## Conclusion

Modelo de dados simplificado seguindo padrões Bagisto:
- Zero modificações no core
- Usar sistemas existentes quando possível  
- Configurações via core_config
- Mínimas tabelas adicionais
- Integração natural com ecossistema
