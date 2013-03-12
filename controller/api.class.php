<?php
if( !defined('IN') ) die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );


define( 'LR_API_TOKEN_ERROR' , 10001 );
define( 'LR_API_USER_ERROR' , 10002 );
define( 'LR_API_DB_ERROR' , 10004 );
define( 'LR_API_NOT_IMPLEMENT_YET' , 10005 );
define( 'LR_API_ARGS_ERROR' , 10006 );
define( 'LR_API_DB_EMPTY_RESULT' , 10007 );
define( 'LR_API_USER_CLOSED' , 10008 );
define( 'LR_API_FORBIDDEN' , 10009 );
define( 'LR_API_UPGRADE_ERROR' , 10010 );
define( 'LR_API_UPGRADE_ABORT' , 10011 );

/**
 * TeamToy Open Api
 *
 * @author easychen
 * @version $Id$
 * @package server
 *
 */

/**
 * apiController class
 * TeamToy OpenAPI实现
 *
 * <code>
 * <?php
 * $api_url = 'http://api.teamtoy.net/index.php';
 * $info = json_decode(file_get_contents( $api_url . '?c=api&a=user_get_token&password=******&email=abc@qq.com' ) , 1);
 * $token = $info['data']['token'];
 *
 * $todos = json_decode(file_get_contents( $api_url . '?c=api&a=todo_list&token=' . $token ) , 1);
 * print_r( $todos ); 
 *
 *
 * ?>
 * </code>
 *
 * 
 * @author easychen
 * @package server
 * 
 */
class apiController extends appController
{
    
    function __construct()
    {
        parent :: __construct();
		apply_filter( 'API_' . g('a') .'_INPUT_FILTER' );
		

		$not_check = array( 'user_sign_up' , 'user_get_token'  );
		$not_check = apply_filter('API_LOGIN_ACTION_FILTER' , $not_check );

		if( !in_array( g('a') , $not_check )) $this->check_token();
    }

    public function __call( $method , $args)
    {
    	// 进入这里表示不存在当前调用的api(no public method)
    	return do_action( 'API_' . $method , $args );
    }
	
    
 	/**
     * 用户注册
     *
     * 只有以管理员的token才能注册用户，否则需要激活码
     *
     * @param string name
     * @param string email
     * @param string password
     * @return user array
     * @author EasyChen
     */
	public function user_sign_up()
    {
		if( !not_empty( v( 'name' ) )) return self::send_error( LR_API_ARGS_ERROR , 'name FIELD REQUIRED' );
        
		
		if( !is_email( v( 'email' ) ) ) return self::send_error( LR_API_ARGS_ERROR , 'email FORMAT ERROR' );
        
		
		if( strlen( v( 'password' ) ) < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'password FIELD REQUIRED' );// actions
		
		
		// admin add user derictly
		$jump = false;

		$token = z( t( v( 'token' ) ) );
        
        if( strlen( $token ) > 2 )
        {
         	session_id( $token );
         	session_set_cookie_params( c('session_time') );
        	@session_start();
        	if($_SESSION['level'] == '9') $jump = true;
        }
        

        if( !$jump )
        {
        
			if( !not_empty( v( 'code' ) )) return self::send_error( LR_API_ARGS_ERROR , 'activecode REQUIRED' );
		
        	$code = z(t(v('code')));
		
			if( get_var( "SELECT COUNT(*) FROM `activecode` WHERE `code` = '" . s($code) . "' AND `timeline` > '" . date( "Y-m-d H:i:s " , strtotime("-1day")) . "'" ) < 1  )
			return self::send_error( LR_API_ARGS_ERROR , 'activecode error or expired' );	
        }

        
		
		
		if( get_var("SELECT COUNT(*) FROM `user` WHERE `email` = '" . s( t(v('email')) ) . "'") > 0 )
		return self::send_error( LR_API_ARGS_ERROR , 'email EXISTS' );
		
		$dsql = array();
		
		$dsql[] = "'" . s( v( 'name' ) ) . "'";
		$dsql[] = "'" . s( pinyin(strtolower(v( 'name' ))) ) . "'";
        $dsql[] = "'" . s( v( 'email' ) ) . "'";
        $dsql[] = "'" . s( md5( v( 'password' ) ) ) . "'";
        $dsql[] = "'" . s( date( "Y-m-d H:i:s" ) ) . "'";
        
		$sql = "INSERT INTO `user` ( `name` , `pinyin` , `email` , `password` , `timeline` ) VALUES ( " . join( ' , ' , $dsql ) . " )";
		
        run_sql( $sql );
        
        if( db_errno() != 0 )
        {
            return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . db_error() );
        }
        
        $lid = last_id();
        
