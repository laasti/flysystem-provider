<?php

namespace Laasti\FlysystemProvider;

use Exception;
use League\Container\ServiceProvider;
use League\Flysystem\MountManager;
use RuntimeException;

class FlysystemProvider extends ServiceProvider
{
    protected $defaultProvides = [
        'League\Flysystem\MountManager',
        'League\Flysystem\FilesystemInterface',
    ];

    public function register()
    {
        $di= $this->getContainer();
        
        $adapters = $di['config.flysystem'];

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

    public function provides($alias = null)
    {
        if (!count($this->provides)) {
            $this->provides = $this->defaultProvides;
            try {
                $adapters = $this->getContainer()['config.flysystem'];

                if (!is_array($adapters) || count($adapters) === 0) {
                    throw new Exception();
                }
            } catch (Exception $e) {
                throw new RuntimeException('To use FlysystemProvider, you must add an array of adapters to the container using the key "config.flysystem".');
            }
            $adapterNames = array_keys($adapters);
            foreach ($adapterNames as $name) {
                $this->provides[] = 'flysystem.adapter.' . $name;
            }
        }

        return parent::provides($alias);
    }
}
