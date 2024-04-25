# Docker - Part 1

Command examples used during lesson demonstration.

> **!** First checkout lesson with `git checkout lesson_2`

```
docker run --name demo -d -i -t php
docker exec -it demo bash
docker kill demo
docker rm demo
docker run -it -v ${PWD}:/home/php-pro php -f /home/php-pro/index.php
docker run --rm -it -v ${PWD}:/home/php-pro php -f /home/php-pro/index.php
docker build -t php-pro .
```