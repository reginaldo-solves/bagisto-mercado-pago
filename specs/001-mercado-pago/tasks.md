# Tarefas: Método de Pagamento Mercado Pago

**Entrada**: Documentos de design de `/specs/001-mercado-pago/`
**Pré-requisitos**: plan.md (obrigatório), spec.md (obrigatório para user stories), research.md, data-model.md, contracts/
**Environment**: Docker (bagisto-docker) - Container PHP 8.2 + MySQL 8.0

## Comandos Docker Essenciais (SEMPRE EXECUTAR DENTRO DO CONTAINER)

### 1. Acessar Container
```bash
docker exec -it e45de18a2adc bash
cd /var/www/html/bagisto
```

### 2. Atualizar Autoload (após alterações)
```bash
composer dump-autoload
```

### 3. Limpar Cache (após configurações)
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### 4. Executar Migrations
```bash
php artisan migrate
```

**IMPORTANTE**: Todos os comandos PHP/Composer devem ser executados dentro do container!

**Testes**: Os exemplos abaixo incluem tarefas de teste. Testes são OPCIONAIS - inclua apenas se explicitamente solicitado na especificação da funcionalidade.

**Organização**: Tarefas são agrupadas por user story para permitir implementação e teste independentes de cada story.

## Formato: `[ID] [P?] [Story] Descrição`

- **[P]**: Pode rodar em paralelo (arquivos diferentes, sem dependências)
- **[Story]**: Qual user story esta tarefa pertence (ex: US1, US2, US3)
- Incluir caminhos exatos de arquivos nas descrições

## Convenções de Caminho

- **Pacote Bagisto**: `packages/Reginaldo/MercadoPago/src/`, `packages/Reginaldo/MercadoPago/tests/`
- **Web App**: `backend/src/`, `frontend/src/` 
- **Mobile**: `api/src/`, `ios/src/` ou `android/src/`
- Caminhos mostrados abaixo assumem estrutura de pacote Bagisto - ajuste baseado no plan.md

<!-- 
  ============================================================================
  IMPORTANT: The tasks below are SAMPLE TASKS for illustration purposes only.
  
  The /speckit.tasks command MUST replace these with actual tasks based on:
  - User stories from spec.md (with their priorities P1, P2, P3...)
  - Feature requirements from plan.md
  - Entities from data-model.md
  - Endpoints from contracts/
  
  Tasks MUST be organized by user story so each story can be:
  - Implemented independently
  - Tested independently
  - Delivered as an MVP increment
  
  DO NOT keep these sample tasks in the generated tasks.md file.
  ============================================================================
-->

## Fase 1: Configuração (Infraestrutura Compartilhada)

**Propósito**: Inicialização do projeto e estrutura básica

- [ ] T001 Criar estrutura do pacote em packages/Reginaldo/MercadoPago
- [ ] T002 Criar composer.json para o pacote Mercado Pago
- [ ] T003 [P] Configurar PHPStan, Pint, e Pest PHP testing tools

---

## Fase 2: Fundacional (Pré-requisitos Bloqueantes)

**Propósito**: Infraestrutura core que DEVE ser completa antes que QUALQUER user story possa ser implementada

**⚠️ CRÍTICO**: Nenhum trabalho de user story pode começar até que esta fase seja completa

- [ ] T004 Criar migration para tabela de webhooks do Mercado Pago
- [ ] T005 Criar model MercadoPagoWebhook para auditoria
- [ ] T006 Instalar SDK Mercado Pago via Composer
- [ ] T007 Criar rota webhook para processamento de eventos

**Ponto de Verificação**: Fundação pronta - implementação de user story pode agora começar em paralelo

---

## Fase 3: User Story 1 - Configuração Admin (Prioridade: P1) 🎯 MVP

**Objetivo**: Administrador pode configurar o método de pagamento Mercado Pago no painel admin

**Teste Independente**: Pode ser totalmente testado pelo acesso ao painel admin, configuração das credenciais e seleção dos métodos de pagamento

