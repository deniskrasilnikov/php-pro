### Trait and Exceptions example

```
docker run -it --rm -v /home/dkrasilnikov/php-pro/src:/home/php-pro php -f /home/php-pro/index.php
```

### Generators example

Run CSV export using array
```
docker run -it --rm -v /home/dkrasilnikov/php-pro/src:/home/php-pro php -f /home/php-pro/generators.php 0
```

Run CSV export using *generators*
```
docker run -it --rm -v /home/dkrasilnikov/php-pro/src:/home/php-pro php -f /home/php-pro/generators.php 1
```