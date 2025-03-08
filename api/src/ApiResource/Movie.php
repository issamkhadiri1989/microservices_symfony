<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Elasticsearch\State\CollectionProvider;
use ApiPlatform\Elasticsearch\State\ItemProvider;
use ApiPlatform\Elasticsearch\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;

#[ApiResource(
    operations: [
        new GetCollection(provider: CollectionProvider::class, stateOptions: new Options(index: 'movie')),
        new Get(provider: ItemProvider::class, stateOptions: new Options(index: 'movie')),
    ],
)]
class Movie
{
    #[ApiProperty(identifier: true)]
    public int $id;

    public string $synopsis;

    private string $name;

    /** @var Genre[]  */
    public iterable $genres;

    public ?string $coverPath = null;
}
