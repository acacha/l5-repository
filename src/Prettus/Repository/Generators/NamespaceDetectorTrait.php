<?php

namespace Prettus\Repository\Generators;

use Illuminate\Console\DetectsApplicationNamespace;

/**
 * Class NamespaceDetectorTrait.
 *
 * @package Prettus\Repository\Generators
 */
trait NamespaceDetectorTrait
{
    use DetectsApplicationNamespace;

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