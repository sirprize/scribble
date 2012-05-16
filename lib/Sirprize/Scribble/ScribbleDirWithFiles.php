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
 * ScribbleDirWithFiles loads scribbles.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
class ScribbleDirWithFiles
{

    protected $dir = null;
    #protected $path = null;
    protected $suffices = null;
    protected $suffixConfigs = null;
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
        #$this->path = $getConfigItem($config, 'path', null, true);
        $suffices = $getConfigItem($config, 'suffices', null, true);

        foreach($suffices as $suffix => $suffixConfig)
        {
            $class = $getConfigItem($suffixConfig, 'class', 'Sirprize\Scribble\File\ScribbleFile', false);
            $this->suffices[$suffix] = $class;
            $this->inputFilters[$suffix] = $getConfigItem($suffixConfig, 'inputFilters', array(), false);
            $this->outputFilters[$suffix] = $getConfigItem($suffixConfig, 'outputFilters', array(), false);
            $this->suffixConfigs[$suffix] = $getConfigItem($suffixConfig, 'config', array(), false);
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

            $file = $this->dir.'/'.$fileinfo->getFilename();

            if(is_dir($file))
            {
                continue;
            }

            $scribble = $this->loadScribble($file);

            if(!$scribble)
            {
                continue;
            }
            
            $scribble
                ->setSlug($fileinfo->getFilename())
                #->setPath($this->path.'/'.$fileinfo->getFilename())
            ;

            $this->scribbles->add($scribble);
        }

        return $this;
    }

    public function getScribbles()
    {
        return $this->scribbles;
    }

    protected function loadScribble($file)
    {
        $scribble = null;

        foreach($this->suffices as $suffix => $fileHandler)
        {
            if(preg_match('/'.$suffix.'$/', $file))
            {
                $scribble = $this->getScribbleInstance($suffix)->load($file);
                break;
            }
        }

        if(!$scribble)
        {
            return false;
        }

        return $scribble;
    }

    protected function getScribbleInstance($suffix)
    {
        $scribble = new $this->suffices[$suffix]($this->suffixConfigs[$suffix]);

        foreach($this->inputFilters[$suffix] as $filterClass => $filterConfig)
        {
            $scribble->addInputFilter($filterClass, new $filterClass($filterConfig));
        }

        foreach($this->outputFilters[$suffix] as $filterClass => $filterConfig)
        {
            $scribble->addOutputFilter($filterClass, new $filterClass($filterConfig));
        }

        return $scribble;
    }
}