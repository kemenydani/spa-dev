<?php

namespace core;

use core\DB as DB;

abstract class Model {

    const IMAGE_PATH = null;
    /**
     * @var string
     */
    static $TABLE = '';
    /**
     * @var string
     */
    static $PKEY = 'id';
    /**
     * @var array
     */
    static $COLUMNS = [];
    /**
     * @var array
     */
    protected $props = [];

    public function __construct($props = [])
    {
        $this->setProperties($props);
    }

    private function update()
    {
        return DB::instance()->update(static::$TABLE, $this->getProperties(), self::$PKEY, $this->getProperty(self::$PKEY));
    }

    private function insert()
    {
        var_dump($this->getProperties());
        return DB::instance()->insert(static::$TABLE, $this->getProperties());
    }

    public function save()
    {
        if($this->getProperty(self::$PKEY) !== null)
        {
            $this->update();
        }
        else
        {
            $inserted = $this->insert();
            if(!$inserted) return false;
            $this->setProperty(static::$PKEY, DB::instance()->lastInsertId());
        }
        return $this;
    }

    public static function find($value, $key = null)
    {
        if(!$key) $key = static::$PKEY;

        $query = " SELECT * FROM " . self::getTable() .
            " WHERE " . $key . " = ?"
        ;

        return self::getOne($query, $value);
        //return DB::instance()->get($query, $value, \PDO::FETCH_CLASS, static::class );
    }

    public static function findAll( $value, $key = null )
    {
        if($key === null) $key = static::$PKEY;

        $query = " SELECT * FROM " . self::getTable() .
                 " WHERE " . $key . " = ? "
        ;
        
        return self::getAll($query, $value);
    }
    
    public static function getAll($query = null, $binds = null)
    {
    	$stmt = "";
    	if(is_string($query)) $stmt = $query;
        if(is_null($query) || is_array($query)) $stmt = " SELECT * FROM " . self::getTable();
        if(is_array($query))
        {
            $commaList = "";
            foreach($query as $key => $value) $commaList .= " " . $key . " = ? AND";
            $stmt .= " WHERE" . substr($commaList, 0, -3);
        	$binds = array_values($query);
        }
        
	    $models = DB::instance()->getAll($stmt, $binds, \PDO::FETCH_CLASS, static::class );

        return $models;
    }

    public static function getRealImagePath($filename)
    {
        if(static::IMAGE_PATH)
        {
            $path = static::IMAGE_PATH . DIRECTORY_SEPARATOR . $filename;
            if(!file_exists($path)) return null;
            return $path;
        }
        return null;
    }

    public static function getOne($query = null, $binds = null)
    {
	    $stmt = "";
	    if(is_string($query)) $stmt = $query;
	    if(is_null($query) || is_array($query)) $stmt = " SELECT * FROM " . self::getTable();
	    if(is_array($query))
	    {
		    $commaList = "";
		    foreach($query as $key => $value) $commaList .= " " . $key . " = ? AND";
		    $stmt .= " WHERE" . substr($commaList, 0, -3);
		    $binds = array_values($query);
	    }

	    $model = DB::instance()->get($stmt, $binds, \PDO::FETCH_CLASS, static::class );
	    
        return $model;
    }

    /**
     * @param array $list
     * @return array
     */
    public function getProperties( $list = [] )
    {
        if(count($list) === 0) return $this->props;

        $result = [];

        foreach($list as $propName)
        {
            if(!array_key_exists($propName, $this->props)) continue;
            $result[$propName] = $this->props[$propName];
        }
        return $result;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->setProperty($name, $value);
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function setProperty($name, $value)
    {
        if(in_array($name, self::getColumns())) $this->props[$name] = $value;
        return $this;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function getProperty($name)
    {
        return array_key_exists($name, $this->props) ? $this->props[$name] : null;
    }

    /**
     * @param array $props
     * @return $this
     */
    public function setProperties( array $props)
    {
        foreach($props as $name => $value)
        {
            if(in_array($name, self::getColumns())) $this->setProperty($name, $value);
        }
        return $this;
    }

    public function getFormatted( $list = [] )
    {
        $list = count($list) === 0 ? static::$COLUMNS : $list;

        $result = [];

        foreach($list as $propName)
        {
            $method = 'format' . underscoreUpper($propName);

            if(method_exists($this, $method))
            {
                $result[$propName] =  $this->$method();
            }
            else
            {
                $result[$propName] = $this->getProperty($propName);
            }
        }
        return $result;
    }

    /**
     * @return string
     */
    public static function getTable() : string
    {
        return strlen(static::$TABLE) > 0 ? DB::prependPrefix(static::$TABLE) : '';
    }

    /**
     * @return string
     */
    public static function getPrimaryKey() : string
    {
        return isset(static::$PKEY) ? static::$PKEY : self::$PKEY;
    }

    /**
     * @return array
     */
    public function getColumns() : array
    {
        return isset(static::$COLUMNS) ? static::$COLUMNS : self::$COLUMNS;
    }

    /**
     * @param array $props
     * @return static
     */
    public static function create( array $props )
    {
        $Model = new static();
        foreach($props as $name => $value) $Model->setProperty($name, $value);
        return $Model;
    }

    /**
     * @param Model $class
     * @param array $props
     * @return bool | Model
     */
    protected static function make( $class, array $props )
    {
        if(class_exists($class)) return $class::create($props);
        return false;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return $this->getProperties();
    }

    /**
     * @return bool
     */
    public function destroy()
    {
        return DB::instance()->delete(static::$TABLE, static::$PKEY, $this->getProperty(static::$PKEY) );
    }

    /**
     * @param $column
     * @param $value
     * @return bool
     */
    public static function delete( $column, $value ) : bool
    {
        return DB::instance()->delete( static::$TABLE, $column, $value );
    }

    /**
     * @param $column
     * @param $range
     * @return bool
     */
    public static function deleteIn( $column, $range ) : bool
    {
        return DB::instance()->deleteIn( static::$TABLE, $column, $range );
    }

    /**
     * @param $method
     * @param array $arguments
     * @return Model|mixed|null
     */
    public function __call($method, array $arguments)
    {
        $propName = underscorize(substr($method, 3));
        $action = substr($method, 0, 3);

        if($action === 'get')    return $this->getProperty($propName);
        if($action === 'set')    return $this->setProperty($propName, $arguments[0]);

        $propName = underscorize(substr($method, 6));
        $action = substr($method, 0, 6);

        if($action === 'format')
        {
            $method = 'format' . underscoreUpper($propName);

            return method_exists($this, $method) ? $this->$method() : $this->getProperty($propName);
        }
    }
}



/*
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

    public function getFormatted( $list = [] )
    {
        $list = count($list) === 0 ? static::$columns : $list;

        $result = [];

        foreach($list as $propName)
        {
            $method = 'format' . underscoreUpper($propName);

            if(method_exists($this, $method))
            {
                $result[] =  $this->$method();
            }
            else
            {
                $result[] = $this->getProperty($propName);
            }
        }
        return $result;
    }

    public function setProperty($name, $value)
    {
        //if(in_array($name, static::$columns)) $this->properties[$name] = $value;
        $this->properties[$name] = $value;
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

        if($action === 'get')    return $this->getProperty($propName);
        if($action === 'set')    return $this->setProperty($propName, $arguments[0]);

        $propName = underscorize(substr($method, 6));
        $action = substr($method, 0, 6);

        if($action === 'format')
        {
            $method = 'format' . underscoreUpper($propName);

            return method_exists($this, $method) ? $this->$method() : $this->getProperty($propName);
        }
    }
}
*/