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
 * CodeblockLanguageHintFilter adds hints by means of language names to the class attribute of <pre><code> tags.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
class CodeblockLanguageHintFilter extends AbstractFilter
{

    public function handleOutput(FileInterface $scribbleFile, $output)
    {
        $ld = $scribbleFile->getLeftKeywordDelimiter();
        $rd = $scribbleFile->getRightKeywordDelimiter();

        return preg_replace(
            '/'.$ld.' *scribble-language-hint: *([\w-]*) *'.$rd.'\s*<pre><code>/i',
            '<pre class="'."$1".'"><code class="'."$1".'">',
            $output
        );
    }

}