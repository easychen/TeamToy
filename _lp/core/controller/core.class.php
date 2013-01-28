<?php

if( !defined('IN') ) die('bad request');


class coreController 
{
	function __construct()
	{
		// load model functions
		$model_function_file = AROOT . 'model' . DS . g('c') . '.function.php';
		if( file_exists( $model_function_file ) )  
			require_once( $model_function_file );
		else
		{
			$cmodel = CROOT . 'model' . DS . g('c') . '.function.php';
			if( file_exists( $cmodel ) )  require_once( $cmodel );
		}
	}
	
	public function index()
	{
		// 
	} 
}

