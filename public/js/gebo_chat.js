/* [ ---- Gebo Admin Panel - chat ---- ] */

	$(document).ready(function() {
		gebo_chat.init()	
	});

	gebo_chat = {
		init: function() {
			
			if(!is_touch_device()){
				chatEditor = $("#chat_editor").cleditor({
					width:"100%",
					height:"120px",
					controls: "icon bold italic underline"
				})[0];
			}
			
			$('.send_msg').click(function(e){
				gebo_chat.sendMsg();
				e.preventDefault();
			});
			gebo_chat.sendMsg_enter();
			
			gebo_chat.add_remove_user();
			gebo_chat.close();
		},
		close: function() {
			$('.chat_close').click(function(e){
				$('.msg_window .chat_msg').not('.msg_clone').remove();
				$('.chat_sidebar li.active').not('.chat_you').removeClass('active');
				$('.act_users').html('1');
				if(!is_touch_device()){
					chatEditor.clear();
				}
				e.preventDefault();
			});
		},
		sendMsg_enter: function() {
			
			var showTimeOut;
			showTimeOut = setInterval(function(){
				if(!is_touch_device()){
					if($(".cleditorMain").css("visibility") === "visible") {
						$(".cleditorMain iframe").contents().on('keyup','body', function(e){
							if(e.keyCode == 13 && $('.enter_msg').hasClass('active') ) {
								gebo_chat.sendMsg();
							}
						});
						chatEditor.focus();
					}
				} else {
					$("#chat_editor").on('keyup', function(e){
						if(e.keyCode == 13 && $('.enter_msg').hasClass('active') ) {
							gebo_chat.sendMsg();
							$("#chat_editor").val("").text("");
						}
					})
				}
				clearInterval(showTimeOut);
			},2000);

			
		},
		sendMsg: function() {
			
			if(!is_touch_device()){
				chatEditor.updateTextArea();
			}
			
			var msg = $("#chat_editor").val(),
				chat_user = $('#chat_user').val(),
				//* remove first and last <br> from message
				tr_msg = msg.replace(/(^(<div><br><\/div>)+|(<div><br><\/div>)$)/g,"").replace(/(^(<br>)+|(<br>)+$)/g,"");
	
			if(tr_msg != "") {
				var msg_cloned = $('.msg_clone').clone();
				$('.msg_window').append(msg_cloned);
				msg_cloned.find('.chat_msg_date').html(moment().format('HH:mm'));
				msg_cloned.find('.chat_msg_body').html(tr_msg);
				msg_cloned.find('.chat_user_name').html(chat_user);
				msg_cloned.removeClass('msg_clone').show();
				$('.msg_window').stop().animate({
					scrollTop: msg_cloned.offset().top
				}, 2000);
			}
			
			if(!is_touch_device()){
				chatEditor.clear().focus();
			}
		},
		add_remove_user: function() {
			$('.chat_sidebar li.online').not('.chat_you').find('a').on('click',function(e){
				if (!$(this).closest('li').hasClass("active")) {
					$(this).closest('li').addClass('active');
				} else {
					$(this).closest('li').removeClass('active');
				}
				$('.act_users').html($('.chat_sidebar li.active').length);
				e.preventDefault();
			});
		}
	};

