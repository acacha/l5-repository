<?php

namespace Prettus\Repository\Generators;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

/**
 * Class Generator.
 *
 * @package Prettus\Repository\Generators
 */
abstract class Generator
{
    use NamespaceDetectorTrait, HasPaths;

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * The array of options.
     *
     * @var array
     */
    protected $options;

    /**
     * The shortname of stub.
     *
     * @var string
     */
    protected $stub;

    /**
     * Backup before overwrite?
     *
     * @var boolean
     */
    protected $backup = false;

    /**
     * The command that runs the generator
     *
     * @var Command
     */
    protected $command;

    /**
     * @param Command $command
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * Create new instance of this class.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->filesystem = new Filesystem;
        $this->options = $options;
    }


    /**
     * Get the filesystem instance.
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }


    /**
     * Set the filesystem instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     *
     * @return $this
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        return $this;
    }


    /**
     * Get stub template for generated file.
     *
     * @return string
     */
    public function getStub()
    {
        $path = config('repository.generator.stubsOverridePath', __DIR__);

        if(!file_exists($path . '/Stubs/' . $this->stub . '.stub')){
            $path = __DIR__;
        }

        return (new Stub($path . '/Stubs/' . $this->stub . '.stub', $this->getReplacements()))->render();
    }


    /**
     * Get template replacements.
     *
     * @return array
     */
    public function getReplacements()
    {
        return [
            'class'          => $this->getClass(),
            'namespace'      => $this->getNamespace(),
            'root_namespace' => $this->getRootNamespace()
        ];
    }


    /**
     * Get base path of destination file.
     *
     * @return string
     */
    public function getBasePath()
    {
        return base_path();
    }


    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getBasePath() . '/' . $this->getName() . '.php';
    }


    /**
     * Get name input.
     *
     * @return string
     */
    public function getName()
    {
        $name = $this->name;
        if (str_contains($this->name, '\\')) {
            $name = str_replace('\\', '/', $this->name);
        }
        if (str_contains($this->name, '/')) {
            $name = str_replace('/', '/', $this->name);
        }

        return Str::studly(str_replace(' ', '/', ucwords(str_replace('/', ' ', $name))));
    }


    /**
     * Get class name.
     *
     * @return string
     */
    public function getClass()
    {
        return Str::studly(class_basename($this->getName()));
    }


    /**
     * Get paths of namespace.
     *
     * @return array
     */
    public function getSegments()
    {
        return explode('/', $this->getName());
    }

    abstract public function getPathConfigNode();


    /**
     * Get class namespace.
     *
     * @return string
     */
    public function getNamespace()
    {
        $segments = $this->getSegments();
        array_pop($segments);
        $rootNamespace = $this->getRootNamespace();
        if ($rootNamespace == false) {
            return null;
        }

        return 'namespace ' . rtrim($rootNamespace . '\\' . implode($segments, '\\'), '\\') . ';';
    }


    /**
     * Setup some hook.
     *
     * @return void
     */
    public function setUp()
    {
        //
    }

    /**
     * Run the generator.
     *
     * @return int
     * @throws FileAlreadyExistsException
     */
    public function run()
    {
        $this->setUp();

        $path = $this->checkFileExists($this->backup);

        $this->createParentFolder($path);

        return $this->applyStub($path);
    }


    /**
     * Get options.
     *
     * @return string
     */
    public function getOptions()
    {
        return $this->options;
    }


    /**
     * Determinte whether the given key exist in options array.
     *
     * @param  string $key
     *
     * @return boolean
     */
    public function hasOption($key)
    {
        return array_key_exists($key, $this->options);
    }


    /**
     * Get value from options by given key.
     *
     * @param  string      $key
     * @param  string|null $default
     *
     * @return string
     */
    public function getOption($key, $default = null)
    {
        if (!$this->hasOption($key)) {
            return $default;
        }

        return $this->options[$key] ?: $default;
    }


    /**
     * Helper method for "getOption".
     *
     * @param  string      $key
     * @param  string|null $default
     *
     * @return string
     */
    public function option($key, $default = null)
    {
        return $this->getOption($key, $default);
    }


    /**
     * Handle call to __get method.
     *
     * @param  string $key
     *
     * @return string|mixed
     */
    public function __get($key)
    {
        if (property_exists($this, $key)) {
            return $this->{$key};
        }

        return $this->option($key);
    }

    /**
     * @param $path
     * @return int
     */
    protected function applyStub($path)
    {
        return $this->filesystem->put($path, $this->getStub());
    }

    /**
     * @param $path
     */
    protected function createParentFolder($path)
    {
        if (!$this->filesystem->isDirectory($dir = dirname($path))) {
            $this->filesystem->makeDirectory($dir, 0777, true, true);
        }
    }

    /**
     * @param $path
     */
    protected function backup($path)
    {
        $this->filesystem->move($path, $path . '.backup');
    }

    /**
     * @return string
     * @throws FileAlreadyExistsException
     */
    protected function checkFileExists($backup = false)
    {
        if ($this->filesystem->exists($path = $this->getPath()) && !$this->force) {
            if (! $backup) {
                throw new FileAlreadyExistsException($path);
            }
            $this->askForBackup($path);
        }
        return $path;
    }

    /**
     * @param $path
     */
    protected function askForBackup($path)
    {
        if ($this->option('yes') ||
            $this->command->confirm('Model Already exists.Would you like to overwrite it (a copy would be done with extension .backup)? [y|N]')
        ) {
            $this->backup($path);
        }
    }
}
