<?php

namespace core;

class DB extends \PDO
{
	public static $_instance;

	const _PREFIX_   = '_xyz_';
    const _HOST_     = 'localhost';
    const _DB_NAME_  = 'engine-dev';
    const _USERNAME_ = 'root';
    const _PASSWORD_ = '';

	public static function instance()
    {
		if ( self::$_instance === null )
		{
		    $dbDefinition = 'mysql:host='.self::_HOST_.';dbname='.self::_DB_NAME_.'';

            self::$_instance = new DB($dbDefinition, self::_USERNAME_, self::_PASSWORD_);
			self::$_instance->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
			self::$_instance->exec("set names utf8");
		}
		return self::$_instance;
	}

	public function update( $table, array $what, $key, $id )
    {
        $table = DB::_PREFIX_ . $table;

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
	    $table = DB::_PREFIX_ . $table;

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

        if( $isInserted ) return DB::instance()->lastInsertId();

        return false;
    }

    public function all( $table )
    {
        $stmt = "SELECT * FROM " . DB::_PREFIX_ . $table;

        $sql = DB::instance()->query( $stmt );

        return $sql->fetchAll(\PDO::FETCH_OBJ );
    }

	public function where( $table, array $conditions = [], $order = [], $limit = [] )
	{

	}

	private static function _find_( $table, $column, $value )
    {
        $table = DB::_PREFIX_ . $table;

        $stmt = "SELECT * FROM {$table} WHERE ".$column." = ? LIMIT 1";

        $sql = DB::instance()->prepare( $stmt );
        $sql->bindValue(1, $value );

        $sql->execute();

        return $sql;
    }

    public function find( $table, $column, $value )
    {
        return DB::_find_( $table, $column, $value )->fetch(\PDO::FETCH_ASSOC );
    }

    public function findAll( $table, $column, $value )
    {
        return DB::_find_( $table, $column, $value )->fetchAll(\PDO::FETCH_OBJ );
    }

    public function delete( $table, $column, $value )
    {
        $table = DB::_PREFIX_ . $table;

        $stmt = "DELETE FROM {$table} WHERE ".$column." = ? LIMIT 1";

        $sql = DB::instance()->prepare( $stmt );
        $sql->bindValue(1, $value );

        $isDeleted = $sql->execute();

        return $isDeleted;
    }

    public function deleteIn( $table, $column, array $range )
    {
        $table = DB::_PREFIX_ . $table;

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

}