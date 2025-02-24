<?php

declare(strict_types=1);

namespace App\Domain\Movie\Command\Handler;

use App\Entity\Movie;
use Elastica\Document;
use Elastica\Index;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AsDecorator(decorates: DatabaseMovieHandler::class)]
final class ElasticsearchMovieHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly NormalizerInterface $normalizer,
        #[Autowire(service: 'fos_elastica.index.movie')] private readonly Index $index,
        #[AutowireDecorated]
        private readonly CommandHandlerInterface $innerHandler,
    ) {

    }

    public function handle(Movie $movie): void
    {
        $this->innerHandler->handle($movie);

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
