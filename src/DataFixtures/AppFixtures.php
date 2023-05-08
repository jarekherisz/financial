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
    public function load(ObjectManager $manager): void
    {
        // Clear tables
        $this->truncateEntities($manager, [Instrument::class, Quote::class]);

        // Load instruments
        $instrument = new Instrument();
        $instrument->setType('ETF');
        $instrument->setExchange('XETRA');
        $instrument->setSymbol('SPYD');
        $instrument->setYahooSymbol('SPYD');
        $instrument->setGoogleSymbol('XTR: SPYD');
        $instrument->setIsin('IE00B6YX5D40');
        $instrument->setDividendModule('divvydiary');
        $instrument->setQuoteModule(YahooFinanceImportQuoteProvider::class);
        $manager->persist($instrument);


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
