$.ajaxSetup
({
  dataType: "text"
});

/*
send form data via ajax and return the data to callback function 
*/
function send_form( name , func )
{
	var url = $('#'+name).attr('action');
	
	var params = {};
	$.each( $('#'+name).serializeArray(), function(index,value) 
	{
		params[value.name] = value.value;
	});
	
	
	$.post( url , params , func );	
}

/*
send form data via ajax and show the return content to pop div 
*/

function send_form_pop( name )
{
	return send_form( name , function( data ){ show_pop_box( data ); } );
}

/*
send form data via ajax and show the return content in front of the form 
*/
function send_form_in( name )
{	
	return send_form( name , function( data ){ set_form_notice( name , data ) } );
}


function set_form_notice( name , data )
{
	data = '<span class="label label-important">' + data + '</span>';
	
	if( $('#form_'+name+'_notice').length != 0 )
	{
		$('#form_'+name+'_notice').html(data);
	}
	else
	{
		var odiv = $( "<div class='form_notice'></div>" );
		odiv.attr( 'id' , 'form_'+name+'_notice' );
		odiv.html(data);
		$('#'+name).prepend( odiv );
	} 
	
}


function show_pop_box( data , popid )
{
	if( popid == undefined ) popid = 'lp_pop_box'
	//console.log($('#' + popid) );
	if( $('#' + popid).length == 0 )
	{
		var did = $('<div><div id="' + 'lp_pop_container' + '"></div></div>');
		did.attr( 'id' , popid );
		did.css( 'display','none' );
		$('body').prepend(did);
	} 
	
	if( data != '' )
		$('#lp_pop_container').html(data);
	
	var left = ($(window).width() - $('#' + popid ).width())/2;
	
	$('#' + popid ).css('left',left);
	$('#' + popid ).css('display','block');
}

function hide_pop_box( popid )
{
	if( popid == undefined ) popid = 'lp_pop_box'
	$('#' + popid ).css('display','none');
}


function remember()
{
	$("input.remember").each( function()
	{
		// read cookie
		if( $.cookie( 'tt2-'+$(this).attr('name')) == 1)
		{
			$(this).attr('checked','true');
		}

		// save cookie
		

		$(this).unbind('click');
		$(this).bind('click',function(evt)
		{
			if( $(this).is(':checked') )
				$.cookie( 'tt2-'+$(this).attr('name') , 1  );
			else
				$.cookie( 'tt2-'+$(this).attr('name') , 0  );
		});

	});

}

function namecard()
{
	 $('.namecard').tooltipster
	 ({
	 	'interactive':true,
	 	'functionBefore':load_user_tooltips
     });
	/*
	$("a.namecard").each( function()
	{
		
	});*/
}

function load_user_tooltips( origin , continueTooltip )
{
	$.ajax(
	{
        type: 'POST',
        url: '?c=dashboard&a=user_tooltips&uid='+origin.data('uid'),
        success: function(data)
        {
            origin.data('tooltipsterContent', data);
            continueTooltip();
        }
    });
}

function load_buddy()
{
	var url = '?c=buddy&a=data' ;
	var params = { };
	$.post( url , params , function( data )
	{
		// add content to list
		$('#buddy_list').html(data);
		//buddy_click();
		//bind_feed();
		done();
		
	} );
	doing();
}

function load_feed( max_id )
{
	var url = '?c=feed&a=data' ;
	var params = { 'max_id':max_id };
	$.post( url , params , function( data )
	{
		// add content to list
		$('#feed_list li.more').remove();
		$('#feed_list').append(data);
		bind_feed();
		namecard();
		done();
		
	} );
	doing();
}

function load_inbox( max_id )
{
	var url = '?c=inbox&a=data' ;
	var params = { 'max_id':max_id };
	$.post( url , params , function( data )
	{
		// add content to list
		$('#notice_list li.more').remove();
		$('#notice_list').append(data);
		bind_notice();
		namecard();
		done();
		$.post( '?c=inbox&a=mark_read' , {}  );
	} );
	doing();
}

function load_todo( type )
{
	var url = '?c=dashboard&a=todo_data&type=' + type ;
	var params = {};
	$.post( url , params , function( data )
	{
		// add content to list
		$('#todo_list_'+type).html(data);

		// bind event
		if( type != 'follow' )
			bind_todo();
		else
			bind_follow_todo();

		done();
	} );

	doing();	

}

function todo_add( text , private , star , uid )
{
	var url = '?c=dashboard&a=todo_add' ;
	if( private == 1 ) is_public = 0 ;
	else is_public = 1;

	if( star == 1 ) is_star = 1 ;
	else is_star = 0;


	var params = { 'text' : text , 'is_public' : is_public , 'is_star' : is_star , 'uid' : uid  };
	$.post( url , params , function( data )
	{
		var data_obj = $.parseJSON( data );
		 
		if( data_obj.err_code == 0 )
		{
			if( data_obj.data.other != 1 )
			{
				if( is_star == 0 )
					$('#todo_list_normal').prepend( $(data_obj.data.html) );
				else
					$('#todo_list_star').prepend( $(data_obj.data.html) );
			
				bind_todo();
			}
			

			$('#todo_form [name=content]').val('');
		}
		else
		{
			alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
		}

		done();
	} );

	doing();
}


function todo_public( tid , type )
{
	var url = '?c=dashboard&a=todo_public' ;
	var params = { 'tid' : tid  , 'type' : type };
	$.post( url , params , function( data )
	{
		var data_obj = $.parseJSON( data );
		 
		if( data_obj.err_code == 0 )
		{
			if( type == 'public' )
			{
				$("ul.gbox li.private").removeClass('private').addClass('public');
				bind_gbox( tid );
				$("#t-"+tid).removeClass('red').addClass('blue');
			}	
			else
			{
				$("ul.gbox li.public").removeClass('public').addClass('private');
				bind_gbox( tid );
				$("#t-"+tid).removeClass('blue').addClass('red');
			}
				
		}
		else
		{
			alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
		}

		done();
	} );

	doing();
}

function todo_forward( tid , url )
{
	if( $('#t-'+tid).hasClass('red') ) return alert('私有TODO不能转让哦~');
	else
	{
		show_float_box( '选择要转让的同事' , url );
		// $('#people_box').modal({ 'show':true,'remote':url });
	} 
}

