<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Scribble\File\OutputFilter;

use Sirprize\Scribble\File\FileInterface;

/**
 * OutputFilterInterface.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
interface OutputFilterInterface
{

    public function __construct(array $config = array());
    public function handleOutput(FileInterface $scribbleFile, $output);

}