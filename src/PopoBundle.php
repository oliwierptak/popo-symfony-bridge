<?php declare(strict_types = 1);

namespace PopoBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use function dirname;

class PopoBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->arrayNode('config')
                    ->arrayPrototype()
                    ->children()
                        ->scalarNode('schemaPath')->isRequired()->end()
                        ->scalarNode('namespace')->end()
                        ->scalarNode('outputPath')->end()
                        ->scalarNode('namespaceRoot')->end()
                        ->scalarNode('schemaPathFilter')->end()
                        ->scalarNode('schemaConfigFilename')->end()
                        ->booleanNode('ignoreNonExistingSchemaFolder')->defaultValue(false)->end()
                        ->scalarNode('schemaFilenameMask')->defaultValue('*.popo.yaml')->end()
                        ->arrayNode('classPluginCollection')->end()
                        ->arrayNode('mappingPolicyPluginCollection')->end()
                        ->arrayNode('namespacePluginCollection')->end()
                        ->arrayNode('phpFilePluginCollection')->end()
                        ->arrayNode('propertyPluginCollection')->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('default')
                ->children()
                    ->scalarNode('namespace')->end()
                    ->scalarNode('outputPath')->end()
                    ->scalarNode('namespaceRoot')->end()
                    ->scalarNode('schemaPathFilter')->end()
                    ->scalarNode('schemaConfigFilename')->end()
                    ->booleanNode('ignoreNonExistingSchemaFolder')->end()
                    ->scalarNode('schemaFilenameMask')->end()
                    ->arrayNode('classPluginCollection')->end()
                    ->arrayNode('mappingPolicyPluginCollection')->end()
                    ->arrayNode('namespacePluginCollection')->end()
                    ->arrayNode('phpFilePluginCollection')->end()
                    ->arrayNode('propertyPluginCollection')->end()
                ->end()
            ->end()
        ->end();
    }

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

    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}