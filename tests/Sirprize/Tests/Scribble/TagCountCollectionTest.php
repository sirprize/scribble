<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
namespace Sirprize\Tests\Scribble;

use Sirprize\Scribble\ScribbleDirWithSubdirs;

class TagCountCollectionTest extends \PHPUnit_Framework_TestCase
{
    
    protected $tags = null;
    protected $tagCounts = null;
    
    
    public function setUp()
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
        $this->tags = $directory->load()->getScribbles()->getTags();
        $this->tagCounts = $directory->load()->getScribbles()->getTagCounts();
    }

    public function tearDown()
    {
        $this->tagCounts = null;
    }
    
    public function testCounts()
    {
        $this->assertSame(1, $this->tagCounts->get('html'));
        $this->assertSame(1, $this->tagCounts->get('markdown'));
        $this->assertSame(1, $this->tagCounts->get('plain-text'));
        $this->assertSame(2, $this->tagCounts->get('some-other-tag'));
        $this->assertSame(2, $this->tagCounts->get('some-tag'));
        $this->assertSame(1, $this->tagCounts->get('textile'));
    }
}