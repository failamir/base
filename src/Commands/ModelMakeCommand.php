<?php

namespace Chart\Console\Commands;

use Illuminate\Foundation\Console\ModelMakeCommand as LaravelModelMakeCommand;

class ModelMakeCommand extends LaravelModelMakeCommand
{
  protected $name = 'factory:model';

  protected $description = 'Create a new Eloquent model class based on Factory template';

  protected function getStub()
  {
      return __DIR__.'/stubs/Model.stub';
  }
}
