# Eloquent ORM

Команди з прикладами роботи з Eloquent ORM.

> **!** First checkout lesson with `git checkout lesson_13_eloquent`

### Створення та збереження моделей

[CreateAuthorCommand](../src/Eloquent/Command/CreateAuthorCommand.php)

```
docker compose run --rm php bash
php src/eloquent.php eloquent:create-author
```

### Прості вибірки моделей та збереження моделі з асоціаціями

[CreateEditionCommand](../src/Eloquent/Command/CreateEditionCommand.php)

```
docker compose run --rm php bash
php src/eloquent.php eloquent:create-edition 8069086793
```

### Конструювання запиту
[BestSellersCommand](../src/Eloquent/Command/BestSellersCommand.php)
```
docker compose run --rm php bash
php src/eloquent.php eloquent:best-sellers
```

### Вибірка і обробка великої кількості об'єктів
[ExportBooksCommand](../src/Eloquent/Command/ExportBooksCommand.php)
```
docker compose run --rm php bash
php src/eloquent.php eloquent:export-books /home/php-pro/export
```