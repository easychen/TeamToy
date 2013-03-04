<?php
define( 'USER_INFO' , "`id` ,  `id` as  `uid` , `name` , `mobile` , `tel` , `eid` , `weibo` , `desp` , `groups` , `pinyin` , `email` , `avatar_small` , `avatar_normal` , `level` , `timeline` , `is_closed`" );


function get_user_info_by_id( $uid )
{
	if( $data = get_line( "SELECT " . USER_INFO . " FROM `user` WHERE `id` = '" . intval( $uid ) . "'" , db() ) )
		if( strlen( $data['groups'] ) > 0 ) 
			$data['groups'] = explode('|', trim( $data['groups'] , '|' )) ;
	
	return $data;
	
}

function get_user_full_info_by_id( $uid )
{
	return get_line( "SELECT * FROM `user` WHERE `id` = '" . intval( $uid ) . "'" );
}

function get_full_info_by_email_password( $email , $password )
{
	$sql = "SELECT * FROM `user` WHERE `email` = '" . s( $email ) . "' AND `password` = '" . md5( $password ) . "' LIMIT 1";
	return get_line( $sql );
}

function close_user_by_id( $uid )
{
	$sql = "UPDATE `user` SET `is_closed` = '1' , `level` = 0  WHERE `id`  = '" . intval($uid) . "' LIMIT 1";
	run_sql( $sql );
}

function get_user_settings_by_id( $uid )
{
	$sql = "SELECT `settings` FROM `user` WHERE `id` = '" . intval($uid) . "' LIMIT 1";
	if( $settings = get_var($sql) )//
		return $array = unserialize( $settings );
	elseif( db_errno() == 0 )
		return array();
	else
		echo 'DBERROR-' . db_errno();	
		
	return false;
}

function get_group_unames( $group )
{
	$sql = "SELECT `name` FROM `user` WHERE `is_closed` = 0 AND `groups` LIKE '%|" . s(strtoupper($group)) . "|%'";
	if( $data = get_data( $sql ) )
		foreach( $data as $item )
			$unames[] = $item['name'];

	return isset($unames)?$unames:false;	
}

function get_group_names()
{
	if( !isset($GLOBALS['TT2_GNAMES']) )
	{
		$sql = "SELECT `groups` FROM `user` WHERE `is_closed` = 0 ";
		$groupstring = '|';
		if( $data = get_data( $sql ) )
			foreach( $data as $item  )
				if( strlen(trim($item['groups'])) > 1 )
					$groupstring = $groupstring . strtoupper($item['groups']).'|';

		if( $groupstring == '|' ) 
			$groups = false;
		else
		{
			$groups = explode( '|' ,  trim( $groupstring , '|' ) );
			if( is_array( $groups ) )
			{
				$groups  = array_unique($groups);
				foreach(  $groups as $k => $v )
					if( strlen(trim($v)) < 1 )
						unset($groups[$k]);		
			}
			
		} 
		

		$GLOBALS['TT2_GNAMES'] = $groups;	
	}
	

	return $GLOBALS['TT2_GNAMES']	;
}

function update_user_settings_array( $array )
{
	$sql = "UPDATE `user` SET `settings` = '" . s( serialize($array) ) . "' WHERE `id` = '" . intval( $_SESSION['uid'] ) . "' LIMIT 1";
	run_sql( $sql );
}

function add_todo( $text , $is_public = 0 , $uid = null )
{
	if( $uid == null || intval($uid) < 1 ) $uid = $_SESSION['uid'];
	
	$sql = "INSERT INTO `todo` ( `content` ,  `timeline` , `owner_uid` ) VALUES ( '" . s( $text ) . "'  , NOW() , '" . intval( $uid ) . "' ) ";
	run_sql( $sql );
	
	if( db_errno() != 0 ) return false;
	$lid = last_id();
	
	$sql = "INSERT INTO `todo_user` ( `tid` , `uid` , `is_public` ,`last_action_at` ) VALUES ( '" . intval( $lid ) . "' , '" . intval($uid) . "', '" . intval( $is_public ) . "' , NOW() )";
	run_sql( $sql );
	
	if( db_errno() != 0 ) return false;
	
	$sql = "INSERT INTO `todo_history` ( `tid` , `uid` , `content` , `type` , `timeline` ) VALUES ( '" . intval($lid) . "' , '" . intval($uid) . "' , '创建了TODO' , 1 , NOW() )";
	
	run_sql( $sql );
	if( db_errno() != 0 ) return false;
	
	
	
	return $lid;
}

