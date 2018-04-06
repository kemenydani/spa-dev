<?php

namespace core;

use core\DB as DB;

abstract class Model
{
    protected $properties = [];
    public static $primaryKey = 'id';
    public static $columns = [];

    public static function getTable()
    {
        return DB::$_PREFIX_ . static::$table;
    }

    public function equals( Model $model )
    {
	    if(!$model->getProperty( self::$primaryKey ) && !$this->getProperty( self::$primaryKey ) ) return false;
	    
    	return (string)$this->getProperty( self::$primaryKey ) === (string)$model->getProperty( self::$primaryKey );
    }
    
    public function getProperty( $name )
    {
        if( array_key_exists($name, $this->properties)) return $this->properties[$name];
        return null;
    }

    public function getProperties( $list = [] )
    {
        if(count($list) === 0) return $this->properties;

        $result = [];

        foreach($list as $propName)
        {
            if(!array_key_exists($propName, $this->properties)) continue;
            $result[$propName] = $this->properties[$propName];
        }
        return $result;
    }

    public function setProperty($name, $value)
    {
        if(in_array($name, static::$columns)) $this->properties[$name] = $value;
        return $this;
    }

    public static function create( array $properties = [] )
    {
        $Model = new static();
        foreach($properties as $name => $value) $Model->setProperty($name, $value);
        return $Model;
    }

    public function save()
    {
        if($this->getProperty(self::$primaryKey) !== null)
        {
            $this->update();
        }
        else
        {
            $inserted = $this->insert();
            if(!$inserted) return false;
            $this->setProperty(static::$primaryKey, DB::instance()->lastInsertId());
        }

        return $this;
    }

    private function update()
    {
        return DB::instance()->update(static::$table, $this->getProperties(), self::$primaryKey, $this->getProperty(self::$primaryKey));
    }

    private function insert()
    {
        return DB::instance()->insert(static::$table, $this->getProperties());
    }

    public static function find( $value, $column = null )
    {
        if( $column === null ) $column = static::$primaryKey;

        $row = DB::instance()->find(static::$table, $column, $value);

        if(!is_array($row)) return false;

        return static::create( (array)$row );
    }

    public static function findAll( $value, $column = null )
    {
        if(!$column) $column = static::$primaryKey;

        $rows = DB::instance()->findAll( static::$table, $column, $value );
        
        if(!is_array($rows)) return false;
        
        $models = [];

        foreach( $rows as $row ) $models[] = static::create( (array)$row );

        return $models;
    }

    public static function all()
    {
        $rows = DB::instance()->all(static::$table);

        $collection = new ModelCollection();

        foreach( $rows as $row ) $collection->collect(static::create((array)$row));
        
        return $collection;
    }

    public function destroy()
    {
	    return DB::instance()->delete(static::$table, static::$primaryKey, $this->getProperty(static::$primaryKey) );
    }
    
    public static function delete( $column, $value ) : bool
    {
        return DB::instance()->delete( static::$table, $column, $value );
    }

    public static function deleteIn( $column, $range ) : bool
    {
        return DB::instance()->deleteIn( static::$table, $column, $range );
    }

    public function __call($method, array $arguments)
    {
        $propName = underscorize(substr($method, 3));
        $action = substr($method, 0, 3);

        if($action === 'get') return $this->getProperty($propName);
        if($action === 'set') return $this->setProperty($propName, $arguments[0]);
    }
}
