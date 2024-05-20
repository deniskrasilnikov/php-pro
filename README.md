# Демонстраційний проєкт курсу PHP Pro 

Проєкт постійно змінюється відповідно до пройдених занять.

Ви можете знайти стан коду під конкретні заняття в відповідніх гілках.

Збудувати та запустити проєкт (всі сервіси) `docker compose up -d`

Зайти всередину PHP сервісу `docker compose exec php bash`<br>
Зайти всередину NGINX сервісу `docker compose exec nginx bash`<br>
Зайти всередину DB сервісу `docker compose exec db bash`

Доступ до веб-версії http://localhost

Запустити консольну команду всередині PHP сервісу:
```
docker compose exec php bash
php src/console.php <command_name>
```
Зупинити всі сервіси `docker compose stop`<br>
Зупинити та **видалити** всі сервіси (контейнери) `docker compose down`

### Lesson docs
* [Docker - Part 1](docs/docker_part_1.md)
* [Docker - Part 2. Docker Compose](docs/docker_part_2.md)
* [OOP - Part 3](docs/oop_part_3.md)
* [Doctrine ORM - Part 2](docs/doctrine_orm_part_2.md)
* [Eloquent ORM](docs/eloquent_orm.md)