<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Literato\Command\BestSellersCommand;
use Literato\Command\CreateAuthorCommand;
use Literato\Command\CreateEditionCommand;
use Literato\Command\ExportBooksCommand;
use Literato\ServiceFactory;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

$output = new ConsoleOutput();
$application = new Application();

try {
    $application->addCommands([
        new CreateAuthorCommand(),
        new CreateEditionCommand(),
        new BestSellersCommand(),
        new ExportBooksCommand(),
    ]);

    $services = new ServiceFactory();
    ConsoleRunner::addCommands($application, new SingleManagerProvider($services->createORMEntityManager()));
    $application->run(new ArgvInput(), $output);
} catch (Exception $e) {
    $output->writeln($e->getMessage());
    $output->writeln($e->getTraceAsString());
}
