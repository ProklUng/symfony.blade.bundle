<?php

namespace Prokl\SymfonyBladeBundle;

use Prokl\SymfonyBladeBundle\DependencyInjection\SymfonyBladeExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class SymfonyBladeBundle
 * @package Prokl\SymfonyBladeBundle
 *
 * @since 08.03.2021
 */
final class SymfonyBladeBundle extends Bundle
{
   /**
   * @inheritDoc
   */
    public function getContainerExtension()
    {
        if ($this->extension === null) {
            $this->extension = new SymfonyBladeExtension();
        }

        return $this->extension;
    }
}
