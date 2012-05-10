<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Scribble\File\InputFilter;

use Sirprize\Scribble\File\FileInterface;
use Sirprize\Scribble\File\InputFilter\AbstractFilter;

/**
 * SnippetFilter looks for snippet file paths and embeds the files into the content.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
class SnippetFilter extends AbstractFilter
{

    protected $regexPart = null;
    protected $scribbleFile = null;

    public function getKeywords()
    {
        return array(
            'scribble-snippet',
            'scribble-ignore-snippets'
        );
    }

    public function handleInput(FileInterface $scribbleFile)
    {
        $this->scribbleFile = $scribbleFile;

        if(preg_match('/<!-- *scribble-ignore-snippets: *(1|true) *-->/', $scribbleFile->getSource()))
        {
            return $scribbleFile->getSource();
        }

        // find all occurences (match entire document) and fire for each occurence
        $this->regexPart = '('.$scribbleFile->getLeftKeywordDelimiter().' *scribble-snippet: *(.*?) *'.$scribbleFile->getRightKeywordDelimiter().'( *\n| *$))';
        return preg_replace_callback('/'.$this->regexPart.'/is', array($this, 'includeSnippet'), $scribbleFile->getSource());
    }

    protected function includeSnippet($matches)
    {
        $code = $this->handleSnippet($this->trim($matches[2]));
        return preg_replace('/(.*?)'.$this->regexPart.'(.*)/is', "\n$1".$code."$4", $matches[0]);
    }

    protected function handleSnippet($codeFile)
    {
        $file = $this->scribbleFile->getDir().'/'.preg_replace('/\.?\.\//', '/', trim($codeFile, '/')); // sandbox snippets to scribble dir

        if(!is_file($file) || !is_readable($file))
        {
            throw new InputFilterException(sprintf('Snippet file does not exist: "%s"', $file));
        }

        $handle = fopen($file, 'r');

        if($handle === false)
        {
            throw new InputFilterException(sprintf('Cant open snippet file: "%s"', $file));
        }

        $code = fread($handle, filesize($file));
        fclose($handle);
        return $code;
    }

    protected function trim($s)
    {
        return trim(preg_replace('/(\n|\r)/', ' ', $s));
    }

}