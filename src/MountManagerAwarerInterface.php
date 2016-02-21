<?php

namespace Laasti\FlysystemProvider;

interface MountManagerAwareInterface
{
    /**
     * @return \League\Flysystem\MountManager
     */
    public function getMountManager();

    /**
     *
     * @param \League\Flysystem\MountManager $manager
     */
    public function setMountManager(\League\Flysystem\MountManager $manager);

}
