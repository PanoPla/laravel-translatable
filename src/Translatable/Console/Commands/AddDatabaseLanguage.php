<?php

namespace panopla\Translatable\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use panopla\Translatable\Language;
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
        $code = $this->option('code');

        if (!$this->validateCode($code)) {
            $this->error('Code is not in a valid format');
            return;
        }

        $name = $this->option('name');

        if (!$this->validateName($name)) {
            $this->error('Name is not a valid string');
            return;
        }

        DB::beginTransaction();
        $language = new Language();
        $language->code = $code;
        $language->name = $name;
        $language->save();
        DB::commit();

        $this->info('Language created successfully');
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

    /**
     * @param $code
     * @return bool
     */
    private function validateCode($code)
    {
        return true;
    }

    /**
     * @param string $name
     * @return bool
     */
    private function validateName($name)
    {
        return true;
    }

}