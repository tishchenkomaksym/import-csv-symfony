<?php

namespace App\Command;

use App\Service\ImportService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportCsvCommand extends Command
{
    protected static $defaultName = 'import:csv {path}';
    protected static $defaultDescription = 'Import data from csv to db';
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $path = $input->getArgument('path');

        $this->importService->importCsv($path);

        foreach ($this->importService->error as $error) {
            $io->note($error);
        }
//        if ($arg1) {
//            $io->note(sprintf('You passed an argument: %s', $arg1));
//        }

        if ($input->getOption('test')) {
            // ...
        }

//        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
