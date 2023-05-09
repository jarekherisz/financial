<?php

namespace App\Command;



use App\Entity\Instrument;
use App\Entity\InstrumentExchange;
use App\Repository\InstrumentExchangeRepository;
use App\Repository\InstrumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(name: 'app:import-instruments', description: 'Import financial instruments')]
class ImportInstrumentCommand extends Command
{
    private InstrumentRepository $instrumentRepository;
    private InstrumentExchangeRepository $instrumentExchangeRepository;
    private KernelInterface $kernel;
    private EntityManagerInterface $entityManager;

    public function __construct(KernelInterface $kernel,
                                EntityManagerInterface $entityManager,
                                InstrumentRepository $instrumentRepository, 
                                InstrumentExchangeRepository $instrumentExchangeRepository)
    {

        $this->instrumentRepository = $instrumentRepository;
        $this->instrumentExchangeRepository = $instrumentExchangeRepository;
        $this->entityManager = $entityManager;
        $this->kernel = $kernel;
        
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $projectDir = $this->kernel->getProjectDir();
        $records = $this->readCsv($projectDir.DIRECTORY_SEPARATOR."data".DIRECTORY_SEPARATOR."instruments.csv");

        foreach ($records as $record) {
            $instrument = $this->instrumentRepository->findOneBy(['isin' => $record['isin']]);


            if (!$instrument) {
                $instrument = new Instrument();
                $instrument->setIsin($record['isin']);
            }


            $instrument->setType($record['type']);
            $instrument->setInvestmentRegion($record['investmentRegion']);
            $instrument->setInvestmentSubject($record['investmentSubject']);
            $instrument->setFullName($record['fullName']);
            $instrument->setManagedBy($record['managedBy']);
            $instrument->setReplicationType($record['replicationType']);

            $this->instrumentRepository->save($instrument, true);

            $instrumentExchange = $this->instrumentExchangeRepository->findOneBy(['instrument' => $instrument, 'exchange' => $record['exchange'], 'ticker' => $record['ticker']]);
            if (!$instrumentExchange) {
                $instrumentExchange = new InstrumentExchange();
                $instrumentExchange->setInstrument($instrument);
                $instrumentExchange->setExchange($record['exchange']);
                $instrumentExchange->setTicker($record['ticker']);

            }
            $instrumentExchange->setTickerGoogle($record['tickerGoogle']);
            $instrumentExchange->setTickerYacho($record['tickerYahoo']);
            $instrumentExchange->setCurrency($record['currency']);
            $this->instrumentExchangeRepository->save($instrumentExchange, true);
        }

        $output->writeln('Import completed.');


        return Command::SUCCESS;
    }

    public function readCsv(string $filePath): array
    {
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);

        return iterator_to_array($csv->getRecords());
    }
}