### Implementação para User Story 1

- [ ] T008 [US1] Criar service provider do pacote em src/Providers/MercadoPagoServiceProvider.php
- [ ] T009 [US1] Criar arquivo de configuração payment.php em src/Config/payment.php
- [ ] T010 [US1] Criar classe de pagamento principal em src/Payment/MercadoPago.php
- [ ] T011 [US1] Implementar método getRedirectUrl() para redirecionamento
- [ ] T012 [US1] Implementar método cancel() para cancelamento
- [ ] T013 [US1] Criar views admin em src/Resources/views/admin/
- [ ] T014 [US1] Implementar integração com sistema de configuração do Bagisto

**Ponto de Verificação**: Neste ponto, User Story 1 deve ser totalmente funcional e testável independentemente

---

## Fase 4: User Story 2 - Processamento Pix (Prioridade: P1)

**Objetivo**: Cliente pode selecionar Pix, gerar QR Code e confirmar pagamento via webhook

**Teste Independente**: Pode ser totalmente testado pela simulação de checkout completo com Pix e confirmação via webhook

### Implementação para User Story 2

- [ ] T015 [P] [US2] Criar controller webhook em src/Http/Controllers/WebhookController.php
- [ ] T016 [US2] Implementar método createPayment() para Pix na classe Payment
- [ ] T017 [US2] Integrar API Mercado Pago para geração de QR Code Pix
- [ ] T018 [US2] Implementar processamento de webhook para confirmação Pix
- [ ] T019 [US2] Criar view shop para exibição do QR Code
- [ ] T020 [US2] Implementar atualização de status do pedido via webhook

**Ponto de Verificação**: Neste ponto, User Stories 1 E 2 devem ambas funcionar independentemente

---

## Fase 5: User Story 3 - Processamento Cartão de Crédito (Prioridade: P2)

**Objetivo**: Cliente pode pagar com cartão usando checkout transparente do Mercado Pago

**Teste Independente**: Pode ser totalmente testado pela simulação de pagamento com cartão em ambiente teste

### Implementação para User Story 3

- [ ] T021 [P] [US3] Implementar método createPayment() para Cartão na classe Payment
- [ ] T022 [US3] Integrar API Mercado Pago para processamento de cartão
- [ ] T023 [US3] Implementar checkout transparente para cartão de crédito
- [ ] T024 [US3] Criar view shop para formulário de cartão seguro
- [ ] T025 [US3] Implementar tratamento de erros de pagamento
- [ ] T026 [US3] Implementar suporte a parcelas

**Ponto de Verificação**: Neste ponto, User Stories 1, 2 E 3 devem funcionar independentemente

---

## Fase 6: User Story 4 - Processamento Boleto (Prioridade: P3)

**Objetivo**: Cliente pode gerar boleto via Mercado Pago e confirmar pagamento

**Teste Independente**: Pode ser totalmente testado pela geração de boleto e simulação de confirmação

### Implementação para User Story 4

- [ ] T027 [P] [US4] Implementar método createPayment() para Boleto na classe Payment
- [ ] T028 [US4] Integrar API Mercado Pago para geração de boleto
- [ ] T029 [US4] Implementar download do PDF do boleto
- [ ] T030 [US4] Criar view shop para exibição do boleto
- [ ] T031 [US4] Implementar processamento de webhook para confirmação de boleto
- [ ] T032 [US4] Implementar cálculo de data de vencimento

**Ponto de Verificação**: Todas as user stories devem agora funcionar independentemente

---

## Fase 7: Polimento & Preocupações Transversais

**Propósito**: Melhorias que afetam múltiplas user stories

- [ ] T033 [P] Criar testes unitários para classe Payment
- [ ] T034 [P] Criar testes de integração para API Mercado Pago
- [ ] T035 [P] Criar testes de webhook processing
- [ ] T036 [P] Implementar logging para auditoria
- [ ] T037 [P] Otimizar performance e cache de configurações
- [ ] T038 [P] Implementar validação de assinatura de webhook
- [ ] T039 [P] Criar documentação README.md
- [ ] T040 [P] Validar compliance PSR-12 e type hints

