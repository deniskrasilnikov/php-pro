<?php

declare(strict_types=1);

namespace Eloquent\Command;

use Eloquent\Model\Book;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'eloquent:export-books', description: 'Export all books to CSV')]
class ExportBooksCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('exportDirectory', InputArgument::REQUIRED, 'Export CSV directory');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exportFilename = rtrim($input->getArgument('exportDirectory'), '/') . '/books_eloquent.csv';
        $file = fopen($exportFilename, 'w');

        $ormTimeStart = microtime(true);

        foreach (Book::query()->lazy() as $book) {
            /** @var Book $book */
            fputcsv($file, $book->getFullInfo());
        }

        $ormTimeEnd = microtime(true);
        fclose($file);

        $output->writeln(
            sprintf(
                "%0.2f Mb\n%d sec",
                memory_get_usage(true) / 1000000,
                $ormTimeEnd - $ormTimeStart
            )
        );

        return Command::SUCCESS;
    }
}