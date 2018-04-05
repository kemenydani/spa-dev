<?php

namespace core;

use core\Model as Model;

class ModelCollection
{
    protected $models = [];

    /**
     * @param array $set
     * @return array
     */
    public function toArray( $set = [] ) : array
    {
        $array = [];

        foreach($this->models as $model) $array[] = $model->getProperties( $set );

        return $array;
    }

    /**
     * @param Model $model
     */
    public function collect( Model $model )
    {
        $this->models[] = $model;
    }

    /**
     * @param $i
     * @return Model
     */
    public function get( $i )
    {
        if(!array_key_exists($i, $this->models)) return null;
        return $this->models[$i];
    }

    /**
     * @return Model
     */
    public function first() : Model
    {
        if(count($this->models) === 0) return null;
        return $this->get(0);
    }

    /**
     * @return Model
     */
    public function last() : Model
    {
        if(count($this->models) === 0) return null;
        return $this->get(count($this->models)-1);
    }

    /**
     * @return int
     */
    public function count() : int
    {
        return count($this->models);
    }
}
