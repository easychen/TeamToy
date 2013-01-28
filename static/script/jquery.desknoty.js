;(function($) {

	$.desknoty = function(options) {

		var defaults = {
			icon: null,
			title: "",
			body: "",
			timeout: 5000,
			sticky: false,
			id: null,
			type: 'normal',
			url: '',
			dir: '',
			onClick: function() {},
			onShow: function() {},
			onClose: function() {},
			onError: function() {}
		}

		var p = this, noti = null;

		p.set = {}

		var init = function() {
			p.set = $.extend({}, defaults, options);
			if(isSupported) {
				if(window.webkitNotifications.checkPermission() != 0){
					getPermissions(init);
				} else {
					if(p.set.type === 'normal') createNoti();
					else if(p.set.type === 'html') createNotiHtml();
				}
			} else {
				alert("Desktop notifications are not supported!");
			}
		}

		var createNoti = function() {
			noti = window.webkitNotifications.createNotification(p.set.icon, p.set.title, p.set.body);
				if(p.set.dir) noti.dir = p.set.dir;
				if(p.set.onclick) noti.onclick = p.set.onclick;
				if(p.set.onshow) noti.onshow = p.set.onshow;
				if(p.set.onclose) noti.onclose = p.set.onclose;
				if(p.set.onerror) noti.onerror = p.set.onerror;
				if(p.set.id) noti.replaceId = p.set.id;
			noti.show();
			if(!p.set.sticky) setTimeout(function(){ noti.cancel(); }, p.set.timeout);
		}
		var createNotiHtml = function() {
			noti = window.webkitNotifications.createHTMLNotification(p.set.url);
				if(p.set.dir) noti.dir = p.set.dir;
				if(p.set.onclick) noti.onclick = p.set.onclick;
				if(p.set.onshow) noti.onshow = p.set.onshow;
				if(p.set.onclose) noti.onclose = p.set.onclose;
				if(p.set.onerror) noti.onerror = p.set.onerror;
				if(p.set.id) noti.replaceId = p.set.id;
			noti.show();
			if(!p.set.sticky) setTimeout(function(){ noti.cancel(); }, p.set.timeout);
		}

		var isSupported = function() {
			if (window.webkitNotifications)return true;
			else return false;
		}
		var getPermissions = function(callback) {
			window.webkitNotifications.requestPermission(callback);
		}
		init();
	}
})(jQuery);