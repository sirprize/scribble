<?php

/*
 * This file is part of the Scribble package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Scribble;

use Sirprize\Scribble\ScribbleCollection;
use Sirprize\Scribble\ScribbleException;

/**
 * ScribbleDirWithSubdirs loads scribbles.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
class ScribbleDirWithSubdirs
{

    protected $dir = null;
    protected $path = null;
    protected $files = null;
    protected $fileConfigs = null;
    protected $inputFilters = null;
    protected $outputFilters = null;
    protected $scribbles = null;

    public function __construct(array $config)
    {
        $getConfigItem = function(array $config, $id, $default = null, $throw = false)
        {
            if(!array_key_exists($id, $config))
            {
                if($throw)
                {
                    throw new ScribbleException(sprintf('Missing service configuration item: "%s"', $id));
                }

                return $default;
            }

            return $config[$id];
        };
        
        $this->dir = $getConfigItem($config, 'dir', null, true);
        $this->path = $getConfigItem($config, 'path', null, true);
        $files = $getConfigItem($config, 'files', null, true);

        foreach($files as $file => $fileConfig)
        {
            $class = $getConfigItem($fileConfig, 'class', 'Sirprize\Scribble\File\ScribbleFile', false);
            $this->files[$file] = $class;
            $this->inputFilters[$file] = $getConfigItem($fileConfig, 'inputFilters', array(), false);
            $this->outputFilters[$file] = $getConfigItem($fileConfig, 'outputFilters', array(), false);
            $this->fileConfigs[$file] = $getConfigItem($fileConfig, 'config', array(), false);
        }
    }

    public function load()
    {
        if($this->scribbles)
        {
            return $this;
        }

        $this->scribbles = new ScribbleCollection();

        if(!is_dir($this->dir))
        {
            throw new ScribbleException(sprintf('Invalid scribble base directory: "%s"', $this->dir));
        }

        foreach(new \DirectoryIterator($this->dir) as $fileinfo)
        {
            if(preg_match('/^\.\.?$/', $fileinfo->getFilename()))
            {
                continue;
            }

            $dir = $this->dir.'/'.$fileinfo->getFilename();

            if(is_file($dir))
            {
                continue;
            }

            $scribble = $this->loadScribble($dir);

            if(!$scribble)
            {
                continue;
            }
            
            $scribble
                ->setSlug($fileinfo->getFilename())
                ->setPath($this->path.'/'.$fileinfo->getFilename())
            ;

            $this->scribbles->add($scribble);
        }

        return $this;
    }

    public function getScribbles()
    {
        return $this->scribbles;
    }

    protected function loadScribble($dir)
    {
        $scribble = null;

        foreach($this->files as $filename => $fileHandler)
        {
            $file = $dir.'/'.$filename;

            if(is_file($file) && is_readable($file))
            {
                $scribble = $this->getScribbleInstance($filename)->load($file);
                break;
            }
        }

        if(!$scribble)
        {
            return false;
        }

        return $scribble;
    }

    protected function getScribbleInstance($filename)
    {
        $scribble = new $this->files[$filename]($this->fileConfigs[$filename]);

        foreach($this->inputFilters[$filename] as $filterClass => $filterConfig)
        {
            $scribble->addInputFilter($filterClass, new $filterClass($filterConfig));
        }

        foreach($this->outputFilters[$filename] as $filterClass => $filterConfig)
        {
            $scribble->addOutputFilter($filterClass, new $filterClass($filterConfig));
        }

        return $scribble;
    }
}