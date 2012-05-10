<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
namespace Sirprize\Tests\Scribble;

use Sirprize\Scribble\ScribbleDirWithSubdirs;

class TagCollectionTest extends \PHPUnit_Framework_TestCase
{
    
    protected $tags = null;
    
    
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
    }

    public function tearDown()
    {
        $this->tags = null;
    }
    
    public function testSortAsc()
    {
        $this->tags->sort();
        $this->assertSame(6, $this->tags->count());
        $this->assertSame('html', $this->tags->first());
        $this->assertSame('markdown', $this->tags->next());
        $this->assertSame('plain-text', $this->tags->next());
        $this->assertSame('some-other-tag', $this->tags->next());
        $this->assertSame('some-tag', $this->tags->next());
        $this->assertSame('textile', $this->tags->next());
    }
    
    public function testSortDesc()
    {
        $this->tags->sort(true);
        $this->assertSame(6, $this->tags->count());
        $this->assertSame('textile', $this->tags->first());
        $this->assertSame('some-tag', $this->tags->next());
        $this->assertSame('some-other-tag', $this->tags->next());
        $this->assertSame('plain-text', $this->tags->next());
        $this->assertSame('markdown', $this->tags->next());
        $this->assertSame('html', $this->tags->next());
    }
}