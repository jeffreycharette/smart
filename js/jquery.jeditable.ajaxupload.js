/*
 * Ajaxupload for Jeditable
 *
 * Copyright (c) 2008-2009 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Depends on Ajax fileupload jQuery plugin by PHPLetter guys:
 *   http://www.phpletter.com/Our-Projects/AjaxFileUpload/
 *
 * Project home:
 *   http://www.appelsiini.net/projects/jeditable
 *
 * Revision: $Id$
 *
 */
 
$.editable.addInputType('ajaxupload', {
    /* create input element */
    element : function(settings) {
        settings.onblur = 'ignore';
		$('.closeBtn, .save, .fwd, .bwd').fadeOut('fast');
        var input = $('<input type="file" id="upload" name="pictures[]" />');
        $(this).append(input);
        return(input);
    },
    content : function(string, settings, original) {
        /* do nothing */
    },
    plugin : function(settings, original) {
        var form = this;
		var element_id = $(original).attr("id");
        form.attr("enctype", "multipart/form-data");
        $("button:submit", form).bind('click', function() {
            $.ajaxFileUpload({
                url: settings.target+"?imagesid="+element_id,
                secureuri:false,
                fileElementId: 'upload',
                dataType: 'html',
                success: function (data, status) {
					$('.closeBtn, .save, .fwd, .bwd').fadeIn();
					if (data=="invalid") {
						$(original).html("Please select an image to upload.");
					}
					else {
						$(original).html("upload");
						$(original).parent().find('ul.imglist').html(data).find("a").lightBox();
					}
					original.editing = false;
                },
                error: function (data, status, e) {
                    $('.closeBtn, .save, .fwd, .bwd').fadeIn();
                    alert(e);
                }
            });
			$(original).html(settings.indicator);
            return(false);
        });
    }
});
