<?php
$GLOBALS['language']['zh_cn'] = array
(
	/* ==== All ======  */
	'ACCOUNT_CLOSED' => '已关闭',
	'ACCOUNT_SUPER_ADMIN' => '超级管理员',


	'TEAMTOY_ABOUT' => '关于TeamToy',
	'TEAMTOY_ABOUT_WITH_VERSION' => '关于TeamToy[V%s]',

	'LOADING' => '正在载入',
		


	/* ==== Login page ======  */
	'LOGIN' => '登入',
	'LOGIN_PAGE_TITLE' => '登入',
	'LOGOUT' => '退出',
	'EMAIL' => '电邮地址',
	'PASSWORD' => '密码',
	'NO_IE_NOTICE' => '不支持IE系浏览器<br/><br/>请换用Chome、Firefox或者Safari',
	'LOGIN_OK_NOTICE' => '成功登入，正在转向中',
	'API_CONNECT_ERROR_NOTICE' => '尝试连接服务器失败，请稍后再试',
	'LOGIN_BAD_ARGS_NOTICE' => '错误的Email地址或者密码，请重试',

	'ADMIN_ONLY_LOGIN' => '只有管理员才能进入此页面，<a href="?c=guest&a=logout">请先用管理员账户登入</a>',

	/* ======== HEADER NAV =========== */
	'INDEX_PAGE' => '首页',
	'TEAM_FEED' => '团队动态',
	'TEAM_MEMBER' => '成员管理',
	'INBOX' => '收件箱',
	'PLUGIN_LIST' => '插件管理',
	'CHECK_NEW_VERSION' => '版本升级',
	'MESSAGE_SOUND' => '消息提示音',
	'UPDATE_PROFILE' => '更新个人资料',
	'UPDATE_AVATAR' => '更换头像',
	'UPDATE_PASSWORD' => '修改密码',
	'LOGIN_VIA_QR_CODE' => '通过二维码登入',

	/* ======== Dashboard =========== */
	'DATA_LOAD_ERROR'=>'数据载入失败，请稍后再试',
	'DB_UPGRADE_SUCCESS'=>'更新完成，请<a href="?c=dashboard">用力刷新页面以保证新代码正常工作</a>',
	'CODE_UPGRADE_ONLY_ADMIN'=>'只有管理员才能进行升级',
	'CODE_UPGRADE_ALREADY_LATEST'=>'已经是最新版本',
	'CODE_UPGRADE_ERROR'=>'升级失败，请稍后再试',
	'CODE_UPGRADE_SUCCESS_DB_UPGRADE'=>'代码更新成功，请<a href="%s">点击这里升级数据表</a>',
	'CODE_UPGRADE_SUCCESS'=>'成功更新，请<a href="?c=inbox">用力刷新页面以保证新代码正常工作</a>',
	'CODE_UPGRADE_CANNOT_CONNECT'=>'联网失败，请稍后再试',

	'AVATAR_UPLOAD_ERROR'=>'文件上传错误，请重新上传',
	'AVATAR_UPDATE_SUCCESS'=>'<a href="?c=buddy">头像更新成功，由于浏览器缓存的关系，您可能看到的还是旧头像，可强制刷新或清空缓存。</a>',
	'AVATAR_UPDATE_ERROR'=>'头像更新失败，错误码-%s，错误信息-%s',

	'API_MESSAGE_SAVE_DATA_ERROR' => '无法保存数据',
	'API_MESSAGE_CANNOT_CONNECT' => '无法连接API服务器',


	'API_MESSAGE_DATABASE_ERROR' => '数据库错误',
	
	'API_MESSAGE_ONLY_ADMIN' => '只有管理员才能进行此操作',
	'API_MESSAGE_USER_CLOSED_BY_ADMIN' => '该用户已被管理员关闭',

	'API_MESSAGE_BAD_ACCOUNT' => '错误的Email地址或密码',
	'API_MESSAGE_CANNOT_RESET_OWN_PASSWORD' => '不能重置自己的密码',

	'API_MESSAGE_UPGARDE_INFO_DATA_ERROR' => '从升级服务器取回的数据不可用',
	'API_MESSAGE_UPGARDE_ALREADY_LATEST' => '已经是最新版本',

	'API_MESSAGE_UPGARDE_FILE_UNZIP_ERROR' => '解压升级包失败',
	'API_MESSAGE_UPGARDE_FILE_FETCH_ERROR' => '下载升级包失败',

	'API_MESSAGE_FETCH_SETTINGS_DATA_ERROR' => '无法获取设置数据',

	'API_MESSAGE_CANNOT_CHANGE_PASSWORD' => '修改密码功能已经被禁用',
	'API_MESSAGE_SAME_PASSWORD' => '新密码和原密码相同',


	'API_MESSAGE_BAD_OPASSWORD' => '原密码错误',
	'API_MESSAGE_CANNOT_CHANGE_OWN_LEVEL' => '不能修改自己的用户等级',
	'API_MESSAGE_CANNOT_CLOSE_ONLY_ADMIN' => '不能关闭系统唯一的一个管理员',


	'API_MESSAGE_USER_NOT_EXISTS' => '用户不存在',
	'API_MESSAGE_ACCOUNT_CLOSED' => '%s关闭了账号【%s】',
	'API_MESSAGE_USER_LEVEL_UPDATED' => '%s将账号【%s】的等级调整为%s',


	'API_MESSAGE_TODO_EXISTS' => '相同的TODO已经存在',
	'API_MESSAGE_EMPTY_RESULT_DATA' => '数据不存在',

	'API_MESSAGE_CANNOT_REMOVE_OTHERS_COMMENT' => '不能删除别人的评论',

	'API_MESSAGE_TODO_ASSIGN_TO_SELF' => 'TODO不能转让给自己',

	'API_MESSAGE_SPEAK_TO_SELF' => '不能向自己发送私信',

	'API_MESSAGE_CANNOT_ASSIGN_OTHERS_TODO' => '不能转让别人的TODO',


	'API_MESSAGE_CANNOT_UPDATE_OTHERS_TODO' => '不能更新别人的TODO',
	'API_MESSAGE_CANNOT_REMOVE_OTHERS_FEED' => '不能删除别人的动态',

	'API_MESSAGE_TODO_ALREADY_FOLLOWED' => '已经关注了此TODO',

	'API_MESSAGE_TODO_ALREADY_DELETE_LOCALLY' => '客户端已经删除了该TODO',
	'API_MESSAGE_TODO_ALREADY_HAD_OTHER_ACTION' => '和云端的新更新发生冲突',


	

	'API_TEXT_JOINT_TEAMTOY' => '%s加入了TeamToy',
	'API_TEXT_NEW_VERSION' => 'TeamToy%s版已经发布',


	'API_TEXT_TODO_ADDED' => '%s添加了TODO【%s】',
	'API_TEXT_COMMENT_TODO_FOLLOWED' => '%s评论了你关注的TODO【%s】：%s',
	'API_TEXT_COMMENT_TODO_OWNED' => '%s评论了你的TODO【%s】: %s',

	'API_TEXT_AT_IN_TODO_COMMENT' => '%s在TODO【%s】的评论中@了你：%s',
	'API_TEXT_AT_IN_CAST_COMMENT' => '%s在动态【%s】的评论中@了你：%s',

	'API_TEXT_COMMENT_TODO' => '%s评论了TODO【%s】：%s',

	'API_TEXT_COMMENT_FEED_OWNED' => '%s评论了你的动态【%s】：%s',
	'API_TEXT_COMMENT_FEED_IN' => '%s评论了你参与的动态【%s】：%s',

	'API_TEXT_ASSIGN_TODO' => '转让了TODO',
	'API_TEXT_ASSIGN_TODO_TO_U' => '%s向你转让了TODO【%s】',
	'API_TEXT_ASSIGN_TODO_FOLLOWED' => '%s将你关注的TODO【%s】转让给了%s',

	'API_TEXT_ASSIGN_TODO_DETAIL' => '%s将TODO【%s】转让给了%s',

	'API_TEXT_FINISH_TODO' => '%s完成了TODO【%s】',
	'API_TEXT_FINISH_TODO_FOLLOWED' => '%s完成了你关注的TODO【%s】',

	'API_TEXT_AT_IN_CAST' => '%s在广播【%s】中@了你',
	'API_TEXT_ADD_CAST' => '%s发起了广播【%s】',



	'API_TEXT_ALREADY_UPGARDE_TO' => '您的TeamToy已经升级至%s版本，<a href="%s">请立即点击这里升级数据表</a>',


	'INPUT_CHECK_BAD_ARGS' => '参数错误，%s不能为空',
	'INPUT_CHECK_BAD_EMAIL' => '参数错误，EMail格式不正确',
	'INPUT_CHECK_EMAIL_EXISTS' => '参数错误，EMail已经存在',
	'INPUT_CHECK_BAD_ACTVECODE' => '参数错误，激活码不存在或者已经过期',
	'INPUT_CHECK_BAD_HTYPE' => '参数错误，HTYPE不正确',


	'INPUT_CHECK_NO_OLDPASS' => '参数错误，原始密码不能为空',
	'INPUT_CHECK_NO_NEWPASS' => '参数错误，新密码不能为空',
	'INPUT_CHECK_NO_TODO_TITLE' => '参数错误，TODO标题不能为空，请点击左侧TODO重新载入后重试',

	'TODO_LOAD_ERROR' => '加载TODO失败，请重试',
	'TODO_CREATED' => '创建了TODO',

	'NEED_LOGIN' => '您访问的页面需要先<a href="?c=guest">登入</a>',

	// ===================================================
	// buddy

	'MEMBER_PAGE_TITLE' => '团队成员',
	'FEED_PAGE_TITLE' => '团队动态',
	'INBOX_PAGE_TITLE' => '收件箱',
	'INSTALL_PAGE_TITLE' => 'TeamToy安装页面',
	'PLUGIN_ADMIN_PAGE_TITLE' => '插件管理',

	'PLUGIN_UPLOAD_FILE_ERROR_RETRY' => '文件上传错误，请重新上传',
	'PLUGIN_GET_NAME_ERROR_RETRY' => '尝试获取插件名称失败，启用%s作为临时名称，<a href="?c=pluglist">请点击继续</a>',
	'PLUGIN_PACKAGE_FORMAT_ERROR' => '找不到插件执行脚本-app.php文件，<a href="?c=pluglist">请重新上传格式正确的插件包</a>',
	'PLUGIN_CREATE_FOLDER_ERROR' => '创建插件目录失败，请将plugin目录设置为可写后<a href="?c=pluglist">重试</a>',


	'DATABASE_INIT_FINISHED' =>  '数据库初始化成功，请使用【member@teamtoy.net】和【%s】<a href="/" target="new">登入并添加用户</a>' ,
	


	'FEED_LOAD_ERROR_RETRY' => '加载动态失败，请重试',
	'INSTALL_FINISHED' => 'API Server 已初始化完成，<a href="?c=guest">请使用管理账号登入</a>',

	'BAD_ARGS' => '错误的参数',

	// view
	'SAVE_AS_AVATAR' => '保存为头像',
	'FIND_CHAT_HISTORY' => '查找聊天记录',
	'NEXT_PAGE' => '下一页',
	'OLD_PASS' => '原密码',
	'INPUT_OLD_PASS' => '输入原密码',
	'NEW_PASS' => '新密码',
	'INPUT_NEW_PASS' => '输入新密码',
	'REPEATE_PASS' => '重复下',
	'REPEATE_PASS_EXPLAIN' => '再重复输入一遍新密码',
	'UPDATE' => '更新',
	'OK' => '确定',

	'FIND_BY_NAME_OR_PINYIN' => '通过姓名或拼音查找',
	'SELECTED_PEOPLE' => '已选同事',
	'ONE_PEOPLE_LEAST' => '请至少选择一位同事',

	'NAME' => '姓名',
	'NAME_INPUT_EXPLAIN' => '不可修改，请填写真实姓名',
	'EMAIL' => '邮箱',
	'EMAIL_INPUT_EXPLAIN' => '必填、用于收发通知',
	'MOBILE' => '手机',
	'MOBILE_INPUT_EXPLAIN' => '必填、如136****',
	'TEL' => '分机',
	'TEL_INPUT_EXPLAIN' => '请填写完整号码',
	'EMPLOYEE_ID' => '工号',
	'EMPLOYEE_ID_INPUT_EXPLAIN' => '为接入公司其他系统预留',
	'WEIBO_ID' => '微博',
	'WEIBO_ID_INPUT_EXPLAIN' => '微博昵称',
	'DESP_TEXT' => '备注',
	'DESP_TEXT_INPUT_EXPLAIN' => '岗位职责/其他联系方式',

	'FEED_DETAIL_CLOSE' => '收起动态详情',
	'TODO_DETAIL_CLOSE' => '收起TODO详情',
	'CLICK_TO_EDIT' => '点击修改',
	'CANCEL' => '取消',
	'SAVE' => '保存',
	'SEND' => '发送',
	'REPLY' => '回复',
	'COMMENT' => '评论',
	'ENTER_TODO_CONTENT' => '请输入TODO内容',

	'TODO_FOLLOWED' => '我关注的TODO',
	'TODO_MINE' => '我的TODO',
	'MARK_ALL_TODO_DONE' => '全部标记为完成',
	'CLEAN_ALL_TODO_DONE' => '清除所有已完成TODO',

	// ======================
	// 团队成员页面
	// 
	'MEMBER_SEARCH' => '搜索成员',
	'MEMBER_ADD' => '添加成员',
	'ADD' => '添加',
	'MEMBER_SEARCH_KEYWORD_EXPLAIN' => '输入姓名或拼音',
	'MEMBER_SEARCH_KEYWORD_EXPLAIN_SHORT' => '姓名或拼音',
	'SEARCH' => '搜索',

	// ======================
	// 广播页面
	'SEND_CAST' => '发起广播',
	'SEND_CAST_TO_ALL_EXPLAIN' => '所有人都会收到通知',
	'SEND_CAST_EXPLAIN' => '如果你只想特定的人收到通知，可以使用@ 进行点名；未进行点名的广播将发送给所有人',
	'AT_TEXT' => '@点名',

	// ======================
	// 收件箱
	'INBOX_RECEIVE_SETTINGS' => '消息推送设置',
	'INBOX_RECEIVE_MESSAGE_VIA_MOBILE_CLIENT' => '通过手机客户端接收通知',
	'INBOX_ANDROID_LOGIN' => '使用Android客户端登入',
	'INBOX_IOS_LOGIN' => '使用iOS客户端登入',

	// ======================
	// 插件管理
	'MORE_PLUGINS' => '更多插件',
	'UPLOAD_PLUGIN' => '上传插件',
	'PLUGIN_NAME' => '名称',
	'PLUGIN_DESP' => '简介',
	'PLUGIN_VERSION' => '版本',
	'PLUGIN_STATUS' => '状态',
	'PLUGIN_ON' => '启用',
	'NO_AVAILABLE_PLUGIN' => '没有可用的插件',
	

	// ======================
	// date display
	'DATE_FULL_FORMAT' => 'Y年n月j日 H:i',
	'DATE_SHORT_FORMAT' => 'n月j日 H:i',
	
	'DATE_RELATED_NOW' => '刚刚',
	'DATE_RELATED_AFTER' => '以后',
	
	'DATE_RELATED_LESS_THAN_A_MINUTE' => '不到1分钟',
	'DATE_RELATED_ONE_MINUTE' => '1分钟前',
	'DATE_RELATED_SOME_MINUTES' => '%s分钟前',
	'DATE_RELATED_ONE_HOUR' => '1小时前',
	'DATE_RELATED_SOME_HOURS' => '%s小时前',

	'FROM_MOBILE_DEVICE' => '<a href="http://teamtoy.net/?c=download&type=mobile" target="_blank">来自移动版</a>',
	'FROM_WEB_DEVICE' => '<a href="http://teamtoy.net/?c=download&type=web" target="_blank">来自网页版</a>',

	
	'TEAMTOY_INTRO_TEXT' => 'TeamToy是为创新团队设计的效率工具，它以【事】为核心，打通了团队每个人的工作，让你可以轻松对其他同事进行评论、求助、点名和广播；借助移动客户端，让你和同事随时随地“在一起”。',

	'ABOUT_VERSION_TEXT' => '版本 - %s',
	'ABOUT_SITE_TEXT' => '官方网站 - <a href="http://teamtoy.org" target="_blank" class="white">TeamToy.org</a>',
	'ABOUT_SUPPORT_TEXT' => '客户支持QQ群 - 166762540',

	'TEAMTOY_STAFF' => '开发团队',
	'ABOUT_STAFF_BLOCK' => '<p>主平台设计、开发 - <a href="http://weibo.com/easy" target="_blank">@Easy</a> </p>

<p>客户支持 - <a href="http://weibo.com/131417999" target="_blank">@欧耶</a></p>

<p><a href="https://github.com/luofei614/teamtoy-board" target="_blank">看板插件 - <a href="http://weibo.com/luofei614" target="_blank">@luofei614</a></p>
<p><a href="http://ttoy-plugin.imlibo.com/" target="_blank">TToy客户端</a> - <a href="http://weibo.com/imlibo" target="_blank">@这是李博</a></p>
<p><a href="https://github.com/easychen/TeamToy-Pocket" target="_blank">TeamToyPocket客户端</a> - <a href="http://weibo.com/easy" target="_blank">@Easy</a></p>
<p>- 感谢您的一路相伴，感谢开源让我们走得更远 -</p>',

	'LIST_LOAD_MORE' => '载入更多',

	'INPUT_COMMENT_CONTENT' => '请输入评论内容',


	'QRCODE_FOR_MOBILE_CLIENT' => '客户端下载二维码',
	'QRCODE_FOR_QRCODE_LOGIN' => '快捷登入二维码',
	'DOWNLOAD_MOBILE_CLIENT' => '客户端下载',
	'QRCODE_LOGIN' => '快捷登入',
	'SCAN_QRCODE_VIA_PHONE' => '请用手机扫描',
	'SCAN_QRCODE_VIA_MOBILE_CLIENT' => '请用<a href="http://teamtoy.net/?c=download&type=mobile" target="new">TeamToy客户端</a>扫描',

	'UPLOAD' => '上传',
	'PLUGIN_INSTALL_WARNNING' => '请只安装可信任来源的插件，ZIP包根目录下直接放app.php，无需再用子目录。',



	'SET_AS_NORMAL_MEMBER' => '降为普通用户',
	'SET_AS_ADMIN' => '升为管理员',
	'RESET_PASSWORD' => '重置密码',

	'EDIT_MEMBER_GROUP' => '编辑用户所属分组',

	'DONOT_CHAT_TO_SELF' => '亲，自言自语就不用发到服务器啦~',
	'CHATBOX_EXPLAIN_TEXT' => '输入聊天文字，按回车键送出',

	'UPGRADE_NOW' => '立刻升级',

	'CLICK_TO_START_STOP_TODO' => '点击切换进行状态',
	'STAR_TODO' => '星标操作',

	'FOLLOW' => '关注',
	'FOLLOWER' => '谁在关注',

	'FINISH' => '完成',
	'STAR' => '星标',
	'PRIVATE' => '私密',
	'ASSIGN' => '转让',
	'DISCUSS' => '讨论',

	'ADD_TODO' => '添加TODO',
	'ADD_STAR' => '加星',
	'SLEF_ONLY' => '仅自己可见',
	'FOR_SOMEONE' => '给 <i class="icon-user"></i> %s',

	'FOOTER_INFO' => '<a href="http://teamtoy.net" target="new">TeamToy</a> - <a href="http://ftqq.com/" target="new">方糖气球</a> 荣誉出品 &copy; 2008~%s',

	
	'JS_API_CALL_ERROR' => 'API调用错误，请稍后再试。错误号%s 错误信息 %s',
	'JS_CANNOT_ASSIGN_PRIVATE_TODO' => '私有TODO不能转让哦',
	'JS_SELECT_MEMBER_TO_ASSIGN' => '选择要转让的同事',
	'JS_MARK_ALL_TODO_DONE_CONFIRM' => '确定要将所有TODO都标记为完成么？不准偷懒哦！',
	'JS_REMOVE_ALL_TODO_DONE_CONFIRM' => '确定清除所有已完成的TODO？',
	'JS_REMOVE_CAST_CONFIRM' => '广播删除后不可恢复，继续？',
	'JS_REMOVE_COMMENT_CONFIRM' => '确定删除这条评论？',
	
	'JS_STOP_PLUGIN_CONFIRM' => '停用%s插件后相关的功能将不可用，继续？',
	'JS_ACCOUNT_CLOSE_CONFIRM' => '确定要关闭该用户么？关闭后此用户资料将保留，但不能登入系统',

	'JS_RESET_PASSWORD_CONFIRM' => '确定要重置%s的密码？',


	'JS_TODO_CENTER_PAGE_TITLE' => 'TODO详情',
	'JS_NEW_DM' => '有新的私信啦',

	'JS_NEW_VERSION' => '有新的版本%s[%s]。升级到最新版？',
	'JS_ALREAD_LASTEST_VERSION' => '当前系统已经是最新了',

	'JS_ALL_CANNOT_EMPTY' => '所有字段均为必填项，请认真填写',

	'JS_ADD_GRUOP_NAME' => '添加分组名称',

	'JS_CHAT_HISTORY_WITH_SOMEONE' => '我和%s的聊天记录',

	'JS_NOTICE_PREFIX' => 'TeamToy有',
	'JS_NOTICE_NOTIFACTION' => '%s条未读通知',
	'JS_NOTICE_DM' => '%s条未读私信',

	'JS_OLD_PASSWORD_CANNOT_EMPTY' => '原密码不能为空',
	'JS_NEW_PASSWORD_CANNOT_EMPTY' => '新密码不能为空',
	'JS_TWO_PASSWORDS_NOT_SAME' => '两次输入的密码不一致',

	'JS_PASSWORD_CHANGED' => '密码修改成功，请使用新密码登入',
	'JS_API_CONNECT_ERROR' => '服务器通信失败，请稍后再试',

	'JS_FILL_MOBILE_EMAIL_PLZ' => 'Email和手机号都是必填项',

	

	'JS_CAST_MENTION_EXPLAIN_ALL' => '所有人都会收到通知',
	'JS_CAST_MENTION_EXPLAIN_MENONTED' => '被点名的人会收到通知',

	'JS_CANNOT_ADD_PRIVATE_TODO_TO_OTHERS' => '私有TODO不能添加给别人',
	'JS_SELECT_MEMBER_TO_ADD' => '点击你要加TODO的同事',
	'JS_SELECT_MEMBER_TO_METION' => '请选择你要点名的同事',

	'WEIBO_LINK' => 'http://s.weibo.com/user/%s',

	'TTEST' => ''
);
