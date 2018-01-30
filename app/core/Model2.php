<?php

namespace core;

use core\DB as DB;
use core\Lang as Lang;

abstract class Model2
{

	public function __call( $methodName, $arguments )
	{
		$propName = Lang::underScorize( substr($methodName, 3 ) );
		$action = substr( $methodName, 0, 3 );
		
		if( $action === 'get' ) return $this->getProperty( $propName );
		if( $action === 'set' ) return $this->setProperty( $propName, $arguments[0] );
	}
	
	// NEW
	public function getProperty( string $name )
	{
		if( !array_key_exists( $name, $this->__props__) ) return false;
		if( !is_array( $this->__props__[ $name ] ) ) return $this->__props__[ $name ];
		return $this->__props__[ $name ]['value'];
	}
	// NEW
	public function setProperty( string $name, $value ) : object
	{
		if( isset($name) && array_key_exists( $name, $this->__props__) )
		{
			$cleanValue = $this->prepareValue( $value );
			
			if( $cleanValue )
			{
				$this->__props__[ $name ] = $cleanValue;
				// TODO: log the change here
			}
		}
		return $this;
	}
	// NEW
	public function prepareValue( $value )
	{
		if( !is_array( $value ) ) return $value;
	
		if( array_key_exists('value', $value ) )
		{
			if( !array_key_exists('rules', $value ) ) return $value['value'];
			
			return $this->validateValue( $value['value'], $value['rules']);
		}
		
		return false;
	}
	// NEW
	public function setProperties( array $properties = [] ) : object
	{
		foreach( $properties as $name => $property )
		{
			$this->setProperty( $property );
		}
		return $this;
	}
	// NEW
	public function getProperties() : array
	{
		$collection = [];
		
		foreach( $this->__props__ as $name => $property )
		{
			$property = $this->getProperty( $name );
			if( $property ) $collection[ $name ] = $property;
		}
		
		return $collection;
	}

	
	public static function create( $props = [] ) : object
	{
		$Model = new static();
		
		foreach( $props as $propName => $propValue )
		{
			$Model->setProperty($propName, $propValue, true );
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
		if( !$this->hasChanges() ) return false;
		
		if ( $this->getProperty( static::$_UNIQUE_KEY) )
		{
			$db_model_id = DB::instance()->update(static::$_TABLE, $this->listChangedPropNames(), static::$_UNIQUE_KEY, self::getProperty(static::$_UNIQUE_KEY));
		}
		else
		{
			$db_model_id = DB::instance()->insert(static::$_TABLE, $this->listChangedPropNames());
		}
		
		if ($db_model_id)
		{
			if(!$this->hasProperty(static::$_UNIQUE_KEY)) $this->setProperty(static::$_UNIQUE_KEY, $db_model_id);
			$this->clearChangeLog();
			return $db_model_id;
		}
		
		
	}
	
	public function toJSON()
	{
		return json_encode( $this->getProperties(), true );
	}
}