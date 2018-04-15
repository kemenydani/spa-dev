<?php

namespace core;

class DB extends \PDO
{
    public static $_instance;

    static $_PREFIX_;
    static $_HOST_;
    static $_DATABASE_;
    static $_USERNAME_;
    static $_PASSWORD_;

    public function __construct($dsn, $username, $password, $options)
    {
        parent::__construct($dsn, $username, $password, $options);

        $this->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        $this->exec("set names utf8");
    }

    public static function instance()
    {
        if ( self::$_instance === null )
        {
            $definition = 'mysql:host='.self::$_HOST_.';dbname='.self::$_DATABASE_.'';

            self::$_instance = new DB($definition, self::$_USERNAME_, self::$_PASSWORD_, []);
        }
        return self::$_instance;
    }

    public static function pivot($table, $columns)
    {
        return DB::instance()->insert(self::prependPrefix($table), $columns);
    }

    public function insert( $table, array $params )
    {
        $commaNames  = implode(',', array_keys($params));
        $commaBinds= join(",", array_pad([], count($params), "?"));

        $stmt = " INSERT INTO " . self::prependPrefix($table) .
            " .({$commaNames}) VALUES ({$commaBinds}) "
        ;

        $sql = DB::instance()->prepare( $stmt );

        $bi = 1;

        foreach( $params as $param => $value )
        {
            $sql->bindValue($bi, $value);
            $bi++;
        }

        $isInserted = $sql->execute();

        if( $isInserted ) return true;

        return false;
    }

    public function update( $table, array $columns, $key, $id )
    {
        $columnList = "";

        foreach($columns as $column => $value) $columnList .= "`" . $column . "` = ?,";

        $columnList = substr( $columnList, 0, -1 );

        $stmt = " UPDATE " . self::prependPrefix($table) .
            " SET {$columnList} WHERE ".$key." = ? "
        ;

        $sql = DB::instance()->prepare( $stmt );

        $ci = 1;

        foreach($columns as $column => $value)
        {
            $sql->bindValue($ci, $value);
            $ci++;
        }

        $sql->bindValue($ci, $id);

        if( $sql->execute() ) return $id;

        return false;
    }

    public function delete( $table, $column, $value )
    {
        $stmt = " DELETE FROM " . self::prependPrefix($table) .
            " WHERE ".$column." = ? " .
            " LIMIT 1 "
        ;

        $stmt = DB::instance()->prepare( $stmt );
        $stmt->bindValue(1, $value );

        $isDeleted = $stmt->execute();

        return $isDeleted;
    }

    public function deleteIn( $table, $column, array $range )
    {
        $commaList = implode(",", $range);

        $stmt = " DELETE FROM " . self::prependPrefix($table) .
            " WHERE ".$column." IN ({$commaList}) "
        ;

        $sql = DB::instance()->prepare( $stmt );

        $bi = 1;

        foreach( $range as $key => $value)
        {
            $sql->bindValue($bi, $value );
            $bi++;
        }

        DB::instance()->beginTransaction();

        $isDeleted = $sql->execute();

        if( !$isDeleted )
        {
            DB::instance()->rollBack();
        }
        else
        {
            DB::instance()->commit();
        }
        return $isDeleted;
    }

    // TODO : delete this when compatibility issues are solved
    public function getRow($stmt = '', $bind = null, $fetch_style = \PDO::FETCH_ASSOC, $class = null)
    {
        return $this->get($stmt = '', $bind = null, $fetch_style = \PDO::FETCH_ASSOC, $class = null);
    }

    public function get($stmt = '', $bind = null, $fetch_style = \PDO::FETCH_ASSOC, $class = null)
    {
        $stmt = $this->prepareBind($stmt, $bind);
        
        if($class !== null) $stmt->setFetchMode($fetch_style, $class);

        $stmt->execute();
	
	    return $class === null ? $stmt->fetch($fetch_style) : $stmt->fetch();
    }

    // TODO : delete this when compatibility issues are solved
    public function getRows($stmt = '', $bind = null, $fetch_style = \PDO::FETCH_ASSOC, $class = null)
    {
        return $this->get($stmt = '', $bind = null, $fetch_style = \PDO::FETCH_ASSOC, $class = null);
    }

    public function getAll($stmt = '', $bind = null, $fetch_style = \PDO::FETCH_ASSOC, $class = null)
    {
    	//var_dump();
        $stmt = $this->prepareBind($stmt, $bind);
        
        if($class !== null) $stmt->setFetchMode($fetch_style, $class);

        $stmt->execute();
        return $class === null ? $stmt->fetchAll($fetch_style) : $stmt->fetchAll();
    }

    private function prepareBind($stmt = '', $bind = null)
    {
        $stmt = DB::instance()->prepare($stmt);

        if(!is_array($bind) && $bind !== null ) $bind = [$bind];

        if(is_array($bind))
        {
            $i = 1;
            foreach ($bind as $key => $value) {
                $name = is_numeric($key) ? $i : ':' . $key;

                $stmt->bindValue($name, $value);
                $i++;
            }
        }
        return $stmt;
    }

    public static function prependPrefix($table)
    {
        return self::$_PREFIX_ . $table;
    }

}

