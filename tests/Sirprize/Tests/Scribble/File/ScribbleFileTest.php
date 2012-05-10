<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
namespace Sirprize\Tests\Scribble\File;

use Sirprize\Scribble\File\ScribbleFile;

class ScribbleFileTest extends \PHPUnit_Framework_TestCase
{

    public function testLoad()
    {
        $file = SCRIBBLE_TESTS_DATA_BASE_DIR_WITH_SUBDIRS . '/markdown-scribble/scribble.md';
        $slug = basename(dirname($file));
        $scribble = new ScribbleFile();
        $scribble->load($file)->setSlug($slug);

        $this->assertSame('markdown-scribble', $scribble->getSlug());
        $this->assertSame('Markdown Scribble', $scribble->getTitle());
        $this->assertTrue($scribble->isPublished());
        $this->assertTrue($scribble->getTags()->containsKey('markdown'));
        $this->assertTrue($scribble->getTags()->containsKey('some-other-tag'));
        $this->assertTrue($scribble->find('heading 3'));
        $this->assertRegExp('/Heading 3/', $scribble->getContent());
        
        $date = new \DateTime('20120328');
        $this->assertSame($date->format('YmdHis'), $scribble->getCreated()->format('YmdHis'));
        
        $date = new \DateTime('20120428');
        $this->assertSame($date->format('YmdHis'), $scribble->getModified()->format('YmdHis'));
        
        // check defaults
        $this->assertSame('<!--', $scribble->getLeftKeywordDelimiter());
        $this->assertSame('-->', $scribble->getRightKeywordDelimiter());
        $this->assertSame('&lt;!--', $scribble->getConvertedLeftKeywordDelimiter());
        $this->assertSame('--&gt;', $scribble->getConvertedRightKeywordDelimiter());
    }

    /**
     * @expectedException Sirprize\Scribble\File\FileException
     */
    public function testInvalidFileException()
    {
        $scribble = new ScribbleFile();
        $scribble->load('some-invalid-file.md');
    }
    
    /**
     * @expectedException Sirprize\Scribble\File\FileException
     */
    public function testInvalidContentException()
    {
        $scribble = new ScribbleFile();
        $scribble->load(SCRIBBLE_TESTS_DATA_INVALID_SCRIBBLE.'/scribble.md');
    }
}