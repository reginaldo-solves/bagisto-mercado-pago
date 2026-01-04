# Quickstart: Método de Pagamento Mercado Pago

**Created**: 2025-01-03  
**Feature**: Método de Pagamento Mercado Pago  
**Status**: Ready for Implementation
**Environment**: Docker (bagisto-docker) - Container PHP 8.2 + MySQL 8.0

## Overview

Este guia rápido fornece instruções passo a passo para configurar e usar o método de pagamento Mercado Pago no Bagisto.

## Prerequisites

- Bagisto v2.3+ instalado e configurado
- PHP 8.2+ com extensões necessárias
- Conta Mercado Pago Brasil com Access Token
- Docker e Docker Compose instalados
- Container Docker bagisto-docker em execução

## Comandos Docker Essenciais

### 1. Acessar Container PHP-FPM
```bash
docker exec -it e45de18a2adc bash
```

### 2. Navegar para o Projeto
```bash
cd /var/www/html/bagisto
```

### 3. Atualizar Autoload
```bash
composer dump-autoload
```

### 4. Instalar Dependências
```bash
composer require mercadopago/dx-php
```

### 5. Executar Migrations
```bash
php artisan migrate
```

### 6. Limpar Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```
- Permissões de administrador no Bagisto

## Installation

### 1. Instalar o Pacote

```bash
# Adicionar ao composer.json do projeto Bagisto
"require": {
    "reginaldo/mercado-pago": "1.0.0"
}

# Instalar via Composer
composer update reginaldo/mercado-pago

# Publicar assets e migrations
php artisan vendor:publish --provider="Reginaldo\MercadoPago\MercadoPagoServiceProvider"
php artisan migrate
```

### 2. Configurar o Pacote

```bash
# Limpar cache
php artisan config:clear
php artisan cache:clear
```

## Configuration

### 1. Obter Access Token do Mercado Pago

1. Acesse [Mercado Pago Developers](https://www.mercadopago.com.br/developers)
2. Faça login com sua conta
3. Vá para "Credenciais" > "Produção" ou "Teste"
4. Copie o "Access Token"

### 2. Configurar no Admin Bagisto

1. Acesse o painel administrativo do Bagisto
2. Navegue para **Configurações > Métodos de Pagamento**
3. Encontre **Mercado Pago** e clique em **Configurar**
4. Preencha as configurações:

#### Configurações Gerais
- **Status**: Ativado
- **Access Token**: Cole o token obtido
- **Modo Sandbox**: Ative para testes

#### Configurações Pix
- **Habilitar Pix**: Marque para ativar
- **Tempo de Expiração**: 30 minutos (padrão)

#### Configurações Cartão de Crédito
- **Habilitar Cartão**: Marque para ativar
- **Máximo de Parcelas**: 12 (padrão)

#### Configurações Boleto
- **Habilitar Boleto**: Marque para ativar
- **Dias para Vencimento**: 3 dias (padrão)

5. Clique em **Salvar**

## Usage

### Para Clientes

#### Pagamento Pix
1. No checkout, selecione **Pix**
2. Confirme o pedido
3. Escaneie o QR Code com app bancário
4. Pague via Pix
5. Pedido confirmado automaticamente

#### Pagamento Cartão de Crédito
1. No checkout, selecione **Cartão de Crédito**
2. Preencha dados do cartão seguro
3. Escolha número de parcelas
4. Confirme pagamento
5. Pedido processado imediatamente

#### Pagamento Boleto
1. No checkout, selecione **Boleto**
2. Confirme o pedido
3. Imprima ou salve o boleto
4. Pague em banco ou lotérica
5. Pedido confirmado após pagamento

### Para Administradores

#### Monitorar Transações
1. Acesse **Vendas > Pedidos**
2. Filtre por método de pagamento "Mercado Pago"
3. Veja status e detalhes das transações

#### Configurações por Canal
1. Acesse **Configurações > Canais**
2. Selecione o canal desejado
3. Configure Mercado Pago específico para o canal

## Testing

### Ambiente de Teste

1. Configure **Modo Sandbox** nas configurações
2. Use Access Token de teste do Mercado Pago
3. Teste todos os métodos de pagamento

### Testes Automáticos

```bash
# Rodar testes do pacote
cd packages/Reginaldo/MercadoPago
./vendor/bin/pest

# Testes de integração
./vendor/bin/pest --testsuite=Integration

# Testes de feature
./vendor/bin/pest --testsuite=Feature
```

### Cenários de Teste

#### Pix
- Gerar QR Code
- Simular pagamento via webhook
- Verificar confirmação automática

#### Cartão de Crédito
- Processar pagamento aprovado
- Processar pagamento recusado
- Testar diferentes parcelas

#### Boleto
- Gerar boleto
- Simular pagamento via webhook
- Verificar data de vencimento

## Troubleshooting

### Problemas Comuns

#### Access Token Inválido
**Erro**: "Invalid access token"
**Solução**: Verifique o token nas configurações e garanta que é o token correto (produção vs teste)

#### Webhook Não Recebido
**Erro**: Pagamentos não confirmados automaticamente
**Solução**: 
1. Verifique se URL do webhook está configurada no Mercado Pago
2. Confirme se firewall não bloqueia requisições
3. Verifique logs de erros do Laravel

#### Pagamento Recusado
**Erro**: "Payment rejected"
**Solução**: Verifique limites da conta Mercado Pago e dados do cliente

### Logs

```bash
# Verificar logs de pagamento
tail -f storage/logs/laravel.log | grep "mercado_pago"

# Logs de webhooks
tail -f storage/logs/laravel.log | grep "webhook"

# Logs de erros
tail -f storage/logs/laravel.log | grep "error"
```

### Debug Mode

```php
// Ativar debug nas configurações
'mercadopago.debug' => true,

// Verificar configurações
php artisan tinker
>>> config('mercadopago');
```

## Security

### Best Practices
- Use HTTPS em produção
- Mantenha Access Token seguro
- Configure rate limiting para webhooks
- Monitore transações suspeitas

### Compliance
- Dados de cartão nunca armazenados
- Tokens do Mercado Pago usados apenas
- Logs de auditoria mantidos
- Conformidade com LGPD

## Performance

### Otimizações
- Cache de configurações ativo
- Queue system para webhooks
- Índices otimizados no database
- Connection pooling para API calls

### Monitoramento
- Tempo de resposta API < 200ms
- Webhooks processados < 30s
- Taxa de sucesso > 95%

## Support

### Documentação Adicional
- [Documentação Oficial Bagisto](https://devdocs.bagisto.com/)
- [Documentação Mercado Pago](https://www.mercadopago.com.br/developers)
- [Guia de Integração](docs/integration.md)

### Contato
- Issues: GitHub repository
- Email: support@reginaldo.dev
- Comunidade: [Bagisto Forum](https://forums.bagisto.com/)

## Next Steps

1. Configure ambiente de teste
2. Teste todos os métodos de pagamento
3. Configure webhooks
4. Monitore performance
5. Prepare para produção

---

**Nota**: Este guia assume familiaridade básica com Bagisto e administração de sistemas Laravel. Para suporte adicional, consulte a documentação completa ou entre em contato com o suporte.
