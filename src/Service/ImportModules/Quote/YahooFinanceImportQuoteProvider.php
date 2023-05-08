<?php

namespace App\Service\ImportModules\Quote;

use App\Entity\Instrument;
use App\Entity\Quote;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpClient\HttpClient;

class YahooFinanceImportQuoteProvider implements ImportProviderInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws Exception
     */
    public function import(Instrument $instrument, int $period1 = 0, int $period2 = 9999999999): void
    {
        $client = HttpClient::create();
        $url = sprintf(
            'https://query1.finance.yahoo.com/v7/finance/download/%s?period1=%d&period2=%d&interval=1d&events=history&includeAdjustedClose=true',
            $instrument->getYahooSymbol(),
            $period1,
            $period2
        );

        $response = $client->request('GET', $url);
        $content = $response->getContent();

        $lines = explode("\n", $content);
        $header = str_getcsv(array_shift($lines));

        // Sprawdź, czy nagłówki są w odpowiedniej kolejności
        $expectedHeader = ['Date', 'Open', 'High', 'Low', 'Close', 'Adj Close', 'Volume'];
        if ($header !== $expectedHeader) {
            throw new Exception("Headers are incorrect or do not match the expected structure");
        }

        foreach ($lines as $line) {
            $data = str_getcsv($line);

            if (count($data) < 7) {
                throw new Exception("Data row is incomplete or has an incorrect number of columns");
            }

            $quote = new Quote();
            $quote->setInstrument($instrument); // Assuming $symbol is an instance of Instrument entity
            $quote->setDate(\DateTime::createFromFormat('Y-m-d', $data[0]));
            $quote->setOpen($data[1]);
            $quote->setHigh($data[2]);
            $quote->setLow($data[3]);
            $quote->setClose($data[4]);
            $quote->setAdjClose($data[5]);
            $quote->setVolume($data[6]);

            $this->entityManager->persist($quote);
        }

        $this->entityManager->flush();
    }
}