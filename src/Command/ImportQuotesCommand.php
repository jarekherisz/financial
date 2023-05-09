<?php

namespace App\Command;

use App\Repository\InstrumentRepository;
use App\Service\ImportModules\Quote\ImportQuoteBuilder;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:import-quotes', description: 'Import financial quotes')]
class ImportQuotesCommand extends Command
{

    private InstrumentRepository $instrumentRepository;
    private ImportQuoteBuilder $importQuoteBuilder;

    public function __construct(ImportQuoteBuilder $importQuoteBuilder, InstrumentRepository $instrumentRepository)
    {

        $this->instrumentRepository = $instrumentRepository;
        $this->importQuoteBuilder = $importQuoteBuilder;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Import financial quotes from Yahoo Finance')
            ->addArgument('symbol', InputArgument::OPTIONAL, 'The symbol to import quotes for');
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $symbol = $input->getArgument('symbol');

        if ($symbol) {
            $instruments = $this->instrumentRepository->findOneBySymbol($symbol);
            if (!$instruments) {
                $io->error(sprintf('No instrument found for symbol: %s', $symbol));
                return Command::FAILURE;
            }
            $io->writeln(sprintf('Importing quotes for symbol: %s', $symbol));
            $importProvider = $this->importQuoteBuilder->getImportProvider($instruments);


            $importProvider->import($instruments);


        } else {
            $io->writeln('Importing quotes for all instruments in the repository');
            $instruments = $this->instrumentRepository->findAll();

            $progressBar = null;
            if(count($instruments)>0)
                $progressBar = new ProgressBar($output, count($instruments));


            foreach ($instruments as $instrument) {
                $symbol = $instrument->getSymbol();

                if($progressBar!=null) {
                    $progressBar->advance();
                    $progressBar->setMessage(sprintf('Importing quotes for symbol: %s', $symbol));
                }
                else
                {
                    $io->writeln(sprintf('Importing quotes for symbol: %s', $symbol));
                }

                $importProvider = $this->importQuoteBuilder->getImportProvider($instrument);
                $importProvider->import($instrument);


                $io->writeln('');
            }

            $progressBar?->finish();
        }

        return Command::SUCCESS;
    }


}