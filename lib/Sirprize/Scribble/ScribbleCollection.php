<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Scribble;

use Doctrine\Common\Collections\ArrayCollection;
use Sirprize\Scribble\TagCollection;
use Sirprize\Scribble\TagCountCollection;

/**
 * ScribbleCollection is a scribble collection implementation.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
class ScribbleCollection extends ArrayCollection
{

    protected $tags = null;
    protected $tagCounts = null;

    public function getTags()
    {
        $this->makeTagCollection();
        return $this->tags;
    }

    public function getTagCounts()
    {
        $this->makeTagCollection();
        return $this->tagCounts;
    }

    public function sortByCreationDate($descending = true)
    {
        $this->sortByDate('created', $descending);
        return $this;
    }

    public function sortByModificationDate($descending = true)
    {
        $this->sortByDate('modified', $descending);
        return $this;
    }

    public function sortBySlug($descending = false)
    {
        $slugs = array();

        foreach($this as $scribble)
        {
            if(!array_key_exists($scribble->getSlug(), $slugs))
            {
                $slugs[$scribble->getSlug()] = array();
            }

            $slugs[$scribble->getSlug()][] = $scribble;
        }
        
        $this->clear();

        if($descending)
        {
            krsort($slugs);
        }
        else {
            ksort($slugs);
        }

        foreach($slugs as $slug)
        {
            foreach($slug as $scribble)
            {
                $this->add($scribble);
            }
        }

        return $this;
    }
    
    /*
    public function sortBySlug($descending = false)
    {
        $scribbles = $this->toArray();

        $this->clear();

        if($descending)
        {
            krsort($scribbles);
        }
        else {
            ksort($scribbles);
        }

        foreach($scribbles as $scribble)
        {
            $this->set($scribble->getSlug(), $scribble);
        }

        return $this;
    }
    */

    protected function makeTagCollection()
    {
        if($this->tags)
        {
            return;
        }

        $this->tags = new TagCollection();
        $this->tagCounts = new TagCountCollection();

        foreach($this as $scribble)
        {
            foreach($scribble->getTags() as $tag)
            {
                $tagLower = strtolower($tag);
                $count = ($this->tags->containsKey($tagLower)) ? $this->tagCounts->get($tagLower) + 1 : 1;
                $this->tags->set($tagLower, $tag);
                $this->tagCounts->set($tagLower, $count);
            }
        }
    }

    protected function sortByDate($whichDate, $descending)
    {
        $dates = array();

        foreach($this as $scribble)
        {
            $date = ($whichDate == 'created') ? $scribble->getCreated() : $scribble->getModified();
            $date = $date->format('YmdHis');

            if(!array_key_exists($date, $dates))
            {
                $dates[$date] = array();
            }

            $dates[$date][] = $scribble;
        }

        if($descending)
        {
            krsort($dates);
        }
        else {
            ksort($dates);
        }

        $this->clear();

        foreach($dates as $date)
        {
            foreach($date as $scribble)
            {
                $this->add($scribble);
            }
        }
    }
}