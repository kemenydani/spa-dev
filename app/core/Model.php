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
    public static $_PROPS_SEARCHABLE = [];

    public function __call( $method, array $arguments )
    {
        $propName = Lang::underScorize( substr($method, 3 ) );
        $action = substr( $method, 0, 3 );

        if( $action === 'get' ) return $this->getProperty( $propName );
        if( $action === 'set' ) return $this->setProperty( $propName, $arguments[0] );
    }

    public static function getSearchableProps()
    {
        return count( static::$_PROPS_SEARCHABLE ) ? static::$_PROPS_SEARCHABLE : static::$_PROPS;
    }

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
            $modelId = $this->insert( $this->getProperties() );
        }

        if( !$modelId ) return false;

        return $this;
    }

    private function update( array $values, $key, $id )
    {
        $lastUpdateId = DB::instance()->update( static::$_TABLE, $this->getProperties(), $key, $id );

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

    public static function find( $value, $column = null )
    {
        if( $column === null ) $column = static::$_UNIQUE_KEY;

        $row = DB::instance()->find( static::$_TABLE, $column, $value );

        //if( $row === false ) return false;

        return static::create( (array)$row );
    }

    public static function findAll( $value, $column = null )
    {
        if( !$column ) $column = static::$_UNIQUE_KEY;

        $rows = DB::instance()->findAll( static::$_TABLE, $column, $value );
        
        if( $rows === false ) return [];
        
        $models = [];

        foreach( $rows as $row )
        {
            $models[] = static::create( (array)$row );
        }

        return $models;
    }

    public function search( $search = null, array $filter = [] )
    {

    }

    public static function all()
    {
        $rows = DB::instance()->all( static::$_TABLE );

        $models = [];

        foreach( $rows as $row )
        {
            $models[] = static::create( (array)$row );
        }

        return $models;
    }

    public function remove()
    {
	    return DB::instance()->delete( static::$_TABLE, static::$_UNIQUE_KEY, $this->getProperty( static::$_UNIQUE_KEY ) );
    }
    
    public static function delete( $column, $value ) : bool
    {
        return DB::instance()->delete( static::$_TABLE, $column, $value );
    }

    public static function deleteIn( $column, $range ) : bool
    {
        return DB::instance()->deleteIn( static::$_TABLE, $column, $range );
    }

    public static function isSearchable()
    {
        return count(static::$_PROPS_SEARCHABLE) > 0;
    }

}
