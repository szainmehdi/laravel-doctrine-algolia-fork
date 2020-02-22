<?php

namespace Zain\LaravelDoctrine\Algolia\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zain\LaravelDoctrine\Algolia\SearchService;

/**
 * @internal
 */
abstract class IndexCommand extends Command
{
    /**
     * @var SearchService
     */
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;

        parent::__construct();
    }

    /**
     * @return array<string, string>
     */
    protected function getEntitiesFromArgs(InputInterface $input, OutputInterface $output)
    {
        $entities   = [];
        $indexNames = [];

        if ($indexList = $input->getOption('indices')) {
            $indexNames = explode(',', $indexList);
        }

        $config = $this->searchService->getConfiguration();

        if (count($indexNames) === 0) {
            $indexNames = array_keys($config['indices']);
        }

        foreach ($indexNames as $name) {
            if (isset($config['indices'][$name])) {
                $entities[$name] = $config['indices'][$name]['class'];
            } else {
                $output->writeln('<comment>No index named <info>' . $name . '</info> was found. Check you configuration.</comment>');
            }
        }

        return $entities;
    }
}