// bind evt
function bind_gbox( tid , is_public )
{
	$(".gbox li.public a").unbind('click');
	$(".gbox li.public a").bind('click',function(evt)
	{
		todo_public( tid , 'private'  );
	});

	$(".gbox li.private a").unbind('click');
	$(".gbox li.private a").bind('click',function(evt)
	{
		todo_public( tid , 'public'  );
	});

	$(".gbox li.star.public a").unbind('click');
	$(".gbox li.star.public a").bind('click',function(evt)
	{
		todo_star( tid , 'remove' , 1 );
	});

	$(".gbox li.star.private a").unbind('click');
	$(".gbox li.star.private a").bind('click',function(evt)
	{
		todo_star( tid , 'remove' , 0 );
	});

	$(".gbox li.nostar.pri a").unbind('click');
	$(".gbox li.nostar.pri a").bind('click',function(evt)
	{
		todo_star( tid , 'add' , 0 );
	});

	$(".gbox li.nostar.pub a").unbind('click');
	$(".gbox li.nostar.pub a").bind('click',function(evt)
	{
		todo_star( tid , 'add' , 1 );
	});

		

	$(".gbox li.follow a").unbind('click');
	$(".gbox li.follow a").bind('click',function(evt)
	{
		todo_unfollow( tid  );
	});

	$(".gbox li.nofollow a").unbind('click');
	$(".gbox li.nofollow a").bind('click',function(evt)
	{
		todo_follow( tid  );
	});

}

function todo_star( tid , type , is_public )
{
	var url = '?c=dashboard&a=todo_star' ;
	var params = { 'tid' : tid  , 'type' : type };
	$.post( url , params , function( data )
	{
		done();

		var data_obj = $.parseJSON( data );
		 
		if( data_obj.err_code == 0 )
		{
			if( type == 'add' )
			{
				if( is_public == 1 )
					$("ul.gbox li.nostar").removeClass('nostar pub pri').addClass('star public');
				else
					$("ul.gbox li.nostar").removeClass('nostar pub pri').addClass('star private');

				$('#todo_list_star').prepend( $("#t-"+tid) );
				bind_todo();
				bind_gbox( tid );
			}	
			else
			{
				if( is_public == 1 )
					$("ul.gbox li.star").removeClass('public private star').addClass('nostar pub');
				else
					$("ul.gbox li.star").removeClass('public private star').addClass('nostar pri');
				$('#todo_list_normal').prepend( $("#t-"+tid) );
				bind_todo();
				bind_gbox( tid );

			}
				

		}
		else
		{
			alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
		}

		
	} );	
	doing();
}

function todo_all_done()
{
	if( confirm( '确定要将所有TODO都标记为完成么？不准偷懒哦！' ) )
	{
		var url = '?c=dashboard&a=todo_all_done' ;
		var params = { };
		$.post( url , params , function( data )
		{
			var data_obj = $.parseJSON( data );
			 
			if( data_obj.err_code == 0 )
			{
				load_todo('normal');
				load_todo('star');
				load_todo('done');
			}
			else
			{
				alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
			}

			done();
		} );
		doing();
	}
}

function todo_clean()
{
	if( confirm( '确定清除所有已完成的TODO？' ) )
	{
		var url = '?c=dashboard&a=todo_clean' ;
		var params = { };
		$.post( url , params , function( data )
		{
			var data_obj = $.parseJSON( data );
			 
			if( data_obj.err_code == 0 )
			{
				load_todo('done');
			}
			else
			{
				alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
			}

			done();
		} );

		doing();

	}
}

// $("li#fid-"+fid+" span.cnt").text( parseInt( $("li#fid-"+fid+" span.cnt").text() ) + 1 );
function feed_remove( fid )
{
	if( confirm( '广播删除后不可恢复，继续？' ) )
	{
		var url = '?c=feed&a=feed_remove' ;
		var params = { 'fid' : fid  };
		$.post( url , params , function( data )
		{
			var data_obj = $.parseJSON( data );
			 
			if( data_obj.err_code == 0 )
			{
				$('#fid-'+fid).remove();
			}
			else
			{
				alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
			}
			done();
		} );
		doing();
	}

	

}

function feed_remove_comment( cid )
{
	if( confirm( '确定删除这条评论？' ) )
	{
		var url = '?c=feed&a=feed_remove_comment' ;
		var params = { 'cid' : cid  };
		$.post( url , params , function( data )
		{
			var data_obj = $.parseJSON( data );
			 
			if( data_obj.err_code == 0 )
			{
				$('#cid-'+cid).remove();
				var fid = data_obj.data.fid;

				var newcnt = parseInt( $("li#fid-"+fid+" span.cnt").text() ) - 1;
				$("li#fid-"+fid+" span.cnt").text( newcnt );
				if( newcnt <= 0 ) $("li#fid-"+fid+" a.ccount").hide();	
				
					
			}
			else
			{
				alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
			}
			done();
		} );
		doing();
	}

	

}


function todo_remove_comment( hid )
{
	if( confirm( '确定删除这条评论？' ) )
	{
		var url = '?c=dashboard&a=todo_remove_comment' ;
		var params = { 'hid' : hid  };
		$.post( url , params , function( data )
		{
			var data_obj = $.parseJSON( data );
			 
			if( data_obj.err_code == 0 )
			{
				$('#hid-'+hid).remove();
			}
			else
			{
				alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
			}
			done();
		} );
		doing();
	}

	

}

function todo_add_comment( tid , comment )
{
	var url = '?c=dashboard&a=todo_add_comment' ;
	var params = { 'tid' : tid  , 'text' : comment };
	$.post( url , params , function( data )
	{
		var data_obj = $.parseJSON( data );
		 
		if( data_obj.err_code == 0 )
		{
			$('#todo_history').prepend( $(data_obj.data.html) );
			$('#comment_text').val('');
		}
		else
		{
			alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
		}

		done();

	} );

	doing();
}

