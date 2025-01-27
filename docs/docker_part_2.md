# Docker - Part 2

Приклади команд, використаних на занятті.

> **!** Спочатку переключіться на відповідну гілку `git checkout lesson_8_docker_compose`

### Docker volumes
```
# named
docker run -it --rm -v demo_volume:/home/demo php bash
printf "<?php echo 'Hello' . PHP_EOL;" > index.php
printf '\n echo "Docker volumes are great" . PHP_EOL;' >> index.php

# anonymous
docker run -it -v /home/demo php bash
```

### Docker ports
```
docker run --rm -d -p 27017:27017 mongodb/mongodb-community-server  
docker run --rm -d -p 81:80 nginx
```

### Docker compose
```
docker-compose up -d
docker compose run --rm php bash
docker-compose up -d --force-recreate db
docker compose stop db
docker compose rm db
docker compose up -d db
```

### Test db connection
```
docker compose run --rm php -f src/test_db.php
```