<?php

namespace App\Service\ImportModules\Quote;

use App\Entity\Instrument;
use App\Entity\InstrumentExchange;
use App\Entity\Quote;
use App\Repository\QuoteRepository;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpClient\HttpClient;

class YahooFinanceImportQuoteProvider extends ImportProviderAbstract implements ImportProviderInterface
{
    private EntityManagerInterface $entityManager;
    private QuoteRepository $quoteRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->quoteRepository = $entityManager->getRepository(Quote::class);
    }

    /**
     * @throws Exception
     */
    public function import(InstrumentExchange $instrumentExchange, int $period1 = 0, int $period2 = 9999999999): void
    {
        $client = HttpClient::create();
        $url = sprintf(
            'https://query1.finance.yahoo.com/v7/finance/download/%s?period1=%d&period2=%d&interval=1d&events=history&includeAdjustedClose=true',
            $instrumentExchange->getTickerYacho(),
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

        $quotesArray = $this->quoteRepository->findArrayByInstrumentExchange($instrumentExchange);
        $i = 0;

        foreach ($lines as $line) {
            $data = str_getcsv($line);

            if (count($data) < 7) {
                throw new Exception("Data row is incomplete or has an incorrect number of columns");
            }

            $date = \DateTime::createFromFormat('Y-m-d', $data[0]);

            if(isset($quotesArray[$date->format('Y-m-d')]))
            {
                $quote = $quotesArray[$date->format('Y-m-d')];
            }else{
                $quote = new Quote();
                $quote->setDate($date);
            }


            $quote->setInstrumentExchange($instrumentExchange); // Assuming $symbol is an instance of Instrument entity

            $quote->setOpen($data[1] === 'null' ? null : $data[1]);
            $quote->setHigh($data[2] === 'null' ? null : $data[2]);
            $quote->setLow($data[3] === 'null' ? null : $data[3]);
            $quote->setClose($data[4] === 'null' ? null : $data[4]);
            $quote->setAdjClose($data[5] === 'null' ? null : $data[5]);
            $quote->setVolume($data[6] === 'null' ? null : $data[6]);


            $this->entityManager->persist($quote);

        }

        try {
            $this->entityManager->flush();
        } catch (DriverException $e) {
            echo $instrumentExchange->getTickerYacho() . PHP_EOL;

            echo 'SQLSTATE: ' . $e->getSQLState() . PHP_EOL;
            echo 'Query: ' . $e->getQuery()->getSQL() . PHP_EOL;
            foreach ($e->getQuery()->getParams() as $paramName => $param) {
                if ($param instanceof \DateTime) {
                    echo $paramName . ': ' . $param->format('Y-m-d H:i:s') . PHP_EOL;
                } else {
                    echo $paramName . ': ' . $param . PHP_EOL;
                }
            }


            throw new Exception($e->getMessage());
        } catch (\Exception $e) {
            echo  $instrumentExchange->getTickerYacho();
            throw new Exception($e->getMessage());
        }
    }
}