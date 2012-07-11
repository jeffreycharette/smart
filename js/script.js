/* Author: wearecharette.com
*/
$(function() {

/* preload hover images */
	$(['/css/images/btn-prev-hover.gif','/css/images/btn-next-hover.gif','/css/images/arrow-more-hvr.gif']).preload();
	
/* Latest Work Layout */
/* Expertise Layout */
	$('.grid').isotope({
	  itemSelector : 'article',
	  masonry: {
		    columnWidth: 165
		  }
	});
	
	$('#industries').isotope({
	  itemSelector : 'article',
	  masonry: {
		    columnWidth: 326
		  }
	});
	$('#industries .medium img').css({ 'width':'100%', 'height':'auto' });
	
	$('#industries .medium').each( function(i){
		i++;
		if ((i%3)==0) {
			$(this).css({ 'margin-right':0 });
		}
		else {
			$(this).css({ 'margin-right':'20px' });
		}
	});

/* Work Layout */
	$('#work').isotope({
	  itemSelector : 'article',
		itemPositionDataEnabled: true
	})
	// log position of each item
	.find('.medium').each(function(){
	  var position = $(this).data('isotope-item-position');
		if (position.x == 0) {
			$(this).css({'margin-left' : 0});
		}
	});

/* Custom Select */
$('#industry, #select-contributors, #select-filter, #select-archive').SelectCustomizer();

$("#industry_customselect").bind('filtered',function(e, value) {
	var value = '.'+value.replace(/ /g, '-');
	if (value == '.all-expertise') { value = '*';}
	
	$('#work article.large, #work article.small').removeClass('large, small').addClass('medium');
	$('#work .medium img').css({ 'width':'100%', 'height':'auto' });
	
	$('#work').find('> '+value).each( function(i){
		i++;
		if ((i%3)==0) {
			$(this).css({ 'margin-right':0 });
		}
		else {
			$(this).css({ 'margin-right':'14px' });
		}
	}).css({ 'margin-left':'0', 'margin-bottom':'30px', 'height':'438px', 'width':'309px' });
	$('#work').isotope({ filter: value });
});

var data = {items: [
{id: "1", text: "Household"},
{id: "2", text: "Finance"},
{id: "3", text: "Healthcare"},
{id: "4", text: "Consumer Electronics"},
{id: "5", text: "Beauty"},
{id: "6", text: "Entertainment"}
]};

$('input#search').jsonSuggest({
	data: data.items, 
	minCharacters: 1,
	onSelect: function(value) {
		var value = '.'+value.text.replace(/ /g, '-').toLowerCase();
		if (value == '.all-expertise') { value = '*';}

		$('#work article.large, #work article.small').removeClass('large, small').addClass('medium');
		$('#work .medium img').css({ 'width':'100%', 'height':'auto' });

		$('#work').find('> '+value).each( function(i){
			i++;
			if ((i%3)==0) {
				$(this).css({ 'margin-right':0 });
			}
			else {
				$(this).css({ 'margin-right':'14px' });
			}
		}).css({ 'margin-left':'0', 'margin-bottom':'30px', 'height':'438px', 'width':'309px' });
		$('#work').isotope({ filter: value });
	}
});

/* Homepage Slideshow */
	$('.slideshow span:first').cycle({
		fx: 'uncover',
		speed:  '300', 
    timeout: '9000',
		nowrap: 1,
    pager:  '.pager',
		next:   '#gallery .next', 
		prev:   '#gallery .prev',
		before: function(slide, next, opt, forward) {
			$(next).find('.description').css({ opacity:0, top:70, right:100, height:270 });
			$(next).find('.description > *').css({ opacity:0 });
		},
		after: function(slide, next, opt, forward) {
			$(next).find('.description').animate({ opacity:1, top:55, right:0, height:300 }, 100).animate({ opacity:1, top:40, right:75, height:330 }, 100);
			$(next).find('.description > *').delay(400).animate({ opacity:1 }, 100);
			$('#gallery .prev').css({"visibility" : [opt.currSlide == 0 ? 'hidden' : 'visible']});
			$('#gallery .next').css({"visibility" : [opt.currSlide == opt.slideCount - 1 ? 'hidden' : 'visible']});
		},
		
    // callback fn that creates a thumbnail to use as pager anchor 
    pagerAnchorBuilder: function(idx, slide) {
    	return '<li><a href="#">.</a></li>'; 
    },
		onPagerEvent: function(idx, slide) {
		}
	});
	
/* case-study-slideshow */
	
	$('.case-study-slideshow').cycle({
		fx: 'scrollHorz',
		nowrap: 1,
		speed: 300,
		prev: '#ssprev',
		next: '#ssnext',
		timeout:0,
		after: function(curr,next,opt) { 
			var slideNum = (opt.currSlide + 1) + ' of ' + opt.slideCount; 
			$('#slide-number').html(slideNum); 
			$('#ssprev').css({"visibility" : [opt.currSlide == 0 ? 'hidden' : 'visible']});
			$('#ssnext').css({"visibility" : [opt.currSlide == opt.slideCount - 1 ? 'hidden' : 'visible']});
		}
	});
	
/* Twitter Feed */

$(".tweet").tweet({
  join_text: "auto",
  username: "smartdesign",
  avatar_size: 48,
  count: 6,
  auto_join_text_default: "we said,",
  auto_join_text_ed: "we",
  auto_join_text_ing: "we were",
  auto_join_text_reply: "we replied",
  auto_join_text_url: "we were checking out",
  loading_text: "loading tweets...",
	refresh_interval: 20,
	retweets: true,
	template: "{avatar}{join}{text}{time}&nbsp;{retweet_action}"
}).bind("loaded",function(){
	$(this).find("a").attr("target","_blank");
	$(this).find("a.tweet_action").click(function(ev) {
	          window.open(this.href, "Retweet",
	                      'menubar=0,resizable=0,width=550,height=420,top=200,left=400');
	          ev.preventDefault();
	        });
	});
	
/* modal */

	$("a.slider").each( function(i) {
		var color = $(this).find('img:first').attr('class');
		if (!color) {
			color = "orange";
		}
		$(this).find('img:first').before('<hgroup class="'+color+'-wrapper-small"><h1>'+$(this).find('img:first').attr('alt')+'</h1></hgroup>');
	});
	$("a.slider").hover(
		function () {
	    $(this).find('hgroup').width('auto').animate({ width:'auto' }, 0);
			$(this).find('hgroup h1').width('auto').animate({ width:'auto' }, 0);
	  },
	  function () {
	    $(this).find('hgroup h1').animate({ width:0 }, 200);
			$(this).find('hgroup').animate({ width:10 }, 200);
	  }
	);
	
	/* localScroll (capabilites page / all inline links) */
	$.localScroll.hash();
	$.localScroll({hash:true})
	
	$(document).keyup(function(e) {
	  if (e.keyCode == 27) { $('.modal-close a').trigger('click'); }   // esc
	});

/* activate first matched modal from hash */

	if (window.location.hash) {
		var hash = window.location.hash.substring(1);
		$('a[href*=#'+hash+']:first').trigger('click');
	}
	
});