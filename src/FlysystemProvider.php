<?php

namespace Laasti\FlysystemProvider;

use League\Container\ServiceProvider;
use League\Flysystem\MountManager;

class FlysystemProvider extends ServiceProvider\AbstractServiceProvider implements ServiceProvider\BootableServiceProviderInterface
{
    protected $defaultProvides = [
        'League\Flysystem\MountManager',
        'League\Flysystem\FilesystemInterface',
    ];

    public function register()
    {
        $di= $this->getContainer();
        $adapters = $this->getConfig();

        $first = true;
        foreach ($adapters as $name => $config) {
            list($adapterClass, $adapterArgs) = $config + [null, []];
            $di->add('flysystem.adapter.' . $name, $adapterClass, true)->withArguments($adapterArgs);
            $di->add('flysystem.filesystem.' . $name, 'League\Flysystem\Filesystem', true)->withArguments(['flysystem.adapter.' . $name]);

            if ($first) {
                $di->add('League\Flysystem\FilesystemInterface', function() use ($di, $name) {
                    return $di->get('flysystem.filesystem.' . $name);
                }, true);
                $first = false;
            }
        }

        $di->add('League\Flysystem\MountManager', function() use ($di, $adapters) {
            $adapterNames = array_keys($adapters);
            $adaptersInstances = [];
            foreach ($adapterNames as $name) {
                $adaptersInstances[$name] = $di->get('flysystem.filesystem.' . $name);
            }

            return new MountManager($adaptersInstances);
        }, true);

    }

    public function boot()
    {
        $this->getContainer()->inflector('Laasti\FlysystemProvider\MountManagerAwarerInterface')
             ->invokeMethod('setMountManager', ['League\Flysystem\MountManager']);
    }

    public function provides($alias = null)
    {
        if (!count($this->provides)) {
            $this->provides = $this->defaultProvides;
            $adapterNames = array_keys($this->getConfig());
            foreach ($adapterNames as $name) {
                $this->provides[] = 'flysystem.adapter.' . $name;
            }
        }

        return parent::provides($alias);
    }

    protected function getConfig()
    {
        $di = $this->getContainer();
        if ($di->has('config') && isset($di->get('config')['flysystem'])) {
            return $di->get('config')['flysystem'];
        }

        return [];
    }
}
