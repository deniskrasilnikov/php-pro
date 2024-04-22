<?php

namespace Literato\Command;

use Faker\Factory as FakerFactory;
use Literato\Entity\Edition;
use Literato\Entity\Publisher;
use Literato\Repository\BookRepository;
use Literato\Repository\EditionRepository;
use Literato\Repository\Exception\EntityIdException;
use Literato\Repository\PublisherRepository;
use Literato\ServiceFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'literato:create-edition')]
class CreateEditionCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('isbn10', InputArgument::REQUIRED, 'Book ISBN-10')
            ->addOption('publisherName', 'p', InputOption::VALUE_REQUIRED, 'Create for given publisher');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $services = new ServiceFactory();
        $styledOutput = new SymfonyStyle($input, $output);

        try {
            $bookRepository = new BookRepository($pdo = $services->createPDO());
            $book = $bookRepository->findByIsbn10($isbn10 = $input->getArgument('isbn10'));

            if (!$book) {
                $styledOutput->warning("Can not find book with ISBN $isbn10");
                return Command::FAILURE;
            }

            $publisherRepository = new PublisherRepository($pdo);
            if ($publisherName = $input->getOption('publisherName')) {
                $publisher = $publisherRepository->findByName($publisherName);
            } else {
                $faker = FakerFactory::create();
                $publisher = new Publisher();
                $publisher->setName($faker->name());
                $publisher->setAddress($faker->address());
                $publisherRepository->add($publisher);
            }

            if (!$publisher) {
                $styledOutput->warning("Can not find publisher with name '$publisherName'");
                return Command::FAILURE;
            }

            $edition = new Edition(
                $book,
                $publisher,
                price: rand(1000, 4000) / 100,
                authorRewardPerCopy: 5.00,
                soldCopiesCount: rand(1, 10),
            );
            $editionRepository = new EditionRepository($pdo);
            $editionRepository->add($edition);

            $fullInfo = $edition->getFullInfo();
            call_user_func_array([$styledOutput, 'definitionList'],
                array_map(
                    fn($key, $value) => [$key => $value],
                    array_keys($fullInfo),
                    array_values($fullInfo)
                ));

        } catch (EntityIdException $e) {
            $services->createLogger()->error($e);
        }

        return Command::SUCCESS;
    }
}