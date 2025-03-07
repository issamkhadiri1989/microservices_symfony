<?php

declare(strict_types=1);

namespace App\Media\Uploader\Storage;

use App\Media\Resolver\ThumbnailResolver;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class AwsStorageUploader implements UploadInterface, ReaderInterface
{
    public function __construct(
        #[Autowire(service: 's3.storage')]
        private readonly FilesystemOperator $storage,
        private readonly SluggerInterface $slugger,
        private readonly ThumbnailResolver $thumbnailResolver,
    ) {

    }

    public function upload(UploadedFile $file): string
    {
        if ($stream = \fopen($file->getRealPath(), 'r')) {
            $targetFilename = $this->generateFilename($file);

            $this->storage->writeStream('original/' . $targetFilename, $stream);

            \fclose($stream);

            return $targetFilename;
        }

        return '';
    }

    private function generateFilename(UploadedFile $file): string
    {
        $originalFilename = \pathinfo($file->getClientOriginalName(), \PATHINFO_FILENAME);

        $originalFilename = $this->slugger->slug($originalFilename);

       return \sprintf('%s__%s.%s', $originalFilename, \uniqid(), $file->guessExtension());
    }

    public function read(string $path): void
    {
        $items = $this->storage->listContents($path);
        foreach ($items as $item) {
            $path = $item->path();
            if ($item instanceof FileAttributes) {
                $this->thumbnailResolver->resolveThumbnail($path, 'my_thumb');
            }
        }
    }
}
