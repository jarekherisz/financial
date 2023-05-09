<?php

namespace App\DataFixtures;

use App\Entity\Instrument;
use App\Entity\Quote;
use App\Service\ImportModules\Quote\YahooFinanceImportQuoteProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use Exception;

class AppFixtures extends Fixture
{
    protected ObjectManager $manager;

    protected function createInstrument($type, $exchange, $symbol, $yahooSymbol, $googleSymbol, $isin, $dividendModule, $quoteModule): Instrument
    {
        $instrument = new Instrument();
        $instrument->setType($type);
        $instrument->setExchange($exchange);
        $instrument->setSymbol($symbol);
        $instrument->setYahooSymbol($yahooSymbol);
        $instrument->setGoogleSymbol($googleSymbol);
        $instrument->setIsin($isin);
        $instrument->setDividendModule($dividendModule);
        $instrument->setQuoteModule($quoteModule);
        $this->manager->persist($instrument);
        return $instrument;
    }


    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        // Clear tables
        $this->truncateEntities($manager, [Instrument::class, Quote::class]);

        // Load instruments
        $this->createInstrument('ETF', 'XETRA', 'SPYD', 'SPYD',
            'XTR:SPYD', 'IE00B6YX5D40', 'divvydiary',
            YahooFinanceImportQuoteProvider::class);

        $this->createInstrument('ETF', 'XETRA', 'EUNL', 'EUNL.DE',
            'FRA:EUNL', 'IE00B4L5Y983', 'divvydiary',
            YahooFinanceImportQuoteProvider::class);


        $manager->flush();
    }

    /**
     * @throws Exception
     */
    private function truncateEntities(ObjectManager $manager, array $entities): void
    {
        /** @var Connection $db */
        $connection = $manager->getConnection();

        $connection->beginTransaction();

        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=0');
        foreach ($entities as $entity) {
            $cmd = $manager->getClassMetadata($entity);
            $connection->executeStatement('TRUNCATE TABLE ' . $cmd->getTableName());
        }
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=1');

        $connection->commit();
        $connection->beginTransaction();
    }


}
