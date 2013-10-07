<?php
if( !defined('SAE_TMP_PATH') )
{
    @mkdir( AROOT . DS . 'tmp');
    define('SAE_TMP_PATH', AROOT . DS . 'tmp' . DS );
}

function my_sql( $sql )
{
    if( function_exists('mysqli_connect') )
        return mysqli_query( db() , $sql  );
    else
        return mysql_query( $sql , db()  ); 
}

function not_empty( $str )
{
	return strlen( $str ) > 0;
}

// From aoihome.sinaapp.com/fun via Aoi [is_email]
function is_email( $email )
{
	return filter_var( $email , FILTER_VALIDATE_EMAIL );
}

function on_sae()
{
    return defined('SAE_ACCESSKEY') && (substr( SAE_ACCESSKEY , 0 , 4 ) != 'kapp') ;
}

function is_online( $uid )
{
    $sql = "SELECT * FROM `online` WHERE `uid` = '" . intval( $uid ) . "' AND `last_active` > '" . date( "Y-m-d H:i:s" , strtotime("-5 minutes") ) . "' LIMIT 1";
    return get_line( $sql );
}

function is_installed()
{
    if( !db()) return false;
    return my_sql("SHOW COLUMNS FROM `user`");
}

function kset( $key , $value )
{
    $sql = "REPLACE INTO `keyvalue` ( `key` , `value` ) VALUES ( '" . s($key) . "' , '" . s($value) . "' )";
    run_sql( $sql );
}

function kget( $key )
{
    return get_var( "SELECT `value` FROM `keyvalue` WHERE `key` = '" . s($key) . "' LIMIT 1" );
}

function kdel( $key )
{
    $sql = "DELETE FROM `keyvalue` WHERE `key` = '" . s($key) . "' LIMIT 1" ;
    run_sql($sql);
}

function local_version()
{
    return intval(file_get_contents(AROOT.'version.txt'));
}

function db_init()
{
    
    $password = substr( md5( time().rand(1,9999) ) , rand( 1 , 20 ) , 12   );
    
    $sql_contents = preg_replace( "/(#.+[\r|\n]*)/" , '' , file_get_contents( AROOT . 'misc' . DS . 'install.sql'));

    // 更换变量
    $sql_contents = str_replace( '{password}' , md5($password) , $sql_contents );

    $sqls = split_sql_file( $sql_contents );
    foreach ($sqls as $sql) 
    {
        run_sql( $sql );
    }

    if(  db_errno() == 0 )
    {
        info_page(__('DATABASE_INIT_FINISHED' , $password ));
        exit;
    }
    else
    {
        info_page( db_error() );
        exit;
    } 
        
    
}

function split_sql_file($sql, $delimiter = ';') 
{
    $sql               = trim($sql);
    $char              = '';
    $last_char         = '';
    $ret               = array();
    $string_start      = '';
    $in_string         = FALSE;
    $escaped_backslash = FALSE;

    for ($i = 0; $i < strlen($sql); ++$i) {
            $char = $sql[$i];

            // if delimiter found, add the parsed part to the returned array
            if ($char == $delimiter && !$in_string) {
                    $ret[]     = substr($sql, 0, $i);
                    $sql       = substr($sql, $i + 1);
                    $i         = 0;
                    $last_char = '';
            }

            if ($in_string) {
                    // We are in a string, first check for escaped backslashes
                    if ($char == '\\') {
                            if ($last_char != '\\') {
                                    $escaped_backslash = FALSE;
                            } else {
                                    $escaped_backslash = !$escaped_backslash;
                            }
                    }
                    // then check for not escaped end of strings except for
                    // backquotes than cannot be escaped
                    if (($char == $string_start)
                            && ($char == '`' || !(($last_char == '\\') && !$escaped_backslash))) {
                            $in_string    = FALSE;
                            $string_start = '';
                    }
            } else {
                    // we are not in a string, check for start of strings
                    if (($char == '"') || ($char == '\'') || ($char == '`')) {
                            $in_string    = TRUE;
                            $string_start = $char;
                    }
            }
            $last_char = $char;
    } // end for

    // add any rest to the returned array
    if (!empty($sql)) {
            $ret[] = $sql;
    }
    return $ret;
}

function is_login()
{
	return $_SESSION['level'] > 0;
}

function is_admin()
{
    return $_SESSION['level'] == 9;
}

