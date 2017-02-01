<?php

namespace Prettus\Repository\Generators;

/**
 * Class RoutesGenerator
 * 
 * @package Prettus\Repository\Generators
 */
class RoutesGenerator extends Generator
{
    /**
     * The placeholder for repository bindings
     *
     * @var string
     */
    public $bindPlaceholder = '//:end-routes:';
    
    /**
     * Run the generator.
     *
     * @return int
     * @throws FileAlreadyExistsException
     */
    public function run()
    {
        $routes = \File::get($this->getPath());
        $replace = $this->getRouteReplacement();
        \File::put(
            $this->getPath(),
            str_replace($this->bindPlaceholder, $replace . PHP_EOL . '        ' . $this->bindPlaceholder, $routes));
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getBasePath() . '/' . parent::getConfigGeneratorClassPath($this->getPathConfigNode(), true) . '.php';
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
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode()
    {
        return 'routes';
    }

    /**
     * Gets repository full class name
     *
     * @return string
     */
    public function getRoute()
    {
        return str_plural(strtolower($this->options['name']));
    }


    /**
     * Gets controller name
     *
     * @return string
     */
    public function getController()
    {
        $repositoryGenerator = new RepositoryEloquentGenerator([
            'name' => $this->name,
        ]);

        $repository = $repositoryGenerator->getRootNamespace() . '\\' . $repositoryGenerator->getName();

        return str_replace([
            "\\",
            '/'
        ], '\\', $repository) . 'RepositoryEloquent';
    }

    /**
     * Get array replacements.
     *
     * @return array
     */
    public function getReplacements()
    {

        return array_merge(parent::getReplacements(), [
            'route' => $this->getRoute(),
            'controller' => $this->getController(),
            'placeholder' => $this->bindPlaceholder,
        ]);
    }

    /**
     * @return string
     */
    public function getRouteReplacement()
    {
        $plural = str_plural($this->option('name'));
        return "Route::resource('" . strtolower($plural) . "', '" . ucfirst($plural) . "Controller');";
    }
}