function get_todo_info_by_id( $tid , $write_db = null )
{
	if( $write_db != null ) $write_db = db();
	
	if(!$tinfo = get_line( "SELECT *,`id` as `tid` FROM `todo` WHERE `id` = '" . intval($tid) . "' LIMIT 1" , $write_db )) return false;
	
	
	// 检查todo是否已经被所有人删除
	if(!$owner_info = get_line( "SELECT * FROM `todo_user` WHERE `is_follow` = 0 AND `tid` = '" . intval( $tid ) . "'" )) return false;
	
	if( ($owner_info['uid'] != intval( $_SESSION['uid'] )) &&  ($owner_info['is_public'] != 1) ) return false;
	//if( $owner_info['is_public'] != 1 ) return false;
	
	
	
	
	
	$data = $tinfo;
	$data['details'] = get_line( "SELECT * FROM `todo_user` WHERE `tid` = '" . intval($tid) . "' AND `uid` = '" . intval($_SESSION['uid']) . "' LIMIT 1" , $write_db );
	
	
	$hdata = get_data( "SELECT * FROM `todo_history` WHERE `tid` = '" . intval($tid) . "' ORDER BY `timeline` DESC LIMIT 100" , $write_db );
	
	
	if( is_array( $hdata ) )
	foreach( $hdata as $hitem )
	{
		$huids[] = $hitem['uid'];	
	}
	
	
	
	
	if( isset( $huids ) && is_array( $huids ) )
	{
		
		
		$sql = "SELECT " . USER_INFO . " FROM `user` WHERE `id` IN ( " . join( ' , ' , $huids ) . " )  ";
		
		if($udata = get_data( $sql ))
		{
			foreach( $udata as $uitem )
			{
				$uarray[$uitem['id']] = $uitem;
			}
			
			//print_r( $uarray );
			
			if( isset( $uarray ) )
			{
				foreach( $hdata as $k=>$hitem )
				{
					if( isset( $uarray[$hitem['uid']] ) )
						$hdata[$k]['user'] = $uarray[$hitem['uid']];
				}
			}
			
			
		}
		
		
	}
	
	
	//print_r( $hdata );
	
	$data['history'] = $hdata;
	
	
	$sql = "SELECT  " . USER_INFO . " FROM `user` WHERE `id` IN ( SELECT `uid` FROM `todo_user` WHERE `tid` = '"  . intval($tid) . "' AND `is_follow` = 1 )  ";
	$data['people'] = get_data( $sql );
	
	$data['owner'] = get_line( "SELECT " .  USER_INFO . " FROM `user` WHERE `id` = '" . intval($owner_info['uid']) . "' LIMIT 1 " );
	
	return $data;

}


function get_user_todo_list_by_uid( $uid = null )
{
	
	
	return false;	
	
}

function get_todo_text_by_id( $tid )
{
	return get_var("SELECT `content` FROM `todo` WHERE `id` = '" . intval( $tid ) . "' LIMIT 1");
}

function get_feed_by_id( $fid )
{
	if($feed = get_line("SELECT * FROM `feed` WHERE `id` = '" . intval( $fid ) . "' LIMIT 1"))
	{
		$feed['user'] = get_line("SELECT " . USER_INFO . " FROM `user` WHERE `id` = '" . intval($feed['uid']) . "'");
		return $feed;	
	}
	

	return false ;
}


function my_join( $sql ,  $array , $field , $as_field )
{

}

function send_notice( $uid , $content , $type = 1 , $data = null )
{
	$sql = "INSERT INTO `notice` ( `to_uid` , `content` , `type` , `data` , `timeline` ) VALUES( '" . intval( $uid ) . "' , '" . s($content) . "' , '" . intval( $type ) . "' , '" . serialize($data) . "' , NOW() )";
	run_sql( $sql );
	
	if( db_errno() != 0 ) die( db_error() );
	else
	{
		do_action('SEND_NOTICE_AFTER', array( 'uid' => $uid , 'content' => $content , 'type' => $type , 'data' => $data ) );
		return true;
	} 
}

function add_history( $tid , $content )
{
	$sql = "INSERT INTO `todo_history` ( `tid` , `uid` , `content` , `type` , `timeline` ) VALUES ( '" . intval($tid) . "' , '" . intval(uid()) . "' , '" . s( $content ) . "' , 1 , NOW() )";
	run_sql( $sql );
	return db_errno() == 0;
}


function publish_feed( $content , $uid , $type = 0 , $tid = 0  )
{
	if( is_mobile_request() ) $device = 'mobile';
	else $device = 'web';

	$tid = intval($tid);
	if( $type == 2 && $tid > 0 )
		$comment_count = get_var( "SELECT COUNT(*) FROM `todo_history` WHERE `tid` = '" . intval($tid) . "' AND `type` = 2 " , db()) ;
	else
		$comment_count = 0;

	$sql = "INSERT INTO `feed` ( `content` , `tid` , `uid` , `type` ,`timeline` , `device` , `comment_count` ) VALUES ( '" . s($content) . "' , '" . intval( $tid ) . "', '" . intval( $uid ) . "'  , '" . intval( $type ) . "' , NOW() , '" . s( $device ) . "' , '" . intval( $comment_count ) . "' )";
	run_sql( $sql );

	$lid = last_id();
	
	if( db_errno() != 0 )
		return  false;
	else
	{
		if( $comment_count > 0 && $type == 2 && $tid > 0 )
		{
			$sql = "UPDATE `feed` SET `comment_count` = '" . intval( $comment_count ) . "' WHERE `tid` = '" . intval( $tid ) . "' AND `comment_count` != '" . intval( $comment_count )  . "' ";
			run_sql( $sql );	
		}
		
		return $lid ;
	}
		
}




//