function feed_add_comment( fid , comment )
{
	// 
	var url = '?c=feed&a=feed_add_comment' ;
	var params = { 'fid' : fid  , 'text' : comment };
	$.post( url , params , function( data )
	{
		var data_obj = $.parseJSON( data );
		 
		if( data_obj.err_code == 0 )
		{
			$('#feed_comment').prepend( $(data_obj.data.html) );
			$('#fcomment_text').val('');
			$("li#fid-"+fid+" span.cnt").text( parseInt( $("li#fid-"+fid+" span.cnt").text() ) + 1 );
			$("li#fid-"+fid+" a.ccount").show();
		}
		else
		{
			alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
		}

		done();

	} );

	doing();
}


function todo_follow( tid )
{
	var url = '?c=dashboard&a=todo_follow&type=follow' ;
	var params = { 'tid' : tid };
	$.post( url , params , function( data )
	{
		var data_obj = $.parseJSON( data );
		 
		if( data_obj.err_code == 0 )
		{
			$('#t-'+tid).removeClass('nofollow').addClass('follow');
			bind_follow_todo();


			$(".gbox li.nofollow").removeClass('nofollow').addClass('follow');
			bind_gbox( tid );
		}
		else
		{
			alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
		}

		done();
	} );
	doing();
}

function todo_unfollow( tid )
{
	var url = '?c=dashboard&a=todo_follow&type=unfollow' ;
	var params = { 'tid' : tid };
	$.post( url , params , function( data )
	{
		var data_obj = $.parseJSON( data );
		 
		if( data_obj.err_code == 0 )
		{
			$('#t-'+tid).removeClass('follow').addClass('nofollow');
			bind_follow_todo();

			$(".gbox li.follow").removeClass('follow').addClass('nofollow');
			bind_gbox( tid );
		}
		else
		{
			alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
		}
		done();
	} );
	doing();
}

function todo_update( tid , text )
{
	var url = '?c=dashboard&a=todo_update' ;
	var params = { 'tid' : tid  , 'text' : text };
	$.post( url , params , function( data )
	{
		var data_obj = $.parseJSON( data );
		 
		if( data_obj.err_code == 0 )
		{
			$('#t-'+tid).replaceWith( $(data_obj.data.html) );
			bind_todo();
			show_todo_detail( tid );
		}
		else
		{
			alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
		}
		done();
	} );	
	doing();
}

function todo_assign( tid , uid )
{
	//alert(tid + '~' + uid );
	var url = '?c=dashboard&a=todo_assign' ;
	var params = { 'tid' : tid  , 'uid' : uid };
	$.post( url , params , function( data )
	{
		var data_obj = $.parseJSON( data );
		 
		if( data_obj.err_code == 0 )
		{
			close_float_box();
			$('#t-'+tid).remove();
			tdboard_close();
		}
		else
		{
			alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
		}
		done();
	} );	
	doing();


}

function mark_todo_done( tid )
{
	
	var url = '?c=dashboard&a=todo_done' ;
	var params = { 'tid' : tid };
	$.post( url , params , function( data )
	{
		var data_obj = $.parseJSON( data );
		 
		if( data_obj.err_code == 0 )
		{
			$('#todo_list_done').prepend($('#t-'+tid));
			bind_todo();
		}
		else
		{
			alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
		}
		done();
	} );	
	doing();
}

function mark_todo_undone( tid )
{
	var url = '?c=dashboard&a=todo_reopen' ;
	var params = { 'tid' : tid };
	$.post( url , params , function( data )
	{
		var data_obj = $.parseJSON( data );
		 
		if( data_obj.err_code == 0 )
		{
			$('#todo_list_normal').prepend($('#t-'+tid));
			bind_todo();
		}
		else
		{
			alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
		}
		done();
	} );
	doing();
}

function show_feed_detail( fid )
{
	// check todo_board exists or not
	// if not exists , create it
	//alert('detail-'+tid);
	close_all_side_board();

	if( $('#fdboard').length == 0 )
	{
		var did = $('<div></div>');
		did.attr( 'id' , 'fdboard' );
		did.css( 'display','none' );
		$('body').prepend(did);
	}
	else
	{
		$('#fdboard').html('');
		$('#fdboard').hide();
	}

	var xy = $("#side_container").position();
	$('#fdboard').css('top' , xy.top);
	$('#fdboard').css('left' , xy.left);
	
	$('#fdboard').fadeIn('slow');

	var url = '?c=feed&a=feed_detail' ;
	var params = { 'fid' : fid };
	$.post( url , params , function( data )
	{
		// add content to list
		$('#fdboard').html(data);
		$('#side_container').css('visibility','hidden');
		enable_at('fcomment_text');
		namecard();
		done();
	} );

	$('#fdboard').html('<h2 class="loading">Loading...</h2>');
	doing();	

}

function enable_at( name )
{
	//console.log( at_users );
	if( at_users.length > 0 )
	{
		$('#'+name).atWho( '@' , { 'data': at_users  } );
	}
}

function show_todo_detail_center( tid )
{
	show_float_box( 'TODO详情' , '?c=dashboard&a=todo_center&tid=' + tid );
}

function show_todo_detail( tid )
{
	// check todo_board exists or not
	// if not exists , create it
	//alert('detail-'+tid);
	close_all_side_board();

	if( $('#tdboard').length == 0 )
	{
		var did = $('<div></div>');
		did.attr( 'id' , 'tdboard' );
		did.css( 'display','none' );
		$('body').prepend(did);
	}
	else
	{
		$('#tdboard').html('');
		$('#tdboard').hide();
	}

	var xy = $("#side_container").position();
	$('#tdboard').css('top' , xy.top);
	$('#tdboard').css('left' , xy.left);
	
	$('#tdboard').fadeIn('slow');

	var url = '?c=dashboard&a=todo_detail' ;
	var params = { 'tid' : tid };
	$.post( url , params , function( data )
	{
		// add content to list
		$('#tdboard').html(data);
		$('#side_container').css('visibility','hidden');
		namecard();
		enable_at('comment_text');
		done();
	} );

	$('#tdboard').html('<h2 class="loading">Loading...</h2>');
	doing();	

}

