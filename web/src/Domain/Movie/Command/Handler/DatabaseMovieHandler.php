<?php

declare(strict_types=1);

namespace App\Domain\Movie\Command\Handler;

use App\Entity\Movie;
use App\Media\Uploader\Storage\UploadInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[AsAlias]
final class DatabaseMovieHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly UploadInterface $upload,
    ) {

    }

    public function handle(Movie $movie, ?UploadedFile $cover = null): void
    {
        $this->manager->persist($movie);

        if (null !== $cover) {
            $cover = $this->upload->upload($cover);
            $movie->setCoverPath($cover);
        }

        $this->manager->flush();
    }
}
