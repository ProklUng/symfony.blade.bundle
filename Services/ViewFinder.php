<?php

namespace Prokl\SymfonyBladeBundle\Services;

use Illuminate\View\FileViewFinder;

/**
 * Class ViewFinder
 * @package Prokl\SymfonyBladeBundle\Services
 */
class ViewFinder extends FileViewFinder
{
    /**
     * Setter for paths.
     *
     * @param array $paths Пути.
     *
     * @return $this
     */
    public function setPaths($paths) : self
    {
        $this->paths = $paths;

        return $this;
    }
}
