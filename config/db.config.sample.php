<?php
// 请不要直接修改本文件，改名为db.config.php后再修改，否则升级时将被覆盖。

if( defined('SAE_APPNAME') )
{
	$GLOBALS['config']['db']['db_host'] = SAE_MYSQL_HOST_M;
	$GLOBALS['config']['db']['db_host_read'] = SAE_MYSQL_HOST_S;
	
	$GLOBALS['config']['db']['db_port'] = SAE_MYSQL_PORT;
	
	$GLOBALS['config']['db']['db_user'] = SAE_MYSQL_USER;
	$GLOBALS['config']['db']['db_password'] = SAE_MYSQL_PASS;
	$GLOBALS['config']['db']['db_name'] = SAE_MYSQL_DB;
	
}
else
{
	// 安装到新浪云以外，请修改以下数据库信息
	$GLOBALS['config']['db']['db_host'] = 'localhost';
	$GLOBALS['config']['db']['db_port'] = 3306;
	$GLOBALS['config']['db']['db_user'] = 'root';
	$GLOBALS['config']['db']['db_password'] = '';
	$GLOBALS['config']['db']['db_name'] = 'lpdb';

}
