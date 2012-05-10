<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Scribble\File\Format\Plaintext\OutputFilter;

use Sirprize\Scribble\File\FileInterface;
use Sirprize\Scribble\File\OutputFilter\AbstractFilter;

/**
 * HtmlConverterFilter converts special chars to HTML Entities and linebreaks to <br />.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
class HtmlConverterFilter extends AbstractFilter
{

    public function handleOutput(FileInterface $scribbleFile, $output)
    {
        return nl2br(htmlspecialchars($output));
    }

}