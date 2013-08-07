/* [ ---- Gebo Admin Panel - widgets ---- ] */

    $(document).ready(function() {
		gebo_widgets.upload();
        //* autosize textarea
        $('.auto_expand').autosize();
        gebo_sortable.init();
    });

    gebo_widgets = {
        upload: function() {
            function w_upload() {
                $("#widget_upload").pluploadQueue({
                    // General settings
                    runtimes : 'html5,flash,silverlight',
                    url : 'lib/plupload/examples/upload.php',
                    max_file_size : '10mb',
                    chunk_size : '1mb',
                    unique_names : true,
            
                    // Specify what files to browse for
                    filters : [
                        {title : "Image files", extensions : "jpg,gif,png"},
                        {title : "Zip files", extensions : "zip"}
                    ],
            
                    // Flash settings
                    flash_swf_url : 'lib/plupload/js/plupload.flash.swf',
            
                    // Silverlight settings
                    silverlight_xap_url : 'lib/plupload/js/plupload.silverlight.xap'
                });
                
            }
            w_upload();
            
            $('#upload_refresh').css({'cursor':'pointer','margin-left':'10px'}).click(function(e) {
                $('#widget_upload').pluploadQueue().destroy();
                w_upload();
                return false;
            });
            
        }
    };
    
    gebo_sortable = {
        init: function() {
            
            var thisCookie = $.cookie('sortOrder');
            if(thisCookie != null) {
                $.each(thisCookie.split(';'),function(i,id) {
                    thisSortable = $('#sortable_panels div[class*="span"]').not('.not_sortable').get(i);
                    if(id != 'null'){
                        $.each(id.split(','),function(i,id) {
                            $("#"+id).appendTo(thisSortable);
                        });
                    }
                })
            }
            
            $('#sortable_panels div[class*="span"]').not('.not_sortable').sortable({
                connectWith: '#sortable_panels div[class*="span"]',
                helper: 'original',
                handle: '.w-box-header',
                cancel: ".sort-disabled, .w-box-header input",
                forceHelperSize: true,
                forcePlaceholderSize: true,
                tolerance: 'pointer',
                activate: function(event, ui) {
                    $(".ui-sortable").addClass('sort_ph');
                },
                stop: function(event, ui) {
                    $(".ui-sortable").removeClass('sort_ph');
                },
                update: function (e, ui) {
                    var elem = [];
                    $('#sortable_panels div[class*="span"]').not('.not_sortable').each(function(){
                        elem.push($(this).sortable("toArray"));
                    });
                    var str = '';
                    var m_len = elem.length;
                    jQuery.each(elem, function(index,value) {
                        var s_len = value.length;
                        if(value == '') {
                            str += 'null';
                        } else {
                            jQuery.each(value, function(index,value) {
                                str += value;
                                if (index != s_len - 1) {
                                    str += ","
                                }
                            });
                        }
                        if (index != m_len - 1) {
                            str += ";"
                        }
                    });
                    $.cookie('sortOrder', str, { expires: 7});
                }
            });
			
			$('.reset_layout').click(function(){
				$.cookie('sortOrder', null);
				location.reload();
			});
        }
    };
