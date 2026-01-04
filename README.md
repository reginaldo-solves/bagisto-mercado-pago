# Mercado Pago Payment Method for Bagisto

Método de pagamento Mercado Pago para Bagisto com suporte a Pix, Cartão de Crédito e Boleto.

## Environment

Este projeto está configurado para rodar em ambiente Docker (bagisto-docker).

## Comandos Docker Essenciais

### 1. Acessar Container
```bash
docker exec -it e45de18a2adc bash
cd /var/www/html/bagisto
```

### 2. Atualizar Autoload
```bash
composer dump-autoload
```

### 3. Instalar Dependências
```bash
composer require mercadopago/dx-php
```

### 4. Executar Migrations
```bash
php artisan migrate
```

### 5. Limpar Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## Installation

1. Clone este repositório em `packages/Reginaldo/MercadoPago`
2. Execute `composer dump-autoload` dentro do container
3. Instale as dependências: `composer require mercadopago/dx-php`
4. Execute as migrations: `php artisan migrate`
5. Configure as credenciais no painel admin

## Configuration

Acesse o painel admin do Bagisto:
1. Vá para Configurações > Configurações do Sistema > Métodos de Pagamento
2. Ative o Mercado Pago
3. Configure suas credenciais da API
4. Habilite os métodos de pagamento desejados

## Features

- ✅ Pix com QR Code
- ✅ Cartão de Crédito (checkout transparente)
- ✅ Boleto Bancário
- ✅ Webhook para confirmação automática
- ✅ Configurações por canal
- ✅ Suporte multi-canal

## Support

Para suporte e dúvidas, consulte a documentação em `/specs/001-mercado-pago/`.

## License

MIT License
