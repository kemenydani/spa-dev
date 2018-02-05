<?php

namespace core;

use core\DB as DB;
use core\Lang as Lang;

abstract class Model
{
    protected $_STATE_ = [];

    public static $_UNIQUE_KEY = 'id';
    public static $_PROPS = [];
    public static $_PROPS_PROTECTED = [];
    public static $_RELATIONS = [];

    public function __call( $method, array $arguments )
    {
        $propName = Lang::underScorize( substr($method, 3 ) );
        $action = substr( $method, 0, 3 );

        if( $action === 'get' ) return $this->getProperty($propName);
        if( $action === 'set' ) return $this->setProperty( $propName, $arguments[0] );
    }

    public function hasProperty( $name )
    {
       return array_key_exists( $name, static::$_PROPS );
    }

    public function getProperty( $name )
    {
        if( $this->hasProperty($name) )
        {
            return $this->_STATE_[$name];
        }

        return null;
    }

    public function getProperties()
    {
        return $this->_STATE_;
    }

    public function getPublicProperties()
    {
        $result = [];

        foreach( $this->getProperties() as $name => $value )
        {
            if( !in_array($name, static::$_PROPS_PROTECTED ) ) $result[$name] = $value;
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

        foreach( $properties as $name => $value )
        {
            $Model->setProperty( $name, $value );
        }

        return $Model;
    }

    public function save()
    {
        if ( $this->hasProperty( static::$_UNIQUE_KEY ) )
        {
            $modelId = $this->update( [], [] );
        }
        else
        {
            $modelId = $this->insert( [] );
        }

        if( !$modelId ) return false;

        return $this;
    }

    private function update( array $values, $key, $id )
    {
        $lastUpdateId = DB::instance()->update( static::$_TABLE, $key, $id );

        if( $lastUpdateId ) return $lastUpdateId;

        return false;
    }

    private function insert( array $values )
    {
        $lastInsertId = DB::instance()->insert( static::$_TABLE, $values );

        if( !$lastInsertId ) return false;

        $this->setProperty( static::$_UNIQUE_KEY, $lastInsertId );

        return $lastInsertId;
    }

    public static function find( $column, $value )
    {
        $row = DB::instance()->find( static::$_TABLE, $column, $value );

        if( !$row ) return false;

        return static::create( (array)$row );
    }

    public static function findAll( $column, $value )
    {
        $rows = DB::instance()->findAll( static::$_TABLE, $column, $value );

        if( !$rows ) return false;

        $models = [];

        foreach( $rows as $row )
        {
            static::create( (array)$row );
        }

        return $models;
    }

    public static function delete( $column, $value ) : bool
    {
        return DB::instance()->delete( static::$_TABLE, $column, $value );
    }

    public static function deleteIn( $column, $range ) : bool
    {
        return DB::instance()->deleteIn( static::$_TABLE, $column, $range );
    }

}
