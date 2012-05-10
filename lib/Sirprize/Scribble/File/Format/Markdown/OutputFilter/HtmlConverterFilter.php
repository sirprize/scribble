<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Scribble\File\Format\Markdown\OutputFilter;

use Sirprize\Scribble\File\FileInterface;
use Sirprize\Scribble\File\OutputFilter\AbstractFilter;

/**
 * HtmlConverterFilter converts Markdown to HTML.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
class HtmlConverterFilter extends AbstractFilter
{

    public function handleOutput(FileInterface $scribbleFile, $output)
    {
        // https://github.com/michelf/php-markdown/
        // Make sure this class is require'd and available
        return \Markdown($output);
    }

}