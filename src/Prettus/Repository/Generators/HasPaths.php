<?php

namespace Prettus\Repository\Generators;

/**
 * Class HasPaths.
 *
 * @package Prettus\Repository\Generators
 */
trait HasPaths
{
    /**
     * Get class-specific output paths.
     *
     * @param $class
     * @param bool $directoryPath
     * @return mixed
     */
    public function getConfigGeneratorClassPath($class, $directoryPath = false)
    {
        switch ($class) {
            case ('models' === $class):
                $path = config('repository.generator.paths.models', 'Entities');
                break;
            case ('repositories' === $class):
                $path = config('repository.generator.paths.repositories', 'Repositories');
                break;
            case ('interfaces' === $class):
                $path = config('repository.generator.paths.interfaces', 'Repositories');
                break;
            case ('presenters' === $class):
                $path = config('repository.generator.paths.presenters', 'Presenters');
                break;
            case ('transformers' === $class):
                $path = config('repository.generator.paths.transformers', 'Transformers');
                break;
            case ('validators' === $class):
                $path = config('repository.generator.paths.validators', 'Validators');
                break;
            case ('controllers' === $class):
                $path = config('repository.generator.paths.controllers', 'Http\Controllers');
                break;
            case ('provider' === $class):
                $path = config('repository.generator.paths.provider', 'RepositoryServiceProvider');
                break;
            case ('criteria' === $class):
                $path = config('repository.generator.paths.criteria', 'Criteria');
                break;
            case ('requests' === $class):
                $path = config('repository.generator.paths.requests', 'Http\Requests');
                break;
            case ('routes' === $class):
                $path = config('repository.generator.paths.routes', 'Http/routes');
                break;
            case ('views' === $class):
                $path = config('repository.generator.paths.views', '../resources/views');
                break;
            default:
                $path = '';
        }

        if ($directoryPath) {
            $path = str_replace('\\', '/', $path);
        } else {
            $path = str_replace('/', '\\', $path);
        }


        return $path;
    }
}