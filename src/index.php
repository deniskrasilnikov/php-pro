<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Literato\Command\CreateAuthorCommand;
use Literato\Command\CreateEditionCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

$output = new ConsoleOutput();
$application = new Application();

try {
    $application->addCommands([
        new CreateAuthorCommand(),
        new CreateEditionCommand(),
    ]);
    $application->run(new ArgvInput(), $output);
} catch (Exception $e) {
    $output->writeln($e->getMessage());
    $output->writeln($e->getTraceAsString());
}
