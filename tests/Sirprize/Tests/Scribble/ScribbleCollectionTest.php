<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
namespace Sirprize\Tests\Scribble;

use Sirprize\Scribble\ScribbleCollection;
use Sirprize\Scribble\File\ScribbleFile;

class ScribbleCollectionTest extends \PHPUnit_Framework_TestCase
{
    
    protected $scribbles = null;
    
    
    public function setUp()
    {
        $this->scribbles = new ScribbleCollection();

        // load html scribble
        $file = SCRIBBLE_TESTS_DATA_BASE_DIR_WITH_SUBDIRS . '/html-scribble/scribble.html';
        $slug = basename(dirname($file));
        $scribble = new ScribbleFile();
        $this->scribbles->set($slug, $scribble->load($file)->setSlug($slug));

        // load markdown scribble
        $file = SCRIBBLE_TESTS_DATA_BASE_DIR_WITH_SUBDIRS . '/markdown-scribble/scribble.md';
        $slug = basename(dirname($file));
        $scribble = new ScribbleFile();
        $this->scribbles->set($slug, $scribble->load($file)->setSlug($slug));

        // load textile scribble
        $file = SCRIBBLE_TESTS_DATA_BASE_DIR_WITH_SUBDIRS . '/textile-scribble/scribble.textile';
        $slug = basename(dirname($file));
        $scribble = new ScribbleFile();
        $this->scribbles->set($slug, $scribble->load($file)->setSlug($slug));

        // load plain text scribble
        $file = SCRIBBLE_TESTS_DATA_BASE_DIR_WITH_SUBDIRS . '/plain-text-scribble/scribble.txt';
        $slug = basename(dirname($file));
        $scribble = new ScribbleFile();
        $this->scribbles->set($slug, $scribble->load($file)->setSlug($slug));
    }

    public function tearDown()
    {
        $this->scribbles = null;
    }

    public function testSortByCreationDateDesc()
    {
        $this->scribbles->sortByCreationDate();
        $this->assertSame('textile-scribble', $this->scribbles->first()->getSlug());
        $this->assertSame('plain-text-scribble', $this->scribbles->next()->getSlug());
        $this->assertSame('markdown-scribble', $this->scribbles->next()->getSlug());
        $this->assertSame('html-scribble', $this->scribbles->next()->getSlug());
    }
    
    public function testSortByCreationDateAsc()
    {
        $this->scribbles->sortByCreationDate(false);
        $this->assertSame('html-scribble', $this->scribbles->first()->getSlug());
        $this->assertSame('markdown-scribble', $this->scribbles->next()->getSlug());
        $this->assertSame('plain-text-scribble', $this->scribbles->next()->getSlug());
        $this->assertSame('textile-scribble', $this->scribbles->next()->getSlug());
    }
    
    public function testSortByModificationDateDesc()
    {
        $this->scribbles->sortByModificationDate();
        $this->assertSame('textile-scribble', $this->scribbles->first()->getSlug());
        $this->assertSame('plain-text-scribble', $this->scribbles->next()->getSlug());
        $this->assertSame('markdown-scribble', $this->scribbles->next()->getSlug());
        $this->assertSame('html-scribble', $this->scribbles->next()->getSlug());
    }
    
    public function testSortByModificationDateAsc()
    {
        $this->scribbles->sortByModificationDate(false);
        $this->assertSame('html-scribble', $this->scribbles->first()->getSlug());
        $this->assertSame('markdown-scribble', $this->scribbles->next()->getSlug());
        $this->assertSame('plain-text-scribble', $this->scribbles->next()->getSlug());
        $this->assertSame('textile-scribble', $this->scribbles->next()->getSlug());
    }
    
    public function testSortBySlugAsc()
    {
        $this->scribbles->sortBySlug();
        $this->assertSame('html-scribble', $this->scribbles->first()->getSlug());
        $this->assertSame('markdown-scribble', $this->scribbles->next()->getSlug());
        $this->assertSame('plain-text-scribble', $this->scribbles->next()->getSlug());
        $this->assertSame('textile-scribble', $this->scribbles->next()->getSlug());
    }

    public function testSortBySlugDesc()
    {
        $this->scribbles->sortBySlug(true);
        $this->assertSame('textile-scribble', $this->scribbles->first()->getSlug());
        $this->assertSame('plain-text-scribble', $this->scribbles->next()->getSlug());
        $this->assertSame('markdown-scribble', $this->scribbles->next()->getSlug());
        $this->assertSame('html-scribble', $this->scribbles->next()->getSlug());
    }
    
    public function testNumTags()
    {
        $this->assertSame(6, $this->scribbles->getTags()->count());
        $this->assertSame(6, $this->scribbles->getTagCounts()->count());
    }
    
    public function testTagCounts()
    {
        $this->assertSame(2, $this->scribbles->getTagCounts()->get('some-tag'));
        $this->assertSame(2, $this->scribbles->getTagCounts()->get('some-other-tag'));
        $this->assertSame(1, $this->scribbles->getTagCounts()->get('html'));
        $this->assertSame(1, $this->scribbles->getTagCounts()->get('markdown'));
        $this->assertSame(1, $this->scribbles->getTagCounts()->get('plain-text'));
        $this->assertSame(1, $this->scribbles->getTagCounts()->get('textile'));
    }
}