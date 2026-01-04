---

description: "Template de lista de tarefas para implementação de funcionalidade"
---

# Tarefas: [NOME DA FUNCIONALIDADE]

**Entrada**: Documentos de design de `/specs/[###-feature-name]/`
**Pré-requisitos**: plan.md (obrigatório), spec.md (obrigatório para user stories), research.md, data-model.md, contracts/

**Testes**: Os exemplos abaixo incluem tarefas de teste. Testes são OPCIONAIS - inclua apenas se explicitamente solicitado na especificação da funcionalidade.

**Organização**: Tarefas são agrupadas por user story para permitir implementação e teste independentes de cada story.

## Formato: `[ID] [P?] [Story] Descrição`

- **[P]**: Pode rodar em paralelo (arquivos diferentes, sem dependências)
- **[Story]**: Qual user story esta tarefa pertence (ex: US1, US2, US3)
- Incluir caminhos exatos de arquivos nas descrições

## Convenções de Caminho

- **Pacote Bagisto**: `packages/Webkul/[PackageName]/src/`, `packages/Webkul/[PackageName]/tests/`
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

- [ ] T001 Criar estrutura do pacote conforme plano de implementação
- [ ] T002 Inicializar pacote Bagisto com Laravel service provider
- [ ] T003 [P] Configurar PHPStan, Pint, e Pest PHP testing tools

---

## Fase 2: Fundacional (Pré-requisitos Bloqueantes)

**Propósito**: Infraestrutura core que DEVE ser completa antes que QUALQUER user story possa ser implementada

**⚠️ CRÍTICO**: Nenhum trabalho de user story pode começar até que esta fase seja completa

Exemplos de tarefas fundacionais (ajuste baseado no seu projeto):

- [ ] T004 Setup database schema and Laravel migrations in packages/Webkul/[PackageName]/database/migrations/
- [ ] T005 [P] Implement Laravel authentication/authorization middleware
- [ ] T006 [P] Setup API routes and Laravel middleware structure
- [ ] T007 Create Eloquent models that all stories depend on
- [ ] T008 Configure error handling and Laravel logging infrastructure
- [ ] T009 Setup Laravel environment configuration management

**Ponto de Verificação**: Fundação pronta - implementação de user story pode agora começar em paralelo

---

## Fase 3: User Story 1 - [Título] (Prioridade: P1) 🎯 MVP

**Objetivo**: [Breve descrição do que esta story entrega]

**Teste Independente**: [Como verificar que esta story funciona por conta própria]

### Testes para User Story 1 (OPCIONAL - apenas se testes solicitados) ⚠️

> **NOTA**: Escreva estes testes PRIMEIRO, certifique-se que FALHAM antes da implementação**

- [ ] T010 [P] [US1] Contract test for [endpoint] in packages/Webkul/[PackageName]/tests/Feature/[Name]Test.php
- [ ] T011 [P] [US1] Integration test for [user journey] in packages/Webkul/[PackageName]/tests/Integration/[Name]Test.php

### Implementação para User Story 1

- [ ] T012 [P] [US1] Create [Entity1] Eloquent model in packages/Webkul/[PackageName]/src/Models/[Entity1].php
- [ ] T013 [P] [US1] Create [Entity2] Eloquent model in packages/Webkul/[PackageName]/src/Models/[Entity2].php
- [ ] T014 [US1] Implement [Service] in packages/Webkul/[PackageName]/src/Services/[Service].php (depends on T012, T013)
- [ ] T015 [US1] Implement [controller/API] in packages/Webkul/[PackageName]/src/Http/Controllers/[Controller].php
- [ ] T016 [US1] Add Laravel validation and error handling
- [ ] T017 [US1] Add Laravel logging for user story 1 operations

**Ponto de Verificação**: Neste ponto, User Story 1 deve ser totalmente funcional e testável independentemente

---

## Phase 4: User Story 2 - [Title] (Priority: P2)

**Goal**: [Brief description of what this story delivers]

**Independent Test**: [How to verify this story works on its own]

### Tests for User Story 2 (OPTIONAL - only if tests requested) ⚠️

- [ ] T018 [P] [US2] Contract test for [endpoint] in packages/Webkul/[PackageName]/tests/Feature/[Name]Test.php
- [ ] T019 [P] [US2] Integration test for [user journey] in packages/Webkul/[PackageName]/tests/Integration/[Name]Test.php

### Implementation for User Story 2

- [ ] T020 [P] [US2] Create [Entity] Eloquent model in packages/Webkul/[PackageName]/src/Models/[Entity].php
- [ ] T021 [US2] Implement [Service] in packages/Webkul/[PackageName]/src/Services/[Service].php
- [ ] T022 [US2] Implement [controller/API] in packages/Webkul/[PackageName]/src/Http/Controllers/[Controller].php
- [ ] T023 [US2] Integrate with User Story 1 components (if needed)

