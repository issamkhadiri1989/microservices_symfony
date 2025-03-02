<?php

declare(strict_types=1);

namespace App\Domain\Movie\Query\Handler;

use Elastica\Index;
use Elastica\Query;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class GetAllMoviesFromProvider implements QueryItemsInterface
{
    public function __construct(
        #[Autowire(service: 'fos_elastica.index.movie')] private readonly Index $index,
    ) {

    }

    public function getAllMovies(int $offset = 0, int $limit = 10): array
    {
        // get 15 documents from Elasticsearch
        $query = new Query(new Query\MatchAll());

        $query->setSize($limit);
        $query->setFrom($offset);
        $query->addSort([
            'id' => 'desc',
        ]);

        $docs = $this->index->search($query);

        return $docs->getResults();
    }
}
