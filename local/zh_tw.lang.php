<?php
$GLOBALS['language']['zh_tw'] = array
(
/* ==== All ====== */
'ACCOUNT_CLOSED' => '已關閉',
'ACCOUNT_SUPER_ADMIN' => '超級管理員',


'TEAMTOY_ABOUT' => '關於TeamToy',
'TEAMTOY_ABOUT_WITH_VERSION' => '關於TeamToy[V%s]',

'LOADING' => '正在載入',



/* ==== Login page ====== */
'LOGIN' => '登入',
'LOGIN_PAGE_TITLE' => '登入',
'LOGOUT' => '登出',
'EMAIL' => '電郵地址',
'PASSWORD' => '密碼',
'NO_IE_NOTICE' => '不支持IE系瀏覽器<br/><br/>請換用Chome、Firefox或者Safari',
'LOGIN_OK_NOTICE' => '成功登入，正在轉向中',
'API_CONNECT_ERROR_NOTICE' => '嘗試連接伺服器失敗，請稍後再試',
'LOGIN_BAD_ARGS_NOTICE' => '錯誤的Email地址或者密碼，請重試',

'ADMIN_ONLY_LOGIN' => '只有管理員才能進入此頁面，<a href="?c=guest&a=logout">請先用管理員賬戶登入</a>',

/* ======== HEADER NAV =========== */
'INDEX_PAGE' => '首頁',
'TEAM_FEED' => '團隊動態',
'TEAM_MEMBER' => '成員管理',
'INBOX' => '收件箱',
'PLUGIN_LIST' => '插件管理',
'CHECK_NEW_VERSION' => '版本升級',
'MESSAGE_SOUND' => '消息提示音',
'UPDATE_PROFILE' => '更新個人資料',
'UPDATE_AVATAR' => '更換頭像',
'UPDATE_PASSWORD' => '修改密碼',
'LOGIN_VIA_QR_CODE' => '通過二維碼登入',

/* ======== Dashboard =========== */
'DATA_LOAD_ERROR'=>'數據載入失敗，請稍後再試',
'DB_UPGRADE_SUCCESS'=>'更新完成，請<a href="?c=dashboard">用力刷新頁面以保證新代碼正常工作</a>',
'CODE_UPGRADE_ONLY_ADMIN'=>'只有管理員才能進行升級',
'CODE_UPGRADE_ALREADY_LATEST'=>'已經是最新版本',
'CODE_UPGRADE_ERROR'=>'升級失敗，請稍後再試',
'CODE_UPGRADE_SUCCESS_DB_UPGRADE'=>'代碼更新成功，請<a href="%s">點擊這裡升級數據表</a>',
'CODE_UPGRADE_SUCCESS'=>'成功更新，請<a href="?c=inbox">用力刷新頁面以保證新代碼正常工作</a>',
'CODE_UPGRADE_CANNOT_CONNECT'=>'聯網失敗，請稍後再試',

'AVATAR_UPLOAD_ERROR'=>'文件上傳錯誤，請重新上傳',
'AVATAR_UPDATE_SUCCESS'=>'<a href="?c=buddy">頭像更新成功，由於瀏覽器緩存的關係，您可能看到的還是舊頭像，可強制刷新或清空緩存。 </a>',
'AVATAR_UPDATE_ERROR'=>'頭像更新失敗，錯誤碼-%s，錯誤信息-%s',

'API_MESSAGE_SAVE_DATA_ERROR' => '無法存檔數據',
'API_MESSAGE_CANNOT_CONNECT' => '無法連接API伺服器',


'API_MESSAGE_DATABASE_ERROR' => '資料庫錯誤',

'API_MESSAGE_ONLY_ADMIN' => '只有管理員才能進行此操作',
'API_MESSAGE_USER_CLOSED_BY_ADMIN' => '該用戶已被管理員關閉',

'API_MESSAGE_BAD_ACCOUNT' => '錯誤的Email地址或密碼',
'API_MESSAGE_CANNOT_RESET_OWN_PASSWORD' => '不能重置自己的密碼',

'API_MESSAGE_UPGARDE_INFO_DATA_ERROR' => '從升級伺服器取回的數據不可用',
'API_MESSAGE_UPGARDE_ALREADY_LATEST' => '已經是最新版本',

'API_MESSAGE_UPGARDE_FILE_UNZIP_ERROR' => '解壓升級包失敗',
'API_MESSAGE_UPGARDE_FILE_FETCH_ERROR' => '下載升級包失敗',

'API_MESSAGE_FETCH_SETTINGS_DATA_ERROR' => '無法獲取設置數據',

'API_MESSAGE_CANNOT_CHANGE_PASSWORD' => '修改密碼功能已經被禁用',
'API_MESSAGE_SAME_PASSWORD' => '新密碼和原密碼相同',


'API_MESSAGE_BAD_OPASSWORD' => '原密碼錯誤',
'API_MESSAGE_CANNOT_CHANGE_OWN_LEVEL' => '不能修改自己的用戶等級',
'API_MESSAGE_CANNOT_CLOSE_ONLY_ADMIN' => '不能關閉系統唯一的一個管理員',


'API_MESSAGE_USER_NOT_EXISTS' => '用戶不存在',
'API_MESSAGE_ACCOUNT_CLOSED' => '%s關閉了賬號【%s】',
'API_MESSAGE_USER_LEVEL_UPDATED' => '%s將賬號【%s】的等級調整為%s',


'API_MESSAGE_TODO_EXISTS' => '相同的TODO已經存在',
'API_MESSAGE_EMPTY_RESULT_DATA' => '數據不存在',

'API_MESSAGE_CANNOT_REMOVE_OTHERS_COMMENT' => '不能刪除別人的評論',

'API_MESSAGE_TODO_ASSIGN_TO_SELF' => 'TODO不能轉讓給自己',

'API_MESSAGE_SPEAK_TO_SELF' => '不能向自己發送私信',

'API_MESSAGE_CANNOT_ASSIGN_OTHERS_TODO' => '不能轉讓別人的TODO',


'API_MESSAGE_CANNOT_UPDATE_OTHERS_TODO' => '不能更新別人的TODO',
'API_MESSAGE_CANNOT_REMOVE_OTHERS_FEED' => '不能刪除別人的動態',

'API_MESSAGE_TODO_ALREADY_FOLLOWED' => '已經關注了此TODO',

'API_MESSAGE_TODO_ALREADY_DELETE_LOCALLY' => '客戶端已經刪除了該TODO',
'API_MESSAGE_TODO_ALREADY_HAD_OTHER_ACTION' => '和雲端的新更新發生衝突',




'API_TEXT_JOINT_TEAMTOY' => '%s加入了TeamToy',
'API_TEXT_NEW_VERSION' => 'TeamToy%s版已經發布',


'API_TEXT_TODO_ADDED' => '%s添加了TODO【%s】',
'API_TEXT_COMMENT_TODO_FOLLOWED' => '%s評論了你關注的TODO【%s】：%s',
'API_TEXT_COMMENT_TODO_OWNED' => '%s評論了你的TODO【%s】: %s',

'API_TEXT_AT_IN_TODO_COMMENT' => '%s在TODO【%s】的評論中@了你：%s',
'API_TEXT_AT_IN_CAST_COMMENT' => '%s在動態【%s】的評論中@了你：%s',

'API_TEXT_COMMENT_TODO' => '%s評論了TODO【%s】：%s',

'API_TEXT_COMMENT_FEED_OWNED' => '%s評論了你的動態【%s】：%s',
'API_TEXT_COMMENT_FEED_IN' => '%s評論了你參與的動態【%s】：%s',

'API_TEXT_ASSIGN_TODO' => '轉讓了TODO',
'API_TEXT_ASSIGN_TODO_TO_U' => '%s向你轉讓了TODO【%s】',
'API_TEXT_ASSIGN_TODO_FOLLOWED' => '%s將你關注的TODO【%s】轉讓給了%s',

'API_TEXT_ASSIGN_TODO_DETAIL' => '%s將TODO【%s】轉讓給了%s',

'API_TEXT_FINISH_TODO' => '%s完成了TODO【%s】',
'API_TEXT_FINISH_TODO_FOLLOWED' => '%s完成了你關注的TODO【%s】',

'API_TEXT_AT_IN_CAST' => '%s在廣播【%s】中@了你',
'API_TEXT_ADD_CAST' => '%s發起了廣播【%s】',



'API_TEXT_ALREADY_UPGARDE_TO' => '您的TeamToy已經升級至%s版本，<a href="%s">請立即點擊這裡升級數據表</a>',


'INPUT_CHECK_BAD_ARGS' => '參數錯誤，%s不能為空',
'INPUT_CHECK_BAD_EMAIL' => '參數錯誤，EMail格式不正確',
'INPUT_CHECK_EMAIL_EXISTS' => '參數錯誤，EMail已經存在',
'INPUT_CHECK_BAD_ACTVECODE' => '參數錯誤，激活碼不存在或者已經過期',
'INPUT_CHECK_BAD_HTYPE' => '參數錯誤，HTYPE不正確',


'INPUT_CHECK_NO_OLDPASS' => '參數錯誤，原始密碼不能為空',
'INPUT_CHECK_NO_NEWPASS' => '參數錯誤，新密碼不能為空',
'INPUT_CHECK_NO_TODO_TITLE' => '參數錯誤，TODO標題不能為空，請點擊左側TODO重新載入後重試',

'TODO_LOAD_ERROR' => '加載TODO失敗，請重試',
'TODO_CREATED' => '創建了TODO',

'NEED_LOGIN' => '您訪問的頁面需要先<a href="?c=guest">登入</a>',

// ================================================ ===
// buddy

'MEMBER_PAGE_TITLE' => '團隊成員',
'FEED_PAGE_TITLE' => '團隊動態',
'INBOX_PAGE_TITLE' => '收件箱',
'INSTALL_PAGE_TITLE' => 'TeamToy安裝頁面',
'PLUGIN_ADMIN_PAGE_TITLE' => '插件管理',

'PLUGIN_UPLOAD_FILE_ERROR_RETRY' => '文件上傳錯誤，請重新上傳',
'PLUGIN_GET_NAME_ERROR_RETRY' => '嘗試獲取插件名稱失敗，啟用%s作為臨時名稱，<a href="?c=pluglist">請點擊繼續</a>',
'PLUGIN_PACKAGE_FORMAT_ERROR' => '找不到插件執行腳本-app.php文件，<a href="?c=pluglist">請重新上傳格式正確的插件包</a>',
'PLUGIN_CREATE_FOLDER_ERROR' => '創建插件目錄失敗，請將plugin目錄設置為可寫後<a href="?c=pluglist">重試</a>',


'DATABASE_INIT_FINISHED' => '資料庫初始化成功，請使用【member@teamtoy.net】和【%s】<a href="/" target="new">登入並添加用戶</a>' ,



'FEED_LOAD_ERROR_RETRY' => '加載動態失敗，請重試',
'INSTALL_FINISHED' => 'API Server 已初始化完成，<a href="?c=guest">請使用管理賬號登入</a>',

'BAD_ARGS' => '錯誤的參數',

// view
'SAVE_AS_AVATAR' => '存檔為頭像',
'FIND_CHAT_HISTORY' => '查找聊天記錄',
'NEXT_PAGE' => '下一頁',
'OLD_PASS' => '原密碼',
'INPUT_OLD_PASS' => '輸入原密碼',
'NEW_PASS' => '新密碼',
'INPUT_NEW_PASS' => '輸入新密碼',
'REPEATE_PASS' => '重複下',
'REPEATE_PASS_EXPLAIN' => '再重複輸入一遍新密碼',
'UPDATE' => '更新',
'OK' => '確定',

'FIND_BY_NAME_OR_PINYIN' => '通過姓名或拼音查找',
'SELECTED_PEOPLE' => '已選同事',
'ONE_PEOPLE_LEAST' => '請至少選擇一位同事',

'NAME' => '姓名',
'NAME_INPUT_EXPLAIN' => '不可修改，請填寫真實姓名',
'EMAIL' => '郵箱',
'EMAIL_INPUT_EXPLAIN' => '必填、用於收發通知',
'MOBILE' => '手機',
'MOBILE_INPUT_EXPLAIN' => '必填、如136****',
'TEL' => '分機',
'TEL_INPUT_EXPLAIN' => '請填寫完整號碼',
'EMPLOYEE_ID' => '工號',
'EMPLOYEE_ID_INPUT_EXPLAIN' => '為接入公司其他系統預留',
'WEIBO_ID' => '微博',
'WEIBO_ID_INPUT_EXPLAIN' => '微博暱稱',
'DESP_TEXT' => '備註',
'DESP_TEXT_INPUT_EXPLAIN' => '崗位職責/其他聯繫方式',

'FEED_DETAIL_CLOSE' => '收起動態詳情',
'TODO_DETAIL_CLOSE' => '收起TODO詳情',
'CLICK_TO_EDIT' => '點擊修改',
'CANCEL' => '取消',
'SAVE' => '存檔',
'SEND' => '發送',
'REPLY' => '回复',
'COMMENT' => '評論',
'ENTER_TODO_CONTENT' => '請輸入TODO內容',

'TODO_FOLLOWED' => '我關注的TODO',
'TODO_MINE' => '我的TODO',
'MARK_ALL_TODO_DONE' => '全部標記為完成',
'CLEAN_ALL_TODO_DONE' => '清除所有已完成TODO',

// ======================
// 團隊成員頁面
//
'MEMBER_SEARCH' => '搜索成員',
'MEMBER_ADD' => '添加成員',
'ADD' => '添加',
'MEMBER_SEARCH_KEYWORD_EXPLAIN' => '輸入姓名或拼音',
'MEMBER_SEARCH_KEYWORD_EXPLAIN_SHORT' => '姓名或拼音',
'SEARCH' => '搜索',

// ======================
// 廣播頁面
'SEND_CAST' => '發起廣播',
'SEND_CAST_TO_ALL_EXPLAIN' => '所有人都會收到通知',
'SEND_CAST_EXPLAIN' => '如果你只想特定的人收到通知，可以使用@ 進行點名；未進行點名的廣播將發送給所有人',
'AT_TEXT' => '@點名',

// ======================
// 收件箱
'INBOX_RECEIVE_SETTINGS' => '消息推送設置',
'INBOX_RECEIVE_MESSAGE_VIA_MOBILE_CLIENT' => '通過手機客戶端接收通知',
'INBOX_ANDROID_LOGIN' => '使用Android客戶端登入',
'INBOX_IOS_LOGIN' => '使用iOS客戶端登入',

// ======================
// 插件管理
'MORE_PLUGINS' => '更多插件',
'UPLOAD_PLUGIN' => '上傳插件',
'PLUGIN_NAME' => '名稱',
'PLUGIN_DESP' => '簡介',
'PLUGIN_VERSION' => '版本',
'PLUGIN_STATUS' => '狀態',
'PLUGIN_ON' => '啟用',
'NO_AVAILABLE_PLUGIN' => '沒有可用的插件',


// ======================
// date display
'DATE_FULL_FORMAT' => 'Y年n月j日 H:i',
'DATE_SHORT_FORMAT' => 'n月j日 H:i',

'DATE_RELATED_NOW' => '剛剛',
'DATE_RELATED_AFTER' => '以後',

'DATE_RELATED_LESS_THAN_A_MINUTE' => '不到1分鐘',
'DATE_RELATED_ONE_MINUTE' => '1分鐘前',
'DATE_RELATED_SOME_MINUTES' => '%s分鐘前',
'DATE_RELATED_ONE_HOUR' => '1小時前',
'DATE_RELATED_SOME_HOURS' => '%s小時前',

'FROM_MOBILE_DEVICE' => '<a href="http://teamtoy.net/?c=download&type=mobile" target="_blank">來自行動版</a>',
'FROM_WEB_DEVICE' => '<a href="http://teamtoy.net/?c=download&type=web" target="_blank">來自網頁版</a>',


'TEAMTOY_INTRO_TEXT' => 'TeamToy是為創新團隊設計的效率工具，它以【事】為核心，打通了團隊每個人的工作，讓你可以輕鬆對其他同事進行評論、求助、點名和廣播；借助移動客戶端，讓你和同事隨時隨地“在一起”。 ',

'ABOUT_VERSION_TEXT' => '版本 - %s',
'ABOUT_SITE_TEXT' => '官方網站- <a href="http://teamtoy.org" target="_blank" class="white">TeamToy.org</a>',
'ABOUT_SUPPORT_TEXT' => '客戶支持QQ群- 166762540',

'TEAMTOY_STAFF' => '開發團隊',
'ABOUT_STAFF_BLOCK' => '<p>主平台設計、開發- <a href="http://weibo.com/easy" target="_blank">@Easy</a> </p>

<p>客戶支持- <a href="http://weibo.com/131417999" target="_blank">@欧耶</a></p>

<p><a href="https://github.com/luofei614/teamtoy-board" target="_blank">看板插件- <a href="http://weibo.com/luofei614" target="_blank ">@luofei614</a></p>
<p><a href="http://ttoy-plugin.imlibo.com/" target="_blank">TToy客戶端</a> - <a href="http://weibo.com/imlibo" target="_blank">@这是李博</a></p>
<p><a href="https://github.com/easychen/TeamToy-Pocket" target="_blank">TeamToyPocket客戶端</a> - <a href="http://weibo.com/easy " target="_blank">@Easy</a></p>
<p>- 感謝您的一路相伴，感謝開源讓我們走得更遠-</p>',

'LIST_LOAD_MORE' => '載入更多',

'INPUT_COMMENT_CONTENT' => '請輸入評論內容',


'QRCODE_FOR_MOBILE_CLIENT' => '客戶端下載二維碼',
'QRCODE_FOR_QRCODE_LOGIN' => '快捷登入二維碼',
'DOWNLOAD_MOBILE_CLIENT' => '客戶端下載',
'QRCODE_LOGIN' => '快捷登入',
'SCAN_QRCODE_VIA_PHONE' => '請用手機掃描',
'SCAN_QRCODE_VIA_MOBILE_CLIENT' => '請用<a href="http://teamtoy.net/?c=download&type=mobile" target="new">TeamToy客戶端</a>掃描',

'UPLOAD' => '上傳',
'PLUGIN_INSTALL_WARNNING' => '請只安裝可信任來源的插件，ZIP包根目錄下直接放app.php，無需再用子目錄。 ',



'SET_AS_NORMAL_MEMBER' => '降為普通用戶',
'SET_AS_ADMIN' => '升為管理員',
'RESET_PASSWORD' => '重置密碼',

'EDIT_MEMBER_GROUP' => '編輯用戶所屬分組',

'DONOT_CHAT_TO_SELF' => '親，自言自語就不用發到伺服器啦~',
'CHATBOX_EXPLAIN_TEXT' => '輸入聊天文字，按回車鍵送出',

'UPGRADE_NOW' => '立刻升級',

'CLICK_TO_START_STOP_TODO' => '點擊切換進行狀態',
'STAR_TODO' => '星標操作',

'FOLLOW' => '關注',
'FOLLOWER' => '誰在關注',

'FINISH' => '完成',
'STAR' => '星標',
'PRIVATE' => '私密',
'ASSIGN' => '轉讓',
'DISCUSS' => '討論',

'ADD_TODO' => '添加TODO',
'ADD_STAR' => '加星',
'SLEF_ONLY' => '僅自己可見',
'FOR_SOMEONE' => '給<i class="icon-user"></i> %s',

'FOOTER_INFO' => '<a href="http://teamtoy.net" target="new">TeamToy</a> - <a href="http://ftqq.com/" target="new" >方糖氣球</a> 榮譽出品&copy; 2008~%s',


'JS_API_CALL_ERROR' => 'API調用錯誤，請稍後再試。錯誤號%s 錯誤信息 %s',
'JS_CANNOT_ASSIGN_PRIVATE_TODO' => '私有TODO不能轉讓哦',
'JS_SELECT_MEMBER_TO_ASSIGN' => '選擇要轉讓的同事',
'JS_MARK_ALL_TODO_DONE_CONFIRM' => '確定要將所有TODO都標記為完成么？不准偷懶哦！ ',
'JS_REMOVE_ALL_TODO_DONE_CONFIRM' => '確定清除所有已完成的TODO？ ',
'JS_REMOVE_CAST_CONFIRM' => '廣播刪除後不可恢復，繼續？ ',
'JS_REMOVE_COMMENT_CONFIRM' => '確定刪除這條評論？ ',

'JS_STOP_PLUGIN_CONFIRM' => '停用%s插件後相關的功能將不可用，繼續？ ',
'JS_ACCOUNT_CLOSE_CONFIRM' => '確定要關閉該用戶麼？關閉後此用戶資料將保留，但不能登入系統',

'JS_RESET_PASSWORD_CONFIRM' => '確定要重置%s的密碼？ ',


'JS_TODO_CENTER_PAGE_TITLE' => 'TODO詳情',
'JS_NEW_DM' => '有新的私信啦',

'JS_NEW_VERSION' => '有新的版本%s[%s]。升級到最新版？ ',
'JS_ALREAD_LASTEST_VERSION' => '當前系統已經是最新了',

'JS_ALL_CANNOT_EMPTY' => '所有字段均為必填項，請認真填寫',

'JS_ADD_GRUOP_NAME' => '添加分組名稱',

'JS_CHAT_HISTORY_WITH_SOMEONE' => '我和%s的聊天記錄',

'JS_NOTICE_PREFIX' => 'TeamToy有',
'JS_NOTICE_NOTIFACTION' => '%s條未讀通知',
'JS_NOTICE_DM' => '%s條未讀私信',

'JS_OLD_PASSWORD_CANNOT_EMPTY' => '原密碼不能為空',
'JS_NEW_PASSWORD_CANNOT_EMPTY' => '新密碼不能為空',
'JS_TWO_PASSWORDS_NOT_SAME' => '兩次輸入的密碼不一致',

'JS_PASSWORD_CHANGED' => '密碼修改成功，請使用新密碼登入',
'JS_API_CONNECT_ERROR' => '伺服器通信失敗，請稍後再試',

'JS_FILL_MOBILE_EMAIL_PLZ' => 'Email和手機號都是必填項',



'JS_CAST_MENTION_EXPLAIN_ALL' => '所有人都會收到通知',
'JS_CAST_MENTION_EXPLAIN_MENONTED' => '被點名的人會收到通知',

'JS_CANNOT_ADD_PRIVATE_TODO_TO_OTHERS' => '私有TODO不能添加給別人',
'JS_SELECT_MEMBER_TO_ADD' => '點擊你要加TODO的同事',
'JS_SELECT_MEMBER_TO_METION' => '請選擇你要點名的同事',

'WEIBO_LINK' => 'http://s.weibo.com/user/%s',

'TTEST' => ''
);