**Checkpoint**: At this point, User Stories 1 AND 2 should both work independently

---

## Phase 5: User Story 3 - [Title] (Priority: P3)

**Goal**: [Brief description of what this story delivers]

**Independent Test**: [How to verify this story works on its own]

### Tests for User Story 3 (OPTIONAL - only if tests requested) ⚠️

- [ ] T024 [P] [US3] Contract test for [endpoint] in packages/Webkul/[PackageName]/tests/Feature/[Name]Test.php
- [ ] T025 [P] [US3] Integration test for [user journey] in packages/Webkul/[PackageName]/tests/Integration/[Name]Test.php

### Implementation for User Story 3

- [ ] T026 [P] [US3] Create [Entity] Eloquent model in packages/Webkul/[PackageName]/src/Models/[Entity].php
- [ ] T027 [US3] Implement [Service] in packages/Webkul/[PackageName]/src/Services/[Service].php
- [ ] T028 [US3] Implement [controller/API] in packages/Webkul/[PackageName]/src/Http/Controllers/[Controller].php

**Checkpoint**: All user stories should now be independently functional

---

[Add more user story phases as needed, following the same pattern]

---

## Phase N: Polish & Cross-Cutting Concerns

**Purpose**: Improvements that affect multiple user stories

- [ ] TXXX [P] Documentation updates in packages/Webkul/[PackageName]/docs/
- [ ] TXXX Code cleanup and refactoring (ensure PSR-12 compliance)
- [ ] TXXX Performance optimization across all stories
- [ ] TXXX [P] Additional unit tests (if requested) in packages/Webkul/[PackageName]/tests/Unit/
- [ ] TXXX Security hardening following Laravel security practices
- [ ] TXXX Run quickstart.md validation

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies - can start immediately
- **Foundational (Phase 2)**: Depends on Setup completion - BLOCKS all user stories
- **User Stories (Phase 3+)**: All depend on Foundational phase completion
  - User stories can then proceed in parallel (if staffed)
  - Or sequentially in priority order (P1 → P2 → P3)
- **Polish (Final Phase)**: Depends on all desired user stories being complete

### User Story Dependencies

- **User Story 1 (P1)**: Can start after Foundational (Phase 2) - No dependencies on other stories
- **User Story 2 (P2)**: Can start after Foundational (Phase 2) - May integrate with US1 but should be independently testable
- **User Story 3 (P3)**: Can start after Foundational (Phase 2) - May integrate with US1/US2 but should be independently testable

### Within Each User Story

- Tests (if included) MUST be written and FAIL before implementation
- Models before services
- Services before endpoints
- Core implementation before integration
- Story complete before moving to next priority

### Parallel Opportunities

- All Setup tasks marked [P] can run in parallel
- All Foundational tasks marked [P] can run in parallel (within Phase 2)
- Once Foundational phase completes, all user stories can start in parallel (if team capacity allows)
- All tests for a user story marked [P] can run in parallel
- Models within a story marked [P] can run in parallel
- Different user stories can be worked on in parallel by different team members

---

## Parallel Example: User Story 1

```bash
# Launch all tests for User Story 1 together (if tests requested):
Task: "Contract test for [endpoint] in packages/Webkul/[PackageName]/tests/Feature/[Name]Test.php"
Task: "Integration test for [user journey] in packages/Webkul/[PackageName]/tests/Integration/[Name]Test.php"

# Launch all models for User Story 1 together:
Task: "Create [Entity1] Eloquent model in packages/Webkul/[PackageName]/src/Models/[Entity1].php"
Task: "Create [Entity2] Eloquent model in packages/Webkul/[PackageName]/src/Models/[Entity2].php"
```

---

## Implementation Strategy

### MVP First (User Story 1 Only)

1. Complete Phase 1: Setup
2. Complete Phase 2: Foundational (CRITICAL - blocks all stories)
3. Complete Phase 3: User Story 1
4. **STOP and VALIDATE**: Test User Story 1 independently
5. Deploy/demo if ready

### Incremental Delivery

1. Complete Setup + Foundational → Foundation ready
2. Add User Story 1 → Test independently → Deploy/Demo (MVP!)
3. Add User Story 2 → Test independently → Deploy/Demo
4. Add User Story 3 → Test independently → Deploy/Demo
5. Each story adds value without breaking previous stories

### Parallel Team Strategy

With multiple developers:

1. Team completes Setup + Foundational together
2. Once Foundational is done:
   - Developer A: User Story 1
   - Developer B: User Story 2
   - Developer C: User Story 3
3. Stories complete and integrate independently

---

## Notes

- [P] tasks = different files, no dependencies
- [Story] label maps task to specific user story for traceability
- Each user story should be independently completable and testable
- Verify tests fail before implementing
- Commit after each task or logical group
- Stop at any checkpoint to validate story independently
- Avoid: vague tasks, same file conflicts, cross-story dependencies that break independence
