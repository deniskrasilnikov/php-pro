<?php

namespace Eloquent\Command;

use Eloquent\Model\Book;
use Eloquent\Model\Edition;
use Eloquent\Model\Publisher;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'eloquent:create-edition', description: 'Create new book edition with some publisher')]
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
        $book = Book::query()
            ->where('isbn10', $isbn = $input->getArgument('isbn10'))
            ->firstOr(fn () => throw new ModelNotFoundException("Book with ISBN $isbn is not found"));

        if ($publisherName = $input->getOption('publisherName')) {
            $publisher = Publisher::query()
                ->where('name', $publisherName)
                ->first();
        } else {
            $faker = FakerFactory::create();
            $publisher = new Publisher();
            $publisher->name = $faker->name();
            $publisher->address = $faker->address();
            $publisher->save();
        }

        $styledOutput = new SymfonyStyle($input, $output);

        if (!$publisher) {
            $styledOutput->warning("Can not find publisher with name '$publisherName'");
            return Command::FAILURE;
        }

        $edition = new Edition();
        $edition->price = rand(1000, 5000);
        $edition->author_reward_base = rand(0, 1000);
        $edition->author_reward_copy = rand(100, 1000);
        $edition->sold_copies_count = rand(1, 100);
        $edition->book()->associate($book);
        $edition->publisher()->associate($publisher);
        $edition->save();

        $fullInfo = $edition->getFullInfo();
        call_user_func_array([$styledOutput, 'definitionList'],
            array_map(
                fn($key, $value) => [$key => $value],
                array_keys($fullInfo),
                array_values($fullInfo)
            ));

        return Command::SUCCESS;
    }
}