<?php

namespace core;

use core\Model as Model;

class ModelCollection
{
    /**
     * @var Model[]
     */
    public $models = [];

    public function __construct( array $models )
    {
        $this->setModels( $models );
    }

    /**
     * @param Model[]
     * @return ModelCollection
     */
    public function setModels( array $models ) : ModelCollection
    {
        $this->models = $models;
        return $this;
    }

    /**
     * @param Model[]
     * @return ModelCollection
     */
    public function pushModels( array $models ) : ModelCollection
    {
        foreach($models as $key => $model) $this->insertModel($key, $model);
        return $this;
    }

    /**
     * @param $key
     * @param Model
     * @return ModelCollection
     */
    public function insertModel( $key, $model ) : ModelCollection
    {
        $this->models[$key] = $model;
        return $this;
    }

    /**
     * @param Model
     * @return ModelCollection
     */
    public function pushModel( $model ) : ModelCollection
    {
        $this->models[] = $model;
        return $this;
    }

    /**
     * @param array $set
     * @return array
     */
    public function getProperties( $set = [] ) : array
    {
        $propertyArray = [];

        foreach($this->getModels() as $key => $model) $propertyArray[$key] = $model->getProperties( $set );

        return $propertyArray;
    }

    /**
     * @param array $set
     * @return array
     */
    public function getFormatted( $set = [] ) : array
    {
        $propertyArray = [];

        foreach($this->getModels() as $key => $model) $propertyArray[$key] = $model->getFormatted( $set );

        return $propertyArray;
    }

    /**
     * @return Model[]
     */
    public function getModels() : array
    {
        return $this->models;
    }

    /**
     * @param $id
     * @return Model|null
     */
    public function getModel( $id )
    {
        return array_key_exists($id, $this->getModels()) ? $this->models[$id] : null;
    }

    /**
     * @return ModelCollection
     */
    public function save() : ModelCollection
    {
        foreach($this->getModels() as $model) $model->save();
        return $this;
    }

    /**
     * @return ModelCollection
     */
    public function destroy() : ModelCollection
    {
        foreach($this->getModels() as $model) $model->destroy();
        return $this;
    }

    /**
     * @param array $data
     * @param Model
     * @return array
     */
    public static function parseModels( array $data, $class )
    {
        $parsed = [];

        foreach($data as $key => $value)
        {
            if(is_array($value)) $parsed[$key] = $class::create($value);
            if(is_object($value)) $parsed[$key] = $value;
        }

        return $parsed;
    }

}
