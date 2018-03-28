<?php

namespace core;

use core\DB as DB;

abstract class Model3
{
    protected $_STATE_ = [];

    public static $_UNIQUE_KEY = 'id';
    public static $_PROPS = [];

    public static function getPropertyNames()
    {
        return static::$_PROPS;
    }

    public static function getTable()
    {
        return DB::$_PREFIX_ . static::$_TABLE;
    }

    public function hasProperty( $name )
    {
       return in_array( $name, static::$_PROPS );
    }

    public function getProperty( $name )
    {
        if( $this->hasProperty( $name ) )
        {
            return $this->_STATE_[$name];
        }
        return null;
    }

    public function getProperties( $list = [] )
    {
        if( count( $list ) === 0 ) return $this->_STATE_;

        $result = [];
        foreach($list as $propName)
        {
            if( !array_key_exists( $propName, $this->_STATE_ ) ) continue;
            $result[$propName] = $this->_STATE_[$propName];
        }

        return $result;
    }

    public function setProperty( $name, $value )
    {
        if( in_array( $name, static::$_PROPS ) )
        {
            $this->_STATE_[$name] = $value;
        }
        
        return $this;
    }

    public static function create( array $properties = [] )
    {
        $Model = new static();

        foreach( $properties as $name => $value ) $Model->setProperty( $name, $value );
        
        return $Model;
    }

    public function save()
    {
        if ( $this->getProperty( static::$_UNIQUE_KEY ) )
        {
            $modelId = $this->update();
        }
        else
        {
            $modelId = $this->insert();
        }

        if( !$modelId ) return false;

        $this->setProperty(static::$_UNIQUE_KEY, $modelId);

        return $this;
    }

    /**
     * @param array $values
     * @param $key
     * @param $id
     * @return bool
     */
    private function update( array $values, $key, $id )
    {
        $lastUpdateId = DB::instance()->update( static::$_TABLE, $this->getProperties(), static::$_UNIQUE_KEY, $this->getProperty(static::$_UNIQUE_KEY) );

        if( $lastUpdateId ) return $lastUpdateId;

        return false;
    }

    /**
     * @return bool
     */
    private function insert()
    {
        $result = DB::instance()->insert( static::$_TABLE, $this->getProperties() );

        $lastInsertId = DB::instance()->lastInsertId();

        if( !$result ) return false;

        $this->setProperty( static::$_UNIQUE_KEY, $lastInsertId );

        return $lastInsertId;
    }

    /**
     * @param $value
     * @param null $column
     * @return bool|Model3
     */
    public static function find( $value, $column = null )
    {
        if( $column === null ) $column = static::$_UNIQUE_KEY;

        $row = DB::instance()->find( static::$_TABLE, $column, $value );

        if( $row === false ) return false;

        return static::create( (array)$row );
    }

    /**
     * @param $value
     * @param null $column
     * @return array
     */
    public static function findAll( $value, $column = null ) : array
    {
        if( !$column ) $column = static::$_UNIQUE_KEY;

        $rows = DB::instance()->findAll( static::$_TABLE, $column, $value );
        
        if( $rows === false ) return [];
        
        $models = [];

        foreach( $rows as $row ) $models[] = static::create( (array)$row );

        return $models;
    }

    /**
     * @return array
     */
    public static function all() : array
    {
        $rows = DB::instance()->all( static::$_TABLE );

        $models = [];

        foreach( $rows as $row ) $models[] = static::create( (array)$row );

        return $models;
    }

    /**
     * @return bool
     */
    public function destroy() : bool
    {
	    return DB::instance()->delete( static::$_TABLE, static::$_UNIQUE_KEY, $this->getProperty( static::$_UNIQUE_KEY ) );
    }

    /**
     * @param $column
     * @param $value
     * @return bool
     */
    public static function delete( $column, $value ) : bool
    {
        return DB::instance()->delete( static::$_TABLE, $column, $value );
    }

    /**
     * @param $column
     * @param $range
     * @return bool
     */
    public static function deleteIn( $column, $range ) : bool
    {
        return DB::instance()->deleteIn( static::$_TABLE, $column, $range );
    }
}
