<?php

declare(strict_types=1);

namespace App\Media\Resolver;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;

final class ThumbnailResolver
{
    public function __construct(
        private readonly DataManager $dataManager,
        private readonly FilterManager $filterManager,
        private readonly CacheManager $cacheManager,
    ) {

    }

    public function resolveThumbnail(string $imagePath, string $filterName): string
    {
        $image = $this->dataManager->find($filterName, $imagePath);

        $filteredImage = $this->filterManager->applyFilter($image, $filterName);

        $this->cacheManager->store($filteredImage, $imagePath, $filterName, 'flysystem.resolver');

        return $this->cacheManager->resolve($imagePath, $filterName);
    }
}
