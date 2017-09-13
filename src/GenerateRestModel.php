<?php

namespace RoySailor\Restify;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class GenerateRestModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restify:generate_model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Rest Model';

    protected $files;

    /**
     * Create a new command instance.
     *
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        try {

            $rootNameSpace = $this->laravel->getNamespace();

            $tableName = $this->ask('Which table you want to use from database?');

            if(!Schema::hasTable($tableName)) {
                throw new \Exception("Table does not exist in database.");
            }

            $className = studly_case(str_singular($tableName)).'RestModel';

            $restModelFile = app_path("RestModels/{$className}.php");

            if(file_exists($restModelFile)) {

                throw new \Exception("Rest Model #{$className} is already exist.");

            }

            if (! $this->files->isDirectory(dirname($restModelFile))) {
                $this->files->makeDirectory(dirname($restModelFile), 0777, true, true);
            }


            $file = include __DIR__.'/RestModelTemplate.php';

            $this->files->put($restModelFile, $file);

            $this->info('Rest Model created successfully.');

        } catch (FatalThrowableError $er){

            $this->error(implode(' ', [$er->getLine(), $er->getMessage()]));


        } catch (\Exception $e) {

            $this->error(implode(' ', [$e->getLine(), $e->getMessage()]));

        }

    }
}
