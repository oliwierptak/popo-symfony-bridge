<?php declare(strict_types = 1);

namespace PopoBundle;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use function dirname;

class PopoBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        /** @phpstan-ignore-next-line */
        $definition->rootNode()->append(
            $this->configureConfigDefinition()
        );

        /** @phpstan-ignore-next-line */
        $definition->rootNode()->append(
            $this->configureDefaultDefinition()
        );
    }

    public function getPath(): string
    {
        return dirname(__DIR__);
    }

    private function configureConfigDefinition(): NodeDefinition|ArrayNodeDefinition
    {
        $rootNode = (new TreeBuilder('config'))->getRootNode();
        /** @phpstan-ignore-next-line */
        $rootNode->arrayPrototype()
            ->children()
                ->scalarNode('schemaPath')->isRequired()->end()
                ->scalarNode('namespace')->end()
                ->scalarNode('outputPath')->end()
                ->scalarNode('namespaceRoot')->end()
                ->scalarNode('schemaPathFilter')->end()
                ->scalarNode('schemaConfigFilename')->end()
                ->booleanNode('ignoreNonExistingSchemaFolder')->defaultValue(false)->end()
                ->scalarNode('schemaFilenameMask')->defaultValue('*.popo.yaml')->end()
                ->arrayNode('classPluginCollection')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('mappingPolicyPluginCollection')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('namespacePluginCollection')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('phpFilePluginCollection')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('propertyPluginCollection')
                    ->scalarPrototype()->end()
                ->end()
            ->end()
        ->end();

        return $rootNode;
    }

    private function configureDefaultDefinition(): NodeDefinition|ArrayNodeDefinition
    {
        $rootNode = (new TreeBuilder('default'))->getRootNode();
        /** @phpstan-ignore-next-line */
        $rootNode->children()
            ->scalarNode('namespace')->end()
            ->scalarNode('outputPath')->end()
            ->scalarNode('namespaceRoot')->end()
            ->scalarNode('schemaPathFilter')->end()
            ->scalarNode('schemaConfigFilename')->end()
            ->booleanNode('ignoreNonExistingSchemaFolder')->end()
            ->scalarNode('schemaFilenameMask')->end()
            ->arrayNode('classPluginCollection')
                ->scalarPrototype()->end()
            ->end()
            ->arrayNode('mappingPolicyPluginCollection')
                ->scalarPrototype()->end()
            ->end()
            ->arrayNode('namespacePluginCollection')
                ->scalarPrototype()->end()
            ->end()
            ->arrayNode('phpFilePluginCollection')
                ->scalarPrototype()->end()
            ->end()
            ->arrayNode('propertyPluginCollection')
                ->scalarPrototype()->end()
            ->end()
        ->end();

        return $rootNode;
    }

    /**
     * @param array<string,mixed> $config
     */
    public function loadExtension(
        array $config,
        ContainerConfigurator $container,
        ContainerBuilder $builder): void
    {
        $container->import('../config/services.yaml');

        $container->services()
            ->get('popo.command.popo')
            ->arg(0, null)
            ->arg(1, $config);
    }
}