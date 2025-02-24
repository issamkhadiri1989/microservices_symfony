<?php

declare(strict_types=1);

namespace App\Domain\Movie\Command\Handler;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias]
final class DatabaseMovieHandler implements CommandHandlerInterface
{
    public function __construct(private readonly EntityManagerInterface $manager)
    {
    }


    public function handle(Movie $movie): void
    {
        $this->manager->persist($movie);
        $this->manager->flush();
    }
}
