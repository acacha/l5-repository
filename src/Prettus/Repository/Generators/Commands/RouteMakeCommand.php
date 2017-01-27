<?php

namespace Prettus\Repository\Generators\Commands;

use File;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Prettus\Repository\Generators\BindingsGenerator;
use Prettus\Repository\Generators\FileAlreadyExistsException;
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
        $routesGenerator->run();
        $this->info($this->type . ' created successfully.');
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
