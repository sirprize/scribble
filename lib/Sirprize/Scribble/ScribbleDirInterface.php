<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Scribble;

/**
 * ScribbleDirInterface.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
interface ScribbleDirInterface
{
    public function __construct(array $config);
    public function load();
    public function getScribbles();
}