# PHP Pro course demonstration project

This project changes continuously according to the needs of course lessons.

You can find the state of code for specific lesson in separate branches.

Build and run using Docker Compose `docker compose up -d`

Enter inside PHP service `docker compose run --rm php bash`<br>
Enter inside DB service `docker compose exec db bash`

Run code inside PHP container `docker compose run --rm php -f src/index.php`

Stop and remove all services `docker compose down`

### Lesson docs
[Docker - Part 2. Docker Compose](docs/docker_part_2.md)