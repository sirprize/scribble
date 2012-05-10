<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
namespace Sirprize\Tests\Scribble;

use Sirprize\Scribble\ScribbleDirWithSubdirs;

class ScribbleDirWithSubdirsTest extends \PHPUnit_Framework_TestCase
{
    
    public function testLoadAll()
    {
        $config = array(
            'dir' => SCRIBBLE_TESTS_DATA_BASE_DIR_WITH_SUBDIRS,
            'path' => '',
            'files' => array(
                'scribble.md' => array(),
                'scribble.textile' => array(),
                'scribble.txt' => array(),
                'scribble.html' => array()
            )
        );
        
        $directory = new ScribbleDirWithSubdirs($config);
        $directory->load();
        
        $this->assertSame(4, $directory->getScribbles()->count());
        $this->assertSame(6, $directory->getScribbles()->getTags()->count());
        $this->assertSame(6, $directory->getScribbles()->getTagCounts()->count());
    }

    public function testLoadSome()
    {
        $config = array(
            'dir' => SCRIBBLE_TESTS_DATA_BASE_DIR_WITH_SUBDIRS,
            'path' => '',
            'files' => array(
                'scribble.md' => array(),
                'scribble.textile' => array(),
            )
        );

        $directory = new ScribbleDirWithSubdirs($config);
        $directory->load();

        $this->assertSame(2, $directory->getScribbles()->count());
        $this->assertSame(3, $directory->getScribbles()->getTags()->count());
        $this->assertSame(3, $directory->getScribbles()->getTagCounts()->count());
    }
    
    /**
     * @expectedException Sirprize\Scribble\ScribbleException
     */
    public function testLoadException()
    {
        $config = array(
            'dir' => 'some-invalid-dir',
            'path' => '',
            'files' => array(
                'scribble.md' => array(),
                'scribble.textile' => array(),
                'scribble.txt' => array(),
                'scribble.html' => array()
            )
        );
        
        $directory = new ScribbleDirWithSubdirs($config);
        $directory->load();
    }
}