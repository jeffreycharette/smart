$(function() {
calliframe("/index.php?id={id}&mode=edit",editor);
function calliframe(url, callback) {
    $(document.body).append('<iframe scrolling="no" style="width:100%;" frameborder="0" name="content" id="wrc-content">');
    $('iframe#wrc-content').attr('src', url);
    $('iframe#wrc-content').load(function() {
        callback(this);
    });
}

function editor(it) {
var itbody=$(it).contents().find('body');
$(itbody).find('.query').each(function(i) {
	var el=$(this);
	var eid=el.attr('id');
	var elm=el;
	var w = el.width();
	var h = el.height();
	var mostLeft = 100000;
	var mostTop = 100000;

	el.children().each(function(i){
		if ($(this).position().left < mostLeft) {
			mostLeft = $(this).position().left; elm=$(this);
			if (elm.height() > h) {
				h = elm.height();
			}
			if (elm.width() > w) {
				w = elm.width();
			}
		}
	});
	if (elm.is(':empty')) {
		elm.before('<div id="fold" style="position:absolute;text-indent:0;"><img class="fold" style="border:none;padding:0;margin:0;" src="/img/fold.png" /></div>');
	}
	else {
		elm.prepend('<div id="fold" style="position:absolute;text-indent:0;"><img class="fold" style="border:none;padding:0;margin:0;" src="/img/fold.png" /></div>');
	}
	//replace with creation of element not Qtip plugin, use hoverintent to keep from the hover issue you are having
	if (w<160) {w=160;}
	if (h<50) {h=50;}

	var sid = $('iframe#wrc-content').contents().find('#set'+eid);
	var sel = $(sid).find('div.box').index($(sid).find('div.selected'));
	if (sel=="-1") {sel=1;}else {sel=sel+1;}
	elm.mopBox({'target':sid,'w':650,'h':450,'speed':200,'step':1,'stepPx':5,'btnW':300,'startPage':sel});

});
$(itbody).wrapInner('<div style="padding:0 40px;"></div>');
}

$('<div id="blanket">').css({
         position: 'absolute',
         top: $(document).scrollTop(), // Use document scrollTop so it's on-screen even if the window is scrolled
         left: 70,
         opacity: 0.7, // Make it slightly transparent
         backgroundColor: '#080000',
         overflow: 'hidden',
         zIndex: 8999  // Make sure the zIndex is below 99999 to keep it below MopBox!
      })
      .appendTo(document.body) // Append to the document body
      .hide(); // Hide it initially
$('<div id="remove">').appendTo(document.body).html('<h3>Remove</h3><p>Are you sure?</p>').hide();

function content_resize() {
	var w = $(window);
	var H = w.height(); 
	var W = w.width();
	var T = w.scrollTop();
	var L = w.scrollLeft()+40;
	$('#blanket').css({width: W-80, height: H, top: T, left: L}); 
};
var resizeTimer = null;
$(window).bind('resize scroll', function() {
    if (resizeTimer) clearTimeout(resizeTimer);
    resizeTimer = setTimeout(content_resize, 200);
});
content_resize();

$('span.tip').each(function (i) {
	var tipText=$(this).text();
	var inputArea=$(this).addClass('hidelabel').next(':text,textarea,:password');
	if (inputArea.val()=="") {inputArea.val(tipText).addClass('suggest');}
	inputArea.focus(function(){if (inputArea.val()==tipText) {inputArea.removeClass('suggest').val("");}}).blur(function(){if(!this.value.length){inputArea.addClass('suggest').val(tipText);}});
	$('form').submit(function() {if (inputArea.val()==tipText) {inputArea.val("");}});
});

});