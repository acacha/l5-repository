<?php

namespace Prettus\Repository\Generators;

/**
 * Class ShowViewGenerator.
 *
 * @package Prettus\Repository\Generators
 */
class ShowViewGenerator extends Generator
{

    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'views/show';

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode()
    {
        return 'views';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getBasePath() . '/' . parent::getConfigGeneratorClassPath($this->getPathConfigNode(), true)
                . '/' . $this->getPluralName() . '/show.blade.php';
    }

    /**
     * Get base path of destination file.
     *
     * @return string
     */
    public function getBasePath()
    {
        return config('repository.generator.basePath', app_path());
    }

    /**
     * Get array replacements.
     *
     * @return array
     */
    public function getReplacements()
    {
        return [
            'plural'            => $this->getPluralName(),
            'singular'          => $this->getSingularName(),
        ];
    }

    /**
     * Gets plural name based on model
     *
     * @return string
     */
    public function getPluralName()
    {
        return str_plural(lcfirst(ucwords($this->getClass())));
    }

    /**
     * Gets singular name based on model
     *
     * @return string
     */
    public function getSingularName()
    {
        return str_singular(lcfirst(ucwords($this->getClass())));
    }
}

