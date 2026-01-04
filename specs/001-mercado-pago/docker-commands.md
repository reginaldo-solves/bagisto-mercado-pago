# Comandos Docker Rápidos

## Acesso ao Container
```bash
docker exec -it e45de18a2adc bash
cd /var/www/html/bagisto
```

## Comandos Essenciais (DENTRO DO CONTAINER)
```bash
# Atualizar autoload
composer dump-autoload

# Instalar dependências
composer require mercadopago/dx-php

# Executar migrations
php artisan migrate

# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Verificar status
php artisan about
```

## Verificar Containers
```bash
docker ps
```

## Logs
```bash
docker logs e45de18a2adc
```

## Reiniciar Container
```bash
docker restart e45de18a2adc
```

## Sair do Container
```bash
exit
```

## URLs de Acesso
- App: http://localhost
- Admin: http://localhost/admin
- phpMyAdmin: http://localhost:8080
