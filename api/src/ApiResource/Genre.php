<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Elasticsearch\State\CollectionProvider;
use ApiPlatform\Elasticsearch\State\Options;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata as Metadata;

#[ApiResource(
    operations: [
        new Metadata\GetCollection(provider: CollectionProvider::class, stateOptions: new Options(index: 'genre')),
        new Metadata\Get(provider: ItemProvider::class, stateOptions: new Options(index: 'genre')),
    ],
)]
class Genre
{
    #[Metadata\ApiProperty(identifier: true)]
    public int $id;

    public string $name;
}
