<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Scribble\File\InputFilter;

use Sirprize\Scribble\File\FileInterface;

/**
 * InputFilterInterface.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
interface InputFilterInterface
{
    public function __construct(array $config = array());
    public function handleInput(FileInterface $scribbleFile);
}