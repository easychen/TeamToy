<?php
if( !defined('IN') ) die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );

class pluginController extends appController
{
	function __construct()
	{
		parent::__construct();
		$this->check_login();
	}
	
	 public function __call( $method , $args)
    {
    	return do_action( 'PLUGIN_' . $method , $args );
    }
}