<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Scribble\File;

use Sirprize\Scribble\TagCollection;
use Sirprize\Scribble\File\FileInterface;
use Sirprize\Scribble\File\FileException;
use Sirprize\Scribble\File\InputFilter\InputFilterInterface;
use Sirprize\Scribble\File\InputFilter\InputFilterException;
use Sirprize\Scribble\File\OutputFilter\OutputFilterInterface;
use Sirprize\Scribble\File\OutputFilter\OutputFilterException;

/**
 * ScribbleFile loads a text file and extracts meta information from keywords.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
class ScribbleFile implements FileInterface
{

    protected $leftKeywordDelimiter = null;
    protected $rightKeywordDelimiter = null;
    protected $convertedLeftKeywordDelimiter = null;
    protected $convertedRightKeywordDelimiter = null;
    protected $dir = null;
    protected $path = null;
    protected $slug = null;
    protected $title = null;
    protected $lede = null; // lead paragraph
    protected $tags = null;
    protected $image = null;
    protected $published = false;
    protected $created = null;
    protected $modified = null;
    protected $source = null;
    protected $content = null;
    protected $inputFilters = array();
    protected $outputFilters = array();

    public function __construct(array $config = array())
    {
        $getConfigItem = function($id, $default = null) use ($config)
        {
            if(!array_key_exists($id, $config))
            {
                return $default;
            }

            return $config[$id];
        };
        
        $this->leftKeywordDelimiter = $getConfigItem('leftKeywordDelimiter', '<!--', false);
        $this->rightKeywordDelimiter = $getConfigItem('rightKeywordDelimiter', '-->', false);
        $this->convertedLeftKeywordDelimiter = $getConfigItem('convertedLeftKeywordDelimiter', '&lt;!--', false);
        $this->convertedRightKeywordDelimiter = $getConfigItem('convertedRightKeywordDelimiter', '--&gt;', false);
    }

    public function addInputFilter($name, InputFilterInterface $filter)
    {
        $this->inputFilters[$name] = $filter;
        return $this;
    }

    public function addOutputFilter($name, OutputFilterInterface $filter)
    {
        $this->outputFilters[$name] = $filter;
        return $this;
    }

    public function load($file)
    {
        try {
            if(!is_file($file) || !is_readable($file))
            {
                throw new FileException(sprintf('Invalid scribble file: "%s"', $file));
            }

            $this->dir = dirname($file);
            $this->tags = new TagCollection();

            $handle = fopen($file, 'r');

            if($handle === false)
            {
                throw new FileException(sprintf('Error opening scribble file "%s"', $file));
            }

            $this->source = fread($handle, filesize($file));
            fclose($handle);

            $this->title = $this->findStringParam('scribble-title');
            $this->lede = $this->findStringParam('scribble-lede');
            $this->image = $this->findStringParam('scribble-image');
            $this->published = $this->findBoolParam('scribble-publish');
            $this->created = $this->findDateParam('scribble-created');
            $this->modified = $this->findDateParam('scribble-modified');
            $this->modified = ($this->modified !== null) ? $this->modified : $this->created;

            foreach($this->findArrayParam('scribble-tags') as $tag)
            {
                $tag = preg_replace('/[^\w-]/', '', $tag); // eliminate invalid chars
                $this->tags->set(strtolower($tag), $tag); // eliminate duplicates
            }

            if($this->title === null)
            {
                throw new FileException(sprintf('Title is missing in "%s"', $file));
            }

            if($this->created === null)
            {
                throw new FileException(sprintf('Creation date is missing in "%s"', $file));
            }

            foreach($this->inputFilters as $filter)
            {
                $this->source = $filter->handleInput($this);
            }

            if(!$this->findBoolParam('scribble-keep-keywords'))
            {
                $this->source = $this->removeParams($this->source);
            }

            $this->content = $this->source;

            foreach($this->outputFilters as $filter)
            {
                $this->content = $filter->handleOutput($this, $this->content);
            }

            return $this;

        } catch(InputFilterException $e) {
            throw new FileException(sprintf('Scribble pre-processor error: "%s" >>> %s', $file, $e->getMessage()));
        }
        catch(OutputFilterException $e) {
            throw new FileException(sprintf('Scribble post-processor error: "%s" >>> %s', $file, $e->getMessage()));
        }
        catch(\Exception $e) {
            throw new FileException(sprintf('Scribble error: "%s" >>> %s', $file, $e->getMessage()));
        }
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function find($find)
    {
        if(preg_match('/('.implode('|', $this->getKeywords()).')/i', $find))
        {
            return false;
        }

        if(stristr($this->slug, (string) $find))
        {
            return true;
        }

        if(stristr($this->title, (string) $find))
        {
            return true;
        }

        if(stristr(strip_tags($this->source), (string) $find))
        {
            return true;
        }

        return false;
    }

    public function getLeftKeywordDelimiter()
    {
        return $this->leftKeywordDelimiter;
    }

    public function getRightKeywordDelimiter()
    {
        return $this->rightKeywordDelimiter;
    }

    public function getConvertedLeftKeywordDelimiter()
    {
        return $this->convertedLeftKeywordDelimiter;
    }

    public function getConvertedRightKeywordDelimiter()
    {
        return $this->convertedRightKeywordDelimiter;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getDir()
    {
        return $this->dir;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getLede()
    {
        return $this->lede;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function isPublished()
    {
        return $this->published;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getModified()
    {
        return $this->modified;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getKeywords()
    {
        $keywords = array(
            'scribble-title',
            'scribble-lede',
            'scribble-tags',
            'scribble-image',
            'scribble-publish',
            'scribble-created',
            'scribble-modified'
        );

        foreach($this->inputFilters as $filter)
        {
            $keywords = array_merge($keywords, $filter->getKeywords());
        }

        foreach($this->outputFilters as $filter)
        {
            $keywords = array_merge($keywords, $filter->getKeywords());
        }

        return $keywords;
    }

    protected function findBoolParam($name)
    {
        $val = $this->findParam($name);

        if($val === null)
        {
            return null;
        }

        return (preg_match('/^ *(false|0) *$/', $val)) ? false : true;
    }

    protected function findDateParam($name)
    {
        $val = $this->findParam($name);

        if($val === null)
        {
            return null;
        }

        try {
            return new \DateTime($val);
        }
        catch(\Exception $e) {
            throw new FileException(sprintf('Invalid date in %s: "%s"', $name, $val));
        }
    }

    protected function findStringParam($name)
    {
        return $this->findParam($name);
    }

    protected function findArrayParam($name)
    {
        $val = $this->findParam($name);
        return ($val) ? preg_split('/\s+/', $val) : array();
    }

    protected function findParam($name)
    {
        if(preg_match('/'.$this->leftKeywordDelimiter.' *'.$name.': *(.*?) *'.$this->rightKeywordDelimiter.'/', $this->source, $matches))
        {
            return trim(preg_replace('/(\n|\r)/', ' ', $matches[1]));
        }

        return null;
    }

    protected function removeParams()
    {
        $c = preg_replace('/'.$this->leftKeywordDelimiter.' *('.implode('|', $this->getKeywords()).'): *(.*?) *'.$this->rightKeywordDelimiter.'/', '', $this->source);
        return trim($c);
    }
}