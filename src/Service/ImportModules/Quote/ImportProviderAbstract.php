<?php

namespace App\Service\ImportModules\Quote;

use InvalidArgumentException;
use ReflectionFunction;

abstract class ImportProviderAbstract implements ImportProviderInterface
{


    /**
     * @var callable
     */
    protected $progressCallback;

    /**
     * @throws \ReflectionException
     */
    public function setProgressCallback(callable $progressCallback): void
    {
        $reflection = new ReflectionFunction($progressCallback);
        if ($reflection->getNumberOfParameters() !== 2) {
            throw new InvalidArgumentException('Callback must take exactly two arguments');
        }

        $this->progressCallback = $progressCallback;
    }

    protected function progressCallback(int $current, int $total): void
    {
        if ($this->progressCallback !== null) {
            call_user_func($this->progressCallback, $current, $total);
        }
    }
}