        if( $lid < 1 )
        {
            return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . db_error() );
        }
        
        if( !$data = get_user_info_by_id( $lid ) )
			return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . db_error() );
        else
		{
			// one code for multi-people
			/*
			$sql = "DELETE FROM `activecode` WHERE `code` = '" .  s($code)  . "' LIMIT 1";
			run_sql( $sql );
			*/
			
			publish_feed( $data['name'] . '加入了TeamToy' , $data['uid'] , 3  );
			return self::send_result( $data );
		}
			
        
    }

    /**
     * 更新当前用户分组
     *
     * @param string uid 
     * @param string groups , 多个group用|分割 
     * @param string token , 必填
     * @return user array
     * @author EasyChen
     */
    public function user_update_groups()
    {
    	// 管理员权限
    	if( $_SESSION['level'] != '9' )
		return self::send_error( LR_API_FORBIDDEN , 'ONLY ADMIN CAN DO THIS' );

		$uid = intval(v('uid'));
		if( $uid < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'UID CAN\'T BE EMPTY' );

		$groups = strtoupper(z(t(v('groups'))));
		
		if( strlen( $groups ) > 0 ) 
			$groups = '|' . trim( $groups , '|'  ) . '|';

		$sql = "UPDATE `user` SET `groups` = '" . s( $groups ) . "' WHERE `id` = '" . intval($uid) . "' LIMIT 1";
		run_sql( $sql );

		if( db_errno() != 0 )
			return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . db_error() );
		else
			return self::send_result( get_user_info_by_id($uid) );

    }
	
	/**
     * 终止当前token
     *
     *
     * @param string token , 必填
     * @return user array
     * @author EasyChen
     */
	public function user_end_session()
	{
		$data = array();
		$data['token'] = $_SESSION['token'];
		$data['uid'] = $_SESSION['uid'];
		
		
		foreach( $_SESSION as $key=>$value )
		{
			unset( $_SESSION[$key] );
		}
		
		session_destroy();
		
		return self::send_result( $data );
			
	}
	
	/**
     * 检查是否存在新版本
     *
     *
     * @param string token , 必填
     * @return info array （ 'new' , 'version' , 'info' ）
     * @author EasyChen
     */
	public function check_new_verison( $in = false )
	{
		$last = intval(kget('last_check'));
		if( $last > 0 && ( (time()-$last) < 60*60 ) && $in  )
		{
			// checked in 1 hour
			// do nothing 
		}
		else
		{
			// set timeout
			$ctx=stream_context_create(array('http'=>array( 'timeout' => 3 )));
			// send domain and uid to help teamtoy.net anti-cc attack
			$url = c('teamtoy_url') . '/?a=last_version&domain=' . c('site_domain') . '&uid=' . $user[ 'id' ];
			
			if( c('dev_version') ) $url = $url . '&dev=1';

			$new = false;
			if($info = @file_get_contents($url , 0 , $ctx))
			{
				$info_array = json_decode($info,true);
				if( $new_build = intval($info_array['version']) )
				{
					if( $new_build > local_version() )
					{
						$new = true;

						$last_noticed_version = intval(kget('last_notice'));
						if( $last_noticed_version > 0 )
						{
							if( $new_build > $last_noticed_version )
								$send = 1;
							else
								$send = 0;
						}
						else
							$send = 1;

						if( $send == 1 )
						{
							// send notice to current user

							$text = 'TeamToy'.$new_build.'版本已发布';
							
							if( !$in )
							{
								send_notice( uid() , $text , 10 , array( 'info' => $info_array['desp'] ) );
								kset('last_notice',$new_build);
							}
							



						}
						
					}

					kset('last_check',time());
				}
			}

			if( !$in )
			{
				if( $new  )
					return self::send_result( array('new'=>1 ,'info' => $info_array['desp'] , 'version' => $info_array['version'] )  );
				else
					return self::send_result( array('new'=>0 )  );
			}
		}

	}
    
    /**
     * 通过email和密码获取token
     *
     * @param string email
     * @param string password
     * @return token array( 'token' , 'uid' , 'uname' , 'email' , 'level' )
     * @author EasyChen
     */
    public function user_get_token()
    {
        $email = z( t( v( 'email' ) ) );
        $password = z( t( v( 'password' ) ) );
        
		if( $user = get_full_info_by_email_password( $email , $password ) )
        {
            if( $user['is_closed'] == '1' )
				return self::send_error( LR_API_USER_CLOSED , 'USER CLOSED BY ADMIN' );
		
			session_set_cookie_params( c('session_time') );
			@session_start();
            $token = session_id();
            $_SESSION[ 'token' ] = $token;
            $_SESSION[ 'uid' ] = $user[ 'id' ];
            $_SESSION[ 'uname' ] = $user['name'];
            $_SESSION[ 'email' ] = $user[ 'email' ];
			$_SESSION[ 'level' ] = $user['level'];
			if( strlen( $user['groups'] ) > 0 )
			{
				$user['groups'] = explode('|', trim( $user['groups'] , '|' )) ;
				$_SESSION[ 'groups' ] = $user['groups'];
			} 
							
			if( c('api_check_new_verison') )
				$this->check_new_verison( true );
			
			return self::send_result( $_SESSION );
        }
        else
        {
            return self::send_error( LR_API_TOKEN_ERROR , 'BAD ACCOUNT OR PASSWORD' );
        }
    }

    /**
     * 重置密码
     *
     * 只有管理员token才能调用
     *
     * @param string token , 必填  
     * @param string uid
     * @return msg array( 'newpass'=>newpass )
     * @author EasyChen
     */
    function user_reset_password()
    {
    	if( $_SESSION['level'] != '9' )
		return self::send_error( LR_API_FORBIDDEN , 'ONLY ADMIN CAN DO THIS' );

		$uid = intval(v('uid'));
		if( $uid < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'UID CAN\'T BE EMPTY' );

		if( $uid == uid() ) return self::send_error( LR_API_ARGS_ERROR , 'CAN\'T RESET YOUR OWN PASSWORD' );

		$rnd = rand( 1 , 10 );
		$newpass = substr( md5($uid.time().rand( 1 , 9999 )) , $rnd , 15 );

		$sql = "UPDATE `user` SET `password` = '". md5($newpass)."' WHERE `id` = '" . intval( $uid ) . "' LIMIT 1";
		run_sql( $sql );

		if( db_errno() != 0 )
			return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . db_error() );
		else
			return self::send_result( array( 'newpass' => $newpass ) );	
    }

     /**
     * 在线升级
     *
     * 只有管理员token才能调用
     *
     * @param string token , 必填  
     * @param string password
     * @return msg array( 'msg'=>ok )
     * @author EasyChen
     */
    function upgrade()
    {
    	if( $_SESSION['level'] != '9' )
		return self::send_error( LR_API_FORBIDDEN , 'ONLY ADMIN CAN DO THIS' );

    	$url = c('teamtoy_url') . '/?a=last_version&domain=' . c('site_domain') . '&uid=' . uid();
    	if( c('dev_version') ) $url = $url . '&dev=1';

    	$info = json_decode( file_get_contents( $url ) , true);
    	if( !isset($info['url']) ) return  self::send_error( LR_API_UPGRADE_ERROR , ' JSON DATA ERROR' );
    	$url = t($info['url']);


		$vid = intval($info['version']);
		if( $vid < 1 ) return  self::send_error( LR_API_UPGRADE_ERROR , ' JSON DATA ERROR' );

		if( $vid == local_version() )
		{
			return  self::send_error( LR_API_UPGRADE_ABORT , ' ALREADY LATEST VERSION' );
		}

		$zip_tmp = SAE_TMP_PATH . DS . 'teamtoy2-' . intval($vid) . '.zip';

		if( @copy( $url ,  $zip_tmp )  )
		{
			include_once( AROOT.'lib'.DS.'dUnzip2.inc.php' );
			$zip = new dUnzip2( $zip_tmp );
			$zip->debug = false;	
		
			$zip->unzipAll( AROOT  );
			@chmod( AROOT , 0755 );
			
			if( isset( $info['post_script'] ) ) $pscript = t($info['post_script']);
			else $pscript = false;

			if( local_version() == $vid )
			{
				if( $pscript )
					send_notice( uid() , 'TeamToy代码已经更新到' . $vid . ',<a href="'. c('site_url') . $pscript .'">请立即升级数据表</a>' , 0  );
				
				return self::send_result( array('msg'=>'ok','post_script'=>$pscript) );
			}
				
			else
				return  self::send_error( LR_API_UPGRADE_ERROR , ' FILE UNZIP ERROR' );

			
		}
		else
		{
			return  self::send_error( LR_API_UPGRADE_ERROR , ' COPY REMOTE FILE ERROR' );
		}
    }
	
	 /**
     * 读取用户个人资料
     *
     * 不包含密码
     *
     * @param string token , 必填  
     * @param int uid
     * @return user array
     * @author EasyChen
     */
    function user_profile()
    {
    	$uid = intval(v('uid'));
		if( $uid < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'UID CAN\'T BE EMPTY' );

		return self::send_result( get_user_info_by_id($uid) );

    }

     /**
     * 更新用户个人资料
     *
     * 不包含密码
     *
     * @param string token , 必填 
     * @param string mobile - 手机号 , 必填
     * @param string email - 电子邮件 , 必填
     * @param string tel - 分机号 , 选填
     * @param string eid - 工号, 选填
     * @param string weibo - 微博昵称, 选填
     * @param string desp - 备注, 选填
     * @return user array
     * @author EasyChen
     */
	function user_update_profile()
	{
		$mobile = z(t(v('mobile')));
		$tel = z(t(v('tel')));
		$eid = z(t(v('eid')));
		$weibo = z(t(v('weibo')));
		$desp = z(t(v('desp')));
		$email = z(t(v('email')));
		
		if( !not_empty($email) ) return self::send_error( LR_API_ARGS_ERROR , 'email FIELD REQUIRED' );
		if( !not_empty($mobile) ) return self::send_error( LR_API_ARGS_ERROR , 'mobile FIELD REQUIRED' );
			
		
		$sql = "UPDATE `user` SET "
		." `mobile` = '" . s($mobile) . "' "
		." , `tel` = '" . s($tel) . "' "
		." , `eid` = '" . s($eid) . "' "
		." , `weibo` = '" . s($weibo) . "' "
		." , `email` = '" . s($email) . "' "
		." , `desp` = '" . s($desp) . "' WHERE `id` = '" . uid() . "' LIMIT 1 ";
		
		run_sql( $sql );
		
		if( db_errno() != 0 )
			return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . db_error() );
		else
			return self::send_result( get_user_info_by_id(uid()) );
		
	}
	
	/**
     * 读取用户扩展设置
     *
     * @ignore
     */
	function user_settings()
	{
		if(!is_array( $settings = get_user_settings_by_id( $_SESSION['uid'] ) ))
			return self::send_error( LR_API_DB_ERROR , 'CAN\'T FIND DATA' );
		else
			return self::send_result( $settings );	
	}
	
	 /**
     * 更新用户密码
     *
     *
     * @param string token , 必填
     * @param string opassword - 原密码 , 必填
     * @param string password -新密码 , 必填
 	 * @return msg array( 'msg'=>ok )
     * @author EasyChen
     */
	function user_update_password()
	{
		
		if( !c('can_modify_password') ) return self::send_error( LR_API_ARGS_ERROR , 'CANNOT MODITY PASSWORD IN THIS MODE' );

		$opassword = z(t(v('opassword')));
		if( !not_empty($opassword) ) return self::send_error( LR_API_ARGS_ERROR , 'old password FIELD REQUIRED' );
		
		$password = z(t(v('password')));
		if( !not_empty($password) ) return self::send_error( LR_API_ARGS_ERROR , 'password FIELD REQUIRED' );
		
		if( $opassword == $password ) return self::send_error( LR_API_ARGS_ERROR , 'password and old password are the same' );
		
		$passwordv1 = md5( $opassword );
		$passwordv2 = ttpassv2( $opassword , uid() );


		$sql = "SELECT COUNT(*) FROM `user` WHERE `id` = '" . intval( uid() ) . "' AND ( `password` = '" . s($passwordv1) . "' OR  `password` = '" . s($passwordv2) . "'  ) ";
		
		if( get_var( $sql ) < 1 )
			return self::send_error( LR_API_ARGS_ERROR , 'Old password wrong'.$sql );
			
		$newpass = ttpassv2( $password , uid() );
		$sql = "UPDATE	`user` SET `password` = '" . s($newpass) . "' WHERE `id` = '" . intval( uid() ) . "' AND ( `password` = '" . s($passwordv1) . "' OR  `password` = '" . s($passwordv2) . "'  ) LIMIT 1";
		
		run_sql( $sql );
		
		if( db_errno() != 0 )
			return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . db_error() );
		else
			return self::send_result( array('msg'=>'ok') );
		

		
	}
	
	/**
     * 更新用户扩展设置
     *
     * @ignore
     */
	function user_update_settings()
	{
		$key = z(t(v('key')));
		if( !not_empty($key) ) return self::send_error( LR_API_ARGS_ERROR , 'key FIELD REQUIRED' );
		
		if(!$value = unserialize(v('value')))
		{
			$value = z(t(v('value')));
			if( !not_empty($value) ) return self::send_error( LR_API_ARGS_ERROR , 'value FIELD REQUIRED' );
		}
		else
		{
			if( !is_array($value) ) return self::send_error( LR_API_ARGS_ERROR , 'value FIELD REQUIRED' );
		
		}
		
		
		
		if(!is_array( $settings = get_user_settings_by_id( $_SESSION['uid'] ) ))
			return self::send_error( LR_API_DB_ERROR , 'CAN\'T FIND DATA' );
		else
		{
			$settings[$key] = $value;
			update_user_settings_array( $settings );
			
			if( db_errno() != 0 )
				return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . db_error() );
			else
				return self::send_result( $settings );
			
		}	
		
	}
	
	/**
     * 更新用户等级
     *
     * 必须是管理员的token，level9为管理员，不能修改自己的等级
     *
     * @param string token , 必填
     * @param string uid  , 必填
     * @return user array
     * @author EasyChen
     */
	function user_level()
	{
		$uid = intval(v('uid'));
		if( $uid < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'UID CAN\'T BE EMPTY' );
		
		if( $uid == uid() ) return self::send_error( LR_API_ARGS_ERROR , 'CANNOT CHANGE YOUR SELF' );
		
		if(!$user = get_user_info_by_id( $uid ))
		return self::send_error( LR_API_ARGS_ERROR , 'UID NOT EXISTS' );
		
		$level = intval(v('level'));
		
		
		if( $_SESSION['level'] != '9' )
		return self::send_error( LR_API_FORBIDDEN , 'ONLY ADMIN CAN DO THIS' );
		
		if( $level == 0 ) $more = " , `is_closed` = 1 ";
		else $more = "";	
		
		$sql = "UPDATE `user` SET `level` = '" . intval( $level ) . "' " . $more . " WHERE `id` = '" . intval($uid) . "' LIMIT 1";
		
		
		
		run_sql( $sql );
		
		if( db_errno() != 0 )
            return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . db_error() );
        else
		{
			if( $level == 0 )
			{
				publish_feed( uname().'关闭了账号【'. $user['name'] .'】' , uid() , 1 );
				$user['level'] = 0;
				return self::send_result( $user );
			}
			else
			{
				publish_feed( uname().'修改了账号【'. $user['name'] .'】权限为'.$level , uid() , 1 );
				return self::send_result( get_user_info_by_id($uid) );
			
			}
			
		
		}
	}
	
	/**
     * 关闭用户
     *
     * 必须是管理员的token
     *
     * @param string token , 必填
     * @param string uid  , 必填
     * @return user array
     * @author EasyChen
     */
	function user_close()
	{
		$uid = intval(v('uid'));
		if( $uid < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'UID CAN\'T BE EMPTY' );
		
		if(!$user = get_user_info_by_id( $uid ))
		return self::send_error( LR_API_ARGS_ERROR , 'UID NOT EXISTS' );
		
		if( $_SESSION['level'] != '9' )
		return self::send_error( LR_API_FORBIDDEN , 'ONLY ADMIN CAN DO THIS' );
		
		if( $user['is_closed'] == '1' )
			return self::send_error( LR_API_USER_CLOSED , 'USER CLOSED BY ADMIN' );
		
		if( $_SESSION['level'] == '9' && $uid == uid() )
		{
			$admin_num = get_var( "SELECT COUNT(*) FROM `user` WHERE `is_closed` = 0 AND `level` = 9 " );
			if( $admin_num < 2 ) return self::send_error( LR_API_FORBIDDEN , 'CANNOT CLOSE THE ONLY ADMIN' );

		}
		
		close_user_by_id($uid);
		
		if( db_errno() != 0 )
            return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . db_error() );
        else
		{
			publish_feed( uname().'关闭了账号【'. $user['name'] .'】' , uid() , 1 );
			return self::send_result( $user );
		
		}
		
		
			
	}
	
	/**
     * 添加TODO
     *
     *
     * @param string token , 必填
     * @param string text - TODO内容 , 必填
     * @param string is_public - 是否公开 , 默认为1
     * @param string uid - 要给添加TODO的用户id , uid为0时添加给自己。私有TODO不能添加给其他人
     * @return todo array
     * @author EasyChen
     */
	public function todo_add()
	{
		$content = z(t(v('text')));
		if( !not_empty($content) ) return self::send_error(  LR_API_ARGS_ERROR , 'TEXT CAN\'T EMPTY' );
		
		
		$is_public = intval(v('is_public'));
		if( $is_public != 0  ) $is_public = 1;
		
		$uid = intval(v('uid'));
		$owner_uid=$uid>0?$uid:uid();

		
		// 检查是否已经存在
		$sql = "SELECT * FROM `todo` WHERE `content` = '" . s( $content ) . "' AND `owner_uid` = '" . intval($owner_uid) . "' LIMIT 1";
		
		if( $todo = get_line($sql) )
		{
			if( get_var( "SELECT COUNT(*) FROM `todo_user` WHERE `tid` = '" . intval( $todo['id'] ) . "' AND `uid` = '" . intval( $owner_uid ) . "' AND `status` != 3 " ) > 0 )
			return self::send_error( LR_API_ARGS_ERROR , 'TODO EXISTS ' );
			
		}
		
		
		
		
		if( !$tid = add_todo( $content , $is_public ))
		{
			return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . db_error() );
		}

		$tinfo = get_todo_info_by_id( $tid );
		
		if( $is_public == 1 )
		{
			if( $uid > 0 && $uid !=uid()  )
			{
				$this->todo_assign(  $tid , $uid , true );
				$tinfo['other'] = 1;
			}
			else
				publish_feed( uname().'添加了TODO【'. $content .'】' , uid() , 2  , $tid );
		}
			
		
		return self::send_result( $tinfo );
		      
	}
	
	/**
     * 删除TODO评论
     *
     *
     * @param string token , 必填
     * @param string hid - 评论id, 必填
     * @return comment array
     * @author EasyChen
     */
	public function todo_remove_comment()
	{
		$hid = intval(v('hid'));
		if( intval( $hid ) < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'HID NOT EXISTS' );
		
		$sql = "SELECT *,`id` as `hid` FROM `todo_history` WHERE `id` = '" . intval( $hid ) . "' LIMIT 1";
		if( !$hitem = get_line( $sql ) )
		{
			if( db_errno() != 0 )
					return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
				else
					return self::send_error( LR_API_DB_EMPTY_RESULT , 'DATA NOT EXISTS' );
		}
		else
		{
			if( ($hitem['uid'] != $_SESSION['uid']) && $_SESSION['level'] < 9 )
			{
				return self::send_error( LR_API_FORBIDDEN , 'CANNOT REMOVE OTHER\'S COMMENT' );
			}
			
			if( $hitem['type'] != 2 )
			{
				return self::send_error( LR_API_ARGS_ERROR , 'HTYPE ERROR' );
			}
			
			$sql = "DELETE FROM `todo_history` WHERE `id` = '" . intval($hid) . "' LIMIT 1";
			
			run_sql( $sql );

			if( db_errno() != 0 )
				return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
			else
			{
				// 更新todo 评论计数
				$tid = $hitem['tid'];
				$count = get_var( "SELECT COUNT(*) FROM `todo_history` WHERE `tid` = '" . intval($tid) . "' AND `type` = 2 " , db()) ;
				$sql = "UPDATE `todo` SET `comment_count` = '" . intval($count) . "' WHERE `id` = '" . intval($tid) . "' LIMIT 1";
				run_sql( $sql );

				$sql = "UPDATE `feed` SET `comment_count` = '" . intval( $count ) . "' WHERE `tid` = '" . intval( $tid ) . "' AND `comment_count` != '" . intval( $count )  . "' ";
				run_sql( $sql );		

				return self::send_result( $hitem );
			}
				
		}
		
		
		
	}
	
	/**
     * 为TODO添加评论
     *
     *
     * @param string token , 必填
     * @param string tid - TODOid, 必填
     * @param string text - 评论内容, 必填
     * @return comment array
     * @author EasyChen
     */
	public function todo_add_comment()
	{
		$content = z(t(v('text')));
		if( !not_empty($content) ) return self::send_error(  LR_API_ARGS_ERROR , 'TEXT CAN\'T EMPTY' );
		
		$tid = intval(v('tid'));
		if( intval( $tid ) < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'TID NOT EXISTS' );
		
		$tinfo = get_line("SELECT * FROM `todo` WHERE `id` = '" . intval( $tid ) . "' LIMIT 1");
		
		
		if( is_mobile_request() ) $device = 'mobile';
		else $device = 'web';

		$sql = "INSERT INTO `todo_history` ( `tid` , `uid` , `content` , `type` , `timeline` , `device` ) 
		VALUES ( '" . intval($tid) . "' , '" . intval($_SESSION['uid']) . "' , '" . s( $content ) . "' , '2' , NOW() , '" . s($device) . "' ) ";
		
		run_sql( $sql );
		
		if( db_errno() != 0 )
			return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
		else
		{
			$lid = last_id();
			
			// 更新todo 评论计数
			$count = get_var( "SELECT COUNT(*) FROM `todo_history` WHERE `tid` = '" . intval($tid) . "' AND `type` = 2 " , db()) ;
			$sql = "UPDATE `todo` SET `comment_count` = '" . intval($count) . "' WHERE `id` = '" . intval($tid) . "' LIMIT 1";
			run_sql( $sql );

			$sql = "UPDATE `feed` SET `comment_count` = '" . intval( $count ) . "' WHERE `tid` = '" . intval( $tid ) . "' AND `comment_count` != '" . intval( $count )  . "' ";
			run_sql( $sql );			


			// 向订阅todo的同学发送通知
			$sql = "SELECT `uid` FROM `todo_user` WHERE `tid`= '" . intval($tid) . "' AND `is_follow` = 1 ";
			
			$follow_uids = array();
			if( $uitems = get_data( $sql ) )
			foreach( $uitems as $uitem )
			{
				if( $uitem['uid'] != uid() )
				{
					if( !in_array( $uitem['uid'] , $follow_uids ) )
					{
						send_notice( $uitem['uid'] , uname() .'评论了你关注的TODO【'. $tinfo['content'] .'】: '.$content , 1 , array('tid'=>intval($tid) , 'count' => $count )  );

						$follow_uids[] = $uitem['uid'];
					} 

				}
					
			}
			
			
			
			// 向todo作者发通知
			if( $tinfo['owner_uid'] != uid() )
			{
				if( !in_array( $tinfo['owner_uid'] , $follow_uids ) )
					send_notice( $tinfo['owner_uid'] , uname() .'评论了你的TODO【'. $tinfo['content'] .'】: '.$content , 1 ,  array('tid'=>intval($tid) , 'count' => $count )  );
			}
			
			// 向被@的同学，发送通知
			if( $ats = find_at($content) )
			{
				$sql = "SELECT `id` FROM `user` WHERE ";
				
				foreach( $ats as $at )
				{
					$at =z(t($at));
					if( $gname = get_group_names() )
					{
						if( in_array(strtoupper($at),$gname)  )
						{
							if( $ndata = get_group_unames($at) )
							foreach( $ndata as $nname )
								$names[] = $nname;

						}else $names[] = $at;			
					}
					else
					{
						$names[] = $at;
					}
				}

				foreach( $names as $at )
				{
					$at =z(t($at));
					
					if( mb_strlen($at, 'UTF-8') < 2 ) continue;

					$wsql[] = " `name` = '" . s(t($at)) . "' ";
					if( c('at_short_name') )
						if( mb_strlen($at, 'UTF-8') == 2 )
							$wsql[] = " `name` LIKE '_" . s($at) . "' ";
				}
				
				if( isset( $wsql ) && is_array( $wsql ) )
				{
					$sql = $sql . join( ' OR ' , $wsql );
					if( $udata = get_data( $sql ) )
						foreach( $udata as $uitem )
							if( !in_array( $uitem['id'] , $follow_uids ) )
								$myuids[] = $uitem['id'];

					if( isset( $myuids ) && is_array($myuids) )
					{
						$myuids = array_unique($myuids);
						foreach( $myuids as $muid )
						{
							if( $muid != uid() && $muid != $tinfo['owner_uid'] )
							send_notice( $muid , uname().'在TODO【'.$tinfo['content'].'】的评论中@了你: '.$content , 1 , array('tid'=>intval($tid) , 'count' => $count  ));
						}
					}
						
					
				}
			}
			
			
			if( $comment = get_line( "SELECT * FROM `todo_history` WHERE `id` = '" . intval($lid) . "' LIMIT 1" , db() ) )
			{
				$comment['user'] = get_user_info_by_id( $_SESSION['uid'] );
				
				
				
				if($tinfo['is_public'] == 1)
					publish_feed( uname().'评论了TODO【'. $tinfo['content'] .'】: '.$content , uid() , 2  , $tid );
				
				return self::send_result( $comment );
			}
			else
			{
				if( db_errno() != 0 )
					return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
				else
					return self::send_error( LR_API_DB_EMPTY_RESULT , 'DATA NOT EXISTS' );
			}
			
			
		}
		
	
	}

	/**
     * 读取TODO详细信息
     *
     * 其他人的私有TODO会无法读取
     *
     * @param string token , 必填
     * @param string tid - TODOid, 必填
     * @return todo array
     * @author EasyChen
     */
	public function todo_detail()
	{
		$tid = intval(v('tid'));
		if( intval( $tid ) < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'TID NOT EXISTS' );
		
		if( $tinfo = get_todo_info_by_id( $tid ) )
		{
			return self::send_result( $tinfo );
		}
		else
		{
			if( db_errno() != 0 )
				return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
			else
				return self::send_error( LR_API_DB_EMPTY_RESULT , 'DATA NOT EXISTS' );
		}
	}
	
	/**
     * 指派TODO给其他人
     *
     * 不可以分配给自己
     *
     * @param string token , 必填
     * @param string tid - TODOid, 必填
     * @param string uid - 要指派的用户id, 必填
     * @return todo array
     * @author EasyChen
     */
	public function todo_assign( $tid = false , $uid = false , $in = false )
	{
		if( !$tid ) $tid = intval(v('tid'));
		if( intval( $tid ) < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'TID NOT EXISTS' );
		
		if( !$uid ) $uid = intval(v('uid'));
		if( intval( $uid ) < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'UIDS ERROR' );
		
		
		if( $uid == $_SESSION['uid'] ) return self::send_error( LR_API_ARGS_ERROR , 'ASSIGN TO SELF' );
		
		if( !$tinfo = get_line( "SELECT * FROM `todo_user` WHERE `tid` = '" . intval($tid) . "' AND `uid` = '" . uid() . "' LIMIT 1" ) )
		{
			if( db_errno() != 0 )
				return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
			else
				return self::send_error( LR_API_DB_EMPTY_RESULT , 'DATA NOT EXISTS' );
		}
		else
		{
			if( $tinfo['uid'] != uid() ) return self::send_error( LR_API_FORBIDDEN , 'CANNOT ASSING OTHER\'S TODO' );
			
			// 更新todo表
			$sql = "UPDATE `todo` SET `owner_uid` = '" . intval( $uid ) . "' WHERE `id` = '" . intval($tid) . "' LIMIT 1";
			run_sql( $sql );
			if( db_errno() != 0 )
				if( $in )
					return false;
				else
					return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );

			
			// 将新的uid加入 todo_user 表
			$sql = "REPLACE INTO `todo_user` ( `uid` , `tid` , `status` , `last_action_at`  ) VALUES ( '" . intval( $uid ) . "' , '" . intval( $tid ) . "' , 1 , NOW() )  ";
			
			run_sql( $sql );
			if( db_errno() != 0 )
				if( $in )
					return false;
				else
					return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
			
			// 将现有uid 变为follow状态
			$sql = "UPDATE `todo_user` SET `is_follow` = 1 WHERE  `tid` = '" . intval($tid) . "' AND `uid` = '" . intval($_SESSION['uid']) . "' LIMIT 1";
			run_sql( $sql );
			
			
			
			if( db_errno() != 0 )
				if( $in )
					return false;
				else
					return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
				
			// 获取被转让人的信息
			$uinfo = get_user_info_by_id($uid);	
			
			$todo_text = get_todo_text_by_id( $tid );
			$todo_count = get_var( "SELECT `comment_count` FROM `todo` WHERE `id` = '" . intval( $tid ) . "'" );
			// 向todo新主人发送通知
			send_notice( intval( $uid ) , uname() .'向你转让了TODO【'. $todo_text .'】' , 1 ,  array('tid'=>intval($tid) , 'count'=> $todo_count )  );
			
			
			// 向todo关注者发送通知
			$sql = "SELECT `uid` FROM `todo_user` WHERE `tid`= '" . intval($tid) . "' AND `is_follow` = 1 ";
			
			if( $uitems = get_data( $sql ) )
			foreach( $uitems as $uitem )
			{
				// 避免向当前转让人发送通知
				if( $uitem['uid'] != uid() )
					send_notice( $uitem['uid'] , uname() .'将你关注的TODO【'. $todo_text .'】转让给了'.$uinfo['name'] , 1 , array('tid'=>intval($tid) , 'count'=> $todo_count )  );
			}
			
			add_history( $tid , '转让了TODO'  );
			
			publish_feed( uname().'将TODO【'. $todo_text .'】转让给了'.$uinfo['name'] , uid() , 2  , $tid );
			
			
			if( $in )
					return get_todo_info_by_id( $tid ) ;
				else
					return self::send_result( get_todo_info_by_id( $tid ) );
		}
		
		
		
	}
	
	/**
     * 获取TODO列表
     *
     *
     * @param string token , 必填
     * @param string since_id - 最小TODO id
     * @param string max_id - 最大TODO id
     * @param string count - 每页TODO条数
     * @param string ord - 排序 ， asc 或者 desc
     * @param string by - 排序字段 
     * @param string group - 按分组输出，默认为false 
     * @return todo list array
     * @author EasyChen
     */
	public function todo_list()
	{
		$uid = intval(v('uid'));
		if( $uid < 1 ) $uid = $_SESSION['uid'];
		
		$since_id = intval( v( 'since_id' ) );
        $max_id = intval( v( 'max_id' ) );
        $count = intval( v( 'count' ) );
        $order = strtolower( z( t( v( 'ord' ) ) ) );
        $by = strtolower( z( t( v( 'by' ) ) ) );
        
        if( $order != 'desc' )
            $ord = ' ASC ';
        else
            $ord = ' DESC ';
        
        if( strlen( $by ) > 0 )
        {
            $osql = ' ORDER BY `' . s( $by ) . '` ' . $ord . ' ';
        }
        else
            $osql = '';
        
        if( $count < 1 ) $count = 10;
        if( $count > 100 ) $count = 100;
        
        if( $since_id > 0 )
            $wsql = " AND `tid` > '" . intval( $since_id ) . "' ";
        elseif( $max_id > 0 )
            $wsql = " AND `tid` < '" . intval( $max_id ) . "' ";
       	else
       		$wsql = '';

       	if( $uid != uid() ) $wsql .= ' AND `is_public` = 1 ';

	   $sql = "SELECT * FROM `todo_user` WHERE `uid` = '" . intval($uid) . "' ";
	   
	   $sql = $sql . $wsql . $osql . " LIMIT " . $count ;
	   
		
		if( !$data = get_data( $sql ) ) return self::send_error( LR_API_DB_EMPTY_RESULT , 'EMPTY RESULT' );
		
		if( db_errno() != 0 )
			return self::send_error(  LR_API_DB_ERROR , 'DATABASE ERROR '   );
		
		
		$tids = array();
		
		foreach( $data as $item )
		{
			$tids[] = $item['tid'];
			$todos[$item['tid']] = $item;
		}
		
		if( count( $tids ) > 0 )
		{
			$sql = "SELECT * FROM `todo` WHERE `id` IN ( " . join( ' , ' ,  $tids ) . ") ORDER BY FIELD( `id` , " . join( ' , ' , $tids ) . "  )";
			$todo = get_data( $sql );
			foreach( $todo as $t )
			{
				$todos[$t['id']]['uid'] = $t['owner_uid'];
				$todos[$t['id']]['content'] = $t['content'];
				$todos[$t['id']]['timeline'] = $t['timeline'];
			}
			
			// todo : sort it 
			
			if( intval(v('group')) != 1 )
				return self::send_result(array_values($todos));
			else
			{
				$ret = Array();
				
				foreach( $todos as $tt )
				{
					if( $tt['is_follow'] ==1 )
						$ret['follow'][] = $tt;
					elseif( $tt['status'] == 3 ) 
						$ret['done'][] = $tt;
					elseif( $tt['is_star'] == 1 )
						$ret['star'][] = $tt;
					else
						$ret['normal'][] = $tt;
				}
				
				return self::send_result($ret);
			}	
			
		}
		else
		{
			if( db_errno() != 0 )
				return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
			else
				return self::send_error( LR_API_DB_EMPTY_RESULT , 'DATA NOT EXISTS' );
		} 	
		
		/*
		if( $data = get_user_todo_list_by_uid() )
		{
			return self::send_result( $data );
		}
		else
		{
			return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . db_error() );
		}*/
			
	}

	/**
     * TODO进行中
     *
     *
     * @param string token , 必填
     * @param string tid - TODO id , 必填
     * @return todo array
     * @author EasyChen
     */

	public function todo_start()
	{
		return $this->todo_set_value( 'status' , 2 );
	}
	
	/**
     * TODO暂停
     *
     *
     * @param string token , 必填
     * @param string tid - TODO id , 必填
     * @return todo array
     * @author EasyChen
     */
	public function todo_pause()
	{
		return $this->todo_set_value( 'status' , 1 );
	}
	
	/**
     * TODO加星
     *
     *
     * @param string token , 必填
     * @param string tid - TODO id , 必填
     * @return todo array
     * @author EasyChen
     */

	public function todo_star()
	{
		return $this->todo_set_value( 'is_star' , 1 );
	}
	
	/**
     * TODO去星
     *
     *
     * @param string token , 必填
     * @param string tid - TODO id , 必填
     * @return todo array
     * @author EasyChen
     */
	public function todo_unstar()
	{
		return $this->todo_set_value( 'is_star' , 0 );
	}
	
	/**
     * TODO设为公开
     *
     *
     * @param string token , 必填
     * @param string tid - TODO id , 必填
     * @return todo array
     * @author EasyChen
     */
	public function todo_public()
	{
		return $this->todo_set_value( 'is_public' , 1 );
	}
	
	/**
     * TODO设为私密
     *
     *
     * @param string token , 必填
     * @param string tid - TODO id , 必填
     * @return todo array
     * @author EasyChen
     */
	public function todo_private()
	{
		return $this->todo_set_value( 'is_public' , 0 );
	}
	
	/**
     * TODO设为已完成
     *
     *
     * @param string token , 必填
     * @param string tid - TODO id , 必填
     * @return todo array
     * @author EasyChen
     */
	public function todo_done()
	{
		return $this->todo_set_value( 'status' , 3 );
	}
	
	/**
     * 重开TODO
     *
     *
     * @param string token , 必填
     * @param string tid - TODO id , 必填
     * @return todo array
     * @author EasyChen
     */
	public function todo_reopen()
	{
		return $this->todo_set_value( 'status' , 1 );
	}
	
	/**
	* @ignore
	*/
	private function todo_set_value( $field , $value )
	{
		$tid = intval(v('tid'));
		
		if( $tid < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'id FIELD REQUIRED' );
		
		$sql = "SELECT * FROM `todo_user` WHERE  `tid` = '" . intval($tid) . "' AND `uid` = '" . intval($_SESSION['uid']) . "' LIMIT 1" ;
        
		if( !$data = get_line( $sql ))
			return self::send_error( LR_API_FORBIDDEN , 'YOU CANNOT UPDATE OTHERS TODO' );
			
		// delete uid and limit 1
		// to make all record updated at sametime
		// for all the followers 
		$sql = "UPDATE `todo_user` SET `" . s( $field ) . "` = '" . intval( $value ) . "' , `last_action_at` = NOW() WHERE `tid` = '" . intval( $tid ) . "' ";
		
		run_sql( $sql );
		
		if( mysql_errno() != 0 )
            return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
        else
		{
			$todoinfo = get_todo_info_by_id( $tid , true );
			kset('dinfo', $todoinfo['details']['is_public'] );


			if( $field == 'status' && $value == 3 )
			{
				
				
				if( $todoinfo['details']['is_public'] == 1 )
				{
					publish_feed( uname().'完成了TODO【'. $todoinfo['content'] .'】' , uid() , 2  , $tid );

					// send notice 
					// 向订阅todo的同学发送通知
					$sql = "SELECT `uid` FROM `todo_user` WHERE `tid`= '" . intval($tid) . "' AND `is_follow` = 1 ";
					
					if( $uitems = get_data( $sql ) )
					foreach( $uitems as $uitem )
					{
						if( $uitem['uid'] != uid() )
							send_notice( $uitem['uid'] , uname() .'完成了你关注的TODO【'. $todoinfo['content'] .'】' , 1 , array('tid'=>intval($tid) , 'count' => $todoinfo['comment_count'] )  );
					}
				}
				
				
				
			}
			
			
			return self::send_result( $todoinfo ); 

		}
		
			
	}
	
	/**
     * TODO取消关注
     *
     *
     * @param string token , 必填
     * @param string tid - TODO id , 必填
     * @return todo array
     * @author EasyChen
     */
	public function todo_unfollow()
	{
		$tid = intval(v('tid'));
		if( $tid < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'id FIELD REQUIRED' );
		
		$sql = "SELECT * FROM `todo_user` WHERE  `tid` = '" . intval($tid) . "' AND `uid` = '" . intval($_SESSION['uid']) . "' LIMIT 1" ;
		
		if( !$data = get_line( $sql ))
		{
			return self::send_error( LR_API_ARGS_ERROR , 'TID NOT EXSITS' );
		}
		else
		{
			$sql = "DELETE FROM `todo_user` WHERE `tid` = '" . intval($tid) . "' AND `uid` = '" . intval($_SESSION['uid']) . "' AND `is_follow` = 1 LIMIT 1";
			run_sql( $sql );
			
			if( db_errno() != 0 )
            	return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
        	else
        	{	
        		return self::send_result(get_todo_info_by_id( $tid , true )); 
        	}
			
			
		}

	}
	
	/**
     * TODO添加关注
     *
     *
     * @param string token , 必填
     * @param string tid - TODO id , 必填
     * @return todo array
     * @author EasyChen
     */
	public function todo_follow()
	{
		$tid = intval(v('tid'));
		
		if( $tid < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'id FIELD REQUIRED' );
		
		$sql = "SELECT * FROM `todo_user` WHERE  `tid` = '" . intval($tid) . "' AND `uid` = '" . intval($_SESSION['uid']) . "' LIMIT 1" ;
		
		if( !$data = get_line( $sql ))
		{
			// 没数据正常的
			$sql = "INSERT IGNORE INTO `todo_user` ( `uid` , `tid` , `status`  , `is_follow` , `last_action_at`  ) VALUES ( '" . intval( $_SESSION['uid'] ) . "' , '" . intval( $tid ) . "' , 1 , 1 ,  NOW() )  ";
			
			run_sql( $sql );
			
			if( db_errno() != 0 )
            return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
        else
			return self::send_result(get_todo_info_by_id( $tid , true )); 
			

		}
		else
		{
			return self::send_error( LR_API_ARGS_ERROR , 'TID exists' );
		}
				
			
		
		
	}

	
	
	/**
     * TODO更新文字内容
     *
     *
     * @param string token , 必填
     * @param string tid - TODO id , 必填
     * @param string text - TODO内容 , 必填
     * @return todo array
     * @author EasyChen
     */
	public function todo_update()
	{
		$tid = intval(v('tid'));
		
		if( $tid < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'id FIELD REQUIRED' );
        
		// check user
		//$sql = "SELECT * FROM `todo_user` WHERE  `tid` = '" . intval($tid) . "' AND `uid` = '" . intval($_SESSION['uid']) . "' LIMIT 1" ;
        $sql = "SELECT * FROM `todo` WHERE `id` = '" . intval($tid) . "' AND `owner_uid` = '" . intval(uid()) . "' LIMIT 1";
		
		if( !$data = get_line( $sql ))
			return self::send_error( LR_API_FORBIDDEN , 'YOU CANNOT UPDATE OTHERS TODO' );
		
		$content = z(t(v('text')));
		if( !not_empty($content) ) return self::send_error( LR_API_ARGS_ERROR , 'text FIELD REQUIRED' );
		
		$sql = "UPDATE `todo` SET `content` = '" . s($content) . "' WHERE `id` = '" . intval($tid) . "' LIMIT 1";
		run_sql( $sql );
		
		if( mysql_errno() != 0 )
            return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
        else
		{
			run_sql( "UPDATE `todo_user` SET `last_action_at` = NOW() WHERE `tid` = '" . intval($tid) . "' AND `uid` = '" . intval($_SESSION['uid']) . "' LIMIT 1");
			
			return self::send_result(get_todo_info_by_id( $tid , true )); 
		}
		
	}
	
	/**
     * 清除已经完成的单个TODO
     *
     *
     * @param string token , 必填
     * @param string tid - TODO id , 必填
     * @return todo array
     * @author EasyChen
     */
	public function todo_remove_done()
	{
		// @TODO  clean all the info in other tables
		$sql = "DELETE FROM `todo_user` WHERE `uid` = '" . intval($_SESSION['uid']) . "' AND `status` = 3 " ;
        run_sql( $sql );
		
		if( mysql_errno() != 0 )
        {
            return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
        }
        else
            return self::send_result( array('msg'=>'ok')  );
	}

	/**
     * 清除所有已经完成的TODO标记为
     *
     *
     * @param string token , 必填
     * @param string tid - TODO id , 必填
     * @return todo array
     * @author EasyChen
     */
	public function todo_all_done()
	{
		// @TODO  clean all the info in other tables
		$sql = "UPDATE `todo_user` SET `status` = 3 WHERE `uid` = '" . intval($_SESSION['uid']) . "' AND ( `status` = 1 OR `status` = 2 )  " ;
        run_sql( $sql );
		
		if( mysql_errno() != 0 )
        {
            return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
        }
        else
            return self::send_result( array('msg'=>'ok')  );
	}
	
	/**
     * 删除TODO
     *
     *
     * @param string token , 必填
     * @param string tid - TODO id , 必填
     * @return todo array
     * @author EasyChen
     */
	public function todo_remove()
	{
		$tid = intval(v('tid'));
		
		if( $tid < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'id FIELD REQUIRED' );
        
        
        $old = get_todo_info_by_id( $tid );
        
        $sql = "DELETE FROM `todo_user` WHERE  `tid` = '" . intval($tid) . "' AND `uid` = '" . intval($_SESSION['uid']) . "' LIMIT 1" ;
        run_sql( $sql );
        
        if( mysql_errno() != 0 )
        {
            return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
        }
        else
            return self::send_result( $old  );
	}
	
	
	/**
     * 离线同步TODO
     *
     * 客户端用
     * @ignore
     */
	public function todo_sync()
	{
		// 首先判断text是否存在
		// 然后根据tid 判断是更新还是添加操作
		// 
		$content = z(t(v('text')));
		if( !not_empty( $content ) ) return self::send_error( LR_API_ARGS_ERROR , 'TEXT CANNOT BE EMPTY' );
		
		$tid = intval(v('tid'));
		if( $tid < 0 )
		{
			if( intval(v('is_delete')) == 1 )
			{
				// 在本地添加后又在本地删除了
				return self::send_result( array( 'msg' => 'already delete local' ) );
			}
			// add
			return $this->todo_add();
		}
		else
		{
			// 鉴权
			$sql = "SELECT * FROM `todo_user` WHERE  `tid` = '" . intval($tid) . "' AND `uid` = '" . intval($_SESSION['uid']) . "' LIMIT 1" ;
        
			if( !$data = get_line( $sql ))
			return self::send_error( LR_API_FORBIDDEN , 'YOU CANNOT UPDATE OTHERS TODO' );
			
			// 判断最后更新时间
			// 
			// 服务器的最后操作时间 $data['last_action_at']
			
			// 本地todo的最后操作时间
			// 
			$client_last_action_at = z(t(v('last_action_at')));
			
			if( not_empty( $data['last_action_at'] ) && not_empty( $client_last_action_at ) )
			{
				if( not_empty(v('client_now')) )
				{
					$offset = time() - strtotime( v('client_now') ) ;
				}else $offset = 0;
				
				// 客户端时间校正
				// 你不能穿越时空
				if( strtotime( v('last_action_at') ) > strtotime( v('client_now') )   ) 
					$offset = 0;
				
				
				
				if( strtotime( $client_last_action_at ) - strtotime( $data['last_action_at']) + $offset  <= 0 )
					return self::send_result( array( 'msg' => 'new action happend' ) );
			}
			
			// update
			if( intval(v('is_delete')) == 1 )
			{
				// remove
				$_REQUEST['tid'] = $tid;
				return $this->todo_remove();
				
			}
			else
			{
				// update
				// 先更新todo表
				$sql = "UPDATE `todo` SET `content` = '" . s($content) . "' WHERE `id` = '" . intval($tid) . "' LIMIT 1";
				run_sql( $sql );
				
				$sql = "UPDATE `todo_user` SET 
				`is_star` = '" . intval( v('is_star') ) . "', 
				`is_public` = '" . intval( v('is_public') ) . "', 
				`status` = '" . intval( v('status') ) . "',
				`last_action_at` = NOW() WHERE  `tid` = '" . intval($tid) . "' AND `uid` = '" . intval($_SESSION['uid']) . "' LIMIT 1";

				run_sql( $sql );
				
				return self::send_result(get_todo_info_by_id( $tid , true )); 
			}
			
		}
		
		
	}
	
	
	/**
     * 发布广播
     *
     * 广播时如果不用@进行点名，则通知全部成员
     *
     * @param string text , 必填
     * @param string type - user行为/主动广播 , 默认为主动广播
     * @return todo array
     * @author EasyChen
     */
	public function feed_publish()
	{
		$content = z(t(v('text')));
		if( !not_empty($content) ) return self::send_error(  LR_API_ARGS_ERROR , 'TEXT CAN\'T EMPTY' );
		
		$reblog_id = intval(v('fid'));
		
		switch( z(t(v('type'))) )
		{
			case 'user' :
				$type = 3;
				break;

			case 'todo' :
				$type = 2;
				break;

			case 'notice' :
				$type = 1;
				break;	
	
			case 'cast' :	
			default:
				$type = 4;	
		}
		
		$sql = "INSERT INTO `feed` ( `content` , `reblog_id` , `uid` , `timeline` , `type` ) VALUES ( '" . s($content) . "' , '" . intval( $reblog_id ) . "' , '" . intval( $_SESSION['uid'] ) . "' , NOW() , " . intval($type) . " )";
		
		run_sql( $sql );
		if( db_errno() != 0 )
            return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . db_error() );
		
		$lid = last_id();
		if( intval($lid) < 1 ) 
			return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR NO LASTID' );
		
		if($feed = get_feed_by_id( $lid , db() ))
		{
			// feed 发布成功
			// 当用户主动发布时，视为广播，检测at信息
			if( $type == 4 )
			{
				if( $ats = find_at($content) )
				{
					$sql = "SELECT `id` FROM `user` WHERE (`level` > 0 AND `is_closed` != 1 )  ";

					foreach( $ats as $at )
					{
						$at =z(t($at));
						if( $gname = get_group_names() )
						{
							if( in_array(strtoupper($at),$gname)  )
							{
								if( $ndata = get_group_unames($at) )
								foreach( $ndata as $nname )
									$names[] = $nname;

							}else $names[] = $at;			
						}
						else
						{
							$names[] = $at;
						}
					}

					foreach( $names as $at )
					{
						$at =z(t($at));
						if( mb_strlen($at, 'UTF-8') < 2 ) continue;

						$wsql[] = " `name` = '" . s(t($at)) . "' ";

						if( c('at_short_name') )
							if( mb_strlen($at, 'UTF-8') == 2 )
								$wsql[] = " `name` LIKE '_" . s($at) . "' ";
					}

					
					
					if( isset( $wsql ) && is_array( $wsql ) )
					{
						$sql = $sql . ' AND ( ' . join( ' OR ' , $wsql ) . ' ) ';

						if( $udata = get_data( $sql ) )
							foreach( $udata as $uitem )
								$myuids[] = $uitem['id'];
						
						if( isset( $myuids ) && is_array($myuids) )
						{
							$myuids = array_unique($myuids);
							foreach( $myuids as $muid )
								if( $muid != uid() )
									send_notice( $muid , uname().'在广播【'.$content.'】中@了你' , 2 , array('fid'=>intval($lid) , 'count'=> $feed['comment_count'] ));
								
						}
					}
				}
				else
				{
					// 如果没有at，则认为是@全部人
					$sql = "SELECT `id` FROM `user` WHERE `level` > 0 AND `is_closed` != 1 AND `id` !=" . intval(uid());
					if( $udata = get_data( $sql ) )
					{
						foreach( $udata as $uitem )
							$myuids[] = $uitem['id'];

							if( isset( $myuids ) && is_array($myuids) )
							{
								$myuids = array_unique($myuids);
								foreach( $myuids as $muid )
									if( $muid != uid() )
										send_notice( $muid, uname().'发起了广播【'.$content.'】' , 2 , array('fid'=>intval($lid) , 'count'=> $feed['comment_count']  ));
							}
						
					}

				}

			}

			





			return self::send_result( $feed );
		}
		else
		{
			return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . db_error() );
		}
			
	}
	
	/**
     * 获取Feed列表
     *
     *
     * @param string token , 必填
     * @param string since_id - 最小TODO id
     * @param string max_id - 最大TODO id
     * @param string count - 每页TODO条数
     * @param string ord - 排序 ， asc 或者 desc
     * @param string by - 排序字段 
     * @return feed list array
     * @author EasyChen
     */
	public function feed_list()
	{
		$since_id = intval( v( 'since_id' ) );
        $max_id = intval( v( 'max_id' ) );
        $count = intval( v( 'count' ) );
        $order = strtolower( z( t( v( 'ord' ) ) ) );
        $by = strtolower( z( t( v( 'by' ) ) ) );
        
        if( strlen($by) < 1 ) $by = 'id';
        
        if( $order == 'asc' )
            $ord = ' ASC ';
        else
            $ord = ' DESC ';
        
        if( strlen( $by ) > 0 )
        {
            $osql = ' ORDER BY `' . s( $by ) . '` ' . $ord . ' ';
        }
        else
            $osql = '';
        
        if( $count < 1 ) $count = 10;
        if( $count > 100 ) $count = 100;
        
        if( $since_id > 0 )
        {
            $wsql = " AND `id` > '" . intval( $since_id ) . "' ";
        }
        elseif( $max_id > 0 )
        {
            $wsql = " AND `id` < '" . intval( $max_id ) . "' ";
        }
		
		$sql = "SELECT * FROM `feed` WHERE 1 ";
        
        
        $sql = $sql . $wsql . $osql . " LIMIT " . $count ;
		if( !$data = get_data( $sql ))
		{
			
			if( db_errno() == 0 )
				return self::send_error( LR_API_DB_EMPTY_RESULT , 'DATA NOT EXISTS' );
			else
				return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . db_error() );
		}
		else
		{
			
			$more = 1;
			if( is_array( $data ) )
			{
				if( count($data) < $count ) $more = 0;
				
				$first = reset( $data );
				$max = $min = $first['id'];
				foreach( $data as $hitem )
				{
					$huids[] = $hitem['uid'];
					
					if( $hitem['id'] > $max ) $max = $hitem['id'];
					if( $hitem['id'] < $min ) $min = $hitem['id'];
				}
			}
			
			
			if( isset( $huids ) && is_array( $huids ) )
			{
				
				
				$sql = "SELECT " . USER_INFO . " FROM `user` WHERE `id` IN ( " . join( ' , ' , $huids ) . " )  ";
				
				if($udata = get_data( $sql ))
				{
					foreach( $udata as $uitem )
					{
						if( strlen( $uitem['groups'] ) > 0 ) 
							$uitem['groups'] = explode('|', trim( $uitem['groups'] , '|' )) ;
						
						$uarray[$uitem['id']] = $uitem;

					}
					
					//print_r( $uarray );
					
					if( isset( $uarray ) )
					{
						foreach( $data as $k=>$hitem )
						{
							if( isset( $uarray[$hitem['uid']] ) )
							{
								$data[$k]['user'] = $uarray[$hitem['uid']];
							}
								
						}
					}
					
					
				}
			
			}
			return self::send_result(  array( 'max' => intval($max) , 'min' => intval($min) , 'items' => $data , 'more'=> intval( $more ) )  );
			
		}
	}

	/**
     * 删除Feed的评论
     *
     *
     * @param string token , 必填
     * @param string cid - 必填
     * @return feed array 
     * @author EasyChen
     */
	public function feed_remove_comment( $cid = flase )
	{
		$cid = intval(v('cid'));
		if( intval( $cid ) < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'HCD NOT EXISTS' );
		
		$sql = "SELECT *,`id` as `cid` FROM `comment` WHERE `id` = '" . intval( $cid ) . "' LIMIT 1";
		if( !$citem = get_line( $sql ) )
		{
			if( db_errno() != 0 )
					return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
				else
					return self::send_error( LR_API_DB_EMPTY_RESULT , 'DATA NOT EXISTS' );
		}
		else
		{
			if( ($citem['uid'] != $_SESSION['uid']) && $_SESSION['level'] < 9 )
			{
				return self::send_error( LR_API_FORBIDDEN , 'CANNOT REMOVE OTHER\'S COMMENT' );
			}
			
			
			$sql = "DELETE FROM `comment` WHERE `id` = '" . intval($cid) . "' LIMIT 1";
			run_sql( $sql );

			// 更新feed评论计数
			$fid = $citem['fid'];
			$count = get_var( "SELECT COUNT(*) FROM `comment` WHERE `fid` = '" . intval($fid) . "' " , db()) ;
			$sql = "UPDATE `feed` SET `comment_count` = '" . intval($count) . "' WHERE `id` = '" . intval($fid) . "' LIMIT 1";
			run_sql( $sql );

			if( db_errno() != 0 )
				return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
			else
				return self::send_result( $citem );
		}
	}

	/**
     * 删除Feed
     *
     *
     * @param string token , 必填
     * @param string fid - 必填
     * @return feed array 
     * @author EasyChen
     */
	public function feed_remove( $fid = false )
	{
		if( !$fid ) $fid = intval(v('fid'));
		if( intval( $fid ) < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'FID NOT EXISTS' );

		$finfo = get_line("SELECT * FROM `feed` WHERE `id` = '" . intval( $fid ) . "' LIMIT 1");
		if( $finfo['uid'] != uid() && !is_admin() ) 
			return self::send_error( LR_API_FORBIDDEN , 'CANNOT REMOVE OTHER\'S FEED' );

		$sql = "DELETE FROM `feed` WHERE `id` = '" . intval( $fid ) . "' LIMIT 1";
		run_sql( $sql );

		$sql = "DELETE FROM `comment` WHERE `fid` = '" . intval( $fid ) . "'";
		run_sql( $sql );

		if( db_errno() != 0 )
				return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
			else
				return self::send_result( $finfo );


	}

	/**
     * 为Feed添加评论
     *
     *
     * @param string token , 必填
     * @param string fid - 必填
     * @param string text - 必填
     * @return feed array 
     * @author EasyChen
     */
	public function feed_add_comment( $text = false , $fid = false )
	{
		if( !$text )
		$content = $text = z(t(v('text')));
		
		if( !not_empty($content) ) return self::send_error(  LR_API_ARGS_ERROR , 'TEXT CAN\'T EMPTY' );
		
		if( !$fid )
		$fid = intval(v('fid'));
		if( intval( $fid ) < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'FID NOT EXISTS' );
		
		
		$finfo = get_line("SELECT * FROM `feed` WHERE `id` = '" . intval( $fid ) . "' LIMIT 1");

		if( is_mobile_request() ) $device = 'mobile';
		else $device = 'web';

		$sql = "INSERT INTO `comment` ( `fid` , `uid` , `content` , `timeline` , `device` ) 
		VALUES ( '" . intval($fid) . "' , '" . intval($_SESSION['uid']) . "' , '" . s( $content ) . "' , NOW() , '" . s($device) . "' ) ";
		
		run_sql( $sql );
		
		if( db_errno() != 0 )
			return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
		else
		{
			$lid = last_id();
			
			// feed表comment_count计数增加
			$count = get_var( "SELECT COUNT(*) FROM `comment` WHERE `fid` = '" . intval($fid) . "' " , db()) ;
			$sql = "UPDATE `feed` SET `comment_count` = '" . intval($count) . "' WHERE `id` = '" . intval($fid) . "' LIMIT 1";
			run_sql( $sql );

			// 向参与了该Feed讨论的同学发送通知
			$sql = "SELECT `uid` FROM `comment` WHERE `fid`= '" . intval($fid) . "' ";
			
			if( $uitems = get_data( $sql ) )
			foreach( $uitems as $uitem )
			{
				if( $uitem['uid'] != uid() )
					$myuids[] = $uitem['uid'];	
			}

			if( isset($myuids) )
			{
				$myuids = array_unique($myuids);
				foreach( $myuids as $muid )
				{
					send_notice( $muid , uname() .'评论了你参与讨论的动态【'. $finfo['content'] .'】: '.$content , 2 , array('fid'=>intval($fid) , 'count'=> $count  )  );	
				}
			}
				
			
			
			// 向Feed作者发通知
			if( $finfo['uid'] != uid() )
			{
				send_notice( $finfo['uid'] , uname() .'评论了你的动态【'. $finfo['content'] .'】: '.$content , 2 ,  array('fid'=>intval($fid) , 'count'=> $count  )  );
			}
			
			// 向被@的同学，发送通知
			if( $ats = find_at($content) )
			{
				$sql = "SELECT `id` FROM `user` WHERE ";

				foreach( $ats as $at )
				{
					$at =z(t($at));
					if( $gname = get_group_names() )
					{
						if( in_array(strtoupper($at),$gname)  )
						{
							if( $ndata = get_group_unames($at) )
							foreach( $ndata as $nname )
								$names[] = $nname;

						}else $names[] = $at;			
					}
					else
					{
						$names[] = $at;
					}
				}
					
				foreach( $names as $at )
				{
					$at =z(t($at));
					if( mb_strlen($at, 'UTF-8') < 2 ) continue;

					$wsql[] = " `name` = '" . s(t($at)) . "' ";
					if( c('at_short_name') )
						if( mb_strlen($at, 'UTF-8') == 2 )
							$wsql[] = " `name` LIKE '_" . s($at) . "' ";
				}
				
				if( isset( $wsql ) && is_array( $wsql ) )
				{
					$sql = $sql . join( ' OR ' , $wsql );
					if( $udata = get_data( $sql ) )
					{
						foreach( $udata as $uitem )
							$myuids[] = $uitem['id'];

						if( isset( $myuids ) && is_array( $myuids ) )
						{
							$myuids = array_unique( $myuids );
							foreach( $myuids as $muid )
								if( $muid != uid() && $muid != $finfo['uid'] )
									send_notice( $muid , uname().'在动态【'.$finfo['content'].'】的评论中@了你: '.$content , 2 , array('fid'=>intval($fid) , $count  ));
						
						}

						
					}
				}
			}
			
			
			if( $comment = get_line( "SELECT * FROM `comment` WHERE `id` = '" . intval($lid) . "' LIMIT 1" , db() ) )
			{
				$comment['user'] = get_user_info_by_id( $_SESSION['uid'] );
				
				return self::send_result( $comment );
			}
			else
			{
				if( db_errno() != 0 )
					return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
				else
					return self::send_error( LR_API_DB_EMPTY_RESULT , 'DATA NOT EXISTS' );
			}
			
			
		}
		
	}
	
	/**
     * 读取Feed详细信息
     *
     *
     * @param string token , 必填
     * @param string fid - 必填
     * @return feed array 
     * @author EasyChen
     */
	public function feed_detail()
	{
		$fid = intval(v('fid'));
		
		if( $fid < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'id FIELD REQUIRED' );
    
		$sql = "SELECT * FROM `feed` WHERE  `id` = '" . intval($fid) . "' LIMIT 1" ;
		
		if( !$data = get_line( $sql ) )
        {
			if( db_errno() != 0 )
				return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
			else
				return self::send_error( LR_API_DB_EMPTY_RESULT , 'DATA NOT EXISTS' );
        }
		else
		{
			// 添加feed的评论信息
			$cdata = get_data( "SELECT * FROM `comment` WHERE `fid` = '" . intval($fid) . "' ORDER BY `timeline` DESC LIMIT 100" , $write_db );
	
			if( is_array( $cdata ) )
			foreach( $cdata as $citem )
			{
				$cuids[] = $citem['uid'];	
			}
			
			if( isset( $cuids ) && is_array( $cuids ) )
			{
				
				$sql = "SELECT " . USER_INFO . " FROM `user` WHERE `id` IN ( " . join( ' , ' , $cuids ) . " )  ";
				
				if($udata = get_data( $sql ))
				{
					foreach( $udata as $uitem )
					{
						if( strlen( $uitem['groups'] ) > 0 ) 
							$uitem['groups'] = explode('|', trim( $uitem['groups'] , '|' )) ;
						
						$uarray[$uitem['id']] = $uitem;
					}
					
					//print_r( $uarray );
					
					if( isset( $uarray ) )
					{
						foreach( $cdata as $k=>$hitem )
						{
							if( isset( $uarray[$hitem['uid']] ) )
								$cdata[$k]['user'] = $uarray[$hitem['uid']];
						}
					}
					
					
				}
				
				
			}
			
			
			$data['comment'] = $cdata;


			return self::send_result( $data );
				
				
		}	
			
	
			
	}
	
	/*
	public function feed_remove()
	{
		$fid = intval(v('fid'));
		
		if( $fid < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'id FIELD REQUIRED' );
        
        
        $sql = "SELECT * FROM `feed` WHERE  `id` = '" . intval($fid) . "' LIMIT 1" ;
        
		$data = get_line( $sql );
        
        if( mysql_errno() != 0 )
        {
            return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
        }
        
        $sql = "DELETE FROM `feed` WHERE  `id` = '" . intval($fid) . "' LIMIT 1" ;
        run_sql( $sql );
        
        if( mysql_errno() != 0 )
        {
            return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
        }
        else
            return self::send_result( $data );
	}*/

	
	public function user_online()
	{
		// 5分钟内有过活动的都算
		$sql = "SELECT `uid` , `last_active` , `device` , `place` FROM `online` WHERE `last_active` > '" . date( "Y-m-d H:i:s" , strtotime("-5 minutes") ) . "'";
		if( !$data = get_data( $sql ) ) return self::send_error( LR_API_DB_EMPTY_RESULT , 'EMPTY RESULT' );
		
		if( db_errno() != 0 )
			return self::send_error(  LR_API_DB_ERROR , 'DATABASE ERROR '   );
		else return self::send_result( $data );

	}
	
	/* ============ unread ================== */
	
	/**
     * 取得用户未读信息
     *
     *
     * @param string token , 必填
     * @return array ('notice'=>'未读计数' , 'nid' => '最后一条Notice ID' , 'text' => '最后一条未读Notice内容')
     * @author EasyChen
     */
	public function user_unread()
	{
		// 处理掉全部的未读计数
		// 私信和系统通知
		
		$sql = "SELECT COUNT(*) FROM `notice` WHERE `to_uid` = '" . intval(uid()) . "' AND `is_read` = 0 ";
		$notice_count = intval(get_var( $sql ));
		

		$sql = "SELECT COUNT(*) FROM `message` WHERE `to_uid` = '" . intval(uid()) . "' AND `is_read` = 0 ";
		$message_count = intval(get_var( $sql ));

		$sql = "SELECT COUNT( * ) AS  `from_cnt` ,  `from_uid` FROM  `message` WHERE  `to_uid` = '" . intval(uid()) . "'  AND  `is_read` = 0 GROUP BY  `from_uid` ";
		$muids = array();
		$muidstring = '';

		if( $mdata = get_data( $sql ) )
		{
			foreach( $mdata as $mitem )
			{
				$muids[] = $mitem['from_uid'];
			}

			$muidstring = join( '|' , $muids );
		}

		$last_notice = get_line( "SELECT * FROM `notice`  WHERE `to_uid` = '" . intval(uid()) . "' AND `is_read` = 0 ORDER BY `id` DESC LIMIT 1" );
		$last_message = get_line( "SELECT * FROM `message`  WHERE `to_uid` = '" . intval(uid()) . "' AND `is_read` = 0 ORDER BY `id` DESC LIMIT 1" );

		// update user online 
		$sql = "REPLACE `online` ( `uid` , `session` , `last_active` , `device` ) VALUES ( '" . intval(uid()) . "' , '"  . s( session_id() ) . "' , NOW() , '" . get_device() . "' ) ";
		run_sql($sql);

		return self::send_result( 
			array(
					'all'=> $message_count+$notice_count,
					'message'=>$message_count,
					'uids'=>$muidstring,
					'notice'=>$notice_count,
					'nid'=>$last_notice['id'],
					'mid'=>$last_message['id'],
					'text'=>$last_notice['content']
				));
	}
	
	/**
     * 获取Notice列表
     *
     *
     * @param string token , 必填
     * @param string since_id - 最小TODO id
     * @param string max_id - 最大TODO id
     * @param string count - 每页TODO条数
     * @param string ord - 排序 ， asc 或者 desc
     * @param string by - 排序字段 
     * @return notice list array
     * @author EasyChen
     */
	public function notice_list()
	{
		$since_id = intval( v( 'since_id' ) );
        $max_id = intval( v( 'max_id' ) );
        
		$count = intval( v( 'count' ) );
		if( $count < 1 ) $count = 10;
        if( $count > 100 ) $count = 100;
		
		if( $since_id > 0 )
            $wsql = " AND `id` > '" . intval( $since_id ) . "' ";
        elseif( $max_id > 0 )
            $wsql = " AND `id` < '" . intval( $max_id ) . "' ";
       
	   
		$osql = " ORDER BY `id` DESC ";	
		
		$sql = "SELECT * FROM `notice` WHERE `to_uid` = '" . intval(uid()) . "' ";
		
		$sql = $sql . $wsql . $osql . " LIMIT " . $count ;
		 
		if( !$data = get_data( $sql ) ) return self::send_error( LR_API_DB_EMPTY_RESULT , 'EMPTY RESULT' );
		
		if( db_errno() != 0 )
			return self::send_error(  LR_API_DB_ERROR , 'DATABASE ERROR '   );
		else
		{
			$more = 1;
			if( is_array( $data ) )
			{
				if( count($data) < $count ) $more = 0;
				
				$first = reset( $data );
				$max = $min = $first['id'];
				foreach( $data as $k=> $item )
				{
					if( $item['id'] > $max ) $max = $item['id'];
					if( $item['id'] < $min ) $min = $item['id'];
					
					if( strlen($item['data']) > 0 )
					{
						$data[$k]['data'] = unserialize( $item['data'] );
					}
				}
			}
			
			return self::send_result(  array( 'max' => intval($max) , 'min' => intval($min) , 'items' => $data , 'more'=> intval( $more ) )  );
		}
			
	}
	
	/**
     * 标记notice为已读
     *
     *
     * @param string token , 必填
     * @param string nid - 选填，不指定是将当前用户全部notice标记为已读
     * @return msg array ('msg'=>'done')
     * @author EasyChen
     */
	public function notice_mark_read()
	{
		if( intval(v('nid')) < 1 )
			$sql = "UPDATE `notice` SET `is_read` = 1 WHERE `to_uid` = '" . intval(uid()) . "'";
		else
			$sql = "UPDATE `notice` SET `is_read` = 1 WHERE `to_uid` = '" . intval(uid()) . "' AND `id` = '" . intval(v('nid')) . "' LIMIT 1";
		run_sql( $sql );
		
		if( db_errno() == 0  )
				return self::send_result( array('msg'=>'done') );
			else	
				return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . db_error() );
	}

	/**
     * 用户更新头像
     *
     * 使用标准Form表单上传，头像文件名为file，只接受jpg格式的文件
     *
     * @param string token , 必填
     * @return user array 
     * @author EasyChen
     */
	public function user_update_avatar()
	{
		if( $_FILES['file']['error'] != 0 ) 
			return self::send_error( OP_API_UPLOAD_ERROR , 'UPLOAD ERROR ' . $_FILES['file']['error'] ); 
						
						
		$tmp_image_name =  SAE_TMP_PATH . md5(time().rand(1,99999)) . '.tmp.jpg';
		
		jpeg_up( $_FILES['file']['tmp_name'], $tmp_image_name)   ;    

		include_once( AROOT . 'lib/thumbnail.class.php' );

		$file_thumb_name = 'avatar-' . uid(). '.jpg';
				
		$tmp_file = SAE_TMP_PATH.$file_thumb_name;

				
		include_once( AROOT . 'lib/icon.class.php' );
				
		$icon = new Icon();
				
		$icon->path = $tmp_image_name;
		$icon->size = 100;
		$icon->dest = $tmp_file;
		$icon->createIcon();
				
		

		if(  on_sae()  )
		{
			$s = new SaeStorage();
			if(!$thumb_url = $s->write( 'upload' , $file_thumb_name , file_get_contents($tmp_file) ))
			{
				return self::send_error( OP_API_STORAGE_ERROR , 'SAVE ERROR ' . $s->errmsg() );
			}
		}
		else
		{
			$local_storage = AROOT . 'static' . DS . 'upload' . DS . 'avatar' . DS ;
			$local_storage_url = c('site_url') . DS . 'static' . DS . 'upload' . DS . 'avatar' . DS ;
			$thumb_path = $local_storage . $file_thumb_name;
			$thumb_url = $local_storage_url . $file_thumb_name;

			if( !copy( $tmp_file , $thumb_path ) )
				return self::send_error( OP_API_STORAGE_ERROR , 'SAVE ERROR '  );
		}
		


		$sql = "UPDATE `user` SET `avatar_small` = '" . s( $thumb_url ) . "' WHERE `id` = '" . intval(uid()) . "' LIMIT 1";
				 
				 
		run_sql( $sql );
				 
		if( mysql_errno() != 0 )
		{
			return self::send_error( OP_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
		}
		else
		{
			return self::send_result(get_user_info_by_id(intval(uid())));
		 
		}		 

		
	}

	/* ============ im  =============== */
	/**
     * 向某个用户发送私信聊天
     *
     *
     * @param string token , 必填
     * @param string uid , 必填
     * @param string text , 必填
     * @return array( 'msg' => 'ok' )
     * @author EasyChen
     */
	public function im_send( $uid = false , $text = false )
	{
		if( !$uid ) $uid = intval(v('uid'));
		if( $uid < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'id FIELD REQUIRED' );
		if( $uid == uid() ) return self::send_error( LR_API_ARGS_ERROR , 'NO NEED TO SPEAK TO UR SELF' );

		if( !$text ) $text = z(t(v('text')));
		if( strlen($text) < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'text FIELD REQUIRED' );

		$sql = "INSERT INTO `message` ( `from_uid` , `to_uid` , `timeline` , `content` ) VALUES ( '" . intval(uid()) . "' 
		, '" . intval($uid) . "' , NOW() , '" . s( $text ) . "' ) ";
		run_sql( $sql );

		if( mysql_errno() != 0 )
		{
			return self::send_error( OP_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
		}
		else
		{
			return self::send_result( array( 'msg' => 'ok' ) );
		}	
        	
	}

	/**
     * 取得当前用户和指定用户聊天记录
     *
     * 不包含未读
     *
     * @param string token , 必填
     * @param string uid , 必填
     * @return im history data array 
     * @author EasyChen
     */
	public function im_history( $uid = false )
	{
		if( !$uid ) $uid = intval(v('uid'));
		if( $uid < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'id FIELD REQUIRED' );
		if( $uid == uid() ) return self::send_error( LR_API_ARGS_ERROR , 'NO NEED TO SPEAK TO UR SELF' );

		$since_id = intval( v( 'since_id' ) );
        $max_id = intval( v( 'max_id' ) );

        $count = intval( v( 'count' ) );
		if( $count < 1 ) $count = 10;
        if( $count > 100 ) $count = 100;

        /*
        $all = intval(v('read_all'));
        if( $all != 1 )
        	$wwsql = " AND `is_read` = 1 ";
        else
        	$wwsql = "";

		*/
        	
		$wsql = '';
		
		if( $since_id > 0 )
            $wsql .= " AND `id` > '" . intval( $since_id ) . "' ";
        elseif( $max_id > 0 )
            $wsql .= " AND `id` < '" . intval( $max_id ) . "' ";
        

       	$word = z(t(v('word')));
       	if( strlen( $word ) > 0 ) $wsql .= $wsql . " AND `content` LIKE '%" . s($word) . "%' ";
	   
		$osql = " ORDER BY `id` DESC ";	



		$sql = "SELECT * FROM `message` WHERE 1 AND (( `from_uid` = '" . intval($uid) . "' AND `to_uid` = '" . uid() . "' ) ";
		$sql .= " OR ( `from_uid` = '" . uid() . "' AND `to_uid` = '" . intval($uid) . "' )) ";

		$sql = $sql . $wsql . $osql . " LIMIT " . $count ;
		//sae_debug( 'sql=' . $sql );

		if( !$data = get_data( $sql ) ) return self::send_error( LR_API_DB_EMPTY_RESULT , 'EMPTY RESULT' );
		
		if( db_errno() != 0 )
			return self::send_error(  LR_API_DB_ERROR , 'DATABASE ERROR '   );
		else
		{
			$more = 1;
			if( is_array( $data ) )
			{
				if( count($data) < $count ) $more = 0;
				
				$first = reset( $data );
				$max = $min = $first['id'];
				foreach( $data as $k=> $item )
				{
					if( $item['id'] > $max ) $max = $item['id'];
					if( $item['id'] < $min ) $min = $item['id'];

				}
			}
			
			return self::send_result(  array( 'max' => intval($max) , 'min' => intval($min) , 'items' => $data , 'more'=> intval( $more ) )  );
		}


	}

	/**
     * 取得当前用户和指定用户的未读私信消息
     *
     * 读取后自动标记为已读
     *
     * @param string token , 必填
     * @param string uid , 必填
     * @return im history data array 
     * @author EasyChen
     */
	public function get_fresh_chat()
	{
		$uid = intval(v('uid'));
		if( $uid < 1 ) return self::send_error( LR_API_ARGS_ERROR , 'id FIELD REQUIRED' );

		$since_id = intval(v('since_id'));
		if( $since_id > 0 ) $wsql = "AND `id` > '" . $since_id . "' " ; 
		else $wsql = '';

		$sql = "SELECT * FROM `message` WHERE `to_uid` = '" . intval(uid()) . "' AND `from_uid` = '" . intval($uid) . "' AND `is_read` = 0 " . $wsql . " ORDER BY `id` DESC LIMIT 100";

		if( !$data = get_data( $sql ) ) return self::send_error( LR_API_DB_EMPTY_RESULT , 'EMPTY RESULT' );
		
		if( db_errno() != 0 )
			return self::send_error(  LR_API_DB_ERROR , 'DATABASE ERROR '   );
		else
		{
			$more = 1;
			if( is_array( $data ) )
			{
				if( count($data) < $count ) $more = 0;
				
				$first = reset( $data );
				$max = $min = $first['id'];
				foreach( $data as $k=> $item )
				{
					if( $item['id'] > $max ) $max = $item['id'];
					if( $item['id'] < $min ) $min = $item['id'];
					
				}
			}

			$sql = "UPDATE `message` SET `is_read` = 1 WHERE `to_uid` = '" . intval(uid()) . "' AND `from_uid` = '" . intval($uid) . "' LIMIT 100";
			run_sql( $sql );
			
			return self::send_result(  array( 'max' => intval($max) , 'min' => intval($min) , 'items' => $data , 'more'=> intval( $more ) )  );
		}
	}
	
	
	/* ============ team  =============== */

	/**
     * 创建激活码
     *
     * 普通成员通过创建激活码，邀请其他用户注册
     *
     * @param string token , 必填
     * @return array('activecode'=>$string) 
     * @author EasyChen
     */
	public function team_activecode()
	{
		$string = substr(md5(rand( 1000 , 9999 ) . time()) , 0 , 4 );
		
		//$string = md5(rand( 1000 , 9999 ) . time());
		
		
		$sql = "REPLACE INTO `activecode` ( `code` , `creator_uid` , `timeline` ) VALUES ( '" . s($string) . "' , '" . uid() . "' , NOW() )";
		
		run_sql( $sql );
		
		if( db_errno() != 0 )
		{
			return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . db_error() );
		}
		else
		{
			return self::send_result( array('activecode'=>$string) );
		}
	}
	
	/**
     * 团队成员列表
     *
     * 不包含密码信息
     *
     * @param string token , 必填
     * @return user list array 
     * @author EasyChen
     */
	public function team_members()
	{
		$sql = "SELECT * FROM `user` WHERE `is_closed` = 0 LIMIT 500";
		if( !$data = get_data( $sql ) )
		{
			if( db_errno() == 0  )
				return self::send_error( LR_API_DB_EMPTY_RESULT , 'NO DATA' );
			else	
				return self::send_error( LR_API_DB_ERROR , 'DATABASE ERROR ' . mysql_error() );
		}
		
		// clean password field
		foreach( $data as $k=>$v )
		{
			$data[$k]['password'] = null;
			unset($data[$k]['password']);
			if( strlen($data[$k]['groups']) > 0 ) $data[$k]['groups'] = explode('|', trim( $data[$k]['groups'] , '|' )) ;
		}
		
		return self::send_result( $data );
			
		
			
	}

	/* ============ groups  =============== */
	/**
     * 分组列表
     *
     * 显示所有分组名
     *
     * @param string token , 必填
     * @return group array 
     * @author EasyChen
     */
	public function groups()
	{
		return self::send_result( get_group_names() );
	}
    
    /*
	 * ignore
     */
    private function check_token()
    {
        $token = z( t( v( 'token' ) ) );
        
        if( strlen( $token ) < 2 )
        {
            return self::send_error( LR_API_TOKEN_ERROR , 'NO TOKEN' );
        }
        
        session_id( $token );
        session_set_cookie_params( c('session_time') );
        @session_start();
        
        if( $_SESSION[ 'token' ] != $token )
        {
            return self::send_error( LR_API_TOKEN_ERROR , 'BAD TOKEN' );
        }
    }

    /*
	 * ignore
     */
    public static function send_error( $number , $msg )
    {
        $obj = array();
        $obj[ 'err_code' ] = intval( $number );
        $obj[ 'err_msg' ] = $msg;
        if( g('API_EMBED_MODE') == 1 )
        	return json_encode( $obj );
        else
		 {
			header('Content-type: application/json');
			die( json_encode( $obj ) );
		 }
		 	
    }
    
    /*
	 * ignore
     */
    public static function send_result( $data )
    {
        $data = apply_filter( 'API_' . g('a') .'_OUTPUT_FILTER' , $data );
        
        $obj = array();
        $obj[ 'err_code' ] = '0';
        $obj[ 'err_msg' ] = 'success';
        $obj[ 'data' ] = $data;

        if( g('API_EMBED_MODE') == 1 )
        	return json_encode( $obj );
        else
		 {
			header('Content-type: application/json');
			die( json_encode( $obj ) );
		 }
    }
}
?>