<?php

namespace models;


interface ImageInterface
{

    public function getId();
    public function getFolder();
    public function getFileName();
    public function deleteImage();

}