function check_online()
{
	var url = '?c=dashboard&a=user_online' ;
	
	var params = {};
	$.post( url , params , function( data )
	{
		var data_obj = $.parseJSON( data );
		//console.log( data_obj ); 
		if( data_obj.err_code == 0 )
		{
			var uids = new Array();
			if(!data_obj.data) return false;
			for( var i = 0; i < data_obj.data.length ; i++ )
			{
				uids.push(parseInt(data_obj.data[i].uid));
			}

			//console.log( uids );
				
			if( uids.length > 0 )
			{
				$('#im_buddy_list li').each( function()
				{
					if( $.inArray( parseInt($(this).attr('uid')) , uids ) == -1 )
						$(this).removeClass('online');
					else
						$(this).addClass('online');
				} );
			}	
		}
	});		
}


function check_notice()
{
	var url = '?c=dashboard&a=user_unread' ;
	
	var params = {};
	$.post( url , params , function( data )
	{
		var data_obj = $.parseJSON( data );
		 
		if( data_obj.err_code == 0 )
		{
			if( data_obj.data.all && parseInt(data_obj.data.all) > 0 )
			{
				if( data_obj.data.notice && parseInt(data_obj.data.notice) > 0 )
					$('div.inbox img.reddot').css( 'visibility' , 'visible' );	

				var old_nid = parseInt($.cookie('last_nid'));
				var nid = parseInt(data_obj.data.nid);
				if( isNaN( old_nid )  ) old_nid = 0;


				var old_mid = parseInt($.cookie('last_mid'));
				var mid = parseInt(data_obj.data.mid);
				if( isNaN( old_mid )  ) old_mid = 0;

				var title = 'TeamToy有';
				var content = '';
				var send = false;

				if( parseInt(data_obj.data.notice) > 0 )
				{
					title += data_obj.data.notice+'条未读通知';
					content += data_obj.data.text;

					if( old_nid < 1  ||  old_nid < nid ) send = true;
				}

				if( parseInt(data_obj.data.message) > 0 )
				{
					title += data_obj.data.message+'条未读私信';
					if( old_mid < 1  ||  old_mid < mid ) send = true;
				}


				


				if( send )
				{
					$.titleAlert(title, 
					{
					    requireBlur:false,
					    stopOnFocus:true,
					    duration:10000,
					    interval:500 
					});	

					play_sound();

					if( window.webkitNotifications && window.webkitNotifications.checkPermission() == 0 )
					{
						var notification = window.webkitNotifications.createNotification
						(
		  					favicon,
		  					title,
		  					content
		  				);

		  				notification.onclick = function()
		  				{
		  					window.open(site_url);
		  				};

		  				if( !$.browser.mozilla )
		  					notification.onshow = function() { setTimeout(function() {notification.close()}, 15000)};

						notification.show();
					}

					
					if( parseInt(data_obj.data.notice) > 0 ) $.cookie('last_nid',nid);
					if( parseInt(data_obj.data.message) > 0 ) $.cookie('last_mid',mid);
				}

				
				
				
				
			}
			else
			{
				$('div.inbox img.reddot').css( 'visibility' , 'hidden' );	
			}

			// deal with im
			if( data_obj.data.message && parseInt(data_obj.data.message) > 0 )
			{
				alert_message( data_obj.data.uids.split( '|' ) );
			}
			else
			{
				blue_buddy_list();
			}
		}
		else
		{
			alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
		}
	} );
}

function alert_message( uids )
{
	$('#im_header').addClass('new_message');
	$.each( uids , function()
	{
		// 名字加红、提升到列表顶部
		$('#im_blist_'+this).addClass('new_message');
		var tmp = $('#im_blist_'+this);
		$('#im_blist_'+this).remove();
		$('#im_buddy_list').data('jsp').getContentPane().prepend( tmp );
		$('#im_buddy_list').data('jsp').reinitialise();
		$('#im_buddy_list').data('jsp').scrollToY(0);
		
		// 重新绑定事件
		$('#im_buddy_list li').unbind('click');
		$('#im_buddy_list li').bind( 'click' , function()
		{
			$('#imkeyword').val('');
			$('#imkeyword').trigger('keydown');
			show_im_box( $(this).attr('uid') );
		});

	} );
}

function update_im_order()
{
	//alert('in');
	var ouids = new Array();
	if( !kget('im_order') ) return true;

	ouids = kget('im_order').split('|');
	//ouids = ouids.reverse();
	for( var i = 0 ; i < ouids.length ; i++ )
	{
		var tmp = $('#im_blist_'+ouids[i]);
		$('#im_blist_'+ouids[i]).remove();
		$('#im_buddy_list').prepend( tmp );
	}
}

function save_im_order( uid )
{
	var ouids = new Array();
	if( kget('im_order') )
		ouids = kget('im_order').split('|');
	
	ouids.push(uid);
	ouids = ouids.unique();

	kset( 'im_order' , ouids.join('|') );
}



function blue_buddy_list()
{
	if( $( '#im_buddy_list li.user_line.new_message').length < 1 )
		if($('#im_header'))
			$('#im_header').removeClass('new_message');
}

function cast_send( text )
{
	//alert( text );
	var url = '?c=feed&a=cast' ;
	
	var params = { 'text' : text  };
	$.post( url , params , function( data )
	{
		var data_obj = $.parseJSON( data );
		 
		if( data_obj.err_code == 0 )
		{
			$('#feed_list').prepend( $(data_obj.data.html) );
			bind_feed();
			$('#cast_form [name=text]').val('');
		}
		else
		{
			alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
		}
		done();
	} );
	doing();
}



function tdboard_close()
{
	$('#tdboard').fadeOut('fast');
	$('#side_container').css('visibility','visible');
}

function fdboard_close()
{
	$('#fdboard').fadeOut('fast');
	$('#side_container').css('visibility','visible');
}

function bind_follow_todo()
{
	// this -- > a 
	// this.parentNode --> .todo_row
	// this.parentNode.parentNode ---> .todo_fav
	// this.parentNode.parentNode.parentNode -----> li	

	$('#todo_list_follow li a.item').each( function()
	{
		$(this.parentNode).unbind( 'click' );
		$(this.parentNode).bind( 'click' , function(evt)
		{
			evt.stopPropagation();
			show_todo_detail( $('#'+this.parentNode.parentNode.id).attr('tid') );
			return false;
		} );

		$('#'+this.parentNode.parentNode.parentNode.id).unbind('click');
		$('#'+this.parentNode.parentNode.parentNode.id).bind('click' , 	function( )
		{
			if( $(this).hasClass('nofollow') )
				todo_follow( $(this).attr('tid') );
			else
				todo_unfollow( $(this).attr('tid') );	

		});
		
		
		
		
	});
}

