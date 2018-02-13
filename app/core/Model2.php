<?php

namespace core;

use core\DB as DB;

abstract class Model2
{
	private static $table      = '';
	private static $primaryKey = 'id';
	private static $props      = [];
	private static $protected  = [];
	private static $relations  = [];
	private $state             = [];
	
	public function hasProperty( $name ) : Bool
	{
		return array_key_exists( $name, $this->state );
	}
	
	public function hasRelation( $name )
	{
		return array_key_exists( $name, static::$relations );
	}
	
	public function getRelation( $name )
	{
		return static::$relations[ $name ];
	}
	
	public function setProperty( $name, $value ) : Model2
	{
		if( $this->hasProperty( $name ) ) $this->state[ $name ] = $value;
		
		return $this;
	}
	
	public function setProperties( $properties ) : Model2
	{
		foreach( $properties as $name => $value )
		{
			if( !$this->hasProperty( $name ) ) continue;
			$this->setProperty( $name, $value );
		}
		
		return $this;
	}
	
	public function getProperty( $name )
	{
		return $this->state[ $name ];
	}
	
	public function getProperties()
	{
		return $this->state;
	}
	
	public function width( $relationName )
	{
		$config = $this->getRelation( $relationName );
		
		$relatedModel = $config['model'];
		
		if( array_key_exists('pivot', $config ) )
		{
			//$junctions = DB::where( $config['pivot'], $config['foreign'] . " = " . $this->getProperty( static::$primaryKey ) );
			/*
			foreach( $junctions as $junction ){
			
			}
			*/
		}
		else
		{
		
		}
		
		//$this->setProperty( $relationName,  );
		
		return $this;
	}
	
	public function update()
	{
	
	
	}
	
	public function insert()
	{
	
	
	}
	
	public static function create( $properties )
	{
		$Model = new static();
		
		return $Model->setProperties( $properties );
	}
	
	public static function find()
	{
	
	}
	
	public static function get()
	{
	
	}
	
	public static function all()
	{
	
	}
	
}
