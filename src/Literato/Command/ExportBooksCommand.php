<?php

declare(strict_types=1);

namespace Literato\Command;

use Literato\Entity\Book;
use Literato\ServiceFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'literato:export-books', description: 'Export all books to CSV')]
class ExportBooksCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('exportDirectory', InputArgument::REQUIRED, 'Export CSV directory');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $services = new ServiceFactory();
        $entityManager = $services->createDoctrineEntityManager();

        $query = $entityManager
            ->getRepository(Book::class)
            ->createQueryBuilder('b')
            ->getQuery();

        $exportFilename = rtrim($input->getArgument('exportDirectory'), '/') ;
        $file = fopen($exportFilename. '/books.csv', 'w');

        $ormTimeStart = microtime(true);
        foreach ($query->toIterable() as $book) {
            /** @var Book $book */
            fputcsv($file, $book->getFullInfo());
            $entityManager->detach($book);
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