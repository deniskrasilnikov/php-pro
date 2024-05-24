# Встановлення Symfony

1. Встановити фреймворк.
    ```
    # зайти в PHP контейнер
    docker compose exec php bash
    
    # встановити Symfony в підкаталог відносно корня проєкту
    composer create-project symfony/skeleton:"7.0.*" symfony
    
    # встановити додаткові пакети для повноцінної web-розробки
    cd symfony
    composer require webapp
    ```

2. В файлі конфігурації для Nginx, вказати в якості *root*  [шлях до директорії *public*](https://github.com/deniskrasilnikov/php-pro/blob/2024-03/docker/nginx/default.conf#L4), де розташований файл-точка входу index.php із запуском ядра Symfony. 
3. Перезібрати образ Nginx і перестворити новий сервіс-контейнер `docker compose up -d --build nginx`