<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
namespace Sirprize\Tests\Scribble\File\InputFilter;

use Sirprize\Scribble\File\ScribbleFile;
use Sirprize\Scribble\File\InputFilter\SnippetFilter;

class SnippetFilterTest extends \PHPUnit_Framework_TestCase
{

    public function testLoadWithSnippet()
    {
        $file = SCRIBBLE_TESTS_DATA_BASE_DIR_WITH_SUBDIRS . '/markdown-scribble/scribble.md';
        $scribble = new ScribbleFile();
        $scribble->addInputFilter('snippet', new SnippetFilter());
        $scribble->load($file);

        $this->assertRegExp('/IComeFromSnippetLand/', $scribble->getContent());
    }
}