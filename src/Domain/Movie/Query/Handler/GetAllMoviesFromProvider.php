<?php

declare(strict_types=1);

namespace App\Domain\Movie\Query\Handler;

use Elastica\Index;
use Elastica\Query;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class GetAllMoviesFromProvider implements QueryInterface
{
    public function __construct(
        #[Autowire(service: 'fos_elastica.index.movie')] private readonly Index $index,
    ) {

    }

    public function getItems(): array
    {
        // get 15 documents from Elasticsearch
        $query = new Query(new Query\MatchAll());

        $query->setSize(15);
        $query->setFrom(0);

        $docs = $this->index->search($query);

        return $docs->getResults();
    }
}
