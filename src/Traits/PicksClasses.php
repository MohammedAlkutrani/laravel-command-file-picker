<?php

namespace Christophrumpel\LaravelCommandFilePicker\Traits;

use Christophrumpel\LaravelCommandFilePicker\ClassFinder;
use Illuminate\Support\Collection;

trait PicksClasses
{

    protected function askToPickClasses($path): string
    {
        $finder = new ClassFinder($this->laravel->make('files'));
        $classes = $finder->getClassesInDirectory($path);

        if ($classes->isEmpty()) {
            return $this->error("No classes found in \"$path\".");
        }

        return $this->askChoice($classes);
    }

    protected function askToPickModels($path = null): string
    {
        $path = $path ?? config('command-file-picker.model_path') ?? app_path();

        $finder = new ClassFinder($this->laravel->make('files'));
        $models = $finder->getModelsInDirectory($path);

        if ($models->isEmpty()) {
            throw new \LogicException('No models found to show.');
        }

        return $this->askChoice($models);
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
