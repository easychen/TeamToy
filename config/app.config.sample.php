<?php
// 请不要直接修改本文件，改名为app.config.php后再修改，否则升级时将被覆盖。
$GLOBALS['config']['site_name'] = 'TeamToy';
$GLOBALS['config']['site_domain'] = $_SERVER['HTTP_HOST'];
$GLOBALS['config']['site_url'] = 'http://'.$GLOBALS['config']['site_domain'];

$GLOBALS['config']['default_controller'] = 'guest';
$GLOBALS['config']['favicon'] = 'static/image/favicon.png';
$GLOBALS['config']['default_avatar'] = 'static/image/user.avatar.png';
$GLOBALS['config']['api_server'] = $GLOBALS['config']['site_url'] . '/index.php';
$GLOBALS['config']['api_check_new_verison'] = true;
$GLOBALS['config']['teamtoy_url'] = 'http://tt2net.sinaapp.com';
$GLOBALS['config']['at_short_name'] = true ;
$GLOBALS['config']['can_modify_password'] = true ;
$GLOBALS['config']['timezone'] = 'Asia/Chongqing' ;
$GLOBALS['config']['dev_version'] = false ;

// session time
// you need change session lifetime in php.ini to0
$GLOBALS['config']['session_time'] = 60*60*24*3 ;

$GLOBALS['config']['plugin_path'] = AROOT . DS . 'plugin' . DS ;
$GLOBALS['config']['plugins'] = array( 'css_modifier' , 'simple_token');


