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

	function i18n()
	{
		@session_write_close(); 
		$c = z(t(v('lang')));

		if( strlen($c) < 1 )
		{
			$c = c('default_language');
			if( strlen($c) < 1 ) $c = 'zh_cn';	
		}
		
		if( !isset(  $GLOBALS['language'][$c] ) )
		{
			$lang_file = AROOT . 'local' . DS . basename($c) . '.lang.php';
			if( file_exists( $lang_file ) )
				include_once( $lang_file );
		}

		$data['js_items'] = js_i18n( $GLOBALS['language'][$c] );

		return render( $data , 'ajax' , 'js' );

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