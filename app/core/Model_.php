<?php

namespace core;

use core\DB as DB;
use core\Lang as Lang;

abstract class Model_
{
    protected $_props_ = [];
    protected $_changeLog_= [];

    public function __call( $methodName, $arguments )
    {
        $propName = Lang::underScorize( substr($methodName, 3 ) );
        $action = substr( $methodName, 0, 3 );

        if( $action === 'get' ) return $this->getProperty( $propName );
        if( $action === 'set' ) return $this->setProperty( $propName, $arguments[0] );
    }

    public function setProperty( $propName, $newValue, $silent = false )
    {
        $this->_props_[$propName] = $newValue;

        if( !$silent ) $this->logChange( $propName );
      
        return $this->_props_[$propName];
    }

    public function getProperty( $propName )
    {
        return $this->hasProperty( $propName) ? $this->_props_[$propName] : null;
    }

    public function getProperties()
    {
        return isset( $this->_props_ ) ? $this->_props_ : [];
    }
    
    public function logChange( $propName )
    {
        $this->_changeLog_[ $propName ] = $propName;
    }

    public function hasChanges()
    {
        return !empty( $this->_changeLog_);
    }

    public function clearChangeLog()
    {
        $this->_changeLog_= [];
    }

    public function listChangedPropNames()
    {
        return array_keys($this->_changeLog_);
    }

    public function getChangedProps()
    {
    	$changedProps = [];
    	
    	foreach( $this->listChangedPropNames() as $propName )
    	{
		    $changedProps[ $propName ] = $this->getProperty($propName);
	    }
	    
	    return $changedProps;
    }

	public static function create( $props = [] )
    {
        $Model = new static();

        foreach( $props as $propName => $propValue )
        {
            $Model->setProperty($propName, $propValue);
        }
        return $Model;
    }

    public function hasProperty( $propName )
    {
        return array_key_exists($propName, $this->_props_);
    }

    public static function all( $json = false )
    {
        $rows = DB::instance()->all( static::$_TABLE );

        if(!$rows) return false;
        
        if( $json === true ) $rows = json_encode( $rows );

        return $rows;
    }

    public static function find( $uniqueValue, $uniqueKey = null )
    {
        $uniqueKey = $uniqueKey === null ? static::$_UNIQUE_KEY : $uniqueKey;

        $result = DB::instance()->find(static::$_TABLE, $uniqueKey, $uniqueValue);

        if($result) return self::create($result);

        return null;
    }

    public function save()
    {
    	//if( !$this->hasChanges() ) return false;

        if ( $this->getProperty( static::$_UNIQUE_KEY) )
        {
            $db_model_id = DB::instance()->update( static::$_TABLE, $this->getChangedProps(), static::$_UNIQUE_KEY, self::getProperty(static::$_UNIQUE_KEY));
        }
        else
        {
            $db_model_id = DB::instance()->insert( static::$_TABLE, $this->getChangedProps() );
        }

        if ($db_model_id)
        {
            if(!$this->hasProperty(static::$_UNIQUE_KEY)) $this->setProperty(static::$_UNIQUE_KEY, $db_model_id);
            $this->clearChangeLog();
            return $db_model_id;
        }
        
        return false;
    }

    public function toJSON()
    {
        return json_encode( $this->getProperties(), true );
    }

}