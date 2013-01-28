<?php
if( !defined('IN') ) die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );

class installController extends appController
{
	function __construct()
	{
		parent::__construct();
		if( !is_installed() ) db_init();
	}
	
	function index()
	{
		return info_page('API Server 已初始化完成，<a href="?c=guest">请使用管理账号登入</a>');
	}
	
	
	
	
}
	