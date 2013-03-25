<?php
$GLOBALS['language']['us_en'] = array
(
	/* ==== All ======  */
	'ACCOUNT_CLOSED' => 'Closed',
	'ACCOUNT_SUPER_ADMIN' => 'Admin',


	'TEAMTOY_ABOUT' => 'About TeamToy',
	'TEAMTOY_ABOUT_WITH_VERSION' => 'About TeamToy[V%s]',

	'LOADING' => 'Loading',
		


	/* ==== Login page ======  */
	'LOGIN' => 'Sign in',
	'LOGIN_PAGE_TITLE' => 'Sign in',
	'LOGOUT' => 'Sign out',
	'EMAIL' => 'Email',
	'PASSWORD' => 'Password',
	'NO_IE_NOTICE' => 'TeamToy hates IE <br/><br/>Use Chrome, Firefox or Safari please',
	'LOGIN_OK_NOTICE' => 'Login successful, forwarding...',

	'API_CONNECT_ERROR_NOTICE' => 'Can\'t connect to server, try later',
	'LOGIN_BAD_ARGS_NOTICE' => 'Bad Email address or password, try again',

	'ADMIN_ONLY_LOGIN' => 'Only Admin can visit this page , <a href="?c=guest&a=logout">sign in as admin first</a>',

	/* ======== HEADER NAV =========== */
	'INDEX_PAGE' => 'Home',
	'TEAM_FEED' => 'Feed',
	'TEAM_MEMBER' => 'Members',
	'INBOX' => 'Inbox',
	'PLUGIN_LIST' => 'Plugins',
	'CHECK_NEW_VERSION' => 'Check new version',
	'MESSAGE_SOUND' => 'Message alert',
	'UPDATE_PROFILE' => 'Profile',
	'UPDATE_AVATAR' => 'Avatar',
	'UPDATE_PASSWORD' => 'Change password',
	'LOGIN_VIA_QR_CODE' => 'QRCode login',

	/* ======== Dashboard =========== */
	'DATA_LOAD_ERROR'=>'Data loading error, try later',
	'DB_UPGRADE_SUCCESS'=>'Upgraded successfully, <a href="?c=dashboard">refresh to enter new version</a>',
	'CODE_UPGRADE_ONLY_ADMIN'=>'Only admin can upgrade',
	'CODE_UPGRADE_ALREADY_LATEST'=>'Already the latest version',
	'CODE_UPGRADE_ERROR'=>'Upgrade failed, try again later',
	'CODE_UPGRADE_SUCCESS_DB_UPGRADE'=>'Code upgraded successfully, <a href="%s">click here to upgrade database</a>',
	'CODE_UPGRADE_SUCCESS'=>'Upgraded successfully, <a href="?c=inbox">refresh to enter new version</a>',
	'CODE_UPGRADE_CANNOT_CONNECT'=>'Can\'t connect to upgrade server, try again later',

	'AVATAR_UPLOAD_ERROR'=>'File upload error, try uploading again please',
	'AVATAR_UPDATE_SUCCESS'=>'<a href="?c=buddy">Avatar updated. If you still see your old avator, refresh your browser</a>',
	'AVATAR_UPDATE_ERROR'=>'Avatar update error, error code -%s，error info -%s',

	'API_MESSAGE_SAVE_DATA_ERROR' => 'Can\'t save data',
	'API_MESSAGE_CANNOT_CONNECT' => 'Can\'t connect to API server',


	'API_MESSAGE_DATABASE_ERROR' => 'Database error',
	
	'API_MESSAGE_ONLY_ADMIN' => 'Only admin can perform this action',
	'API_MESSAGE_USER_CLOSED_BY_ADMIN' => 'This account has been closed by admin',

	'API_MESSAGE_BAD_ACCOUNT' => 'Bad Email or password',
	'API_MESSAGE_CANNOT_RESET_OWN_PASSWORD' => 'You can\'t reset your own password',

	'API_MESSAGE_UPGARDE_INFO_DATA_ERROR' => 'There are some problems with the data fetched from upgrade server',
	'API_MESSAGE_UPGARDE_ALREADY_LATEST' => 'Already the latest version',

	'API_MESSAGE_UPGARDE_FILE_UNZIP_ERROR' => 'Failed to unzip upgrade package',
	'API_MESSAGE_UPGARDE_FILE_FETCH_ERROR' => 'Failed to download upgrade package',

	'API_MESSAGE_FETCH_SETTINGS_DATA_ERROR' => 'Failed to fetch user settings',

	'API_MESSAGE_CANNOT_CHANGE_PASSWORD' => 'Changing password has been forbidden',
	'API_MESSAGE_SAME_PASSWORD' => 'New password is the same as the old one',


	'API_MESSAGE_BAD_OPASSWORD' => 'Old password error',
	'API_MESSAGE_CANNOT_CHANGE_OWN_LEVEL' => 'Can\'t change your own user level',
	'API_MESSAGE_CANNOT_CLOSE_ONLY_ADMIN' => 'You will need at least ONE admin account! Can\'t close the ONLY admin',


	'API_MESSAGE_USER_NOT_EXISTS' => 'Account does not exist',
	'API_MESSAGE_ACCOUNT_CLOSED' => '%s closed user [%s]',
	'API_MESSAGE_USER_LEVEL_UPDATED' => '%s changed user [%s] to user level %s',


	'API_MESSAGE_TODO_EXISTS' => 'Same TODO already existed',
	'API_MESSAGE_EMPTY_RESULT_DATA' => 'Data does not exist',

	'API_MESSAGE_CANNOT_REMOVE_OTHERS_COMMENT' => 'You can\'t delete other\'s comment',

	'API_MESSAGE_TODO_ASSIGN_TO_SELF' => 'You don\'t need to assign TODO to yourself',

	'API_MESSAGE_SPEAK_TO_SELF' => 'You can\'t send message to yourself',

	'API_MESSAGE_CANNOT_ASSIGN_OTHERS_TODO' => 'You can\'t assign other\'s TODO',


	'API_MESSAGE_CANNOT_UPDATE_OTHERS_TODO' => 'You can\'t update other\'s TODO',
	'API_MESSAGE_CANNOT_REMOVE_OTHERS_FEED' => 'You can\'t delete other\'s feed',

	'API_MESSAGE_TODO_ALREADY_FOLLOWED' => 'You have already followed this TODO',

	'API_MESSAGE_TODO_ALREADY_DELETE_LOCALLY' => 'This TODO has been removed locally',
	'API_MESSAGE_TODO_ALREADY_HAD_OTHER_ACTION' => 'This update has conflicts with data on the server',


	

	'API_TEXT_JOINT_TEAMTOY' => '%s joined TeamToy',
	'API_TEXT_NEW_VERSION' => 'TeamToy version %s is released',


	'API_TEXT_TODO_ADDED' => '%s added TODO [%s]',
	'API_TEXT_COMMENT_TODO_FOLLOWED' => '%s added a comment to the TODO you followed [%s]: %s',
	'API_TEXT_COMMENT_TODO_OWNED' => '%s added a comment to your TODO [%s]: %s',

	'API_TEXT_AT_IN_TODO_COMMENT' => '%s mentioned you in the comment of TODO [%s]: %s',
	'API_TEXT_AT_IN_CAST_COMMENT' => '%s mentioned you in the comment of feed [%s]: %s',

	'API_TEXT_COMMENT_TODO' => '%s added a comment to your TODO [%s]: %s',

	'API_TEXT_COMMENT_FEED_OWNED' => '%s added a comment to your feed [%s]: %s',
	'API_TEXT_COMMENT_FEED_IN' => '%s added a comment to the feed you participated [%s]: %s',

	'API_TEXT_ASSIGN_TODO' => 'assigned TODO',
	'API_TEXT_ASSIGN_TODO_TO_U' => '%s assigned a TODO [%s] to you',
	'API_TEXT_ASSIGN_TODO_FOLLOWED' => '%s assigned the TODO you followed[%s] to %s',

	'API_TEXT_ASSIGN_TODO_DETAIL' => '%s assigned TODO [%s] to %s',

	'API_TEXT_FINISH_TODO' => '%s finished TODO [%s]',
	'API_TEXT_FINISH_TODO_FOLLOWED' => '%s finished the TODO you followed [%s]',

	'API_TEXT_AT_IN_CAST' => '%s mentioned you in a broadcast message [%s]',
	'API_TEXT_ADD_CAST' => '%s sent a broadcast message [%s]',



	'API_TEXT_ALREADY_UPGARDE_TO' => 'Your TeamToy had been upgraded to version %s, <a href="%s">click to upgrade database</a>',


	'INPUT_CHECK_BAD_ARGS' => 'parameter error, %s cannot be empty',
	'INPUT_CHECK_BAD_EMAIL' => 'parameter error, bad email format',
	'INPUT_CHECK_EMAIL_EXISTS' => 'parameter error, email exists already',
	'INPUT_CHECK_BAD_ACTVECODE' => 'parameter error, activation code expired or does not exist',
	'INPUT_CHECK_BAD_HTYPE' => 'parameter error, bad HTYPE',


	'INPUT_CHECK_NO_OLDPASS' => 'parameter error, old password cannot be empty',
	'INPUT_CHECK_NO_NEWPASS' => 'parameter error, new password cannot be empty',
	'INPUT_CHECK_NO_TODO_TITLE' => 'parameter error, TODO title cannot be empty, click the todo text on the left to reload',

	'TODO_LOAD_ERROR' => 'Failed to load TODO, retry again please',
	'TODO_CREATED' => 'created TODO',

	'NEED_LOGIN' => 'This page needs you to <a href="?c=guest">sign in first</a> ',

	// ===================================================
	// buddy

	'MEMBER_PAGE_TITLE' => 'Members',
	'FEED_PAGE_TITLE' => 'Feeds',
	'INBOX_PAGE_TITLE' => 'Inbox',
	'INSTALL_PAGE_TITLE' => 'TeamToy Installation Guide',
	'PLUGIN_ADMIN_PAGE_TITLE' => 'Plugins',

	'PLUGIN_UPLOAD_FILE_ERROR_RETRY' => 'Failed to upload file, try uploading it again',
	'PLUGIN_GET_NAME_ERROR_RETRY' => 'Failed to fetch plugin name, a temporary name %s has been assigned. <a href="?c=pluglist">Click here to continue</a>',
	'PLUGIN_PACKAGE_FORMAT_ERROR' => 'Can\'t find core script app.php, <a href="?c=pluglist">please upload a plugin package in correct format</a>',
	'PLUGIN_CREATE_FOLDER_ERROR' => 'Failed to create plugin folder, please change the permission of your /plugin folder to writable, <a href="?c=pluglist">then try again</a>',


	'DATABASE_INIT_FINISHED' =>  'Database initiated successfully. <a href="/" target="new">Sign in</a> using [member@teamtoy.net] / [%s] and add more menbers' ,
	


	'FEED_LOAD_ERROR_RETRY' => 'Failed to load feed, please try again',
	'INSTALL_FINISHED' => 'API Server initiated successfully, <a href="?c=guest">you can now sign in as admin</a>',

	'BAD_ARGS' => 'Bad parameters',

	// view
	'SAVE_AS_AVATAR' => 'Save as avatar',
	'FIND_CHAT_HISTORY' => 'Search',
	'NEXT_PAGE' => 'Next page',
	'OLD_PASS' => 'Old password',
	'INPUT_OLD_PASS' => 'Enter old password',
	'NEW_PASS' => 'New Password',
	'INPUT_NEW_PASS' => 'Enter new password',
	'REPEATE_PASS' => 'Repeat',
	'REPEATE_PASS_EXPLAIN' => 'Repeat the new password',
	'UPDATE' => 'Update',
	'OK' => 'OK',

	'FIND_BY_NAME_OR_PINYIN' => 'Find by name',
	'SELECTED_PEOPLE' => 'Selected',
	'ONE_PEOPLE_LEAST' => 'You need to select at least ONE member.',

	'NAME' => 'Name',
	'NAME_INPUT_EXPLAIN' => 'can\'t be modified',
	'EMAIL' => 'Email',
	'EMAIL_INPUT_EXPLAIN' => 'can\'t be empty, will be notice via emails',
	'MOBILE' => 'Mobile',
	'MOBILE_INPUT_EXPLAIN' => 'can\'t be empty',
	'TEL' => 'Tel.',
	'TEL_INPUT_EXPLAIN' => 'enter your full telephone number',
	'EMPLOYEE_ID' => 'Employee ID',
	'EMPLOYEE_ID_INPUT_EXPLAIN' => 'for future integration with other company systems',
	'WEIBO_ID' => 'Weibo',
	'WEIBO_ID_INPUT_EXPLAIN' => 'Weibo ID',
	'DESP_TEXT' => 'Note',
	'DESP_TEXT_INPUT_EXPLAIN' => 'job title / other contact methods',

	'FEED_DETAIL_CLOSE' => 'hide feed details',
	'TODO_DETAIL_CLOSE' => 'hide todo details',
	'CLICK_TO_EDIT' => 'click to modify',
	'CANCEL' => 'Cancel',
	'SAVE' => 'Save',
	'SEND' => 'Send',
	'REPLY' => 'Reply',
	'COMMENT' => 'Comment',
	'ENTER_TODO_CONTENT' => 'Enter TODO content here',

	'TODO_FOLLOWED' => 'TODOs I\'ve followed',
	'TODO_MINE' => 'My TODOs',
	'MARK_ALL_TODO_DONE' => 'Mark all as done',
	'CLEAN_ALL_TODO_DONE' => 'Archieve all completed TODOs',

	// ======================
	// 团队成员页面
	// 
	'MEMBER_SEARCH' => 'Search',
	'MEMBER_ADD' => 'Add Members',
	'ADD' => 'Add',
	'MEMBER_SEARCH_KEYWORD_EXPLAIN' => 'name',
	'MEMBER_SEARCH_KEYWORD_EXPLAIN_SHORT' => 'name',
	'SEARCH' => 'Search',

	// ======================
	// 广播页面
	'SEND_CAST' => 'Broadcast',
	'SEND_CAST_TO_ALL_EXPLAIN' => 'Everyone will be notified',
	'SEND_CAST_EXPLAIN' => 'If you don\'t mention anyone, everyone will be notified ',
	'AT_TEXT' => '@person_to_mention',

	// ======================
	// 收件箱
	'INBOX_RECEIVE_SETTINGS' => 'Message Settings',
	'INBOX_RECEIVE_MESSAGE_VIA_MOBILE_CLIENT' => 'Mobile Client',
	'INBOX_ANDROID_LOGIN' => 'Android client',
	'INBOX_IOS_LOGIN' => 'iOS client',

	// ======================
	// 插件管理
	'MORE_PLUGINS' => 'More plugins',
	'UPLOAD_PLUGIN' => 'Upload plugins',
	'PLUGIN_NAME' => 'Name',
	'PLUGIN_DESP' => 'Desp.',
	'PLUGIN_VERSION' => 'Version',
	'PLUGIN_STATUS' => 'Status',
	'PLUGIN_ON' => 'Activated',
	'NO_AVAILABLE_PLUGIN' => 'no plugins available',
	

	// ======================
	// date display
	'DATE_FULL_FORMAT' => 'Y/n/j \a\t g:ia',
	'DATE_SHORT_FORMAT' => 'n/j \a\t g:ia',
	
	'DATE_RELATED_NOW' => 'just now',
	'DATE_RELATED_AFTER' => 'later',
	
	'DATE_RELATED_LESS_THAN_A_MINUTE' => 'in less than a minute',
	'DATE_RELATED_ONE_MINUTE' => 'a minute ago',
	'DATE_RELATED_SOME_MINUTES' => '%s minutes ago',
	'DATE_RELATED_ONE_HOUR' => 'an hour ago',
	'DATE_RELATED_SOME_HOURS' => '%s hours ago',

	'FROM_MOBILE_DEVICE' => '<a href="http://teamtoy.net/?c=download&type=mobile" target="_blank">from mobile</a>',
	'FROM_WEB_DEVICE' => '<a href="http://teamtoy.net/?c=download&type=web" target="_blank">from web</a>',

	
	'TEAMTOY_INTRO_TEXT' => 'TeamToy is a productivity tool designed for teams. It focuses on "Getting things done" and put everyone together. You can assign a TODO, @ somebody or send broadcast messages to a group, anytime & anywhere using our mobile client.',

	'ABOUT_VERSION_TEXT' => 'Version - %s',
	'ABOUT_SITE_TEXT' => 'Web Site - <a href="http://teamtoy.org" target="_blank" class="white">TeamToy.org</a>',
	'ABOUT_SUPPORT_TEXT' => 'Email - <a href="mailto:inbox@teamtoy.net" target="_blank" class="white">inbox@teamtoy.net</a>',

	'TEAMTOY_STAFF' => 'Staff',

	'ABOUT_STAFF_BLOCK' => '<p>Design & Develop - <a href="http://twitter.com/easychen" target="_blank">@EasyChen</a> </p>

	<p>English translate - <a href="http://twitter.com/easychen" target="_blank">@EasyChen</a> <a href="http://twitter.com/lazycai" target="_blank">@LazyCai</a> </p>

<p><a href="https://github.com/luofei614/teamtoy-board" target="_blank">Kanban Plugin - <a href="http://weibo.com/luofei614" target="_blank">@luofei614</a></p>
<p><a href="http://ttoy-plugin.imlibo.com/" target="_blank">TToy iOS Client</a> - <a href="http://weibo.com/imlibo" target="_blank">libo</a></p>
<p><a href="https://github.com/easychen/TeamToy-Pocket" target="_blank">TeamToyPocket Client(iOS/Android)</a> - <a href="http://twitter.com/easychen" target="_blank">@EasyChen</a></p>
',

	'LIST_LOAD_MORE' => 'Load more',

	'INPUT_COMMENT_CONTENT' => 'Enter comment here',


	'QRCODE_FOR_MOBILE_CLIENT' => 'QR code for mobile client',
	'QRCODE_FOR_QRCODE_LOGIN' => 'QR code for mobile sign in',
	'DOWNLOAD_MOBILE_CLIENT' => 'Download Mobile Client',
	'QRCODE_LOGIN' => 'Quick sign in',
	'SCAN_QRCODE_VIA_PHONE' => 'Scan with your phone',
	'SCAN_QRCODE_VIA_MOBILE_CLIENT' => 'Scan with <a href="http://teamtoy.net/?c=download&type=mobile" target="new">TeamToy moible client</a>',

	'UPLOAD' => 'Upload',
	'PLUGIN_INSTALL_WARNNING' => 'Please install only plugins from reliable sources. Please make sure your app.php is in the root directry of your Zip package.',



	'SET_AS_NORMAL_MEMBER' => 'Set as non-admin',
	'SET_AS_ADMIN' => 'Set as admin',
	'RESET_PASSWORD' => 'Reset password',

	'EDIT_MEMBER_GROUP' => 'Edit group',

	'DONOT_CHAT_TO_SELF' => 'You don\'t need to talk to yourself on a server, do you?',
	'CHATBOX_EXPLAIN_TEXT' => 'Type text here, press ENTER to send',

	'UPGRADE_NOW' => 'Upgrade now',

	'CLICK_TO_START_STOP_TODO' => 'toggle start/stop',
	'STAR_TODO' => 'STAR',

	'FOLLOW' => 'Following',
	'FOLLOWER' => 'Followers',

	'FINISH' => 'Done',
	'STAR' => 'Stared',
	'PRIVATE' => 'Private',
	'ASSIGN' => 'Assign',
	'DISCUSS' => 'Comments',

	'ADD_TODO' => 'Add TODO',
	'ADD_STAR' => 'Stared',
	'SLEF_ONLY' => 'Private',
	'FOR_SOMEONE' => 'To <i class="icon-user"></i> %s',

	'FOOTER_INFO' => '<a href="http://teamtoy.net" target="new">TeamToy</a> | <a href="http://ftqq.com/" target="new">Ftqq.com</a> &copy; 2008~%s',

	
	'JS_API_CALL_ERROR' => 'API error, try again later . Error code - %s, error info - %s',
	'JS_CANNOT_ASSIGN_PRIVATE_TODO' => 'You can\'t assign a private TODO ',
	'JS_SELECT_MEMBER_TO_ASSIGN' => 'choose the member to assign to',
	'JS_MARK_ALL_TODO_DONE_CONFIRM' => 'Are you sure to make all TODOs done?',
	'JS_REMOVE_ALL_TODO_DONE_CONFIRM' => 'Archieve all finished TODOs？',
	'JS_REMOVE_CAST_CONFIRM' => 'Remove this feed?',
	'JS_REMOVE_COMMENT_CONFIRM' => 'Remove this comment?',
	
	'JS_STOP_PLUGIN_CONFIRM' => 'All related feature will unavailable when %s plugin is deactivated. Continue?',
	'JS_ACCOUNT_CLOSE_CONFIRM' => 'Are you sure to close this account? This user will not be able to login to TeamToy anymore',

	'JS_RESET_PASSWORD_CONFIRM' => 'Reset %s\'s password?',


	'JS_TODO_CENTER_PAGE_TITLE' => 'TODO Detail',
	'JS_NEW_DM' => 'has new messages',

	'JS_NEW_VERSION' => 'has new version %s[%s]. Upgrade now?',
	'JS_ALREAD_LASTEST_VERSION' => 'Already the latest version',

	'JS_ALL_CANNOT_EMPTY' => 'All fields must be filled.',

	'JS_ADD_GRUOP_NAME' => 'add group name',

	'JS_CHAT_HISTORY_WITH_SOMEONE' => 'Chat history with %s',

	'JS_NOTICE_PREFIX' => 'You have ',
	'JS_NOTICE_NOTIFACTION' => '%s unread notices',
	'JS_NOTICE_DM' => '%s unread messages',

	'JS_OLD_PASSWORD_CANNOT_EMPTY' => 'Old password cannot be empty',
	'JS_NEW_PASSWORD_CANNOT_EMPTY' => 'New password cannot be empty',
	'JS_TWO_PASSWORDS_NOT_SAME' => 'Two passwords cannot be the same',

	'JS_PASSWORD_CHANGED' => 'Password updated successfully, use new password to sign in',
	'JS_API_CONNECT_ERROR' => 'Can\'t connect to server, try again later ',

	'JS_FILL_MOBILE_EMAIL_PLZ' => 'Email and mobile number cannot be empty',

	

	'JS_CAST_MENTION_EXPLAIN_ALL' => 'Everyone will be notified',
	'JS_CAST_MENTION_EXPLAIN_MENONTED' => 'Mentions will be notified',

	'JS_CANNOT_ADD_PRIVATE_TODO_TO_OTHERS' => 'Can\'t add private TODO to others',
	'JS_SELECT_MEMBER_TO_ADD' => 'Choose the member you want to add to',
	'JS_SELECT_MEMBER_TO_METION' => 'Choose members to mention',

	//'WEIBO_LINK' => 'https://twitter.com/search/users?q=%s',
	'WEIBO_LINK' => 'http://s.weibo.com/user/%s',

	'TTEST' => ''
);
