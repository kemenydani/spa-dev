<?php


class DB {
	
	public function where( $table, array $params = [] )
	{
		//if( empty($conditions) ) return false;
		
		//$table = DB::_PREFIX_ . $table;
		
		$sql = "SELECT * FROM {$table} WHERE ";
		
		$bc = 1;
		
		$binds = [];
		
		foreach( $params as $column => $value )
		{
			$bind = null;
			$stmt = null;
			
			if( is_callable( $value ) )
			{
				$value( $bind, $stmt );
				
				if( is_array($bind))
				{
					foreach( $bind as $item )
					{
						$binds[$bc] = $item;
					}
				}
				else
				{
					$binds[$bc] = $bind;
				}
				
				$sql .= $column . ' ' . $stmt;
			}
			else
			{
				$binds[$bc] = $value;
				$sql .= $column . ' = ? ';
			}
			
		}
		
		return $sql;
	}
	
	
}

var_dump((new DB())->where('apple', [
	'foo' => 1,
	'bar' => function( &$bind, &$stmt ) {
		$bind = 555;
		$stmt = 'asd IN(1,2,43,54,56)';
	},
	'baz' => 1,
]));