function bind_todo()
{
	//alert('in');
	$('li a.todo_play').unbind('click');
	$('li a.todo_play').bind('click' , function(evt)
	{
		var mtype;

		if( $(this.parentNode).hasClass('ing') )
			mtype = 'pause';
		else
			mtype = 'start';
		
		var tid = $(this).attr('tid');
		var url = '?c=dashboard&a=todo_start&tid=' + tid + '&type=' + mtype  ;
	
		var params = {};
		$.post( url , params , function( data )
		{
			var data_obj = $.parseJSON( data );
				 
			if( data_obj.err_code == 0 )
			{
				if( mtype == 'pause' )
				{
					$('#t-'+tid).removeClass('ing');
					console.log('remove class');
				}
					
				else
				{
					$('#t-'+tid).addClass('ing');
					console.log('add class');
				}
					
				// buddy_click();
				done();

			}
			else
			{
				alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
			}
		} );

		doing();
		evt.stopPropagation();
		
	});



	$('#todo_list_star li a.item,#todo_list_normal li a.item,#todo_list_done li a.item').each( function()
	{
		// this -- > a 
		// this.parentNode --> .todo_row
		// this.parentNode.parentNode ---> .todo_fav
		// this.parentNode.parentNode.parentNode -----> li		

		$(this.parentNode).unbind( 'click' );
		$(this.parentNode).bind( 'click' , function(evt)
		{
			evt.stopPropagation();
			show_todo_detail( $('#'+this.parentNode.parentNode.id).attr('tid') );
			return false;
		} );

		$(this.parentNode.parentNode).unbind( 'click' );
		$(this.parentNode.parentNode).bind( 'click' , function(evt)
		{
			evt.stopPropagation();
			
			if( this.parentNode.parentNode.id != 'todo_list_done' )
			{
				var is_public = 0;
				if( $('#'+this.parentNode.id).hasClass('blue') ) 
					is_public  = 1;

				if( this.parentNode.parentNode.id == 'todo_list_star' )
					todo_star( $('#'+this.parentNode.id).attr('tid')  , 'remove' , is_public );
				else
					todo_star( $('#'+this.parentNode.id).attr('tid')  , 'add' , is_public );
			}

			

			return false;
		} );
		


		
		$('#'+this.parentNode.parentNode.parentNode.id).unbind('click');
		$('#'+this.parentNode.parentNode.parentNode.id).bind('click' , 	function( )
		{
			if( this.parentNode.id == 'todo_list_done' )
			{
				mark_todo_undone( $(this).attr('tid') );
			}
			else
			{
				mark_todo_done( $(this).attr('tid') );  
				// 
			}	
				

		});
		
	});
	

}

function bind_feed()
{
	$('#feed_list li.todo .hotarea').each( function()
	{
		$(this).css({'cursor':'pointer'});

		$(this).unbind( 'click' );
		$(this).bind( 'click' , function(evt)
		{
			evt.stopPropagation();
			show_todo_detail( $('#'+this.parentNode.id).attr('tid') );
			return false;
		} );
	});

	$('#feed_list li.cast .hotarea').each( function()
	{
		$(this).css({'cursor':'pointer'});

		$(this).unbind( 'click' );
		$(this).bind( 'click' , function(evt)
		{
			evt.stopPropagation();
			show_feed_detail( $('#'+this.parentNode.id).attr('fid') );
			return false;
		} );
	});


}

function bind_notice()
{
	$('#notice_list li.todo').each( function()
	{
		$(this).css({'cursor':'pointer'});

		$(this).unbind( 'click' );
		$(this).bind( 'click' , function(evt)
		{
			evt.stopPropagation();
			show_todo_detail( $('#'+this.id).attr('tid') );
			return false;
		} );	
	});

	$('#notice_list li.cast').each( function()
	{
		$(this).css({'cursor':'pointer'});

		$(this).unbind( 'click' );
		$(this).bind( 'click' , function(evt)
		{
			evt.stopPropagation();
			show_feed_detail( $('#'+this.id).attr('fid') );
			return false;
		} );	
	});
}

function buddy_search()
{
	$('#buddy_key').bind( 'keyup keydown' , function(evt)
	{
		if( $('#buddy_key').val() != '' )
		{
			$('#buddy_list li.user').each(function()
			{
				if( ($(this).attr('pinyin').indexOf( $('#buddy_key').val() ) < 0) 
					&& ( $(this).attr('user').indexOf( $('#buddy_key').val() ) < 0 ))
				 	$(this).css('display','none');
				else 
					$(this).css('display','block');
			});
		}
		else
		{
			$('#buddy_list li.user').each(function()
			{
				$(this).css('display','block');
			});
		}

		
	});
}

function buddy_click()
{
	
	$('li.user').unbind('click');
	$('li.user').bind('click',function(evt)
	{
		$(this).toggleClass('selected');
		buddy_build_names();

		if( $("li.selected").length > 0 )
			$('#buddy_mulit_box').slideDown();
		else
			$('#buddy_mulit_box').slideUp();
		
	});
}

function buddy_build_names()
{
	$("#namelist").empty();
	$('li.user.selected').each( function()
	{
		$("#namelist").append( $('<li class="nameitem" uid="' + $(this).attr('uid') + '"><i class="icon-user"></i>'+ $(this).attr('user') +'</li>') )
	});
	
}

function cast_at_check()
{
	$('#cast_text').bind( 'keydown keyup' , function(evt)
	{
		if( /@/.test( $('#cast_text').val() ) ) $('#cast_user_tips').text('点名的人会收到通知');
		else $('#cast_user_tips').text('所有人都会收到通知');

		if( $('#cast_text').val() == '' ) $('#cast_form [type=submit]').attr('disabled',true);
		else  $('#cast_form [type=submit]').attr('disabled',false);
	});
}


