<?php

declare(strict_types=1);

namespace App\Domain\Movie\Query\Handler;

interface QueryItemsInterface
{
    public function getAllMovies(int $offset = 0, int $limit = 10): array;
}
