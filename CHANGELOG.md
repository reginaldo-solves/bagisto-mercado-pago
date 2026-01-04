# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-01-04

### Adicionado
- ✅ Método de pagamento Mercado Pago completo
- ✅ Suporte a Pix, Cartão de Crédito e Boleto
- ✅ Painel administrativo completo
- ✅ Sistema de webhooks com validação
- ✅ Multi-canal e multi-idioma
- ✅ Instalador automático
- ✅ Documentação completa

### Funcionalidades
- **Pagamento**: Integração completa com API do Mercado Pago
- **Configuração**: Interface admin com todos os campos necessários
- **Webhooks**: Processamento automático de notificações
- **Traduções**: Inglês e Português (pt-BR)
- **Instalação**: Automática e manual

### Arquivos
- `src/Payment/MercadoPago.php` - Classe principal de pagamento
- `src/Http/Controllers/MercadoPagoController.php` - Controller
- `src/Models/MercadoPagoWebhook.php` - Model para webhooks
- `src/Config/paymentmethods.php` - Configuração do método
- `src/Providers/MercadoPagoServiceProvider.php` - Service Provider
- `database/migrations/` - Migrações para webhooks
- `src/Resources/views/` - Templates Blade
- `src/Resources/lang/` - Traduções
- `install.php` - Instalador automático
- `INSTALLATION.md` - Guia de instalação

### Compatibilidade
- **Bagisto**: 2.3+
- **Laravel**: 11.x
- **PHP**: 8.2+
- **Mercado Pago SDK**: dx-php

### Instalação
```bash
# Automática (recomendada)
curl -sS https://raw.githubusercontent.com/reginaldo-solves/bagisto-mercado-pago/main/install.php | php

# Via Composer
composer require reginaldo-solves/bagisto-mercado-pago
```

### Documentação
- README completo com exemplos
- Guia de configuração detalhado
- Troubleshooting e suporte
- Estrutura de arquivos documentada

---

## [Próximas Versões]

### [1.1.0] - Planejado
- Testes automatizados com Pest
- Melhorias na interface admin
- Suporte a mais moedas
- Otimizações de performance

### [1.2.0] - Planejado
- Interface de checkout aprimorada
- Suporte a assinaturas recorrentes
- Relatórios de transações
- Dashboard de métricas
