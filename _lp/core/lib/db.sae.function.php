<?php

function db_read( $host = null , $port = null , $user = null , $password = null , $db_name = null )
{
	$db_key = MD5( 'read-' . $host .'-'. $port .'-'. $user .'-'. $password .'-'. $db_name  );
	
	if( !isset( $GLOBALS['LP_'.$db_key] ) )
	{
		@include_once( AROOT .  'config/db.config.sample.php' );
		@include_once( AROOT .  'config/db.config.php' );
		
		$db_config = $GLOBALS['config']['db'];
		
		if( $host == null ) $host = $db_config['db_host_read'];
		if( $port == null ) $port = $db_config['db_port'];
		if( $user == null ) $user = $db_config['db_user'];
		if( $password == null ) $password = $db_config['db_password'];
		if( $db_name == null ) $db_name = $db_config['db_name'];
		
		if( !$GLOBALS['LP_'.$db_key] = mysql_connect( $host.':'.$port , $user , $password , true ) )
		{
			//
			echo 'can\'t connect to database';
			return false;
		}
		else
		{
			if( $db_name != '' )
			{
				if( !mysql_select_db( $db_name , $GLOBALS['LP_'.$db_key] ) )
				{
					echo 'can\'t select database ' . $db_name ;
					return false;
				}
			}
		}
		
		mysql_query( "SET NAMES 'UTF8'" , $GLOBALS['LP_'.$db_key] );
	}
	
	return $GLOBALS['LP_'.$db_key];
}


// db functions
function db( $host = null , $port = null , $user = null , $password = null , $db_name = null )
{
	$db_key = MD5( $host .'-'. $port .'-'. $user .'-'. $password .'-'. $db_name  );
	
	if( !isset( $GLOBALS['LP_'.$db_key] ) )
	{
		@include_once( AROOT .  'config/db.config.sample.php' );
		@include_once( AROOT .  'config/db.config.php' );
		
		//include_once( CROOT .  'lib/db.function.php' );
		
		$db_config = $GLOBALS['config']['db'];
		
		if( $host == null ) $host = $db_config['db_host'];
		if( $port == null ) $port = $db_config['db_port'];
		if( $user == null ) $user = $db_config['db_user'];
		if( $password == null ) $password = $db_config['db_password'];
		if( $db_name == null ) $db_name = $db_config['db_name'];
		
		if( !$GLOBALS['LP_'.$db_key] = mysql_connect( $host.':'.$port , $user , $password , true ) )
		{
			//
			echo 'can\'t connect to database';
			return false;
		}
		else
		{
			if( $db_name != '' )
			{
				if( !mysql_select_db( $db_name , $GLOBALS['LP_'.$db_key] ) )
				{
					echo 'can\'t select database ' . $db_name ;
					return false;
				}
			}
		}
		
		mysql_query( "SET NAMES 'UTF8'" , $GLOBALS['LP_'.$db_key] );
	}
	
	return $GLOBALS['LP_'.$db_key];
}

function s( $str , $db = NULL )
{
	if( $db == NULL ) $db = db();
	return   mysql_real_escape_string( $str , $db )  ;
	
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
	if( $db == NULL ) $db = db_read();
	
	$GLOBALS['LP_LAST_SQL'] = $sql;
	$data = Array();
	$i = 0;
	$result = mysql_query( $sql ,$db );
	
	if( mysql_errno() != 0 )
		echo mysql_error() .' ' . $sql;
	
	while( $Array = mysql_fetch_array($result, MYSQL_ASSOC ) )
	{
		$data[$i++] = $Array;
	}
	
	if( mysql_errno() != 0 )
		echo mysql_error() .' ' . $sql;
	
	mysql_free_result($result); 

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
	return mysql_query( $sql , $db );
}

function db_errno( $db = NULL )
{
	if( $db == NULL ) $db = db();
	return mysql_errno( $db );
}


function db_error( $db = NULL )
{
	if( $db == NULL ) $db = db();
	return mysql_error( $db );
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
	mysql_close( $db );
}

?>