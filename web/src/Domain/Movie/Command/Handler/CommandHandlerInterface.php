<?php

declare(strict_types=1);

namespace App\Domain\Movie\Command\Handler;

use App\Entity\Movie;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface CommandHandlerInterface
{
    public function handle(Movie $movie, ?UploadedFile $cover = null): void;
}
