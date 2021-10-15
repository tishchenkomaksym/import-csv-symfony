<?php

namespace App\Command;

use App\Service\ImportService;
use League\Csv\Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportCsvCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'import:csv {path}';

    /**
     * @var string
     */
    protected static $defaultDescription = 'Import data from csv to db';

    /**
     * @var ImportService
     */
    private ImportService $importService;

    public function __construct(ImportService $importService)
    {
        parent::__construct();

        $this->importService = $importService;
    }


    protected function configure(): void
    {
        $this
            ->addArgument('path', InputArgument::REQUIRED, 'Argument description')
            ->addOption('test', null, InputOption::VALUE_NONE, 'Option description');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $path = $input->getArgument('path');

        $this->importService->importCsv($path);

        foreach ($this->importService->error as $error) {
            $io->info($error);
        }

        return Command::SUCCESS;
    }
}
