<?php

namespace models;

use core\ModelCollection;

class _UserCollection extends ModelCollection
{
    /**
     * @var User[] $models List of Model objects.
     */
    public $models = [];

    public function __construct( $users = [] )
    {
        $this->collectModelArray( $users );
    }


}