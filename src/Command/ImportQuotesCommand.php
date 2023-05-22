<?php

namespace App\Command;

use App\Repository\InstrumentExchangeRepository;
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


    public function __construct(private readonly InstrumentExchangeRepository $instrumentExchangeRepository,
                                private readonly ImportQuoteBuilder           $importQuoteBuilder,
                                private readonly InstrumentRepository         $instrumentRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Import financial quotes from Yahoo Finance')
            ->addArgument('ticker', InputArgument::OPTIONAL, 'The ticker to import quotes for');
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $ticker = $input->getArgument('ticker');

        $instrumentsExchange = [];

        // Jeżeli podano ticker, to importuj notowania tylko dla tego instrumentu
        if ($ticker) {
            $instrumentsExchange = $this->instrumentExchangeRepository->findByTicker($ticker);
            if (count($instrumentsExchange) == 0) {
                $io->error(sprintf('No instrument found for ticker: %s', $ticker));
                return Command::FAILURE;
            }
        }
        // W przeciwnym wypadku importuj notowania dla wszystkich instrumentów
        else {
            $io->writeln('Importing quotes for all instruments in the repository');
            $instruments = $this->instrumentRepository->findAll();
            foreach ($instruments as $instrument) {
                foreach ($instrument->getInstrumentExchange() as $instrumentExchange) {
                    $instrumentsExchange[] = $instrumentExchange;
                }
            }
        }

        // Deklaracja paska postępu
        $progressBar = null;
        // Jeżeli importujemy notowania dla wszystkich instrumentów, to wyświetlaj pasek postępu
        if (count($instrumentsExchange) > 1)
            $progressBar = new ProgressBar($output, count($instrumentsExchange));

        foreach ($instrumentsExchange as $instrumentExchange) {

            if ($progressBar != null)
                // Zwiększ postęp paska
                $progressBar->advance();
            else
                // Jeżeli importujemy notowania dla jednego instrumentu, to wyświetlaj ticker
                $io->writeln(sprintf('Importing quotes for ticker: %s', $instrumentExchange->getTicker()));

            // Jeżeli instrument ma zdefiniowany moduł importu notowań, to go użyj
            if ($instrumentExchange->getQuoteImportModule() != null) {
                $quoteImportModule = $this->importQuoteBuilder->getImportProvider($instrumentExchange->getQuoteImportModule());

                $instrumentExchangeLastQuote =  $instrumentExchange->getLastQuote();
                if($instrumentExchangeLastQuote==null)
                    $period1 = 0;
                else
                    $period1 = $instrumentExchangeLastQuote->getDate()->getTimestamp();

                $period2 = 9999999999;

                $quoteImportModule->import($instrumentExchange, $period1, $period2);
            }
        }


        $progressBar?->finish();
        $io->writeln('');

        return Command::SUCCESS;
    }


}