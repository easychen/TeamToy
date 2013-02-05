<?php
if( !defined('IN') ) die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );

class pluglistController extends appController
{
	function __construct()
	{
		parent::__construct();
		$this->check_login();
	}
	
	function index()
	{
		if( !is_admin() ) 
			return info_page('只有管理员才能进入此页面，<a href="?c=guest&a=logout">请先用管理员账户登入</a>');
		
		$data['top'] = $data['top_title'] = '插件管理';
		$data['plist'] = scan_plugin_info();
		render( $data , 'web' , 'card' );
	}

	function turn()
	{
		if( !is_admin() ) 
			return render( array( 'code' => LR_API_FORBIDDEN , 'message' => 'ONLY ADMIN CAN DO THIS' ) , 'rest' );
		
		$on = intval(v('on'));
		$folder_name = z(t(v('folder_name')));
		if( strlen( $folder_name ) < 1 )
			return render( array( 'code' => LR_API_ARGS_ERROR , 'message' => 'FOLDER NAME CANNOT BE EMPTY' ) , 'rest' );
		
		$sql = "REPLACE `plugin` (`folder_name` , `on`) VALUES ( '" . s($folder_name) . "' , '" . intval( $on ) . "' )";
		run_sql( $sql );

		if( db_errno() == 0 )
			return render( array( 'code' => 0 , 'message' => 'ok' ) , 'rest' );
		else
			return render( array( 'code' => LR_API_DB_ERROR , 'message' => db_error() ) , 'rest' );
	}

	function upload()
	{
		if( !is_admin() ) 
			return info_page('只有管理员才能进入此页面，<a href="?c=guest&a=logout">请先用管理员账户登入</a>');

		$data = array();
		return render( $data , 'ajax' );
	}

	function uploaded()
	{
		if( !is_admin() ) 
			return info_page('只有管理员才能进入此页面，<a href="?c=guest&a=logout">请先用管理员账户登入</a>');
		
		if( $_FILES['pfile']['error'] != 0 )
			return info_page('文件上传错误，请重新上传');
		
		$tmp_name = $_FILES['pfile']['tmp_name'];

		$tname = uid() . '-' . time();
		$plug_path = c('plugin_path') . DS . $tname;
		if(@mkdir( $plug_path ))
		{
			include_once( AROOT.'lib'.DS.'dUnzip2.inc.php' );
			$zip = new dUnzip2( $tmp_name );
			$zip->debug = false;	
			$zip->unzipAll( $plug_path  );
			@chmod( $plug_path , 0755 );
			$info_file = $plug_path . DS . 'app.php';
			if( file_exists( $info_file ) )
			{
				if( $info = get_plugin_info( file_get_contents( $info_file ) ))
				{
					if( isset( $info['folder_name'] ) ) $folder_name = $info['folder_name'];
					if( strlen( $folder_name ) < 1 ) $folder_name =  reset(explode('.' ,basename($_FILES['pfile']['name']) ));
					if( strlen( $folder_name ) > 0 )
					{
						if( file_exists( c('plugin_path') . DS . $folder_name ) )
						{
							@rename( c('plugin_path') . DS . $folder_name . DS . 'app.php' , c('plugin_path') . DS . $folder_name . DS . 'app.bak.php'); 
							@rename( c('plugin_path') . DS . $folder_name , c('plugin_path') . DS . $folder_name .'_'. uid() .'_' . time() );	
						} 
						rename( $plug_path , c('plugin_path') . DS . $folder_name  );
						header("Location: ?c=pluglist");
						return true;
					}
					else 
						return info_page('尝试获取插件名称失败，启用'.$tname.'作为临时名称，<a href="?c=pluglist">请点击继续</a>');  

				}
			}
			else
			{
				// clear dir

			}	

			return 	info_page('找不到插件执行脚本-app.php文件，<a href="?c=pluglist">请重新上传格式正确的插件包</a>');  
			
			

		}else 
			return info_page('创建插件目录失败，请将plugin目录设置为可写后<a href="?c=pluglist">重试</a>');
		

	}

}