<?php

namespace Literato\Command;

use Doctrine\ORM\Exception\ORMException;
use Faker\Factory as FakerFactory;
use Literato\Entity\Author;
use Literato\Entity\Book;
use Literato\Entity\Enum\Genre;
use Literato\Entity\Exception\BookValidationException;
use Literato\Entity\Exception\TextWordLengthException;
use Literato\Entity\Novel;
use Literato\Entity\Novelette;
use Literato\ServiceFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'literato:create-author')]
class CreateAuthorCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('firstName', InputArgument::OPTIONAL, 'Author first name')
            ->addArgument('lastName', InputArgument::OPTIONAL, 'Author last name');
    }

    /**
     * @throws ORMException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $services = new ServiceFactory();

        try {
            $faker = FakerFactory::create();

            $author = new Author();
            $author->setFirstName($input->getArgument('firstName') ?: $faker->firstName());
            $author->setLastName($input->getArgument('lastName') ?: $faker->lastName());

            // >> ORM
            $entityManager = $services->createORMEntityManager();
            $entityManager->persist($author);
            // <<

            $styledOutput = new SymfonyStyle($input, $output);
            $styledOutput->writeln("Created author <info>{$author->getFullName()}</info>");

            $firstNovelette = new Novelette();
            $firstNovelette->setName($faker->text(20));
            $firstNovelette->setIsbn10($faker->isbn10());
            $firstNovelette->setText($faker->text());
            $firstNovelette->setGenres([Genre::Romance, Genre::Thriller]);
            $author->addBook($firstNovelette);

            // >> ORM
            $entityManager->persist($firstNovelette);
            // <<

            $latestNovel = new Novel();
            $latestNovel->setName($faker->text(20));
            $latestNovel->setIsbn10($faker->isbn10());
            $latestNovel->setText($faker->text());
            $latestNovel->setGenres([Genre::SciFi, Genre::MagicalRealism]);
            $latestNovel->setSynopsis($faker->text(50));
            $author->addBook($latestNovel);

            // >> ORM
            $entityManager->persist($latestNovel);
            $entityManager->flush(); // apply all changes to DB
            // <<

            $output->writeln("Created {$author->getBooksCount()} book(s):");
            $this->printBook($firstNovelette, $styledOutput);
            $this->printBook($latestNovel, $styledOutput);
        } catch (TextWordLengthException|BookValidationException|ORMException $e) {
            $services->createLogger()->error($e);
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