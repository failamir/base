<?php

namespace Chart\Console\Commands;

use Chart\Console\Commands\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class TransformerMakeCommand extends GeneratorCommand
{
    protected $signature = 'factory:transformer {name} {--m|model=}';

    protected $description = 'Create a new json transformer class that extends the base Transformer class';

    protected $type = 'Transformer';

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    public function handle()
    {
      $name = $this->parseName($this->getNameInput());

      if ($this->filesystem->exists($path = $this->getPath($name))) {
        return $this->error($this->getNameInput() . ' already exists!');
      }

      $model = $this->option('model');
      $modelNamespace = $this->parseName($model, 'getModelNamespace');

      if($model == null || !$this->filesystem->exists($this->getPath($modelNamespace))){
        return $this->error("Model $model does not exists.");
      }

      $args = [
       'name'=> $name,
       'model'=> $model,
      ];

      $this->makeDirectory($path);
      $this->filesystem->put($path, $this->buildClass($args));
      $this->info("{$this->type} created successfully.");
    }

    protected function getStub()
    {
      return $this->filesystem->get(__DIR__ . '/stubs/Transformer.stub');
    }

    protected function getDefaultNamespace($rootNamespace)
    {
      return $rootNamespace . '\Transformers';
    }

    protected function getModelNamespace($rootNamespace)
    {
      return $rootNamespace;
    }

    public function buildClass(Array $args)
    {
      $stub = parent::buildClass($args);

      $this->replaceModel($stub, $args['model'])
           ->replaceStructure($stub, $args['model']);

      return $stub;
    }

    protected function replaceModel(&$stub, $model)
    {
        $stub = str_replace('{{param}}', '$item', $stub);

        if(!$model){
          $stub = str_replace('{{modelnamespace}}', "", $stub);
          return $this;
        }

        $class = substr($model, strrpos($model, '\\') + 1);

        $stub = str_replace('{{modelnamespace}}', "\nuse $model;\n", $stub);
        return $this;
    }

    protected function replaceStructure(&$stub, $model)
    {
        if(!$model){
          $structure = "'id' => \$item->id,\n\t\t\t\t\t";
          $structure .= "'created_at' => \$item->created_at,\n\t\t\t\t\t";
          $structure .= "'updated_at' => \$item->updated_at,\n\t\t\t\t\t";
          $stub = str_replace('{{structure}}', $structure, $stub);
          return $this;
        }

        $columns = $model::getColumns();

        $structure = '';
        foreach ($columns as $column) {
          $structure .= "'$column' => \$item->{$column},\n\t\t\t\t\t";
        }

        $stub = str_replace('{{structure}}', $structure, $stub);
        return $this;
    }
}