function admin_user_on( uid )
{
	return admin_user( uid , 1 );
}

function admin_user_off( uid )
{
	return admin_user( uid , 0 );
}


function admin_user( uid , on )
{
	var url = '?c=buddy&a=admin_user&set=' + on  ;
	
	var params = { 'uid' : uid  };
	$.post( url , params , function( data )
	{
		var data_obj = $.parseJSON( data );
			 
		if( data_obj.err_code == 0 )
		{
			$('li#uid-'+uid).replaceWith( $(data_obj.data.html) );
			// buddy_click();
		}
		else
		{
			alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
		}
	} );
}

function plugin_turn( fold_name , name , obj )
{
	var on , doo ;
	if( $(obj).is(':checked') ) on = 1 ;
	else on = 0;

	if( on == 0 )
	{
		if( confirm( '停用'+name+'插件后相关的功能将不可用，继续？' ) )
			doo = 1
	}
	else doo = 1;

	if( doo == 1 )
	{
		var url = '?c=pluglist&a=turn&on=' + on + '&folder_name=' + fold_name ;
		var params = {};
		$.post( url , params , function( data )
		{
			var data_obj = $.parseJSON( data );
			 
			if( data_obj.err_code == 0 )
			{
				location='?c=pluglist';
			}
			else
			{
				alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
			}		
		}); 

	}
}

function save_password()
{
	if( $('#password_form [name=oldpassword]').val() == '' )
	{
		alert( '原密码不能为空' );
		return false;
	}

	if( $('#password_form [name=newpassword]').val() == '' )
	{
		alert( '新密码不能为空' );
		return false;
	}

	if( $('#password_form [name=newpassword]').val() != $('#password_form [name=newpassword2]').val() )
	{
		alert( '两次输入的密码不一致' );
		return false;
	}

	send_form( 'password_form' , function(data){ password_updated( data ); } )

}


function password_updated( data )
{
	var data_obj = $.parseJSON( data );
	 
	if( data_obj.err_code == 0 )
	{
		
		alert('密码修改成功，请使用新密码登入');
		close_float_box();
		location = '?c=guest&a=logout';
	}
	else
	{
		alert('服务器通信失败，请稍后再试'+data);
	}
}


function profile_updated( data )
{
	var data_obj = $.parseJSON( data );
	 
	if( data_obj.err_code == 0 )
	{
		
		close_float_box();
	}
	else
	{
		if( data_obj.err_code == 10006 )
			alert('Email和手机号都是必填项');
		else
			alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
	}
}


function admin_close_user( uid )
{
	if( confirm( '确定要关闭该用户么？关闭后此用户资料将保留，但不能登入系统' ) )
	{
		var url = '?c=buddy&a=user_close' ;
	
		var params = { 'uid' : uid  };
		$.post( url , params , function( data )
		{
			//console.log( data );
			var data_obj = $.parseJSON( data );
			//console.log( data_obj );
			 
			if( data_obj.err_code == 0 )
			{
				$('li#uid-'+uid).remove();
			}
			else
			{
				alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
			}
		} );	
	}

	
}


var at_users = new Array();

