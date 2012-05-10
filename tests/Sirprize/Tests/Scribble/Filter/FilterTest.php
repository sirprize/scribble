<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
namespace Sirprize\Tests\Scribble\Filter;

use Sirprize\Scribble\ScribbleDirWithSubdirs;
use Sirprize\Scribble\Filter\Criteria;
use Sirprize\Scribble\Filter\Filter;

class FilterTest extends \PHPUnit_Framework_TestCase
{

    protected $scribbles = null;

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
        $this->scribbles = $directory->load()->getScribbles();
    }

    public function tearDown()
    {
        $this->scribbles = null;
    }

    public function testSlug()
    {
        $criteria = new Criteria();
        $criteria->setMode(Criteria::MODE_PUBLISHED);
        $criteria->setSlug('markdown-scribble');
        $filter = new Filter();
        $filter->apply($this->scribbles, $criteria);
        $this->assertSame(1, $filter->getScribbles()->count());
    }
    
    public function testTags()
    {
        $criteria = new Criteria();
        $criteria->setMode(Criteria::MODE_PUBLISHED);
        $criteria->setTags(array('some-tag', 'plain-text'));
        $filter = new Filter();
        $filter->apply($this->scribbles, $criteria);
        $this->assertSame(1, $filter->getScribbles()->count());
    }
    
    public function testNonExistingTags()
    {
        $criteria = new Criteria();
        $criteria->setMode(Criteria::MODE_PUBLISHED);
        $criteria->setTags(array('some-tag', 'some-non-existing-tag'));
        $filter = new Filter();
        $filter->apply($this->scribbles, $criteria);
        $this->assertSame(0, $filter->getScribbles()->count());
    }
    
    public function testRelatedTags()
    {
        $criteria = new Criteria();
        $criteria->setMode(Criteria::MODE_PUBLISHED);
        $criteria->setTags(array('some-other-tag'));
        $filter = new Filter();
        $filter->apply($this->scribbles, $criteria);
        $this->assertSame(2, $filter->getRelatedTags()->count());
        $this->assertSame(2, $filter->getRelatedTagCounts()->count());
    }
    
    public function testDefaultMode()
    {
        $criteria = new Criteria();
        $filter = new Filter();
        $filter->apply($this->scribbles, $criteria);
        $this->assertSame(3, $filter->getScribbles()->count());
        
        $criteria = new Criteria();
        $criteria->setMode(Criteria::MODE_PUBLISHED);
        $filter = new Filter();
        $filter->apply($this->scribbles, $criteria);
        $this->assertSame(3, $filter->getScribbles()->count());
        
        $criteria = new Criteria();
        $criteria->setMode(Criteria::MODE_UNPUBLISHED);
        $filter = new Filter();
        $filter->apply($this->scribbles, $criteria);
        $this->assertSame(1, $filter->getScribbles()->count());
        
        $criteria = new Criteria();
        $criteria->setMode(Criteria::MODE_ALL);
        $filter = new Filter();
        $filter->apply($this->scribbles, $criteria);
        $this->assertSame(4, $filter->getScribbles()->count());
    }
}