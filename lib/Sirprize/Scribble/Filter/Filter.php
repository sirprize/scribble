<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Scribble\Filter;

use Sirprize\Scribble\ScribbleCollection;
use Sirprize\Scribble\TagCollection;
use Sirprize\Scribble\TagCountCollection;
use Sirprize\Scribble\File\ScribbleFile;

/**
 * Filter is a filtering criteria container.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
class Filter
{

    protected $scribbles = null;
    protected $relatedTags = null;
    protected $relatedTagCounts = null;

    public function __construct()
    {
        $this->scribbles = new ScribbleCollection();
        $this->relatedTags = new TagCollection();
        $this->relatedTagCounts = new TagCountCollection();
    }

    public function getScribbles()
    {
        return $this->scribbles;
    }

    public function getRelatedTags()
    {
        return $this->relatedTags;
    }

    public function getRelatedTagCounts()
    {
        return $this->relatedTagCounts;
    }

    public function apply(ScribbleCollection $all, Criteria $criteria)
    {
        $this->scribbles = new ScribbleCollection();
        $this->relatedTags = new TagCollection();
        $this->relatedTagCounts = new TagCountCollection();
        
        foreach($all as $scribble)
        {
            if(!$this->matchesCriteria($scribble, $criteria))
            {
                continue;
            }

            $this->scribbles->add($scribble);

            foreach($scribble->getTags() as $tag)
            {
                $tagLower = strtolower($tag);

                if(count($criteria->getTags()) && !$criteria->getTags()->containsKey($tagLower))
                {
                    $count = ($this->relatedTags->containsKey($tagLower)) ? $this->relatedTagCounts->get($tagLower) + 1 : 1;
                    $this->relatedTags->set($tagLower, $tag);
                    $this->relatedTagCounts->set($tagLower, $count);
                }
            }
        }

        return $this;
    }

    protected function matchesCriteria(ScribbleFile $scribble, Criteria $criteria)
    {
        if(!$scribble)
        {
            return false;
        }
        
        if($criteria->getSlug() !== null)
        {
            if($scribble->getSlug() !== $criteria->getSlug())
            {
                return false;
            }
        }

        // unpublished scribbles only
        if($criteria->getMode() === Criteria::MODE_UNPUBLISHED)
        {
            if($scribble->isPublished())
            {
                return false;
            }
        }
        // all scribbles
        else if($criteria->getMode() === Criteria::MODE_ALL)
        {}
        // published scribbles only (default)
        else {
            if(!$scribble->isPublished())
            {
                return false;
            }
        }

        foreach($criteria->getTags() as $key => $tag)
        {
            if(!$scribble->getTags()->containsKey($key))
            {
                return false;
            }
        }

        if($criteria->getFind() && !$scribble->find($criteria->getFind()))
        {
            return false;
        }

        return true;
    }
}