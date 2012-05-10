<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Scribble\File\Format\Textile\OutputFilter;

use Sirprize\Scribble\File\FileInterface;
use Sirprize\Scribble\File\OutputFilter\AbstractFilter;

/**
 * HtmlConverterFilter converts Textile to HTML.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
class HtmlConverterFilter extends AbstractFilter
{

    public function handleOutput(FileInterface $scribbleFile, $output)
    {
        // https://github.com/netcarver/textile
        // Make sure this class is require'd and available
        $textile = new \Textile('html5');
        return $textile->TextileThis($output);
    }

}