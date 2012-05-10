<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Scribble\File\OutputFilter;

use Sirprize\Scribble\File\OutputFilter\OutputFilterInterface;

/**
 * AbstractFilter.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
abstract class AbstractFilter implements OutputFilterInterface
{

    protected $config = array();

    public function __construct(array $config = array())
    {
        $this->config = $config;
    }

    public function getKeywords()
    {
        return array();
    }

}