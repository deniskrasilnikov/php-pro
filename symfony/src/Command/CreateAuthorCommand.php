<?php

namespace App\Command;

use App\Module\Literato\Entity\Author;
use App\Module\Literato\Entity\Book;
use App\Module\Literato\Entity\Exception\BookValidationException;
use App\Module\Literato\Entity\Exception\TextWordLengthException;
use App\Module\Literato\Manager\BookManager;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Generator;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'literato:create-author')]
class CreateAuthorCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly BookManager $bookManager,
        private readonly Generator $faker,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('firstName', InputArgument::OPTIONAL, 'Author first name')
            ->addArgument('lastName', InputArgument::OPTIONAL, 'Author last name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $author = new Author();
            $author->setFirstName($input->getArgument('firstName') ?: $this->faker->firstName());
            $author->setLastName($input->getArgument('lastName') ?: $this->faker->lastName());

            $this->entityManager->persist($author);
            $this->entityManager->flush();

            $styledOutput = new SymfonyStyle($input, $output);
            $styledOutput->writeln("Created author <info>{$author->getFullName()}</info>");

            $firstNovelette = $this->bookManager->createNovelette($author);
            $latestNovel = $this->bookManager->createNovel($author);

            $output->writeln("Created {$author->getBooksCount()} book(s):");
            $this->printBook($firstNovelette, $styledOutput);
            $this->printBook($latestNovel, $styledOutput);
        } catch (TextWordLengthException|BookValidationException $e) {
            $this->logger->error($e);
            throw $e;
        }

        return Command::SUCCESS;
    }

    private function printBook(Book $book, SymfonyStyle $output): void
    {
        $fullInfo = $book->getFullInfo();
        call_user_func_array([$output, 'definitionList'],
            array_map(
                fn($key, $value) => [$key => $value],
                array_keys($fullInfo),
                array_values($fullInfo)
            ));
    }

}