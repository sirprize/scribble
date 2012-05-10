<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Scribble\Filter;

use Sirprize\Scribble\TagCollection;

/**
 * Criteria is a filtering criteria container.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
class Criteria
{

    const MODE_ALL = 'all';
    const MODE_PUBLISHED = 'published';
    const MODE_UNPUBLISHED = 'unpublished';

    protected $tags = null;
    protected $slug = null;
    protected $find = null;
    protected $mode = null;

    public function __construct()
    {
        $this->tags = new TagCollection();
    }

    public function setTags(array $tags, $limit = 100)
    {
        foreach($tags as $tag)
        {
            if(count($tags) > $limit)
            {
                break;
            }

            if(preg_match('/^[\w-]+$/', $tag))
            {
                $this->tags->set(strtolower($tag), $tag);
            }
        }

        return $this;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setSlug($slug)
    {
        $this->slug = preg_replace('/[^\w-]+/', '', $slug);
        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setFind($find)
    {
        $this->find = strip_tags($find);
        return $this;
    }

    public function getFind()
    {
        return $this->find;
    }

    public function setMode($mode)
    {
        if($mode == Criteria::MODE_PUBLISHED || $mode == Criteria::MODE_UNPUBLISHED || $mode == Criteria::MODE_ALL)
        {
            $this->mode = $mode;
        }

        return $this;
    }

    public function getMode()
    {
        return $this->mode;
    }
}