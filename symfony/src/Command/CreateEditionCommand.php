<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Exception\ORMException;
use Faker\Generator;
use Literato\Entity\Book;
use Literato\Entity\Edition;
use Literato\Entity\Publisher;
use Psr\Log\LoggerInterface;
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
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly Generator $faker,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('isbn10', InputArgument::REQUIRED, 'Book ISBN-10')
            ->addOption('publisherName', 'p', InputOption::VALUE_REQUIRED, 'Create for given publisher');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $styledOutput = new SymfonyStyle($input, $output);

        try {
            $bookRepository = $this->entityManager->getRepository(Book::class);
            $book = $bookRepository->findOneBy(['isbn10' => $isbn10 = $input->getArgument('isbn10')]);

            if (!$book) {
                throw new EntityNotFoundException("Can not find book with ISBN $isbn10");
            }

            if ($publisherName = $input->getOption('publisherName')) {
                $publisherRepository = $this->entityManager->getRepository(Publisher::class);
                $publisher = $publisherRepository->findOneBy(['name' => $publisherName]);
            } else {
                $publisher = new Publisher();
                $publisher->setName($this->faker->name());
                $publisher->setAddress($this->faker->address());
                $this->entityManager->persist($publisher);
            }

            if (!$publisher) {
                $styledOutput->warning("Can not find publisher with name '$publisherName'");
                return Command::FAILURE;
            }

            $edition = new Edition(
                $book,
                $publisher,
                price: rand(1000, 5000),
                authorBaseReward: rand(0, 1000),
                authorRewardPerCopy: rand(100, 1000),
                soldCopiesCount: rand(1, 100),
            );
            $this->entityManager->persist($edition);
            $this->entityManager->flush();

            $fullInfo = $edition->getFullInfo();
            call_user_func_array([$styledOutput, 'definitionList'],
                array_map(
                    fn($key, $value) => [$key => $value],
                    array_keys($fullInfo),
                    array_values($fullInfo)
                ));
        } catch (ORMException $e) {
            $styledOutput->warning($e->getMessage());
            $this->logger->error($e);
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}