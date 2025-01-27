<?php

declare(strict_types=1);

namespace App\Command;

use App\Module\Literato\Entity\Edition;
use App\Module\Literato\Repository\EditionRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'literato:best-sellers', description: 'Print best selling books of all times')]
class BestSellersCommand extends Command
{
    public function __construct(
        private readonly EditionRepository $editionRepository,
    ) {
        parent::__construct();
    }

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
        $bestSellers = $this->editionRepository->findBestSellers(
            (int)$input->getOption('count'),
            $input->getOption('publisherName')
        );
        $styledOutput = new SymfonyStyle($input, $output);

        foreach ($bestSellers as $edition) {
            $this->printEdition($edition, $styledOutput);
        }

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