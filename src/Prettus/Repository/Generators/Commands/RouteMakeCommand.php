<?php

namespace Prettus\Repository\Generators\Commands;

use File;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Prettus\Repository\Generators\BindingsGenerator;
use Prettus\Repository\Generators\FileAlreadyExistsException;
use Prettus\Repository\Generators\RoutesGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RouteMakeCommand extends Command
{

    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'make:l5-route';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Add resource routes to routes file.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Route';


    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        $routesGenerator = new RoutesGenerator([
            'name' => $this->argument('name'),
        ]);
        // generate routes file if not exists
        if (!file_exists($routesGenerator->getPath())) {
            touch($routesGenerator->getPath());
            // placeholder to mark the place in file where to prepend resource routes
            File::put(
                $routesGenerator->getPath(),
                $routesGenerator->bindPlaceholder
            );
        }
        if ($this->checkPlaceholderExists($routesGenerator) && $this->checkRouteDoesNotExists($routesGenerator)) {
            $routesGenerator->run();
            $this->info($this->type . ' created successfully.');
        }
    }

    /**
     * Check placeholder exists.
     *
     * @return bool
     */
    protected function checkPlaceholderExists($routesGenerator)
    {
        if (strpos(file_get_contents($routesGenerator->getPath()),$routesGenerator->bindPlaceholder) !== false)
            return true;
        $this->error('Routes file (' . $routesGenerator->getPath() . ') does not contains placeholder ' .
            $routesGenerator->bindPlaceholder);
        return false;
    }

    /**
     * Check route not already exists.
     *
     * @param $routesGenerator
     * @return bool
     */
    protected function checkRouteDoesNotExists($routesGenerator)
    {
        if (strpos(file_get_contents($routesGenerator->getPath()),$routesGenerator->getRouteReplacement()) !== false) {
            $this->warn('Route in routes file (' . $routesGenerator->getPath() . ') already exists. Skip adding route');
            return false;
        }
        return true;
    }

    /**
     * The array of command arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return [
            [
                'name',
                InputArgument::REQUIRED,
                'The name of model for which the controller is being generated.',
                null
            ],
        ];
    }


    /**
     * The array of command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [];
    }
}
