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

    public function getImportProvider(string $class): ImportProviderInterface
    {
        foreach($this->importProviders as $importProvider)
        {
            if($importProvider::class === $class)
                return $importProvider;
        }

        throw new Exception("No import provider '$class' found");
    }
}