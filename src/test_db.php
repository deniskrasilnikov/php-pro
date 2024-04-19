<?php

declare(strict_types=1);

$conn = new PDO(
    sprintf('mysql:host=%s;dbname=%s', getenv('DB_HOST'), getenv('DB_NAME')),
    getenv('DB_USER'),
    getenv('DB_PASSWORD')
);

$result = $conn->query('SELECT @@version')->fetch(PDO::FETCH_ASSOC);

echo ("MySQL version is " . $result['@@version']) . "\n";