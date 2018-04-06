<?php

namespace core;

use core\Model as Model;

class ModelCollection
{
	/**
	 * @var Model[] $models List of Model objects.
	 */
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
     * @param integer $key exact key for new item
     */
    public function collect( Model $model, $key = null )
    {
    	if( $key === null ) $this->models[] = $model;
	    $this->models[$key] = $model;
    }
	
	public function collectMultiple( $models, $merge = false )
	{
    	if( !$merge ) $this->models = $models;
		$this->models = array_merge( $this->models, $models );
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
	 * Returns a list of Model objects
	 * @return Model[]
	 */
    public function all() : array
    {
    	return $this->models;
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
