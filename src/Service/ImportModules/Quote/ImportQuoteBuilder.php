<?php

namespace App\Service\ImportModules\Quote;

use App\Entity\Instrument;

class ImportQuoteBuilder
{

    public function __construct(
        /**
         * @var ImportProviderInterface[]
         */
        private readonly iterable $importProviders
    )
    {

    }

    public function getImportProvider(Instrument $instrument): ImportProviderInterface
    {
        foreach($this->importProviders as $importProvider)
        {
            if($importProvider::class === $instrument->getQuoteModule())
                return $importProvider;
        }

        throw new Exception("No import provider found for symbol $instrument->getSymbol()");
    }
}