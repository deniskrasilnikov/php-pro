<?php

declare(strict_types=1);

namespace Literato;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use PDO;
use Psr\Log\LoggerInterface;

class ServiceFactory
{
    /**
     * logger component
     */
    public function createLogger(): LoggerInterface
    {
        $logger = new Logger('default');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../../log/errors.log', Level::Warning));

        return $logger;
    }

    /**
     * db abstraction component
     */
    public function createPDO(): PDO
    {
        $pdo = new PDO(
            sprintf('mysql:host=%s;dbname=%s', getenv('DB_HOST'), getenv('DB_NAME')),
            getenv('DB_USER'),
            getenv('DB_PASSWORD')
        );
        $pdo->query('SET NAMES utf8mb4');

        return $pdo;
    }
}