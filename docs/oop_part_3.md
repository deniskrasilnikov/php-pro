# OOP - Part 3

Приклади команд, використаних на занятті.

> **!** Спочатку переключіться на відповідну гілку `git checkout lesson_5`

### Trait та Exceptions

```
docker run -it --rm -v ${PWD}/src:/home/php-pro php -f /home/php-pro/index.php
```

### Generators

Запустити CSV експорт без генераторів (дані експортуються як звичайний array)
```
docker run -it --rm -v ${PWD}/src:/home/php-pro php -f /home/php-pro/generators.php 0
```

Запустити CSV експорт з використанням *генераторів*
```
docker run -it --rm -v ${PWD}/src:/home/php-pro php -f /home/php-pro/generators.php 1
```