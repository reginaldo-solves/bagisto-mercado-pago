# Mercado Pago Payment Method for Bagisto

Método de pagamento Mercado Pago para Bagisto com suporte a Pix, Cartão de Crédito e Boleto.

## 📦 Estrutura do Pacote

Este pacote está localizado em `packages/Webkul/MercadoPago/` seguindo a estrutura padrão do Bagisto para métodos de pagamento.

## 🚀 Funcionalidades

- ✅ **Pix** - Pagamento instantâneo via QR Code
- ✅ **Cartão de Crédito** - Processamento seguro de cartões
- ✅ **Boleto** - Geração de boletos bancários
- ✅ **Painel Admin** - Configuração completa via interface
- ✅ **Webhooks** - Notificações em tempo real
- ✅ **Multi-canal** - Suporte a múltiplos canais
- ✅ **Multi-idioma** - Inglês e Português (pt-BR)
- ✅ **Docker Ready** - Ambiente configurado para Docker

## 🐳 Ambiente Docker

Este projeto está configurado para rodar em ambiente Docker (bagisto-docker).

### Comandos Docker Essenciais

#### 1. Acessar Container
```bash
docker exec -it e45de18a2adc bash
cd /var/www/html/bagisto
```

#### 2. Atualizar Autoload
```bash
composer dump-autoload
```

#### 3. Instalar Dependências
```bash
composer require mercadopago/dx-php
```

#### 4. Executar Migrations
```bash
php artisan migrate --path=packages/Webkul/MercadoPago/database/migrations
```

#### 5. Limpar Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## ⚠️ **IMPORTANTE: Modificações no Bagisto**

Este pacote requer modificações nos arquivos core do Bagisto para funcionar corretamente.

### 📋 **Arquivos Modificados:**
- `composer.json` - Autoload PSR-4
- `bootstrap/providers.php` - Service Provider
- `packages/Webkul/Admin/src/Config/system.php` - Configuração admin
- `packages/Webkul/Admin/src/Resources/lang/*/app.php` - Traduções

### 🚀 **Métodos de Instalação:**

#### **Opção 1: Instalação Automática (Recomendada)**
```bash
# Baixar e executar installer
curl -sS https://raw.githubusercontent.com/reginaldo-solves/bagisto-mercado-pago/main/install.php | php

# Ou manualmente
cd packages/Webkul/MercadoPago
php install.php
```

#### **Opção 2: Via Composer (Modificado)**
```bash
# Exigir pacote com auto-instalação
composer require reginaldo-solves/bagisto-mercado-pago

# Executar pós-instalação
php artisan mercadopago:install
```

#### **Opção 3: Manual**
Veja [INSTALLATION.md](INSTALLATION.md) para instruções detalhadas.

### 🔧 **Contorno para Instalação Limpa:**

Se você precisa instalar em uma instância limpa do Bagisto sem modificações:

1. **Use o installer automático** (Opção 1)
2. **Baixe o pacote completo** com as modificações
3. **Execute as migrações** manualmente

## 📋 Instalação

### 1. Clonar o Pacote
```bash
# Opção A: Com auto-instalação
git clone https://github.com/reginaldo-solves/bagisto-mercado-pago.git packages/Webkul/MercadoPago

# Opção B: Manual (se já tem as modificações)
cp -r /path/to/bagisto-mercado-pago /var/www/html/bagisto/packages/Webkul/MercadoPago
```

### 2. Configurar Autoload
Adicionar ao `composer.json` principal:
```json
"autoload": {
    "psr-4": {
        "Webkul\\MercadoPago\\": "packages/Webkul/MercadoPago/src/"
    }
}
```

### 3. Registrar ServiceProvider
Adicionar ao `bootstrap/providers.php`:
```php
Webkul\MercadoPago\Providers\MercadoPagoServiceProvider::class,
```

### 4. Instalar Dependências
```bash
composer require mercadopago/dx-php
composer dump-autoload
```

### 5. Executar Migrations
```bash
php artisan migrate --path=packages/Webkul/MercadoPago/database/migrations
```

