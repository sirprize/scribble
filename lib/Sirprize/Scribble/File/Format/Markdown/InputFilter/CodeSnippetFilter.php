<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Scribble\File\Format\Markdown\InputFilter;

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
        $code = parent::handleSnippet($codeFile);
        $code = preg_replace('/^/m', "\t", $code);
        return "<div style=\"display:none\" class=\"consecutive-scribble-snippet-separator-hack\"></div>\n".$code;
    }

}