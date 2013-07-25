<?php
if( !defined('IN') ) die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );

class feedController extends appController
{
	function __construct()
	{
		parent::__construct();
		$this->check_login();
	}
	
	function index()
	{
		$data['title'] = $data['top_title'] =  __('FEED_PAGE_TITLE') ;
		render( $data , 'web' , 'card' );
	}

	function cast()
	{
		$text = z(t(v('text')));
		if( strlen( $text ) < 1 ) render( array( 'code' => 100002 , 'message' => __('BAD_ARGS') ) , 'rest' );

		$params = array();
		$params['text'] = $text;
		
		
		if($content = send_request( 'feed_publish' ,  $params , token()  ))
		{
			$data = json_decode($content , 1);
			if( $data['err_code'] == 0 )
			{
				return render( array( 'code' => 0 , 'data' =>  array( 'html' => render_html( array( 'item' => $data['data'] ) , AROOT . 'view' 
						. DS . 'layout' . DS . 'ajax' . DS . 'widget' . DS . 'feed.tpl.html'  ) ) ) , 'rest' );
			}
			else
				return render( array( 'code' => 100002 , 'message' => __('API_MESSAGE_CANNOT_CONNECT') . $data['err_msg']  ) , 'rest' );
			//return render( array( 'code' => 0 , 'data' => $data['data'] ) , 'rest' );
		}

		return render( array( 'code' => 100001 , 'message' => __('API_CONNECT_ERROR_NOTICE') ) , 'rest' );

	}

	function data()
	{
		
		$params = array();
		$params['max_id'] = intval(v('max_id'));
		
		if($content = send_request( 'feed_list' ,  $params , token()  ))
		{
			$data = json_decode($content , 1);
			if( intval($data['err_code']) != 0 ) 
				return false;

			return render( $data , 'ajax' , 'raw'  );

		}

		return null;
	}

	function feed_remove()
	{
		$fid = intval(v('fid'));
		if( $fid < 1 ) return render( array( 'code' => 100002 , 'message' => __('BAD_ARGS') ) , 'rest' );

		$params = array();
		$params['fid'] = $fid;

		if($content = send_request( 'feed_remove' ,  $params , token()  ))
		{
			$data = json_decode($content , 1);
			if( $data['err_code'] == 0 )
			{
				return render( array( 'code' => 0 , 'data' => $data['data']) , 'rest' );
			}
			else
				return render( array( 'code' => 100002 , 'message' => __('API_MESSAGE_CANNOT_CONNECT') ) , 'rest' );
			//return render( array( 'code' => 0 , 'data' => $data['data'] ) , 'rest' );
		}

		return render( array( 'code' => 100001 , 'message' => __('API_CONNECT_ERROR_NOTICE') ) , 'rest' );
	}

	function feed_remove_comment()
	{
		$cid = intval(v('cid'));
		if( $cid < 1 ) return render( array( 'code' => 100002 , 'message' => __('BAD_ARGS') ) , 'rest' );

		$params = array();
		$params['cid'] = $cid;

		if($content = send_request( 'feed_remove_comment' ,  $params , token()  ))
		{
			$data = json_decode($content , 1);
			if( $data['err_code'] == 0 )
			{
				return render( array( 'code' => 0 , 'data' => $data['data']) , 'rest' );
			}
			else
				return render( array( 'code' => 100002 , 'message' => __('API_MESSAGE_CANNOT_CONNECT') ) , 'rest' );
			//return render( array( 'code' => 0 , 'data' => $data['data'] ) , 'rest' );
		}

		return render( array( 'code' => 100001 , 'message' => __('API_CONNECT_ERROR_NOTICE') ) , 'rest' );

	}

	function feed_add_comment()
	{
		$text = z(t(v('text')));
		if( strlen( $text ) < 1 ) render( array( 'code' => 100002 , 'message' => __('BAD_ARGS') ) , 'rest' );

		$fid = intval(v('fid'));
		if( $fid < 1 ) return render( array( 'code' => 100002 , 'message' => __('BAD_ARGS') ) , 'rest' );

		$params = array();
		$params['text'] = $text;
		$params['fid'] = $fid;

		if($content = send_request( 'feed_add_comment' ,  $params , token()  ))
		{
			$data = json_decode($content , 1);
			if( $data['err_code'] == 0 )
			{
				return render( array( 'code' => 0 , 'data' =>  array( 'html' => render_html( array( 'item' => $data['data'] ) , AROOT . 'view' 
						. DS . 'layout' . DS . 'ajax' . DS . 'widget' . DS . 'fcomment.tpl.html'  ) ) ) , 'rest' );
			}
			else
				return render( array( 'code' => 100002 , 'message' => __('API_MESSAGE_CANNOT_CONNECT') ) , 'rest' );
			//return render( array( 'code' => 0 , 'data' => $data['data'] ) , 'rest' );
		}

		return render( array( 'code' => 100001 , 'message' => __('API_CONNECT_ERROR_NOTICE') ) , 'rest' );
	}

	function feed_detail()
	{

		//return ajax_echo( print_r( $_REQUEST , 1 ) );
		$fid = intval(v('fid'));
		if( $fid < 1 ) return info_page(__('FEED_LOAD_ERROR_RETRY'));

		$params = array();
		$params['fid'] = $fid;
		
		if($content = send_request( 'feed_detail' ,  $params , token()  ))
		{
			$data = json_decode($content , 1);
			if( intval($data['error_code']) != 0 ) 
				return false;
			else
				return render( $data , 'ajax' , 'raw'  );
		}

		return info_page(__('FEED_LOAD_ERROR_RETRY'));

		
	}

	
}