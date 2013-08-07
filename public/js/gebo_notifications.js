/* [ ---- Gebo Admin Panel - notifications ---- ] */

	$(document).ready(function() {
		gebo_notifications.sticky();
		gebo_notifications.smoke_js();
	});

    gebo_notifications = {
        sticky: function() {
            $('#sticky_a').click(function(e){
                $.sticky("Lorem ipsum dolor sit&hellip;.", {autoclose : 5000, position: "top-right", type: "st-error" });
            });
            $('#sticky_b').click(function(e){
                $.sticky("Lorem ipsum dolor sit&hellip;", {autoclose : 5000, position: "top-right", type: "st-success" });
            });
            $('#sticky_c').click(function(e){
                $.sticky("Lorem ipsum dolor sit&hellip;", {autoclose : 5000, position: "top-right", type: "st-info" });
            });
            $('#sticky_d').click(function(e){
                $.sticky("Lorem ipsum dolor sit&hellip;", {autoclose : 5000, position: "top-right" });
            });
            $('#sticky_d_st').click(function(e){
                $.sticky("Lorem ipsum dolor sit&hellip;", {autoclose : false, position: "top-right" });
            });
            $('#sticky_e').click(function(e){
                $.sticky("Lorem ipsum dolor sit&hellip;", {autoclose : 5000, position: "top-right" });
            });
            $('#sticky_f').click(function(e){
                $.sticky("Lorem ipsum dolor sit&hellip;", {autoclose : 5000, position: "top-center" });
            });
            $('#sticky_g').click(function(e){
                $.sticky("Lorem ipsum dolor sit&hellip;", {autoclose : 5000, position: "top-left" });
            });
            $('#sticky_h').click(function(e){
                $.sticky("Lorem ipsum dolor sit&hellip;", {autoclose : 5000, position: "bottom-right" });
            });
            $('#sticky_i').click(function(e){
                $.sticky("Lorem ipsum dolor sit&hellip;", {autoclose : 5000, position: "bottom-left" });
            });
        },
		smoke_js: function() {
			$('#smoke_signal').click(function(e){
                smoke.signal('This goes away after a few seconds...5 seconds, in fact.');
				e.preventDefault();
            });
			$('#smoke_alert').click(function(e){
                smoke.alert('This is a normal alert screen...nothing too fancy.');
				e.preventDefault();
            });
			$('#smoke_confirm').click(function(e){
                tstconfirm();
				e.preventDefault();
            });
			$('#smoke_prompt').click(function(e){
                tstprompt();
				e.preventDefault();
            });
			
			function tstconfirm(){
				smoke.confirm('This is still cool, yeah?',function(e){
					if (e){
						smoke.alert('"yeah it is" pressed', {ok:"close"});
					}else{
						smoke.alert('"no way" pressed', {ok:"close"});
					}
				}, {ok:"yeah it is", cancel:"no way"});
			}
			
			function tstprompt(){
				smoke.prompt('What\'s your name?',function(e){
					if (e){
						smoke.alert('Your name is '+e);
					}else{
						smoke.alert('no');
					}
				},{value:"your name"});
			}		
			
		}
    };