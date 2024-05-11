<?php

namespace Eloquent\Command;

use Faker\Factory as FakerFactory;
use Eloquent\Model\Author;
use Eloquent\Model\Book;
use Eloquent\Model\Novel;
use Eloquent\Model\Novelette;
use Literato\Entity\Enum\Genre;
use Literato\Entity\Exception\BookValidationException;
use Literato\ServiceFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'eloquent:create-author', description: 'Create new author with 2 books')]
class CreateAuthorCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('firstName', InputArgument::OPTIONAL, 'Author first name')
            ->addArgument('lastName', InputArgument::OPTIONAL, 'Author last name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $faker = FakerFactory::create();

            $author = new Author();
            $author->first_name = $input->getArgument('firstName') ?: $faker->firstName();
            $author->last_name = $input->getArgument('lastName') ?: $faker->lastName();
            $author->save();

            $styledOutput = new SymfonyStyle($input, $output);
            $styledOutput->writeln("Created author <info>{$author->getFullName()}</info>");

            $firstNovelette = new Novelette();
            $firstNovelette->type = 'Novelette';
            $firstNovelette->name = $faker->text(20);
            $firstNovelette->isbn10 = $faker->isbn10();
            $firstNovelette->text = $faker->text();
            $firstNovelette->genres = [Genre::Romance, Genre::Thriller];
            $author->novelettes()->save($firstNovelette);

            $latestNovel = new Novel();
            $latestNovel->type = 'Novel';
            $latestNovel->name = $faker->text(20);
            $latestNovel->isbn10 = $faker->isbn10();
            $latestNovel->text = $faker->text();
            $latestNovel->genres = [Genre::SciFi, Genre::MagicalRealism];
            $latestNovel->synopsis = $faker->text(50);
            $author->novels()->save($latestNovel);

            $output->writeln("Created {$author->getBooksCount()} book(s):");
            $this->printBook($firstNovelette, $styledOutput);
            $this->printBook($latestNovel, $styledOutput);
        } catch (BookValidationException $e) {
            $services = new ServiceFactory();
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