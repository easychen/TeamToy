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
			return info_page( __('INSTALL_FINISHED') );
		elseif( intval(v('do')) == 1 )
		{
			 db_init();
		}
		else
		{
			$data['title'] = $data['top_title'] =  __('INSTALL_PAGE_TITLE') ;
			return render( $data , 'web' , 'fullwidth' );
		}
			

	}

	function index_en()
	{
		$GLOBALS['i18n'] = 'us_en';
		return $this->index();
	}
	
	
	
	
}
	