/*
class DB extends \PDO
{
	public static $_instance;

	static $_PREFIX_;
	static $_HOST_;
	static $_DATABASE_;
	static $_USERNAME_;
	static $_PASSWORD_;

	public static function instance()
    {
		if ( self::$_instance === null )
		{
		    $definition = 'mysql:host='.self::$_HOST_.';dbname='.self::$_DATABASE_.'';

            self::$_instance = new DB($definition, self::$_USERNAME_, self::$_PASSWORD_);
			self::$_instance->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
			self::$_instance->exec("set names utf8");
		}
		return self::$_instance;
	}

	public static function pivot( $table, $fields ) {
       return DB::instance()->insert($table, $fields);
    }

	public function update( $table, array $what, $key, $id )
    {
        $table = DB::$_PREFIX_ . $table;

        $what = (array)$what;

        $where = "";

        foreach($what as $column => $value)
        {
            $where .= "`" . $column . "` = ?,";
        }

        $names = substr( $where, 0, -1 );

        $stmt = "UPDATE {$table} SET {$names} WHERE ".$key." = ?";

        $sql = DB::instance()->prepare( $stmt );

        $ci = 1;

        foreach($what as $column => $value)
        {
            $sql->bindValue($ci, $value);
            $ci++;
        }

        $sql->bindValue($ci, $id);

        if( $sql->execute() ) return $id;

        return false;
    }

	public function insert( $table, array $params )
    {
	    $table = DB::$_PREFIX_ . $table;

        $commaSepColumnNameList = implode(',', array_keys($params));
        $commaSepQuestionMarkList = join(",", array_pad([], count($params), "?"));

        $stmt = "INSERT INTO {$table} ({$commaSepColumnNameList}) VALUES ({$commaSepQuestionMarkList})";

        $sql = DB::instance()->prepare( $stmt );

        $bi = 1;

        foreach( $params as $param => $value )
        {
            $sql->bindValue($bi, $value);
            $bi++;
        }

        $isInserted = $sql->execute();

        if( $isInserted ) return true;

        return false;
    }
    // TODO remove
    public function all( $table )
    {
        $stmt = "SELECT * FROM " . DB::$_PREFIX_ . $table;

        $sql = DB::instance()->query( $stmt );

        return $sql->fetchAll(\PDO::FETCH_ASSOC );
    }
    // TODO: REMOVE cuz now there is get and getAll
	private static function _find_( $table, $column, $value )
    {
        $table = DB::$_PREFIX_ . $table;

        $stmt = "SELECT * FROM {$table} WHERE ".$column." = ? LIMIT 1";

        $sql = DB::instance()->prepare( $stmt );
        $sql->bindValue(1, $value );

        $sql->execute();

        return $sql;
    }
    // TODO: REMOVE cuz now there is get
    public function find( $table, $column, $value )
    {
        return DB::_find_( $table, $column, $value )->fetch(\PDO::FETCH_ASSOC );
    }
    // TODO: REMOVE cuz now there is getAll
    public function findAll( $table, $column, $value )
    {
        return DB::_find_( $table, $column, $value )->fetchAll(\PDO::FETCH_OBJ );
    }

    public function delete( $table, $column, $value )
    {
        $table = DB::$_PREFIX_ . $table;

        $stmt = "DELETE FROM {$table} WHERE ".$column." = ? LIMIT 1";

        $sql = DB::instance()->prepare( $stmt );
        $sql->bindValue(1, $value );

        $isDeleted = $sql->execute();

        return $isDeleted;
    }

    public function deleteIn( $table, $column, array $range )
    {
        $table = DB::$_PREFIX_ . $table;

        $commaList = implode(",", $range);

        $stmt = "DELETE FROM {$table} WHERE ".$column." IN ({$commaList})";

        $sql = DB::instance()->prepare( $stmt );

        $bi = 1;

        foreach( $range as $key => $value)
        {
            $sql->bindValue($bi, $value );
            $bi++;
        }

        DB::instance()->beginTransaction();

        $isDeleted = $sql->execute();

        if( !$isDeleted )
        {
            DB::instance()->rollBack();
        }
        else
        {
            DB::instance()->commit();
        }

        return $isDeleted;
    }

    public function getRow($stmt = '', $bind = null, $fetch_style = \PDO::FETCH_ASSOC)
    {
        $sth = DB::instance()->prepare($stmt);

        if(!is_array($bind) && $bind !== null ) $bind = [$bind];

        if(is_array($bind))
        {
            $i = 1;
            foreach ($bind as $key => $value) {
                $name = is_numeric($key) ? $i : ':' . $key;

                $sth->bindValue($name, $value);
                $i++;
            }
        }

        $sth->execute();
        $result = $sth->fetch($fetch_style);

        return isset($result) ? $result : null;
    }

    public function getRows($stmt = '', $bind = null, $fetch_style = \PDO::FETCH_ASSOC)
    {
        $sth = DB::instance()->prepare($stmt);

        if(!is_array($bind) && $bind !== null ) $bind = [$bind];

        if(is_array($bind))
        {
            $i = 1;
            foreach ($bind as $key => $value) {
                $name = is_numeric($key) ? $i : ':' . $key;

                $sth->bindValue($name, $value);
                $i++;
            }
        }

        $sth->execute();
        $result = $sth->fetchAll($fetch_style);

        return isset($result) ? $result : null;
    }

}
*/