function uid()
{
	return intval($_SESSION['uid']);
}

function uname()
{
	return t($_SESSION['uname']);
}

function forward( $url )
{
	header( "Location: " . $url );
}

function jsforword( $url )
{
	return '<script>location="' . $url . '"</script>';
}

function image( $filename )
{
	return 'static/image/' . $filename;
}

function avatar( $url )
{
	if( strlen($url) < 1 ) return c('default_avatar');
	else return $url;
}

function ctime( $timeline )
{
    $time = strtotime($timeline);
    if( time() > ($time+60*60*24*300) )return date( __('DATE_FULL_FORMAT') ,$time);
    elseif( time() > ($time+60*60*8) ) return date( __('DATE_SHORT_FORMAT') ,$time);
    else return date("H:i:s",$time);
}

/*
function rtime( $timeline )
{
	return date("m月d日 H点i分" , strtotime($timeline) );
}
*/

function rtime( $time = false, $limit = 86400, $format = null) 
{
	if( $format === null ) $format = __('DATE_SHORT_FORMAT');

    $time = strtotime($time);

	$now = time();
	$relative = '';

	if ($time === $now) $relative = __('DATE_RELATED_NOW');
	elseif ($time > $now) $relative = __('DATE_RELATED_AFTER') ;
	else 
	{
		$diff = $now - $time;

		if ($diff >= $limit) $relative = date($format, $time);

		elseif ($diff < 60) 
		{
			$relative = __('DATE_RELATED_LESS_THAN_A_MINUTE');
		}
		elseif (($minutes = ceil($diff/60)) < 60)
		{
			if( (int)$minutes === 1 ) $relative = __( 'DATE_RELATED_ONE_MINUTE' );
            else  $relative = __( 'DATE_RELATED_SOME_MINUTES' ,  $minutes );      
		}
		else
		{
			$hours = ceil($diff/3600);

            if( (int)$hours === 1 ) $relative = __( 'DATE_RELATED_ONE_HOUR' );
            else  $relative = __( 'DATE_RELATED_SOME_HOURS' ,  $hours );  

		}
	}

	return $relative;
}

function noname( $content , $name )
{
	$len = strlen($name);
	if( substr( $content , 0 , $len ) == $name )
		return substr( $content , $len  );
	else
		return $content;
}

function render_html( $data , $tpl )
{
	ob_start();
	extract($data);
	require( $tpl );
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
	// 
}

function read_class( $is_read )
{
    if( intval($is_read) == 1 ) return  'read' ; 
    else return  'unread';
}

function feed_class(  $type )
{
	switch( $type )
	{
		case 2:
			 $class= 'todo';
			break;
		case 1:
			$class= 'notice';
			break;
		case 3:
			$class= 'user';
			break;

        case 4:
            $class= 'cast';
            break;   
            
		default:
			$class = 'normal';		
	}

	return $class;
}

function device( $type )
{
	if( strtolower(t($type)) == 'mobile' )
		$ret = __('FROM_MOBILE_DEVICE');
	else
		$ret = __('FROM_WEB_DEVICE');
	return $ret;
}

function get_device()
{
    if( is_mobile_request()) return 'mobile';
    else return 'web';
}


// ========================================
// client functions
// ========================================


function login( $email , $password )
{
    $params = array();
    $params['email'] = $email;
    $params['password'] = $password;

    if($content = send_request( 'user_get_token' ,  $params ))
    {
        $data = json_decode( $content , 1 );
        if( ($data['err_code'] == 0) && is_array( $data['data'] ) )
            return $data['data'];
        else
            return false;
    }
    return null;
}

function token()
{
	return $_SESSION['token'];
}

