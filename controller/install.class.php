<?php
if( !defined('IN') ) die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );

class installController extends appController
{
	function __construct()
	{
		parent::__construct();
	}
	
	function index()
	{
		if( is_installed() )
			return info_page('API Server 已初始化完成，<a href="?c=guest">请使用管理账号登入</a>');
		elseif( intval(v('do')) == 1 )
		{
			 db_init();
		}
		else
		{
			$data['title'] = $data['top_title'] = 'TeamToy安装页面';
			return render( $data , 'web' , 'fullwidth' );
		}
			

	}
	
	
	
	
}
	