---

## Dependências & Ordem de Execução

### Dependências de Fase

- **Configuração (Fase 1)**: Sem dependências - pode começar imediatamente
- **Fundacional (Fase 2)**: Depende de Configuração completion - BLOQUEIA todas as user stories
- **User Stories (Fase 3+)**: Todas dependem de Fundacional completion
  - User stories podem então prosseguir em paralelo (se staffed)
  - Ou sequencialmente em ordem de prioridade (P1 → P2 → P3 → P4)
- **Polimento (Fase Final)**: Depende de todas as user stories desejadas completas

### Dependências de User Story

- **User Story 1 (P1)**: Pode começar após Fundacional - Sem dependências de outras stories
- **User Story 2 (P1)**: Pode começar após Fundacional - Pode integrar com US1 mas deve ser independentemente testável
- **User Story 3 (P2)**: Pode começar após Fundacional - Pode integrar com US1/US2 mas deve ser independentemente testável
- **User Story 4 (P3)**: Pode começar após Fundacional - Pode integrar com US1/US2/US3 mas deve ser independentemente testável

### Dentro de Cada User Story

- Models antes de services
- Services antes de controllers
- Core implementation antes de integração
- Story completa antes de mover para próxima prioridade

### Oportunidades de Paralelo

- Todas as tarefas Setup marcadas [P] podem rodar em paralelo
- Todas as tarefas Fundacionais marcadas [P] podem rodar em paralelo (dentro da Fase 2)
- Uma vez que Fundacional estiver completa, todas as user stories podem começar em paralelo (se capacidade da equipe permitir)
- Todas as tarefas de teste para uma user story marcadas [P] podem rodar em paralelo
- Diferentes user stories podem ser trabalhadas em paralelo por diferentes membros da equipe

---

## Exemplo Paralelo: User Story 2

```bash
# Lançar todas as tarefas de User Story 2 juntas:
Task: "Criar controller webhook em src/Http/Controllers/WebhookController.php"
Task: "Implementar método createPayment() para Pix na classe Payment"
Task: "Criar view shop para exibição do QR Code"
```

---

## Estratégia de Implementação

### MVP First (Apenas User Story 1)

1. Completar Fase 1: Configuração
2. Completar Fase 2: Fundacional (CRÍTICO - bloqueia todas as stories)
3. Completar Fase 3: User Story 1
4. **PARAR E VALIDAR**: Testar User Story 1 independentemente
5. Deploy/demo se pronto

### Entrega Incremental

1. Completar Configuração + Fundacional → Fundação pronta
2. Adicionar User Story 1 → Testar independentemente → Deploy/Demo (MVP!)
3. Adicionar User Story 2 → Testar independentemente → Deploy/Demo
4. Adicionar User Story 3 → Testar independentemente → Deploy/Demo
5. Adicionar User Story 4 → Testar independentemente → Deploy/Demo
6. Cada story adiciona valor sem quebrar stories anteriores

### Estratégia de Equipe Paralela

Com múltiplos desenvolvedores:

1. Equipe completa Configuração + Fundacional juntas
2. Uma vez que Fundacional estiver pronto:
   - Desenvolvedor A: User Story 1
   - Desenvolvedor B: User Story 2  
   - Desenvolvedor C: User Story 3 + 4
3. Stories completam e integram independentemente

---

## Notas

- [P] tarefas = arquivos diferentes, sem dependências
- [Story] label mapeia tarefa para user story específica para rastreabilidade
- Cada user story deve ser independentemente completável e testável
- Verificar testes falham antes de implementar
- Commit após cada tarefa ou grupo lógico
- Parar em qualquer ponto de verificação para validar story independentemente
- Evitar: tarefas vagas, conflitos no mesmo arquivo, dependências cross-story que quebram independência
