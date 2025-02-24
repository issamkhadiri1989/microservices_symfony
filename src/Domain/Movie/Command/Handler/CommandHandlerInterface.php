<?php

declare(strict_types=1);

namespace App\Domain\Movie\Command\Handler;

use App\Entity\Movie;

interface CommandHandlerInterface
{
    public function handle(Movie $movie): void;
}
