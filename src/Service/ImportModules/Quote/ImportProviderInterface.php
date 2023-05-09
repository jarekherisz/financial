<?php

namespace App\Service\ImportModules\Quote;

use App\Entity\Instrument;

interface ImportProviderInterface
{
    public function import(Instrument $instrument): void;

    public function setProgressCallback(callable $progressCallback): void;

}