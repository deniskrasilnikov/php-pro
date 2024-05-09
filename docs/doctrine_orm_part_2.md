# Doctrine ORM - Part 2

Команди з прикладами роботи ORM.

> **!** First checkout lesson with `git checkout lesson_12_doctrine_part2`

### Базові вибірки через Repository конкретної сутності

[CreateEditionCommand](../src/Literato/Command/CreateEditionCommand.php)

```
docker compose run --rm php bash
php src/index.php literato:create-edition 8069086793
```

### Конструювання запиту через QueryBuilder
[BestSellersCommand](../src/Literato/Command/BestSellersCommand.php)
```
docker compose run --rm php bash
php src/index.php literato:best-sellers
```

### Вибірка і обробка великої кількості об'єктів за допомогою ORM-вбудованого метода-ітератора
[ExportBooksCommand](../src/Literato/Command/ExportBooksCommand.php)
```
docker compose run --rm php bash
php src/index.php literato:export-books /home/php-pro/export
```