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
		if( is_mobile_request() ) return forward( 'client/' );
		if( is_login() ) return forward( '?c=dashboard' );
		
		// do login
		$data['title'] = $data['top_title'] = __('LOGIN_PAGE_TITLE');
		$data['css'][] = 'login_screen.css';

		$data['langs'] = @glob( AROOT . 'local/*.lang.php'  );

		return render( $data , 'web' , 'fullwidth'  );
	}
	
	function login()
	{
		if( $user = login( v('email') , v('password') ) )
		{
			foreach( $user as $key => $value )
				$_SESSION[$key] = $value;
				
			return ajax_echo( __('LOGIN_OK_NOTICE') .jsforword('?c=dashboard'));

		}elseif( $user === null )
		{
			return ajax_echo( __('API_CONNECT_ERROR_NOTICE') );
		}
		else
		{
			return ajax_echo( __('LOGIN_BAD_ARGS_NOTICE') );
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