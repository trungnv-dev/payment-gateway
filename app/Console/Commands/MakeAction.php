<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeAction extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'action:generate {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new action';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Actions';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/action.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Actions';
    }
}
