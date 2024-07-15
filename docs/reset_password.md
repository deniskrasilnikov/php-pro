# Як додати функціонал відновлення паролю

> **&#9432;️** Попередньо в проєкті має бути реалізован функціонал реєстрації та логіну.

Встановити бандл згідно документації https://symfony.com.ua/doc/current/security/reset_password.html

Слідувати інструкціям команди `bin/console make:reset-password`

## Налаштування безпеки
Додати в `config/packages/security.yaml` правило публічного доступу до сторінки відновлення паролю

```yaml
    access_control:
        - { path: ^/reset-password, roles: PUBLIC_ACCESS }
```

Доступ до сторінки відновлення паролю http://localhost/reset-password

## Налаштування MailCatcher

Додати в docker `compose.yaml` новий сервіс
```yaml
    mailcatcher:
        image: schickling/mailcatcher
        ports:
            - 1080:1080
            - 1025:1025
```

Додати в `symfony/.env` змінну для mailer-компонента фреймворку

```MAILER_DSN=smtp://mailcatcher:1025```

Виключити відкаладену відправку листів, додавши в `config/packages/mailer.yaml`

```yaml
framework:
    mailer:
        message_bus: false
```
Запустити сервіс `docker compose up -d mailcatcher`

Доступ до веб-інтерфейсу пошти http://localhost:1080

Документація сервсіу https://mailcatcher.me/