function send_request( $action , $param , $token = null )
{
	require_once( AROOT . 'controller' . DS . 'api.class.php' );
    require_once( AROOT . 'model' . DS . 'api.function.php' );
    $GLOBALS['API_EMBED_MODE'] = 1;
        
    // local request
    $bake_request = $_REQUEST;
    $_REQUEST['c'] = 'api';
    $GLOBALS['a'] = $_REQUEST['a'] = $action;
    if( $token !== null )
        $_REQUEST['token'] = $token;

    if( (is_array( $param )) && (count($param) > 0) )
        foreach( $param as $key => $value )
        {
            $_REQUEST[$key] =  $value ;
        }
            

    $api = new apiController();
    // magic call
    if( method_exists($api, $action) || has_hook('API_'.$action) )
    {
        $content = $api->$action();
        $_REQUEST = $bake_request;
        $GLOBALS['a'] = $_REQUEST['a'];
       
        return $content;
        //if($data = json_decode( $content , 1 ))
        //return json_encode($data['data']);
    }
    else
    {
        return 'API_'.$action . ' NOT EXISTS';
    }
   
    return null;
    
    
    // remote request ...........
    /*

    $url = c('api_server') . '?c=api&a=' . u($action) . '&token=' . u($token) ;
	
	if( (is_array( $param )) && (count($param) > 0) )
		foreach( $param as $key => $value )
			$url .= '&' . $key . '=' . u( $value );
	
	

	if($content = file_get_contents( $url ))
		return $content;
	
	return $url;
	*/
}

function find_at( $text )
{
	$reg = '/@(\S+?(?:\s|$))/is';
	if( preg_match_all( $reg , $text , $out ) )
		return $out[1];
	else
		return false;

}

function jpeg_up( $source , $dest )
{
	if( !function_exists('exif_read_data') ) return copy( $source , $dest );
    $img_info = @exif_read_data( $source , ANY_TAG  );
  	switch( $img_info['Orientation'] )
  	{
  	  	case 6:
  		  	$r = 270;
  		  	break;
                        
          	case 3:
                	$r = 180;
                        break;
                        
          	case 8:
                	$r = 90;
                        break;
                        
          	default:
                	$r = 0;
  	}
  
  	$img_src = ImageCreateFromJPEG( $source );
    $rotate = imagerotate($img_src, $r, 0,true);    
	ImageJPEG($rotate,$dest);
        
        
        
}

function upload_as_form( $url , $data )
{
    @session_write_close(); 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 

    $ch = apply_filter( 'UPLOAD_CURL_SETTINGS' , $ch );

    $response = curl_exec($ch);
    return $response;
}


function pinyin($_string, $_code='utf8')
{ //gbk页面可改为gb2312，其他随意填写为utf8
        $_datakey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha". 
                        "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|". 
                        "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er". 
                        "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui". 
                        "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang". 
                        "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang". 
                        "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue". 
                        "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne". 
                        "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen". 
                        "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang". 
                        "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|". 
                        "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|". 
                        "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu". 
                        "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you". 
                        "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|". 
                        "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo"; 
        $_datavalue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990". 
                        "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725". 
                        "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263". 
                        "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003". 
                        "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697". 
                        "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211". 
                        "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922". 
                        "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468". 
                        "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664". 
                        "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407". 
                        "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959". 
                        "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652". 
                        "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369". 
                        "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128". 
                        "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914". 
                        "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645". 
                        "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149". 
                        "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087". 
                        "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658". 
                        "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340". 
                        "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888". 
                        "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585". 
                        "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847". 
                        "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055". 
                        "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780". 
                        "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274". 
                        "|-10270|-10262|-10260|-10256|-10254"; 
        $_tdatakey   = explode('|', $_datakey); 
        $_tdatavalue = explode('|', $_datavalue);
        $_data = array_combine($_tdatakey, $_tdatavalue);
        arsort($_data); 
        reset($_data);
        if($_code!= 'gb2312') $_string = _u2_utf8_gb($_string); 
        $_res = ''; 
        for($i=0; $i<strlen($_string); $i++) { 
                $_p = ord(substr($_string, $i, 1)); 
                if($_p>160) { 
                        $_q = ord(substr($_string, ++$i, 1)); $_p = $_p*256 + $_q - 65536;
                } 
                $_res .= _pinyin($_p, $_data); 
        } 
        return preg_replace("/[^a-z0-9]*/", '', $_res); 
} 

function _pinyin($_num, $_data)
{ 
        if($_num>0 && $_num<160 ){
                return chr($_num);
        }elseif($_num<-20319 || $_num>-10247){
                return '';
        }else{ 
                foreach($_data as $k=>$v){ if($v<=$_num) break; } 
                return $k; 
        } 
}

