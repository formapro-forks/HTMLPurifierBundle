<?php

namespace Bundle\ExerciseCom\HTMLPurifierBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class HTMLPurifierExtension extends Extension
{
    protected $resources = array(
        'htmlpurifier' => 'htmlpurifier.xml',
    );

    public function configLoad(array $configs, ContainerBuilder $container)
    {
        foreach ($configs as $config) {
            $this->doConfigLoad($config, $container);
        }
    }

    public function doConfigLoad(array $config, ContainerBuilder $container)
    {
        if (!$container->hasDefinition('htmlpurifier')) {
            $this->loadDefaults($container);
        }

        if (isset($config['alias'])) {
            $container->setAlias($config['alias'], 'htmlpurifier');
        }

        foreach (array('allowed_html', 'base_uri', 'absolute_uri', 'namespace') as $attribute) {
            if (isset($config[$attribute])) {
                $container->setParameter('htmlpurifier.'.$attribute, $config[$attribute]);
            }
        }
    }

    /**
     * Returns the base path for the XSD files.
     *
     * @return string The XSD base path
     */
    public function getXsdValidationBasePath()
    {
        return null;
    }

    public function getNamespace()
    {
        return 'http://www.symfony-project.org/schema/dic/symfony';
    }

    public function getAlias()
    {
        return 'htmlpurifier';
    }

    protected function loadDefaults($container)
    {
        $loader = new XmlFileLoader($container, __DIR__.'/../Resources/config');
        $loader->load($this->resources['htmlpurifier']);
    }
}
