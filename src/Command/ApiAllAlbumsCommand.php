<?php

namespace App\Command;

use App\Service\InnerResponse;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ApiAllAlbumsCommand extends Command
{
    protected static $defaultName = 'api:all-albums';
    protected static $defaultDescription = 'Add a short description for your command';
    private InnerResponse $innerResponse;

    public function __construct(string $name = null, InnerResponse $innerResponse)
    {
        $this->innerResponse = $innerResponse;
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response= $this->innerResponse->fetchAllAlbums();
        echo $response;
        return Command::SUCCESS;
    }
}
