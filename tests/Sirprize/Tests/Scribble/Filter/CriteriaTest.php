<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
namespace Sirprize\Tests\Scribble\Filter;

use Sirprize\Scribble\Filter\Criteria;

class CriteriaTest extends \PHPUnit_Framework_TestCase
{
    
    public function testSlug()
    {
        $criteria = new Criteria();
        $criteria->setSlug('abc*""def');
        $this->assertSame('abcdef', $criteria->getSlug());
    }
    
    public function testTags()
    {
        $criteria = new Criteria();
        $criteria->setTags(array('abc*""def', '123'));
        $this->assertFalse(in_array('abc*""def', $criteria->getTags()->toArray()));
    }
}