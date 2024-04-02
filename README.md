### Docker command examples

```
docker run --name lesson2 -d -i -t php
docker exec -it lesson2 bash
docker kill lesson2
docker rm lesson2
docker run -it -v /home/dkrasilnikov/php-pro/src:/home/php-pro php -f home/php-pro/index.php
docker run --rm -it -v /home/dkrasilnikov/php-pro/src:/home/php-pro php -f home/php-pro/index.php
```