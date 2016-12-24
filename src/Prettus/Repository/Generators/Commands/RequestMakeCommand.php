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
     * Parse the name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function parseName($name)
    {
        return $name;
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
    public function getDestinationPath()
    {
        return $this->getBasePath() . '/' . $this->getConfigGeneratorClassPath($this->getPathConfigNode(), true);
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
        return 'requests';
    }
}
