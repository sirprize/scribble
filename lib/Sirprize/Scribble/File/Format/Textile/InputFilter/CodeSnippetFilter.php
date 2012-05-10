<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Scribble\File\Format\Textile\InputFilter;

use Sirprize\Scribble\File\InputFilter\SnippetFilter;

/**
 * CodeSnippetFilter.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
class CodeSnippetFilter extends SnippetFilter
{

    protected function handleSnippet($codeFile)
    {
        return "bc.. ".parent::handleSnippet($codeFile);
    }

}