function load_im_buddy_list()
{
	var url = '?c=dashboard&a=im_buddy_list' ;
	
	var params = {};
	$.post( url , params , function( data )
	{
		var data_obj = $.parseJSON( data );
		if( data_obj.err_code == 0 )
		{
			$('#im_buddy_list').html( data_obj.data.html );
			update_im_order();
			
			$('#im_header').bind( 'click' ,  toggle_im );
			if( $.cookie('im_hide') == 1 ) toggle_im();
			$('#im').show();
			$('#im_buddy_list').jScrollPane();

			$('#im_buddy_list li').unbind('click');
			$('#im_buddy_list li').bind( 'click' , function()
			{
				$('#imkeyword').val('');
				$('#imkeyword').trigger('keydown');
				show_im_box( $(this).attr('uid') );
			});

			$('#imkeyword').bind('keyup keydown' , function(evt)
			{
				if( $('#imkeyword').val() != '' )
				{
					$('#im_buddy_list li.user_line').each(function()
					{
						if( ($(this).attr('pinyin').indexOf( $('#imkeyword').val() ) < 0) 
							&& ( $(this).attr('user').indexOf( $('#imkeyword').val() ) < 0 ))
						 	$(this).css('display','none');
						else 
							$(this).css('display','block');
					});
				}
				else
				{
					$('#im_buddy_list li.user_line').each(function()
					{
						$(this).css('display','block');
					});
				}

				
			});

			$('#im_buddy_list li.user_line').each(function()
			{
				at_users.push( {  'name':$(this).attr('user') 
								, 'pinyin':$(this).attr('pinyin') 
								, 'value':$(this).attr('user') 
								, 'id':$(this).attr('uid') 
								} );			
			});

			// add groups
			var url = '?c=buddy&a=groups' ;
			$.post( url , {} , function( data2 )
			{
				var data_obj2 = $.parseJSON( data2 );
				if( data_obj2 && data_obj2.err_code == 0 && data_obj2.data != null )
				{
					$.each(data_obj2.data , function(k,v)
					{
						at_users.push({'name':v});
					});
				}
			});


			if( $('#cast_text') ) enable_at('cast_text');
		}
		else
		{
			console.log('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
		}
	});	
}

var im_check_ref;

function show_im_box( uid )
{
	var url = '?c=dashboard&a=im_buddy_box&uid=' + uid ;
	
	var params = {};
	$.post( url , params , function( data )
	{
		var data_obj = $.parseJSON( data );
		if( data_obj.err_code == 0 )
		{
			save_im_order( uid );

			if( $('#im_box_'+uid).length > 0 )
				$('#im_box_'+uid).replaceWith( data_obj.data.html  );
			else
				$('#im_area_list').prepend( data_obj.data.html  );

			namecard();
			
			$('#im_area_list li').hide();
			$('#im_area_list li#im_box_'+uid).show();
			$('#im_box').show();

			$('#im_area_list li#im_box_'+uid+' .im_history').jScrollPane();
			$('#im_area_list li#im_box_'+uid+' .im_history').data('jsp').scrollToBottom();		

			$('#im_area_list li#im_box_'+uid+' .im_form_textarea').bind( 'keypress' , function(e)
			{
				if( e.which == 13 )
				{
					var text = $('#im_area_list li#im_box_'+uid+' .im_form_textarea').val();
					var url = '?c=dashboard&a=im_send&uid=' + uid + '&text=' + encodeURIComponent( text ) ;

					var params = {};
					$.post( url , params , function( data )
					{
						done();
						var data_obj = $.parseJSON( data );
						if( data_obj.err_code == 0 )
						{
							$('#im_area_list li#im_box_'+uid+' .im_history').data('jsp').getContentPane().append( data_obj.data.html );
							$('#im_area_list li#im_box_'+uid+' .im_history').data('jsp').reinitialise();
							$('#im_area_list li#im_box_'+uid+' .im_history').data('jsp').scrollToBottom();
							$('#im_area_list li#im_box_'+uid+' .im_form_textarea').val('');
							
							// alert('send text'++'TO'+$(this).attr('uid'));
						}
						else
						{
							alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
						}

						$('#im_area_list li#im_box_'+uid+' .im_form_textarea').attr('disabled',false);
					});		

					doing();
					$('#im_area_list li#im_box_'+uid+' .im_form_textarea').attr('disabled','disabled');
					
				}

			} );

			var url = '?c=dashboard&a=im_history&uid=' + uid ;
			var params = {};
			$.post( url , params , function( data )
			{
				//alert(data+'~in');
				//alert($('#im_area_list li#im_box_'+uid+' .im_history').data('jsp').getContentPane() + '~ini');
				$('#im_area_list li#im_box_'+uid+' .im_history').data('jsp').getContentPane().append( data );
				$('#im_area_list li#im_box_'+uid+' .im_history').data('jsp').reinitialise();
				$('#im_area_list li#im_box_'+uid+' .im_history').data('jsp').scrollToBottom();

				im_check_ref = setInterval( function(){ check_im( uid ); } , 1000*10 );
			});	

			//check_im( uid );
			$( '#im_blist_'+uid ).removeClass('new_message');
			blue_buddy_list();


			

		}
		else
		{
			console.log('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
		}
	});	

	//$('#im_area_list').html('<li>'+ uid +'</li>');
	//$('#im_box').show();

	//alert( uid );
}

function user_reset_password( uid , uname )
{
	if( confirm( '确定要重置'+uname+'的密码？' ) )
	{
		var url = '?c=dashboard&a=user_reset_password&uid=' + uid ;
		var params = {};
		$.post( url , params , function( data )
		{
			var data_obj = $.parseJSON( data );
			//console.log( data_obj );
			 
			if( data_obj.err_code == 0 )
			{
				// 显示新密码
				noty(
				{
					layout:'topRight',
					text:uname+'的密码已经被重置为' + data_obj.data.newpass,
					closeWith:['button'],
					buttons: [
				    {
				    	addClass: 'btn btn-primary btn-small', text: '关闭', onClick: function($noty) 
				    	{
				    		$noty.close()
				      	}
				    }]
				});
			}
			else
			{
				alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
			}
		});

	}
}

function check_im( uid )
{
	var url = '?c=dashboard&a=get_fresh_chat&uid=' + uid ;
	var params = {};
	$.post( url , params , function( data )
	{
		//alert(data+'~in');
		//alert($('#im_area_list li#im_box_'+uid+' .im_history').data('jsp').getContentPane() + '~ini');
		if( data )
		{
			$('#im_area_list li#im_box_'+uid+' .im_history').data('jsp').getContentPane().append( data );
			$('#im_area_list li#im_box_'+uid+' .im_history').data('jsp').reinitialise();
			$('#im_area_list li#im_box_'+uid+' .im_history').data('jsp').scrollToBottom();
			$.titleAlert("有新的私信啦", 
			{
			    requireBlur:false,
			    stopOnFocus:true,
			    duration:10000,
			    interval:500 
			});
			play_sound();	
		}  
		//im_check_ref = setInterval( function( uid ){ check_im( uid ); } , 100000 );
});	

}

function close_im_box()
{
	$('#im_box').hide();
	clearInterval( im_check_ref );
	//$('#im_area_list').html('');
}



function toggle_im()
{
	if( $('#im').hasClass('peep') )
	{
		$('#im').removeClass('peep');
		$('#im_swith').html('&minus;');
		$.cookie('im_hide' , 0 );
	}
	else
	{
		$('#im').addClass('peep');
		$('#im_swith').html('&plus;');
		$.cookie('im_hide' , 1 );
	}						
}

function check_version()
{
	var url = '?c=dashboard&a=check_version' ;
	
	var params = {};
	$.post( url , params , function( data )
	{
		var data_obj = $.parseJSON( data );
		 
		done();
		if( data_obj.err_code == 0 )
		{
			// error in ie , becoz .new 
			if( data_obj.data.new && parseInt( data_obj.data.new )  == 1 )
			{
				if( confirm( '有新的版本'+data_obj.data.version + '['+ data_obj.data.info +']。升级到最新版？' ) )
				{
					location = '?c=dashboard&a=upgrade';
				}
			}
			else
			{
				alert('当前版本已经是最新了');
			}
		}
		else
		{
			alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
		}
	} );
	doing();	
}

function user_added( data )
{
	var data_obj = $.parseJSON( data );
	//console.log( data_obj );
			 
	if( data_obj.err_code == 0 )
	{
		//$('li#uid-'+uid).remove();
		$('#buddy_list').append( $(data_obj.data.html) );
		
		$('html, body').animate({
                    scrollTop: $("footer").offset().top
                     }, 1000);

		$('#buddy_form [type=text]').val('');

		$('#buddy_form [type=password]').val('');

		//buddy_click();
		
	}
	else
	{
		if( data_obj.err_code == 100002 )
		{
			return alert('所有字段均为必填项，请认真填写');
		}
		else
		{
			alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
		}	

	}

		
}


function edit_tag( uid )
{
	$('#t-tags-'+uid).hide();
	$('#t-tags-link-'+uid).hide();
	$('#t-tags-edit-'+uid).show();
	
	if( $('#t-tags-input-'+uid+'_tag').length < 1 )
		$('#t-tags-input-'+uid).tagsInput({'defaultText':'添加分组名称'});
}

function save_tag( uid )
{
	var url = '?c=buddy&a=update_groups&uid='+uid+'&groups='+encodeURIComponent($('#t-tags-input-'+uid).val()) ;
	
	var params = {};
	$.post( url , params , function( data )
	{
		var data_obj = $.parseJSON( data );
		 
		done();
		if( data_obj.err_code == 0 )
		{
			$('#uid-'+uid).replaceWith( $(data_obj.data.html) );
		}
		else
		{
			alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
		}
	} );

	doing();	

}

function cancel_tag( uid )
{
	$('#t-tags-'+uid).show();
	$('#t-tags-link-'+uid).show();
	$('#t-tags-edit-'+uid).hide();
}

function show_im_all_history( uid , uname )
{
	show_float_box( '我和'+ uname +'的聊天记录' , '?c=dashboard&a=im_all&uid='+uid );
}

function im_next_btn()
{
	if( parseInt(im_his_more) == 1 )
	{
		$('#im_next_link').addClass('btn-primary');
		$('#im_next_link').removeClass('disable');
	}
	else
	{
		$('#im_next_link').removeClass('btn-primary');
		$('#im_next_link').addClass('disable');
	} 
}

function im_all_update( uid , keyword , max )
{
	// im_all_json
	var url = '?c=dashboard&a=im_all_json&uid='+uid+'&keyword='+encodeURIComponent(keyword)+'&max_id='+max ;
	
	var params = {};
	$.post( url , params , function( data )
	{
		var data_obj = $.parseJSON( data );
		 
		done();
		if( data_obj.err_code == 0 )
		{
			$('#im_all_text_div').html( $(data_obj.data.html) );
			$('#im_all_text_div').animate
			({
        		scrollTop: 0},
        	'fast');

			im_his_min = parseInt(data_obj.data.min);
			im_his_more = parseInt(data_obj.data.more);
			im_next_btn();

			
			
		}
		else
		{
			//alert('API调用错误，请稍后再试。错误号'+data_obj.err_code + ' 错误信息 ' + data_obj.message);
		}
	} );

	doing();
}


function doing()
{
	$("li#doing_gif").show();
}

function done()
{
	$("li#doing_gif").hide();
}

function show_float_box( title , url )
{
	$('#float_box').off('show');
	$('#float_box').on('show', function () 
	{
  		$('#float_box_title').text(title);
  		$('#float_box .modal-body').load(url);
	})

	$('#float_box .modal-body').html('<div class="muted"><center>Loading</center>');
	$('#float_box').modal({ 'show':true });

}

function close_float_box()
{
	$('#float_box').modal('hide');
}

function assign_chooser()
{
	if( $('#todo_form [name=private]:checked').val() == 1 )
	{
		alert( '私有TODO不能添加给别人' );
		return false;
	} 

	show_float_box( '点击你要加TODO的同事' , '?c=dashboard&a=people_box&jsfunc=assign_set&self=1' );
}

function assign_set( tid , uid , uname )
{
	$('#assign_chooser_span a').html('给 <i class="icon-user"></i> '+uname);
	$('#todo_assign_uid').val(uid);
	close_float_box();
}

function at_chooser()
{
	show_float_box( '请在选择你要点名的同事' , '?c=dashboard&a=people_box&jsfunc=cast_at_selected&multi=1' );
}

function cast_at_selected( uids , unames )
{
	$.each( unames , function()
	{
		//alert( this );
		var that = this;
		$('#cast_text').val( $('#cast_text').val() + ' @'+that );
	} );
	close_float_box();
	$('#cast_text').focus();
}

function close_all_side_board()
{
	$('#tdboard').hide();
	$('#fdboard').hide();	
	//$('#side_container').css( 'visibility' , 'visible' );
}



function get_img_src( file , fn ) 
{
	if ($.browser.msie) {
		if ($.browser.version <= 6) {
			fn(file.value);
			return;
		} else if ($.browser.version <= 8) {
			var src = '';
			file.select();
			try {
				src = document.selection.createRange().text;
			} finally {
				document.selection.empty();
			}
			src = src.replace(/[)'"%]/g, function(s){ return escape(escape(s)); });
			fn(src);
			return;
		}
	}
	if ($.browser.mozilla) {
		var oFile = file.files[0];
		if (oFile.getAsDataURL) {
			fn(oFile.getAsDataURL());
			return;
		}
	}
	try {
		var oFile = file.files[0];
		var oFReader = new FileReader();
		oFReader.onload = function (oFREvent) {
			/*
			var img = new Image();
			img.onload = function( evt )
			{

			}
			img.src = oFREvent.target.result;
			*/
			fn(oFREvent.target.result);
		};
		oFReader.onerror = function(a) {
			fn(options.okImg);
		};
		oFReader.readAsDataURL(oFile);
	} catch(e) {
		fn(options.okImg);
	}
}

function kset( key , value )
{
	if( window.localStorage )
	return window.localStorage.setItem( key , value );
}

function kget( key  )
{
	if( window.localStorage )
	return window.localStorage.getItem( key );
}

function kremove( key )
{
	if( window.localStorage )
	return window.localStorage.removeItem( key );
}


Array.prototype.unique =
  function() {
    var a = [];
    var l = this.length;
    for(var i=0; i<l; i++) {
      for(var j=i+1; j<l; j++) {
        // If this[i] is found later in the array
        if (this[i] === this[j])
          j = ++i;
      }
      a.push(this[i]);
    }
    return a;
  };


function play_sound()
{
	if( $.cookie( 'tt2-sound-enable' ) == 1 )
		 document.getElementById('ttsoundplayer').play();
}

if( $.cookie( 'tt2-sound-enable' ) != 1 && $.cookie( 'tt2-sound-enable' ) != 0  )
$.cookie( 'tt2-sound-enable' , 1 );


/* post demo
$.post( 'url&get var'  , { 'post':'value'} , function( data )
{
	var data_obj = jQuery.parseJSON( data );
	console.log( data_obj  );
	
	if( data_obj.err_code == 0  )
	{
					
	}
	else
	{
		
	}	
} );

*/