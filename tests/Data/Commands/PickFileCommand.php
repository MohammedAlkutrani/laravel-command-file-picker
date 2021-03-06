<?php

namespace Christophrumpel\LaravelCommandFilePicker\Tests\Data\Commands;

use Christophrumpel\LaravelCommandFilePicker\Traits\PicksFiles;
use Illuminate\Console\Command;

class PickFileCommand extends Command
{

    use PicksFiles;

    protected $signature = "run:test-command-pick-file";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $filePath = $this->askToPickFiles(__DIR__.'/../Models');

        $this->info('Thanks. You have chosen: '.$filePath);
    }

}
