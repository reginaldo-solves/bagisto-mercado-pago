# Mercado Pago Payment Method for Bagisto

Método de pagamento Mercado Pago para Bagisto com suporte a Pix, Cartão de Crédito e Boleto.

## 🚀 Funcionalidades

- ✅ **Pix** - Pagamento instantâneo via QR Code
- ✅ **Cartão de Crédito** - Processamento seguro de cartões
- ✅ **Boleto** - Geração de boletos bancários
- ✅ **Painel Admin** - Configuração completa via interface
- ✅ **Webhooks** - Notificações em tempo real
- ✅ **Multi-canal** - Suporte a múltiplos canais
- ✅ **Multi-idioma** - Inglês e Português (pt-BR)

## 📋 Instalação

### 1. Instalar o Pacote via Composer
```bash
composer require reginaldo-solves/bagisto-mercado-pago
```

### 2. Publicar e Executar as Migrations
```bash
php artisan migrate
```

### 3. Limpar Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```
E pronto! O método de pagamento estará disponível para configuração no painel de administração.

## ⚙️ Configuração

### 1. Acessar Painel Admin
```
http://localhost/admin/configuration/sales/payment_methods
```

### 2. Configurar Mercado Pago
Preencha os seguintes campos:

| Campo | Descrição | Onde Encontrar |
|-------|-----------|---------------|
| **Título** | Nome visível para clientes | `Mercado Pago` |
| **Descrição** | Texto explicativo | `Pague com Mercado Pago - Aceitamos Pix, Cartão de Crédito e Boleto` |
| **Chave Pública** | Chave pública para API | [Mercado Pago Developers](https://mercadopago.com.br/developers) > Credenciais |
| **Token de Acesso** | Token privado para API | [Mercado Pago Developers](https://mercadopago.com.br/developers) > Credenciais |
| **URL para Notificações** | Webhook para confirmações | `https://sualoja.com/mercado-pago/webhook` |
| **Status** | Ativar/desativar método | ✅ Ativado |
| **Ambiente de Teste** | Sandbox vs Produção | ✅ Ativado (para testes) |

### 3. Configurar Webhook no Mercado Pago
1. Acesse [mercadopago.com.br/developers](https://mercadopago.com.br/developers)
2. Vá em "Webhooks"
3. Configure a URL: `https://sualoja.com/mercado-pago/webhook`
4. Selecione os eventos: `payment_created`, `payment_updated`

## 🐛 Troubleshooting

**Método não aparece no admin:**
1. Verifique se o pacote foi instalado corretamente via Composer.
2. Limpe o cache do Bagisto: `php artisan config:clear` e `php artisan cache:clear`.

**Webhook não funciona:**
1. Verifique se a URL está acessível publicamente e usa HTTPS.
2. Monitore os logs de erro do Laravel (`storage/logs/laravel.log`).

## 🤝 Contribuição

Contribuições são bem-vindas! Sinta-se à vontade para abrir uma issue ou enviar um pull request.

## 📄 Licença

MIT License - veja o arquivo [LICENSE](LICENSE) para detalhes.
