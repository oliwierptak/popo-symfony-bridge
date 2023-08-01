<?php declare(strict_types = 1);

namespace PopoBundle\Command;

use Popo\Command\GenerateCommand;
use Popo\PopoConfigurator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'popo:generate',
    description: 'Run POPO generator.',
    hidden: false
)]
class PopoGenerateCommand extends GenerateCommand
{
    /**
     * @var array<string,mixed>
     */
    private array $configurationData;

    /**
     * @param array<string,mixed> $data
     */
    public function __construct(string $name = null, array $data = [])
    {
        parent::__construct($name);

        $this->configurationData = $data;
    }

    protected function executeCommand(InputInterface $input, OutputInterface $output): int
    {
        $data = $this->configurationData['config'] ?? [];
        array_walk($data, function (array &$item) {
            $item = array_merge(
                $this->configurationData['default'] ?? [],
                $item,
            );
        });

        foreach ($data as $config) {
            $this->buildPopoInput($input, $config);

            $result = parent::executeCommand($input, $output);
            if ($result === Command::FAILURE) {
                return $result;
            }
        }

        return Command::SUCCESS;
    }

    /**
     * @param array<string,mixed> $config
     */
    protected function buildPopoInput(InputInterface $input, array $config): void
    {
        $configurator = (new PopoConfigurator())
            ->fromArray($config)
            ->setShouldIgnoreNonExistingSchemaFolder(
                (bool) ($input->hasOption(static::OPTION_IGNORE_NON_EXISTING_SCHEMA_FOLDER) ? $input->getOption(
                    static::OPTION_IGNORE_NON_EXISTING_SCHEMA_FOLDER
                ) : false)
            );

        $input->setOption(static::OPTION_SCHEMA_PATH, $configurator->getSchemaPath());
        $input->setOption(static::OPTION_SCHEMA_PATH_FILTER, $configurator->getSchemaPathFilter());
        $input->setOption(static::OPTION_SCHEMA_CONFIG_FILENAME, $configurator->getSchemaConfigFilename());
        $input->setOption(static::OPTION_SCHEMA_FILENAME_MASK, $configurator->getSchemaFilenameMask() ?? '*.popo.yaml');
        $input->setOption(static::OPTION_OUTPUT_PATH, $configurator->getOutputPath());
        $input->setOption(static::OPTION_NAMESPACE, $configurator->getNamespace());
        $input->setOption(static::OPTION_NAMESPACE_ROOT, $configurator->getNamespaceRoot());
        $input->setOption(static::OPTION_IGNORE_NON_EXISTING_SCHEMA_FOLDER, $configurator->shouldIgnoreNonExistingSchemaFolder());
        $input->setOption(static::OPTION_CLASS_PLUGIN_COLLECTION, $configurator->getClassPluginCollection());
        $input->setOption(static::OPTION_MAPPING_POLICY_PLUGIN_COLLECTION, $configurator->getMappingPolicyPluginCollection());
        $input->setOption(static::OPTION_NAMESPACE_PLUGIN_COLLECTION, $configurator->getNamespacePluginCollection());
        $input->setOption(static::OPTION_PHP_FILE_PLUGIN_COLLECTION, $configurator->getPhpFilePluginCollection());
        $input->setOption(static::OPTION_PROPERTY_PLUGIN_COLLECTION, $configurator->getPropertyPluginCollection());
    }
}