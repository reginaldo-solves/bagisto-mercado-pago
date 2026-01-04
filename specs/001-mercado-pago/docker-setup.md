# Docker Setup: Método de Pagamento Mercado Pago

**Created**: 2025-01-03  
**Feature**: Método de Pagamento Mercado Pago  
**Environment**: Docker (bagisto-docker) - Container PHP 8.2 + MySQL 8.0

## Comandos Docker Essenciais

### 1. Verificar Containers em Execução
```bash
docker ps
```

### 2. Acessar Container PHP-FPM
```bash
docker exec -it e45de18a2adc bash
```
*Nota: Substitua `e45de18a2adc` pelo ID atual do container PHP-FPM*

### 3. Navegar para o Diretório do Projeto
```bash
cd /var/www/html/bagisto
```

### 4. Atualizar Autoload do Composer
```bash
composer dump-autoload
```

### 5. Instalar Dependências do Pacote
```bash
composer require mercadopago/dx-php
```

### 6. Executar Migrations
```bash
php artisan migrate
```

### 7. Limpar Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 8. Sair do Container
```bash
exit
```

## Fluxo de Trabalho Recomendado

### Durante o Desenvolvimento
1. **Sempre acesse o container** antes de executar comandos PHP/Composer
2. **Execute `composer dump-autoload`** após qualquer alteração nos arquivos do pacote
3. **Limpe o cache** após alterações em configurações ou rotas
4. **Verifique os logs** se houver problemas: `php artisan log:clear`

### Comandos Úteis

#### Verificar Logs do Container
```bash
docker logs e45de18a2adc
```

#### Reiniciar Container
```bash
docker restart e45de18a2adc
```

#### Verificar Status dos Serviços
```bash
docker-compose ps
```

#### Reconstruir Container (se necessário)
```bash
docker-compose down
docker-compose up -d --build
```

## Estrutura de Arquivos no Container

O projeto está localizado em `/var/www/html/bagisto` dentro do container:

```
/var/www/html/bagisto/
├── packages/Reginaldo/MercadoPago/     # Pacote Mercado Pago
├── config/                            # Configurações do Laravel
├── vendor/                            # Dependências do Composer
├── storage/                           # Logs e uploads
└── public/                            # Arquivos públicos
```

## Troubleshooting

### Problema: Pacote não é encontrado
**Solução**: Execute `composer dump-autoload` dentro do container

### Problema: Configurações não são atualizadas
**Solução**: Limpe o cache com `php artisan config:clear`

### Problema: Rotas não funcionam
**Solução**: Limpe o cache de rotas com `php artisan route:clear`

### Problema: Views não são atualizadas
**Solução**: Limpe o cache de views com `php artisan view:clear`

### Problema: Permissões de arquivos
**Solução**: Dentro do container, execute:
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

## Ambiente de Desenvolvimento

- **PHP**: 8.2
- **MySQL**: 8.0
- **Laravel**: 11.x
- **Bagisto**: Latest
- **Container**: PHP-FPM (ID: e45de18a2adc)

## URLs de Acesso

- **Aplicação**: http://localhost
- **Admin**: http://localhost/admin
- **phpMyAdmin**: http://localhost:8080
- **Mailpit**: http://localhost:8025

## Notas Importantes

1. **Sempre execute comandos PHP/Composer dentro do container**
2. **Verifique o ID do container com `docker ps` antes de acessar**
3. **Após alterações no código, execute `composer dump-autoload`**
4. **Mantenha o container em execução durante o desenvolvimento**
5. **Use `exit` para sair do container quando terminar**
