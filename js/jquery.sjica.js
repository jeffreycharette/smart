$(function() {
if ($('#banner .query').length) {
	$('#banner .query a.anchor').remove();
	$('#banner .query').cycle({ 
		sync:1,
		timeout: 4000, 
		speed: 900,
		cleartype:  1,
		pause: 1,
		pager:  '#slideNav'
	});
}
	$('a.replace').each(function() {
		var page=$(this);
		page.html('<div style="height:100px;text-align:center;"><br /><img src="/img/ajax-loader.gif" width="31" height="31" /></div>');
		$.post(page.attr('href'),'',function(data) {
			page.replaceWith(data);
		});
	});
function load_history(filter) {
        $('body').focus();
        $('.pagination').html('<img width="14" height="14" src="/img/ajax-loader.gif" />');
        $('.loader').html('<img width="14" height="14" src="/img/ajax-loader.gif" />');
	var vars = [], hash;
	var hashes = filter.split('&');
	for(var i = 0; i < hashes.length; i++) {
		hash = hashes[i].split('=');
		vars.push(hash[0]);
		vars[hash[0]] = decodeURIComponent(hash[1]);
	}
	var pathname=window.location.pathname.replace('.html','.json');
	$.get(pathname,filter,function(data) {
		$(data.query).each(function(i) {
			$('#'+this.id).html(this.value);
			$('.pagination:eq('+i+')').html(this.pagination);
			if (vars['match']!="" && vars['match']!=undefined) {
				if (vars['match']=="" || vars['match']==undefined) {
					$('.subtitle').html('> all');
				}
				else {
					$('.subtitle').html('> '+vars['match']);
				}
			}
			else {
				if (vars['archive']=="" || vars['archive']==undefined) {
					$('.subtitle').html('');
				}
				else {
					$('.subtitle').html('> '+vars['tid']+' '+vars['archive']);
				}
			}
		});
		$('.loader').html(" ");
		$('#prints a.lightbox').lightBox();
	},"json");
}
$.history.init(function(filter) {
	if ($('.filter').length) {
		if (filter=="" || filter==undefined) {
			var filter=window.location.href.split('?')[1];
		}
		load_history(filter == undefined ? "&" : filter);
	}
});

var currentValue;

$('.filter').change(function(){
	var val = $(this).val();
	var tid = $(this).attr('id');
    if (currentValue != val) {
        currentValue = val;
	var filter='archive='+currentValue+'&field=start&sort=ASC&tid='+tid;
        $.history.load(filter);
    }
});
$('a.filter').live('click',function(){
	var val = $(this).attr('href').replace('#','');
	var tid = $(this).attr('rel');
	var filter='match='+val+'&on='+tid;
	$.history.load(filter);
	return false;
});

$('span.tip').each(function (i) {
	var tipText=$(this).text();
	var inputArea=$(this).addClass('hidelabel').next(':text,textarea,:password');
	if (inputArea.val()=="") {inputArea.val(tipText).addClass('suggest italic');}
	inputArea.focus(function(){if (inputArea.val()==tipText) {inputArea.removeClass('suggest italic').val("");}}).blur(function(){if(!this.value.length){inputArea.addClass('suggest italic').val(tipText);}});
	$('form').submit(function() {if (inputArea.val()==tipText) {inputArea.val("");}});
});
$('#subForm').live('mouseover',function(){
	var validator = $(this).validate({
		rules: {
			"name": {
				required: true,
				minlength: 2
			},
			"position": {
				required: true,
				minlength: 2
			},
			"email": {
				required: true,
				email: true
			},
			"phone": {
                                required: true,
                                minlength: 2
                        },
			"street_address": {
                                required: true,
                                minlength: 2
                        },
			"city": {
                                required: true,
                                minlength: 2
                        },
			"state_province": {
                                required: true,
                                minlength: 2
                        },
			"postal_code": {
                                required: true,
                                minlength: 2
                        },
			"interest": {
                                required: true,
                                minlength: 2
                        }
		},
		messages: {
			"name": {
				required: "Please enter your full Name.",
				minlength: "Your Name must consist of at least 2 characters."
			},
			"position": {
                                required: "Please enter your Organization / Title.",
                                minlength: "Your Organization / Title must consist of at least 2 characters."
                        },
			"phone": {
                                required: "Please enter your Phone Number.",
                                minlength: "Your Phone Number must consist of at least 2 characters."
                        },
                        "street_address": {
                                required: "Please enter your Street Address.",
                                minlength: "Your Street Address must consist of at least 2 characters."
                        },
			"city": {
                                required: "Please enter your City.",
                                minlength: "Your City must consist of at least 2 characters."
                        },
                        "state_province": {
                                required: "Please enter your State or Province.",
                                minlength: "Your State or Province must consist of at least 2 characters."
                        },
                        "postal_code": {
                                required: "Please enter your Postal Code.",
                                minlength: "Your Postal Code must consist of at least 2 characters."
                        },
                        "interest": {
                                required: "Please enter your Interest.",
                                minlength: "Your Interest must consist of at least 2 characters."
                        },
			"email": "Please enter a valid email address."
		},
		errorPlacement: function(error, element) { 
			$('#form_join p.right').html(error); 
		},
		submitHandler: function(form) {
			if ( $("#subForm").validate().form() ) {
   				form.submit();
			}
 		}
	});
        $('.cancel').live('click',function() {
                $('#window').hide();
                $('#blanket').fadeOut('fast');
                validator.resetForm();
        });
});
$('.join').live('click',function() {
        $('#form_join').show();
        $('#blanket').css({opacity:0,display:'block'}).animate({opacity:.7},1000);
        $('#window').fadeIn('fast');
        return false;
});
function content_resize() {
	var w = $(window);
	var H = w.height(); 
	var W = w.width();
	var T = w.scrollTop();
	var L = w.scrollLeft();
	$('#blanket').css({width: W, height: H, top: T, left: L}); 
};
var resizeTimer = null;
$(window).bind('resize scroll', function() {
    if (resizeTimer) clearTimeout(resizeTimer);
    resizeTimer = setTimeout(content_resize, 0);
});
content_resize();
return false;
});
