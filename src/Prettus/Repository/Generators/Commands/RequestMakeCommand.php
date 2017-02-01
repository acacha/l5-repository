<?php

namespace Prettus\Repository\Generators\Commands;

use Illuminate\Foundation\Console\RequestMakeCommand as BaseRequestMakeCommand;
use Prettus\Repository\Generators\HasPaths;
use Prettus\Repository\Generators\NamespaceDetectorTrait;

/**
 * Class RequestMakeCommand.
 *
 * @package App\Console\Commands
 */
class RequestMakeCommand extends BaseRequestMakeCommand
{
    use NamespaceDetectorTrait, HasPaths;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:l5-request';

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->getRootNamespace();
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    public function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\'. str_replace('/', '\\', $this->getConfigGeneratorClassPath($this->getPathConfigNode(), true));
    }

    /**
     * Get path.
     *
     * @param string $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = str_replace_first($this->getRootNamespace(), '', $name);
        return $this->getDestinationPath().'/'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    protected function getDestinationPath()
    {
        return $this->getBasePath();
    }

    /**
     * Get base path of destination file.
     *
     * @return string
     */

    protected function getBasePath()
    {
        return config('repository.generator.basePath', app_path());
    }

    /**
     * Get generator path config node.
     *
     * @return string
     */
    protected function getPathConfigNode()
    {
        return 'requests';
    }
}
