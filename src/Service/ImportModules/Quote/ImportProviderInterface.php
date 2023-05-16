<?php

namespace App\Service\ImportModules\Quote;

use App\Entity\Instrument;
use App\Entity\InstrumentExchange;

interface ImportProviderInterface
{
    public function import(InstrumentExchange $instrumentExchange): void;

    public function setProgressCallback(callable $progressCallback): void;

}