<?php

namespace App\Service\ImportModules\Quote;

use App\Entity\Instrument;
use App\Entity\InstrumentExchange;

interface ImportProviderInterface
{
    public function import(InstrumentExchange $instrumentExchange, int $period1 = 0, int $period2 = 9999999999): void;

    public function setProgressCallback(callable $progressCallback): void;

}