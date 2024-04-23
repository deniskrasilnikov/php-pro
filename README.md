# PHP Pro course demonstration project

This project changes continuously according to the needs of course lessons.

You can find the state of code for specific lesson in separate branches.

Build and run using Docker:

```
docker build -t php-pro .
docker run -it --rm -v ${PWD}:/home/php-pro php-pro -f src/index.php
```