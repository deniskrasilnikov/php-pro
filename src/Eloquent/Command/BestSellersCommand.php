<?php

declare(strict_types=1);

namespace Eloquent\Command;

use Eloquent\Model\Edition;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'eloquent:best-sellers', description: 'Print best selling books of all times')]
class BestSellersCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addOption(
                'publisherName',
                'p',
                InputOption::VALUE_REQUIRED,
                'Print best sellers for given publisher only'
            )
            ->addOption('count', 'c', InputOption::VALUE_REQUIRED, 'Change default number of bestsellers to show', 3);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $queryBuilder = Edition::query();
        $queryBuilder->orderByDesc('sold_copies_count')
            ->limit($input->getOption('count'));

        if ($publisherName = $input->getOption('publisherName')) {
            $queryBuilder->whereHas('publisher', function (Builder $builder) use ($publisherName) {
                $builder->where('name', $publisherName);
            });
        }

        $styledOutput = new SymfonyStyle($input, $output);

        foreach ($queryBuilder->get() as $edition) {
            /** @var Edition $edition */
            $this->printEdition($edition, $styledOutput);
        }

        $styledOutput->info($queryBuilder->toRawSql()); // SQL

        return Command::SUCCESS;
    }

    private function printEdition(Edition $edition, SymfonyStyle $output): void
    {
        $fullInfo = $edition->getFullInfo();

        call_user_func_array([$output, 'definitionList'],
            array_map(
                fn($key, $value) => [$key => $value],
                array_keys($fullInfo),
                array_values($fullInfo)
            ));
    }
}