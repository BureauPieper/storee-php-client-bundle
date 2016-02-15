<?php

namespace Bureaupieper\StoreeBundle\DependencyInjection;

use Bureaupieper\StoreeBundle\DependencyInjection\Compiler\TestCompilerPass;
use Bureaupieper\StoreeClient\Client\Config;
use Bureaupieper\StoreeClient\Resources\ConfigTree;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BureaupieperStoreeExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

//        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
//        $loader->load('services.yml');

        $logger = null;
        if ($config['logs']['enabled'] && $config['logs']['service']) {
            $logger = new Reference($config['logs']['service']);
        }

        $cacheDriver = null;
        if ($config['cache']['enabled'] && $config['cache']['service']) {
            $cacheDriver = new Reference($config['cache']['service']);
        }

        $guzzle = null;
        if (isset($config['guzzle_service'])) {
            $guzzle = $container->getDefinition($config['guzzle_service']);
        }

        // Remove the values for childNodes that can be enabled which are set to false by the processor.
        // @see https://github.com/symfony/symfony/issues/17153
        if (!$config['logs']['default_driver']['mail']['enabled']) {
            unset($config['logs']['default_driver']['mail']);
        }

        /**
         * Standalone client also uses the symfony/config component
         */
        $tree = new Definition();
        $tree->setClass('Symfony\Component\Config\Definition\Builder\TreeBuilder');
        $tree->setFactory('Bureaupieper\StoreeClient\Resources\ConfigTree::get');
        $tree->setArguments(['symfony']);

        $def = new Definition();
        $def->setClass('Bureaupieper\StoreeClient\Client\Config');
        $def->setArguments([$config, $tree]);

        $client = new Definition('Bureaupieper\StoreeClient\Client', [
            $def,
            $guzzle,
            $cacheDriver,
            $logger
        ]);
        $client->addMethodCall('setDebug', [$container->getParameter('kernel.debug')]);

        $container->setDefinition('bureaupieper_storee.client', $client);
    }
}

