#!/bin/sh
docker compose exec -e XDEBUG_MODE="${1:-off}" -w /home/php-pro/symfony php bash