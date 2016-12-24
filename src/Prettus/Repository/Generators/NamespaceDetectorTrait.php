<?php

namespace Prettus\Repository\Generators;

use Illuminate\Console\AppNamespaceDetectorTrait;

/**
 * Class NamespaceDetectorTrait.
 *
 * @package Prettus\Repository\Generators
 */
trait NamespaceDetectorTrait
{
    use AppNamespaceDetectorTrait;

    /**
     * Get root namespace.
     *
     * @return string
     */
    public function getRootNamespace()
    {
        return config('repository.generator.rootNamespace', $this->getAppNamespace());
    }

}