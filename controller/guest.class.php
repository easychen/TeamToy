<?php
if( !defined('IN') ) die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );

class guestController extends appController
{
	function __construct()
	{
		parent::__construct();
	}
	
	function index()
	{
		if( is_login() ) return forward( '?c=dashboard' );
		
		// do login
		$data['title'] = $data['top_title'] = '登入';
		$data['css'][] = 'login_screen.css';
		return render( $data , 'web' , 'fullwidth'  );
	}
	
	function login()
	{
		if( $user = login( v('email') , v('password') ) )
		{
			foreach( $user as $key => $value )
				$_SESSION[$key] = $value;
				
			return ajax_echo( '成功登入，正在转向中 ' .jsforword('?c=dashboard'));	
		}elseif( $user === null )
		{
			return ajax_echo( '尝试连接服务器失败，请稍后再试' );
		}
		else
		{
			return ajax_echo( '错误的Email地址或者密码，请重试' );
		}
	}
	
	function logout()
	{
		foreach( $_SESSION as $key=>$value )
		{
			unset( $_SESSION[$key] );
		}
		
		forward('?c=guest');
	}
}