<?php

namespace Laasti\FlysystemProvider;

trait MountManagerAwareTrait
{
    /**
     * Flysystem Mount Manager
     * var \League\Flysystem\MountManager
     */
    protected $mountManager;

    /**
     * Get Translator instance
     * @return \League\Flysystem\MountManager
     */
    public function getMountManager()
    {
        return $this->mountManager;
    }

    /**
     * Set Flysystem Mount Manager
     * @param \League\Flysystem\MountManager $mountManager
     * @return \League\Flysystem\MountManager
     */
    public function setMountManager(\League\Flysystem\MountManager $mountManager)
    {
        $this->mountManager = $mountManager;
        return $this->mountManager;
    }
}
