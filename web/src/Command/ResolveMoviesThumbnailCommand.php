<?php

declare(strict_types=1);

namespace App\Command;

use App\Media\Uploader\Storage\ReaderInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:movie:thumbnail:resolve')]
class ResolveMoviesThumbnailCommand extends Command
{
    public function __construct(
        private readonly ReaderInterface $reader,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->reader->read('original/');

        return Command::SUCCESS;
    }
}
