<?php

namespace Bureaupieper\StoreeBundle\DependencyInjection;

use Bureaupieper\StoreeBundle\DependencyInjection\Compiler\TestCompilerPass;
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

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('parameters.yml');
        $loader->load('services.yml');

        $logger = null;
        if ($config['logs']['enabled'] && $config['logs']['service']) {
            $logger = new Reference($config['logs']['service']);
        }
        unset($config['logs']['service']);

        $cacheDriver = null;
        if ($config['cache']['enabled'] && $config['cache']['service']) {
            $cacheDriver = new Reference($config['cache']['service']);
        }
        unset($config['cache']['service']);

        $guzzle = null;
        if (isset($config['guzzle_service'])) {
            $guzzle = $container->getDefinition($config['guzzle_service']);
        }
        unset($config['guzzle']);

        $client = new Definition('Bureaupieper\StoreeClient\Client', [
            $config,
            $guzzle,
            $cacheDriver,
            $logger
        ]);
        $container->setDefinition('bureaupieper_storee.client', $client);
    }
}
