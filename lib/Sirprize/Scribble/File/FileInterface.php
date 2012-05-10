<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Scribble\File;

use Sirprize\Scribble\File\InputFilter\InputFilterInterface;
use Sirprize\Scribble\File\OutputFilter\OutputFilterInterface;

/**
 * FileInterface.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
interface FileInterface
{

    public function __construct(array $config);
    public function addInputFilter($name, InputFilterInterface $filter);
    public function addOutputFilter($name, OutputFilterInterface $filter);
    public function getLeftKeywordDelimiter();
    public function getRightKeywordDelimiter();
    public function getKeywords();
    public function load($file);
    public function find($find);
    public function getSlug();
    public function getPath();
    public function getDir();
    public function getTitle();
    public function getLede();
    public function getTags();
    public function getImage();
    public function isPublished();
    public function getCreated();
    public function getModified();
    public function getSource();
    public function getContent();
}