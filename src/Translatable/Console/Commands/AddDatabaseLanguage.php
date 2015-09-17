<?php

namespace panopla\Translatable\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AddDatabaseLanguage extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'translatable:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new language to the database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        //
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['code', null, InputOption::VALUE_REQUIRED, 'An example option.', null],
            ['name', null, InputOption::VALUE_REQUIRED, 'An example option.', null],
        ];
    }
}