<!--
Sync Impact Report:
- Version change: 1.0.0 → 1.1.0 (adição de princípios de integração de pagamento e idioma pt-BR)
- Modified principles: I. Modular Architecture (expandido), II. Laravel Framework Foundation (mantido), III. E-Commerce Domain Focus (mantido), IV. Multi-Channel Support (mantido), V. Extensibility & Customization (expandido)
- Added principles: VI. Integração de Pagamentos, VII. Padrão de Idioma pt-BR
- Templates requiring updates: ⚠ plan-template.md, ⚠ spec-template.md, ⚠ tasks-template.md (precisam atualização para pt-BR)
- Follow-up TODOs: Atualizar todos os templates para Português do Brasil
- Environment: Docker (bagisto-docker) - Container PHP 8.2 + MySQL 8.0
-->

# Constituição Bagisto

## Princípios Fundamentais

### I. Arquitetura Modular
Bagisto é construído sobre uma arquitetura modular baseada em pacotes. Toda funcionalidade principal DEVE ser implementada como um pacote independente dentro do diretório `packages/Webkul/`. Pacotes DEVEM ser autocontidos, independentemente testáveis, e seguir padrões PSR-4 de autoloading. Limites de domínio claros são obrigatórios - nenhum pacote apenas organizacional.

### II. Fundação Laravel Framework
Bagisto estende as capacidades do framework Laravel. Todos os pacotes DEVEM alavancar o service container, sistema de middleware, e convenções do Laravel. Recursos do framework DEVEM ser usados consistentemente entre pacotes - sem reinvenção customizada do framework. Pacotes do ecossistema Laravel são preferidos sobre implementações customizadas.

### III. Foco em Domínio E-Commerce (NÃO-NEGOCIÁVEL)
Todas as funcionalidades DEVEM servir às necessidades de negócio e-commerce. Design orientado a domínio é obrigatório: Entender padrões e-commerce → Implementar soluções específicas do domínio → Validar contra cenários de negócio reais. Jornada do cliente, catálogo de produtos, processamento de pedidos, e fluxos de pagamento têm prioridade sobre preferências técnicas.

### IV. Suporte Multi-Canal
Bagisto suporta múltiplos canais de venda (web, mobile, headless, marketplace). APIs principais DEVEM ser agnósticas de canal. Implementações específicas de canal DEVEM estender contratos principais sem modificá-los. Testes de integração são obrigatórios para todos os pontos de contato de canal.

### V. Extensibilidade & Customização
Comerciantes DEVEM poder customizar o Bagisto sem modificar pacotes principais. Toda lógica de negócio DEVE ser sobrescritível através de configuração, eventos, ou substituição de serviços. Modificações em pacotes principais são proibidas exceto para correções de bugs. Compatibilidade com marketplace de extensões mantida.

### VI. Integração de Pagamentos
Métodos de pagamento DEVEM ser implementados como pacotes independentes em `packages/Webkul/Payment/`. Integrações DEVEM seguir estritamente a documentação oficial do Bagisto. APIs de terceiros (como Mercado Pago) DEVEM ser integradas via APIs oficiais. Código DEVE ser limpo, versionável, e compatível com atualizações futuras do Bagisto. Nenhuma modificação no core do Bagisto é permitida.

### VII. Padrão de Idioma Português Brasil (pt-BR)
Todo conteúdo do projeto (constituição, especificações, planos, tarefas e comentários) DEVE ser escrito exclusivamente em Português do Brasil (pt-BR). Nenhuma resposta, documentação ou artefato DEVE ser gerado em inglês, exceto quando for trecho de código, nome de classes, métodos, variáveis ou termos técnicos oficiais.

## Padrões Técnicos

### Requisitos de Stack Tecnológico
- **Backend**: PHP 8.2+, Laravel 11.x, MySQL 8.0+
- **Frontend**: Vue.js 3.x, TailwindCSS, Build tools (Vite/Webpack)
- **Testes**: Pest PHP, PHPUnit para código legado
- **Gerenciamento de Pacotes**: Composer, PSR-4 autoloading
- **Documentação**: Markdown seguindo convenções do projeto

### Padrões de Qualidade de Código
- Todos os pacotes DEVEM seguir padrões PSR-12 de codificação
- Type hints obrigatórios para assinaturas de métodos e tipos de retorno
- Blocos PHPDoc obrigatórios para APIs públicas
- Ferramentas de análise estática (PHPStan, Pint) devem passar sem erros
- Cobertura de código mínima 80% para novas funcionalidades

### Padrões de Database
- Todas as migrations DEVEM ser reversíveis
- Foreign key constraints obrigatórias onde existirem relacionamentos
- Índices obrigatórios para colunas frequentemente consultadas
- Soft deletes preferidos sobre hard deletes para dados de negócio

## Fluxo de Desenvolvimento

### Processo de Desenvolvimento de Pacotes
1. Criar esqueleto do pacote em `packages/Webkul/[PackageName]/`
2. Definir service provider e registrar com Laravel
3. Implementar modelos de domínio seguindo padrões existentes
4. Criar rotas de API seguindo convenções RESTful
5. Adicionar componentes frontend usando Vue.js composition API
6. Escrever testes abrangentes (unit, integration, feature)
7. Atualizar documentação e exemplos

### Requisitos de Code Review
- Todos os PRs DEVEM ser revisados por pelo menos um membro do core team
- Testes automatizados DEVEM passar antes do merge
- Atualizações de documentação obrigatórias para mudanças de API
- Breaking changes requerem aviso de deprecation e guia de migração

### Portas de Qualidade
- Análise estática deve passar sem erros
- Cobertura de testes não pode diminuir abaixo da baseline atual
- Benchmarks de performance não podem regredir
- Vulnerabilidades de segurança devem ser endereçadas antes do release

## Governança

Esta constituição supersede todas as outras práticas de desenvolvimento. Emendas requerem:
- Documentação de mudanças propostas com justificativa
- Aprovação da maioria dos mantenedores core
- Plano de migração para violações de código existente
- Incremento de versão segundo regras de versionamento semântico

Todas as atividades de desenvolvimento DEVEM verificar compliance com estes princípios. Complexidade além de padrões normais deve ser explicitamente justificada em documentos de design. Use esta constituição como referência primária para decisões arquitetônicas.

**Versão**: 1.1.0 | **Ratificada**: 2025-01-03 | **Última Emenda**: 2025-01-03
