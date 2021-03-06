<?php

namespace Christophrumpel\LaravelCommandFilePicker\Traits;

use Christophrumpel\LaravelCommandFilePicker\ClassFinder;
use Illuminate\Support\Collection;

trait PicksClasses
{

    private $loader;

    protected function loadModels($path = null)
    {
        $path = $path ?? config('command-file-picker.model_path') ?? app_path();

        $finder = new ClassFinder(app()->make(\Illuminate\Filesystem\Filesystem::class));
        $models = $finder->getModelsInDirectory($path);

        if (!$models->isEmpty()) {
            $this->loader = $models;
            return $this;
        }

        throw new \LogicException('No models found to show.');
    }

    protected function toCollection()
    {
        if(!$this->loader) {
            throw new \LogicException('Call '.__METHOD__.' on null');
        }
        return $this->loader;
    }

    protected function pick()
    {
        if(!$this->loader) {
            throw new \LogicException('Call '.__METHOD__.' on null');
        }
        return $this->askChoice($this->loader);
    }

    protected function askToPickModels($path = null): string
    {
        return $this->loadModels($path)->pick();
    }

    protected function askToPickClasses($path): string
    {
        $finder = new ClassFinder($this->laravel->make('files'));
        $classes = $finder->getClassesInDirectory($path);

        if ($classes->isEmpty()) {
            return $this->error("No classes found in \"$path\".");
        }

        return $this->askChoice($classes);
    }



    private function askChoice(Collection $classes): string
    {
        $linkedModels = $classes->map(function (array $model) {
                return $model['link'];
            })
            ->toArray();

        $chosenClass = $this->choice('Please pick a model', $linkedModels);

        return $classes->filter(function ($class) use ($chosenClass) {
            return $class['link'] === $chosenClass;
        })
            ->first()['name'];
    }

}
