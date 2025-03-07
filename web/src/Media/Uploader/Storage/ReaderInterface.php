<?php

declare(strict_types=1);

namespace App\Media\Uploader\Storage;

interface ReaderInterface
{
    public function read(string $path): void;
}
