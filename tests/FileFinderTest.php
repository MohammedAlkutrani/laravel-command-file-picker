<?php

namespace Christophrumpel\LaravelCommandFilePicker\Tests;

use Christophrumpel\LaravelCommandFilePicker\FileFinder;
use Orchestra\Testbench\TestCase;

class FileFinderTest extends TestCase
{

    /** @test */
    public function it_can_find_files_within_directory()
    {
        $finder = new FileFinder(app()->make('files'));
        $files = $finder->getFilesInDirectory(__DIR__.'/Data/Classes');

        $this->assertEquals([
            [
                'path' => __DIR__.'/Data/Classes/Helper.php',
                'link' => '<href=file://'.__DIR__.'/Data/Classes/Helper.php>'.__DIR__.'/Data/Classes/Helper.php</>'
            ],
            [
                'path' => __DIR__.'/Data/Classes/Support.php',
                'link' => '<href=file://'.__DIR__.'/Data/Classes/Support.php>'.__DIR__.'/Data/Classes/Support.php</>'
            ],
        ], $files->toArray());
    }
}
