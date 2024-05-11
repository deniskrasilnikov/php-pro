<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Eloquent\Command\CreateAuthorCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

require dirname(__DIR__) . '/vendor/autoload.php';

$capsule = new Capsule();

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => getenv('DB_HOST'),
    'database' => getenv('DB_NAME'),
    'username' => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
]);

$capsule->bootEloquent();

$output = new ConsoleOutput();
$application = new Application();

try {
    $application->addCommands([
        new CreateAuthorCommand(),
    ]);
    $application->run(new ArgvInput(), $output);
} catch (Exception $e) {
    $output->writeln($e->getMessage());
    $output->writeln($e->getTraceAsString());
}