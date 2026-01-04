# Plano de Implementação: Método de Pagamento Mercado Pago

**Branch**: `001-mercado-pago` | **Date**: 2025-01-03 | **Spec**: [spec.md](spec.md)
**Input**: Especificação de funcionalidade de `/specs/001-mercado-pago/spec.md`
**Environment**: Docker (bagisto-docker) - Container PHP 8.2 + MySQL 8.0

**Note**: This template is filled in by the `/speckit.plan` command. See `.specify/templates/commands/plan.md` for the execution workflow.

## Summary

Criar um método de pagamento Mercado Pago para Bagisto como pacote independente em `packages/Reginaldo/MercadoPago`, suportando Pix, Cartão de Crédito e Boleto via API oficial do Mercado Pago Brasil, com configurações admin e zero modificações no core do Bagisto.

## Technical Context

**Language/Version**: PHP 8.2+  
**Primary Dependencies**: Laravel 11.x, Mercado Pago SDK PHP, Guzzle HTTP  
**Storage**: MySQL 8.0+ (para configurações e transações)  
**Testing**: Pest PHP, PHPUnit para código legado  
**Target Platform**: Linux server (Bagisto e-commerce)  
**Project Type**: Pacote de pagamento modular  
**Performance Goals**: <200ms p95 tempo de resposta API, <50ms processamento webhook  
**Constraints**: Zero modificações no core Bagisto, compliance PSR-12, compatibilidade com atualizações  
**Scale/Scope**: Suporte a múltiplos canais de venda, configurações por canal

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

### Verificação de Princípios

✅ **I. Arquitetura Modular**: Pacote independente em `packages/Reginaldo/MercadoPago`  
✅ **II. Fundação Laravel Framework**: Usa service container, middleware, convenções Laravel  
✅ **III. Foco em Domínio E-Commerce**: Método de pagamento para e-commerce brasileiro  
✅ **IV. Suporte Multi-Canal**: Configurações por canal, APIs agnósticas  
✅ **V. Extensibilidade & Customização**: Sem modificações core, sobrescritível via configuração  
✅ **VI. Integração de Pagamentos**: Segue documentação oficial, API oficial Mercado Pago  
✅ **VII. Padrão de Idioma pt-BR**: Todo conteúdo em Português Brasil

### Portas de Qualidade

✅ **PSR-12**: Padrões de codificação seguidos  
✅ **Type Hints**: Obrigatórios em assinaturas de métodos  
✅ **PHPDoc**: Blocos obrigatórios para APIs públicas  
✅ **Testes**: Cobertura mínima 80% para novas funcionalidades  
✅ **Migrations**: Reversíveis com foreign key constraints

### Reavaliação Pós-Design

Após Phase 1 design, todos os princípios continuam atendidos:

✅ **Design Modular**: Estrutura clara de pacote com responsabilidades separadas  
✅ **Laravel Integration**: Service provider, migrations, models seguindo padrões Laravel  
✅ **E-commerce Focus**: Entidades e fluxos otimizados para pagamentos brasileiros  
✅ **Multi-channel Support**: Configurações e transações com suporte a canal_id  
✅ **Extensibility**: Configurações via core_config, eventos Laravel para integração  
✅ **Payment Integration**: SDK oficial Mercado Pago, API contracts definidos  
✅ **pt-BR Compliance**: Documentação e código em Português Brasil  

**Status**: ✅ APROVADO - Nenhuma violação identificada

## Project Structure

### Documentation (this feature)

```text
specs/001-mercado-pago/
├── plan.md              # This file (/speckit.plan command output)
├── research.md          # Phase 0 output (/speckit.plan command)
├── data-model.md        # Phase 1 output (/speckit.plan command)
├── quickstart.md        # Phase 1 output (/speckit.plan command)
├── contracts/           # Phase 1 output (/speckit.plan command)
└── tasks.md             # Phase 2 output (/speckit.tasks command - NOT created by /speckit.plan)
```

### Source Code (Bagisto Payment Package)

```text
packages/
└── Reginaldo/
    └── MercadoPago/
        ├── src/
        │   ├── Providers/MercadoPagoServiceProvider.php
        │   ├── Payment/MercadoPago.php
        │   ├── Http/Controllers/
        │   │   └── WebhookController.php
        │   ├── Config/payment.php
        │   └── Resources/views/
        │       ├── admin/
        │       │   └── payment.blade.php
        │       └── shop/
        │           └── payment.blade.php
        ├── database/
        │   └── migrations/
        │       ├── create_mercado_pago_transactions_table.php
        │       └── create_mercado_pago_webhooks_table.php
        ├── tests/
        │   ├── Payment/MercadoPagoTest.php
        │   └── Webhook/WebhookTest.php
        ├── composer.json
        └── routes/web.php
```

**Structure Decision**: Estrutura padrão de pacote de pagamento Bagisto em `packages/Reginaldo/MercadoPago/` seguindo documentação oficial, com simplicidade e zero modificações em `app/` ou `vendor/`. Apenas os componentes essenciais para método de pagamento.

## Phase 0: Outline & Research

### Unknowns from Technical Context

Nenhum NEEDS CLARIFICATION identificado - contexto técnico completo baseado na especificação e constituição Bagisto.

### Research Tasks

1. **Mercado Pago SDK PHP**: Investigar SDK oficial para integração com APIs Brasil
2. **Bagisto Payment Architecture**: Estudar padrões de implementação de métodos de pagamento existentes
3. **Webhook Processing Patterns**: Melhores práticas para processamento de webhooks em Laravel
4. **PSR-12 Compliance**: Padrões específicos para pacotes Laravel
5. **Multi-channel Configuration**: Como implementar configurações por canal em Bagisto

### Docker Environment Setup

**IMPORTANTE**: O projeto está configurado para rodar em ambiente Docker. Sempre execute os comandos dentro do container:

```bash
# Acessar container
docker exec -it e45de18a2adc bash
cd /var/www/html/bagisto

# Atualizar autoload após alterações
composer dump-autoload

# Limpar cache após configurações
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Research Findings

#### Mercado Pago SDK PHP
**Decision**: Usar SDK oficial `mercadopago/dx-php`  
**Rationale**: SDK mantido pelo Mercado Pago, suporte completo para APIs Brasil, documentação extensa  
**Alternatives considered**: Guzzle HTTP direto (mais trabalho, menos manutenível), SDKs de terceiros (não oficiais)

#### Bagisto Payment Architecture  
**Decision**: Seguir estrutura padrão de pacotes de pagamento do Bagisto  
**Rationale**: Consistência com ecossistema, compatibilidade garantida, documentação oficial disponível  
**Alternatives considered**: Estrutura customizada (quebraria convenções, mais complexo manter)

#### Webhook Processing Patterns
**Decision**: Usar Laravel Queue + Events para processamento assíncrono  
**Rationale**: Performance melhor, retry automático, logging integrado  
**Alternatives considered**: Processamento síncrono (risco de timeouts, baixa performance)

#### PSR-12 Compliance
**Decision**: Configurar PHPStan + Pint no pipeline do pacote  
**Rationale**: Garantia de qualidade, integração com ferramentas existentes do Bagisto  
**Alternatives considered**: Manual compliance (propenso a erros, difícil manter)

#### Multi-channel Configuration
**Decision**: Usar sistema de configuração do Bagisto com escopo por canal  
**Rationale**: Nativo do framework, suporte existente, admin interface integrada  
**Alternatives considered**: Configuração customizada (reinvenção da roda, mais complexidade)