function _u2_utf8_gb($_c)
{ 
        $_string = ''; 
        if($_c < 0x80){
                $_string .= $_c;
        }elseif($_c < 0x800) { 
                $_string .= chr(0xc0 | $_c>>6); 
                $_string .= chr(0x80 | $_c & 0x3f); 
        }elseif($_c < 0x10000){ 
                $_string .= chr(0xe0 | $_c>>12); 
                $_string .= chr(0x80 | $_c>>6 & 0x3f); 
                $_string .= chr(0x80 | $_c & 0x3f); 
        }elseif($_c < 0x200000) { 
                $_string .= chr(0xf0 | $_c>>18); 
                $_string .= chr(0x80 | $_c>>12 & 0x3f); 
                $_string .= chr(0x80 | $_c>>6 & 0x3f); 
                $_string .= chr(0x80 | $_c & 0x3f); 
        } 
        return iconv('utf-8', 'gb2312', $_string); 
}

// **************************************************************
// * Plugins & hooks
// ************************************************************** 
function add_filter( $tag , $function_to_add , $priority = 10 , $accepted_args_num = 1 )
{
    return add_hook( $tag , $function_to_add , $priority , $accepted_args_num );
}

function add_action( $tag , $function_to_add , $priority = 10 , $accepted_args_num = 1 )
{
    return add_hook( $tag , $function_to_add , $priority , $accepted_args_num );
}

function add_hook( $tag , $function_to_add , $priority = 10 , $accepted_args_num = 1 )
{
    $tag = strtoupper($tag);
    $idx = build_hook_id( $tag , $function_to_add , $priority );
    $GLOBALS['TTHOOK'][$tag][$priority][$idx] = array( 'function' => $function_to_add , 'args_num' => $accepted_args_num );
}

function do_action( $tag , $value = null )
{
    return apply_hook( $tag , $value );
}

function apply_filter( $tag , $value = null )
{
    return apply_hook( $tag , $value );
}



function apply_hook( $tag , $value )
{
    $tag = strtoupper($tag);
    if( $hooks  = has_hook( $tag ) )
    {
        ksort( $hooks );
        $args = func_get_args();
        reset( $hooks );

        do
        {
            foreach( (array) current( $hooks ) as $hook )
            {
                if( !is_null($hook['function']) )
                {
                    $args[1] = $value;
                    $value = call_user_func_array( $hook['function'] , array_slice($args, 1, (int) $hook['args_num']));
                }
            }
        }while( next( $hooks ) !== false );

    }

    return $value;
}

function has_hook( $tag , $priority = null )
{
    $tag = strtoupper($tag);
    if( is_null($priority) ) return isset( $GLOBALS['TTHOOK'][$tag] )? $GLOBALS['TTHOOK'][$tag]:false;
    else return isset( $GLOBALS['TTHOOK'][$tag][$priority] )? $GLOBALS['TTHOOK'][$tag][$priority]:false;
}

function remove_hook( $tag , $priority = null )
{
    $tag = strtoupper($tag);
    if( is_null($priority) ) unset( $GLOBALS['TTHOOK'][$tag] );
    else unset( $GLOBALS['TTHOOK'][$tag][$priority] );
}
// This function is based on wordpress  
// from  https://raw.github.com/WordPress/WordPress/master/wp-includes/plugin.php
// requere php5.2+

function build_hook_id( $tag , $function ) 
{
    if ( is_string($function) )
        return $function;

    if ( is_object($function) ) 
    {
        // Closures are currently implemented as objects
        $function = array( $function, '' );
    }
    else
    {
        $function = (array) $function;
    }

    if (is_object($function[0]) ) 
    {
        // Object Class Calling
        if ( function_exists('spl_object_hash') ) 
        {
            return spl_object_hash($function[0]) . $function[1];
        }
        else
        {
            return substr( serialize($function[0]) , 0 , 15 ). $function[1];
        }

    }
    elseif( is_string($function[0]) )
    {
        // Static Calling
        return $function[0].$function[1];
    }
}

function scan_plugin_info()
{
    if( file_exists( c('plugin_path') ) )
    foreach( glob( c('plugin_path') . DS . "*" , GLOB_ONLYDIR ) as $pfold  )
    {
        $app_file = $pfold .DS .'app.php';
        if( file_exists( $app_file ) )
            if($pinfo = get_plugin_info( file_get_contents( $app_file ) ))
            $plist[] = $pinfo;
    }
    return isset( $plist ) ? $plist : false;
}

