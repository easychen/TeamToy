<?php
$GLOBALS['language']['zh_cn'] = array
(
	/* ==== All ======  */
	'ACCOUNT_CLOSED' => '已关闭',
	'ACCOUNT_SUPER_ADMIN' => '超级管理员',


	'TEAMTOY_ABOUT' => '关于TeamToy',
	'TEAMTOY_ABOUT_WITH_VERSION' => '关于TeamToy[V%s]',
		


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

	'TODO_DETAIL_CLOSE' => '收起TODO详情',
	'CLICK_TO_EDIT' => '点击修改',
	'CANCEL' => '取消',
	'SAVE' => '保存',

	'TODO_FOLLOWED' => '我关注的TODO',
	'TODO_MINE' => '我的TODO',
	'MARK_ALL_TODO_DONE' => '全部标记为完成',
	'CLEAN_ALL_TODO_DONE' => '清除所有已完成TODO',
	






	'TEST' => ''
);
