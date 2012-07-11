$.fn.SelectCustomizer = function(){
    // Select Customizer jQuery plug-in
	// based on customselect by Ace Web Design http://www.adelaidewebdesigns.com/2008/08/01/adelaide-web-designs-releases-customselect-with-icons/
	// modified by David Vian http://www.ildavid.com/dblog
	// and then modified AGAIN be Dean Collins http://www.dblog.com.au
    return this.each(function(){
        var obj = $(this);
		var name = obj.attr('id');
		var id_slc_options = name+'_options';
		var id_icn_select = name+'_iconselect';
		var id_holder = name+'_holder';
		var custom_select = name+'_customselect';
        obj.after("<div id=\""+id_slc_options+"\" class=\"optionswrapper\"> </div>");
        obj.find('option').each(function(i){
            $("#"+id_slc_options).append("<div title=\"" + $(this).attr("value") + "\" class=\"selectitems\"><span>" + $(this).html() + "</span></div>");
        });
        obj.before("<input type=\"hidden\" value =\"\" name=\"" + this.name + "\" id=\""+custom_select+"\"/><div id=\""+id_icn_select+"\">" + this.title + "</div><div id=\""+id_holder+"\" class=\"selectwrapper\"> </div>").remove();
        $("#"+id_icn_select).click(function(a){
			if($("#"+id_holder).css('display') == 'none') {
				$("#"+id_holder).fadeIn(200);
				$("#"+id_holder).focus();
				a.stopPropagation();
				$(document).keypress(function(e) {
					if(!e) var e = window.event;
					e.cancelBubble = true;
					e.returnValue = false;
					if (e.stopPropagation) {
						e.stopPropagation();
						e.preventDefault();
					}
				});
				$(document).keyup(function(e) {
					
					if(e.which == 40) {
						var lastSelected = $("#"+id_holder+" .selectedclass");
						if(lastSelected.size() == 0) {
							var nextSelected =  $("#"+id_slc_options+" div:first:");
						} else {
							var nextSelected = lastSelected.next(".selectitems");
						}
						if(nextSelected.size() == 1) {
							lastSelected.removeClass("selectedclass");
							nextSelected.addClass("selectedclass");
							$("#"+custom_select).val(nextSelected.title);
           					$("#"+id_icn_select).html(nextSelected.html());
							var rowOffset = (nextSelected.offset().top - $("#"+id_holder).offset().top);
							if(rowOffset > 130) {
								$("#"+id_slc_options).scrollTo(($("#"+id_slc_options).scrollTop() + 27) +  "px");
							}
						}
						
					} else if(e.which == 38) {
						var lastSelected = $("#"+id_holder+" .selectedclass");
						var nextSelected = lastSelected.prev(".selectitems");
						if(nextSelected.size() == 1) {
							lastSelected.removeClass("selectedclass");
							nextSelected.addClass("selectedclass");
							$("#"+custom_select).val(nextSelected.title);
           					$("#"+id_icn_select).html(nextSelected.html());
							var rowOffset = (nextSelected.offset().top - $("#"+id_holder).offset().top);
							if(rowOffset > 0) {
								$("#"+id_slc_options).scrollTo(($("#"+id_slc_options).scrollTop() - 27) +  "px");
							}
						}
					} else if(e.which == 13) {
						$("#"+id_holder).fadeOut(250);
						$(document).unbind('keyup');
						$(document).unbind('keypress');
						$('body').unbind('click');
					}
					
				});
				$('body').click(function(){
					$("#"+id_holder).fadeOut(200);
					$('body').unbind('click');
					$(document).unbind('keyup');
					$(document).unbind('keypress');
				});
			} else {
				$("#"+id_holder).fadeOut(200);
				$('body').unbind('click');
				$(document).unbind('keyup');
				$(document).unbind('keypress');
			}
			
			
        });
        $("#"+id_holder).append($("#"+id_slc_options)[0]);
		$("#"+id_holder).append("<div class=\"selectfooter\"></div>");
		$("#"+id_slc_options+" > div:last").addClass("last");
        $("#"+id_holder+ " .selectitems").mouseover(function(){
            $(this).addClass("hoverclass");
        });
        $("#"+id_holder+" .selectitems").mouseout(function(){
            $(this).removeClass("hoverclass");
        });
        $("#"+id_holder+" .selectitems").click(function(){
            $("#"+id_holder+" .selectedclass").removeClass("selectedclass");
            $(this).addClass("selectedclass");
            var thisselection = $(this).html();
            $("#"+custom_select).val(this.title);
            $("#"+id_icn_select).html(thisselection);
            $("#"+id_holder).fadeOut(250);
			$(document).unbind('keyup');
			$(document).unbind('keypress');
			$('body').unbind('click');
        });
    });
}