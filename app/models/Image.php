<?php

namespace models;

use core\Model as Model;

abstract class Image extends Model implements ImageInterface
{
    public function getId()
    {
        return $this->getProperty('id');
    }

    public function getFolder()
    {
        return $this->getProperty('folder');
    }

    public function getFileName()
    {
        return $this->getProperty('file_name');
    }

    abstract public function deleteImage();
}