### 6. Limpar Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

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
| **Chave Pública do Mercado Pago** | Chave pública para API | [Mercado Pago Developers](https://mercadopago.com.br/developers) > Credenciais |
| **Token de Acesso do Mercado Pago** | Token privado para API | [Mercado Pago Developers](https://mercadopago.com.br/developers) > Credenciais |
| **URL para Notificações** | Webhook para confirmações | `https://sualoja.com/mercado-pago/webhook` |
| **Chave Secreta do Webhook** | Segurança adicional | Opcional, gerado no painel MP |
| **Status** | Ativar/desativar método | ✅ Ativado |
| **Ambiente de Teste** | Sandbox vs Produção | ✅ Ativado (para testes) |

### 3. Configurar Webhook no Mercado Pago
1. Acesse [mercadopago.com.br/developers](https://mercadopago.com.br/developers)
2. Vá em "Webhooks"
3. Configure a URL: `https://sualoja.com/mercado-pago/webhook`
4. Selecione os eventos:
   - payment_approved
   - payment_rejected
   - payment_pending

## 🏗️ Estrutura de Arquivos

```
packages/Webkul/MercadoPago/
├── README.md                           # Este arquivo
├── CONFIGURATION_GUIDE.md              # Guia detalhado de configuração
├── composer.json                       # Dependências e autoload
├── database/
│   └── migrations/
│       └── 2025_01_03_000000_create_mercadopago_webhooks_table.php
├── routes/
│   └── web.php                         # Rotas do pacote
└── src/
    ├── Config/
    │   └── paymentmethods.php          # Configuração do método
    ├── Http/Controllers/
    │   └── MercadoPagoController.php    # Controller principal
    ├── Models/
    │   └── MercadoPagoWebhook.php      # Model para webhooks
    ├── Payment/
    │   └── MercadoPago.php             # Classe de pagamento
    ├── Providers/
    │   └── MercadoPagoServiceProvider.php # Service Provider
    └── Resources/
        ├── lang/
        │   ├── en/messages.php         # Traduções inglês
        │   └── pt_BR/messages.php      # Traduções português
        └── views/
            └── payment/
                ├── form.blade.php      # Formulário de pagamento
                ├── success.blade.php   # Página de sucesso
                ├── pending.blade.php   # Página de pendente
                └── failure.blade.php   # Página de falha
```

## 🔧 Desenvolvimento

### Requisitos
- PHP 8.2+
- Laravel 11.x
- Bagisto 2.3+
- Docker & Docker Compose
- Conta Mercado Pago Brasil

### SDK Utilizado
- **mercadopago/dx-php** - SDK oficial do Mercado Pago

### Testes
```bash
# Executar testes (quando implementados)
./vendor/bin/pest
```

## 📚 Documentação

- [Guia de Configuração](CONFIGURATION_GUIDE.md) - Configuração detalhada
- [Documentação Mercado Pago](https://mercadopago.com.br/developers) - API oficial
- [Documentação Bagisto](https://devdocs.bagisto.com/) - Framework

## 🐛 Troubleshooting

### Problemas Comuns

**Método não aparece no admin:**
1. Verifique se o ServiceProvider está registrado
2. Execute `composer dump-autoload`
3. Limpe o cache: `php artisan config:clear`

**Webhook não funciona:**
1. Verifique se a URL está acessível publicamente
2. Confirme se usa HTTPS
3. Verifique logs de erro do Laravel

**Pagamento falha:**
1. Confirme as credenciais (sandbox vs produção)
2. Verifique se o SDK está instalado
3. Monitore os logs de erro

## 🤝 Contribuição

1. Fork o repositório
2. Crie uma branch: `git checkout -b feature/nova-funcionalidade`
3. Commit suas mudanças: `git commit -m 'Add nova funcionalidade'`
4. Push: `git push origin feature/nova-funcionalidade`
5. Pull Request

## 📄 Licença

MIT License - veja arquivo [LICENSE](LICENSE) para detalhes.

## 🔗 Links Úteis

- [Repositório GitHub](https://github.com/reginaldo-solves/bagisto-mercado-pago)
- [Mercado Pago Brasil](https://mercadopago.com.br)
- [Bagisto E-commerce](https://bagisto.com)
- [Documentação Bagisto](https://devdocs.bagisto.com)

## 📞 Suporte

- **Issues**: [GitHub Issues](https://github.com/reginaldo-solves/bagisto-mercado-pago/issues)
- **Email**: reginaldo.solves@gmail.com
- **Discord**: [Comunidade Bagisto](https://discord.gg/bagisto)

---

**⭐ Se este pacote foi útil, deixe uma estrela no GitHub!**

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
