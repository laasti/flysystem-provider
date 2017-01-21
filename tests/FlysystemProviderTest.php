<?php

namespace Laasti\FlysystemProvider\Tests;

use Laasti\FlysystemProvider\FlysystemProvider;
use League\Container\Container;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Adapter\NullAdapter;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\MountManager;

class FlysystemProviderTest extends \PHPUnit_Framework_TestCase
{

    public function testProvider()
    {
        $container = new Container();
        $container->add('config', [
            'flysystem' => [
                'file' => ['League\Flysystem\Adapter\Local', [__DIR__]],
                'null' => ['League\Flysystem\Adapter\NullAdapter']
            ]
        ]);
        $container->addServiceProvider(new FlysystemProvider);
        $default = $container->get('League\Flysystem\FilesystemInterface');
        $manager = $container->get('League\Flysystem\MountManager');
        $byName = $container->get('flysystem.adapter.null');

        $this->assertTrue($default instanceof FilesystemInterface);
        $this->assertTrue($manager instanceof MountManager);
        $this->assertTrue($byName instanceof NullAdapter);
        $this->assertTrue($manager->getAdapter('file://') instanceof Local);
        $this->assertTrue($manager->getAdapter('null://') instanceof NullAdapter);
    }
}
