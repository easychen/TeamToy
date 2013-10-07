<?php

// db functions
function db( $host = null , $port = null , $user = null , $password = null , $db_name = null )
{
	$db_key = MD5( $host .'-'. $port .'-'. $user .'-'. $password .'-'. $db_name  );
	
	if( !isset( $GLOBALS['LP_'.$db_key] ) )
	{
		@include_once( AROOT .  'config/db.config.sample.php' );
		@include_once( AROOT .  'config/db.config.php' );
		
		//include_once( AROOT .  'config/db.config.php' );
		//include_once( CROOT .  'lib/db.function.php' );
		
		$db_config = $GLOBALS['config']['db'];
		
		if( $host == null ) $host = $db_config['db_host'];
		if( $port == null ) $port = $db_config['db_port'];
		if( $user == null ) $user = $db_config['db_user'];
		if( $password == null ) $password = $db_config['db_password'];
		if( $db_name == null ) $db_name = $db_config['db_name'];
		
		if( !$GLOBALS['LP_'.$db_key] = mysqli_connect( $host , $user , $password , '' , $port ) )
		{
			//
			echo 'can\'t connect to database';
			return false;
		}
		else
		{
			if( $db_name != '' )
			{
				if( !mysqli_select_db( $GLOBALS['LP_'.$db_key] , $db_name ) )
				{
					echo 'can\'t select database ' . $db_name ;
					return false;
				}
			}
		}
		
		mysqli_query( $GLOBALS['LP_'.$db_key] , "SET NAMES 'UTF8'"  );
	}
	
	return $GLOBALS['LP_'.$db_key];
}

function s( $str , $db = NULL )
{
	if( $db == NULL ) $db = db();
	return  mysqli_real_escape_string( $db , $str )  ;
	
}

// $sql = "SELECT * FROM `user` WHERE `name` = ?s AND `id` = ?i LIMIT 1 "
function prepare( $sql , $array )
{
	
	foreach( $array as $k=>$v )
		$array[$k] = s($v );
	
	$reg = '/\?([is])/i';
	$sql = preg_replace_callback( $reg , 'prepair_string' , $sql  );
	$count = count( $array );
	for( $i = 0 ; $i < $count; $i++ )
	{
		$str[] = '$array[' .$i . ']';	
	}
	
	$statement = '$sql = sprintf( $sql , ' . join( ',' , $str ) . ' );';
	eval( $statement );
	return $sql;
	
}

function prepair_string( $matches )
{
	if( $matches[1] == 's' ) return "'%s'";
	if( $matches[1] == 'i' ) return "'%d'";	
}


function get_data( $sql , $db = NULL )
{
	if( $db == NULL ) $db = db();
	
	$GLOBALS['LP_LAST_SQL'] = $sql;
	$data = Array();
	$i = 0;
	$result = mysqli_query( $db , $sql );
	
	if( mysqli_errno( $db ) != 0 )
		echo mysqli_error( $db ) .' ' . $sql;
	
	while( $Array = mysqli_fetch_array($result, MYSQL_ASSOC ) )
	{
		$data[$i++] = $Array;
	}
	
	if( mysqli_errno( $db ) != 0 )
		echo mysqli_error( $db ) .' ' . $sql;
	
	mysqli_free_result($result); 

	if( count( $data ) > 0 )
		return $data;
	else
		return false;
}

function get_line( $sql , $db = NULL )
{
	$data = get_data( $sql , $db  );
	return @reset($data);
}

function get_var( $sql , $db = NULL )
{
	$data = get_line( $sql , $db );
	return $data[ @reset(@array_keys( $data )) ];
}

function last_id( $db = NULL )
{
	if( $db == NULL ) $db = db();
	return get_var( "SELECT LAST_INSERT_ID() " , $db );
}

function run_sql( $sql , $db = NULL )
{
	if( $db == NULL ) $db = db();
	$GLOBALS['LP_LAST_SQL'] = $sql;
	return mysqli_query( $db , $sql  );
}

function db_errno( $db = NULL )
{
	if( $db == NULL ) $db = db();
	return mysqli_errno( $db );
}


function db_error( $db = NULL )
{
	if( $db == NULL ) $db = db();
	return mysqli_error( $db );
}

function last_error()
{
	if( isset( $GLOBALS['LP_DB_LAST_ERROR'] ) )
	return $GLOBALS['LP_DB_LAST_ERROR'];
}

function close_db( $db = NULL )
{
	if( $db == NULL )
		$db = $GLOBALS['LP_DB'];
		
	unset( $GLOBALS['LP_DB'] );
	mysqli_close( $db );
}

?>