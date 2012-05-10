<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Scribble;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * TagCollection is a tag collection implementation.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
class TagCollection extends ArrayCollection
{

    public function sort($descending = false)
    {
        $elements = $this->toArray();
        $this->clear();

        if($descending)
        {
            krsort($elements);
        }
        else {
            ksort($elements);
        }

        foreach($elements as $tag => $count)
        {
            $this->set($tag, $count);
        }

        return $this;
    }

}