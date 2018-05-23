<?php

namespace App\Service;


use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class FileHelper
{
    /**
     * @var path
     */
    public $path;

    /**
     * @var fileSystem
     */
    public $fileSystem;

    /**
     * @var finder
     */
    public $finder;

    /**
     * @var rootPath
     */
    public $rootPath;

    /**
     * @var fileInfo
     */
    public $fileInfo;

    /**
     * FileHelper constructor.
     *
     * @param string $path
     */
    public function __construct(string $path){
        $this->path = $path;

        $this->rootPath = $_SERVER['DOCUMENT_ROOT'];
        $this->fileInfo = pathinfo($path);
    }

    /**
     *
     * if file json file exist and update parameter is true
     * we get content convert it to array and merge
     * with passed $json content
     *
     * @param array $json
     * @param bool $update
     *
     * @return bool
     */
    public function save(Array $json, bool $update = true){
        $this->fileSystem = new Filesystem();

        if($this->fileSystem->exists($this->path) && $update){
            $pastJson = $this->open();
            if($pastJson !== false){
                $pastJson = json_decode($pastJson, true);
                $json = array_merge($pastJson, $json);
            } else {
                return false;
            }
        }

        $final = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        try {
            $this->fileSystem->dumpFile($this->path, $final);
        } catch (IOException $e){
            return false;
        }

        return true;
    }


    /**
     * function to open file.
     *
     * @param string|null $filename
     * @param string|null $rootPath
     *
     * @return bool|string
     */
    public function open(string $filename = null, string $rootPath = null){
        $this->finder = new Finder();

        if($filename == null) $filename = $this->fileInfo['filename'];
        if($rootPath == null) $rootPath = $this->rootPath;

        try {
            $this->finder->path($filename)->in($rootPath);
            $final = '';

            foreach ($this->finder as $file) {
                $final .= $file->getContents();
            }

            return $final;
        } catch (IOExceptionInterface $e) {
            return false;
        }

    }
}