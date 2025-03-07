<?php

declare(strict_types=1);

namespace App\EventListener\Movie;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;
use Elastica\Document;
use Elastica\Index;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AsEntityListener(entity: Movie::class, method: 'indexMovieOnPostPersist', event: Events::postPersist)]
class MovieIndexationListener
{
    public function __construct(
        private readonly NormalizerInterface $normalizer,
        #[Autowire(service: 'fos_elastica.index.movie')] private readonly Index $index,
        #[Autowire(env: "ENABLE_INDEXATION")] private readonly bool $indexationEnabled,
    ) {

    }

    // had listener rah y tlanca a chaque fois tzid wahd l movie f l bdd. normalement hada makhassch ykon ila kona radi nfouto b rabbit MQ
    public function indexMovieOnPostPersist(Movie $movie, PostPersistEventArgs $event): void
    {
        if (false === $this->indexationEnabled) {
            return;
        }
        $data = $this->normalizer->normalize($movie, context: [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (object $object, ?string $format, array $context): string {
                return $object->getName();
            },
            AbstractNormalizer::ATTRIBUTES => ['id', 'name', 'synopsis', 'genres' => ['id', 'name']],
        ]);
        $document = new Document((string)$movie->getId(), $data);
        $this->index->addDocument($document);
    }
}
