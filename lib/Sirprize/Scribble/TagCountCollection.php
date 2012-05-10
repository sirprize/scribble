<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Scribble;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * TagCountCollection is a tag-count collection implementation.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
class TagCountCollection extends ArrayCollection
{

    public function sort($descending = false)
    {
        $elements = $this->toArray();
        $this->clear();

        if($descending)
        {
            arsort($elements);
        }
        else {
            asort($elements);
        }

        foreach($elements as $tag => $count)
        {
            $this->set($tag, $count);
        }

        return $this;
    }
}