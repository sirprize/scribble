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
 * CodeblockEmptyLineFixerFilter.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
class CodeblockBlankLineFixerFilter extends AbstractFilter
{

    public function handleOutput(FileInterface $scribbleFile, $output)
    {
        return preg_replace('/<\/code>\s*<code>/', '', $output);
    }

}