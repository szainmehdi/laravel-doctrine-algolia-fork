<?php

namespace Zain\LaravelDoctrine\Algolia\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zain\LaravelDoctrine\Algolia\Settings\SettingsManager;

/**
 * @internal
 */
abstract class SearchSettingsCommand extends Command
{
    /**
     * @var SettingsManager
     */
    protected $settingsManager;

    public function __construct(SettingsManager $settingsManager)
    {
        $this->settingsManager = $settingsManager;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($indexList = $input->getOption('indices')) {
            $indexList = explode(',', $indexList);
        }

        $params = [
            'indices' => (array) $indexList,
            'extra'   => $input->getArgument('extra'),
        ];

        $message = $this->handle($params);

        $output->writeln($message);

        return 0;
    }

    /**
     * @param array<string, array> $params
     *
     * @return array<int, string>
     */
    abstract protected function handle($params);
}
