<?php

declare(strict_types=1);

namespace App\Media\Uploader\Storage;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploadInterface
{
    public function upload(UploadedFile $file): string;
}