function get_plugin_info( $content )
{
    $reg = '/\*\*\*\s+(.+)\s+\*\*\*/is';
    if( preg_match( $reg , $content , $out ) )
    {
        $info_content = $out[1];
        $lines = explode('##',$info_content);
        array_shift($lines);
        foreach( $lines as $line )
        {
            $line = trim($line);
            list( $key , $value ) = explode( ' ' , $line , 2 );
            $ret[$key] = z(t($value)); 
        }

        if( isset($ret) )return $ret;

    }

    return false;
}

function ttpassv2( $password , $salt = '' )
{
    return substr( md5(md5( $password  ) . 'T!e*a-m^T$o#y' . $salt  ) , 0 , 30 );
}

// =================================================
// make mentions
// =================================================
function member_info()
{
    if( !isset($GLOBALS['TT_MEMBER_INFO']) )
    {
        $sql = "SELECT `id` as `uid` , `name` FROM `user` WHERE `level` > 0 AND `is_closed` != 1 ";
        if($data = get_data($sql))
        {
            foreach( $data as $item )
            {
                $name = trim($item['name']);
                $GLOBALS['TT_MEMBER_INFO']['@'.$name] = 
                '<a href="javascript:void(0);" uid=' . $item['uid'] . ' class="namecard">'. '@'.$name .'</a>';

                if(c('at_short_name'))
                    if( mb_strlen( $name , 'UTF-8' ) == 3 )
                        $GLOBALS['TT_MEMBER_INFO']['@'.mb_substr($name , 1 , 2 , 'UTF-8' )] =
                        '<a href="javascript:void(0);" uid=' . $item['uid'] . ' class="namecard">'. '@'.mb_substr($name , 1 , 2 , 'UTF-8' ).'</a>';
                        
            }

        }
    }
    return  $GLOBALS['TT_MEMBER_INFO'];
}

function link_at( $str )
{
    $to_replace = array_keys( member_info() );
    $replace_to = array_values( member_info() );
    return str_replace( $to_replace , $replace_to , $str );
}

function find_links( $html )
{
    $reg = '/(http[s]*:\/\/([-a-zA-Z0-9@:%~#&_=\+\.\?\/]+?))((\s+)|$)/is';
    if( preg_match_all( $reg , $html , $out ) )
    {
        foreach( $out[0] as $item )
        {
            $ret[] = trim($item);
        }

        $ret = array_unique($ret);
        return $ret;
    }
    return false;
}

function replace_links( $html )
{
    $reg = '/(http[s]*:\/\/([-a-zA-Z0-9@:%~#&_=\+\.\?\/]+?))((\s+)|$)/is';
    if(  $ret = preg_replace( $reg , "<a href='$1' target='_blank'>$1</a> " , $html ) )
    {
        return $ret;
    }
    else return $html;
   

}

function js_i18n( $array )
{
    $ret = array();
    foreach( $array as $key => $value )
    {
        if( strtoupper( substr( $key , 0 , 3 ) ) == 'JS_'  )
            $ret[$key] = $value;
    }
    return $ret;
}

function plugin_append_lang( $lang_array )
{
    $c = g('i18n');
    if( isset( $lang_array[$c] ) )
        $GLOBALS['language'][$c] 
    = array_merge( $GLOBALS['language'][$c] , $lang_array[$c] ) ;
}


function array_remove( $value , $array )
{
    return array_diff($array, array($value));
}

function phpmailer_send_mail(  $to , $subject , $body , $from ,  $host , $port , $user , $password )
{
    if( !isset( $GLOBALS['LP_MAILER'] ) )
    {
        include_once( AROOT . 'lib' . DS . 'phpmailer.class.php' );
        $GLOBALS['LP_MAILER'] = new PHPMailer();
    }

    $mail = $GLOBALS['LP_MAILER'];
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->IsSMTP(); 
    $mail->Host = $host;
    $mail->SMTPAuth = true;   
    //$mail->SMTPKeepAlive = true;
    $mail->Port = $port;
    $mail->Username = $user;
    $mail->Password = $password;
    $mail->SetFrom($from );
    $mail->AddReplyTo($from);

    $mail->Subject = $subject ;
    $mail->WordWrap = 50;
    $mail->MsgHTML($body);
    $mail->AddAddress( $to );

    if(!$mail->Send())
    {
        $GLOBALS['LP_MAILER_ERROR'] = $mail->ErrorInfo;
        //echo $mail->ErrorInfo;
        return false;
    }
    else
    {
        $mail->ClearAddresses();
        return true;
    }
   


    

}


?>