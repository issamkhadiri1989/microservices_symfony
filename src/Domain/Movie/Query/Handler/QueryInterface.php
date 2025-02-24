<?php

declare(strict_types=1);

namespace App\Domain\Movie\Query\Handler;

interface QueryInterface
{
    public function getItems(): array;
}
