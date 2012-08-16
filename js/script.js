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
	$('.industries li:nth-child(6n)').css({ 'padding-right':0 });

/* Work Layout */
	var workCount = 5;
	
	if ($('#work').hasClass('thinking')) {
		workCount = 100;
		$('#load-more').parent('p').hide();
	}
	var loadCount = workCount,
			workLength = $('#work article').length;
			
	// Add class to show first workCount elements
	$('#work article:lt('+workCount+')').addClass('show');

	$('#work').isotope({
	  itemSelector : 'article',
		itemPositionDataEnabled: true,
		filter : $('#work article.show')
	})
	// log position of each item
	.find('.medium').each(function(){
	  var position = $(this).data('isotope-item-position');
		if (position) {
			if (position.x == 0) {
				$(this).css({'margin-left' : 0});
			}
		}
	});
	
	if (workCount >= workLength) {
		$('#load-more').parent('p').hide();
	}
	else {
		$('#load-more').click( function() {
			loadCount = loadCount + workCount;
		
			// add class to show more elements
			$('#work article:lt('+loadCount+')').addClass('show');
		
			// filter with isotope
			$('#work').isotope({ filter : $('#work article.show') })
			.find('.medium, .small').each(function(){
			  var position = $(this).data('isotope-item-position');
				if (position) {
					if (position.x == 0) {
						$(this).css({'margin-left' : 0});
					}
				}
			});
			if (loadCount >= workLength) {
				$('#load-more').parent('p').hide();
			}
		});
	}
	
	var thinkingCount = 4,
			thinkingLoad = thinkingCount,
			thinkingLength = $('#article-list article').length;
			
	$('#article-list article:gt('+thinkingCount+'), #article-list article:eq('+thinkingCount+')').hide();
	$('#article-list article:visible:last').addClass('last');
	
	if ( thinkingCount >= thinkingLength ) {
		$('#think-more').parent('p').hide();
	}
	else {
		$('#think-more').click( function() {
			thinkingLoad = thinkingLoad + thinkingCount;
		
			// add class to show more elements
			$('#article-list article:lt('+thinkingLoad+')').fadeIn().removeClass('last');
			$('#article-list article:visible:last').addClass('last');
	
			if (thinkingLoad >= thinkingLength) {
				$('#think-more').parent('p').fadeOut();
				$('.lined-top').css({ 'border':'none' });
			}
		});
	}
	
/* Awards */

$('#awards dl:odd').css({ 'margin-right': 0 });
$('#awards dl').find('dd:even').css({ 'background-color': '#F6F6F6' });

/* Custom Select */
$('#industry, #select-contributors, #select-filter, #select-archive').SelectCustomizer();

$("#industry_customselect").bind('filtered',function(e, value) {
	// no longer hiding content that needs to be loaded
	$('#load-more').hide();
	// clear search
	$('#search').val('');
	
	var value = '.'+value.replace(/ /g, '-');
	if (value == '.all-expertise') { value = 'article';}
	
	$('#work article.large, #work article.small').removeClass('large small').addClass('medium');
	$('#work .medium img').css({ 'width':'99%', 'height':'auto' });
	$('#work').find(value).each( function(i){
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
		// no longer hiding content that needs to be loaded
		$('#load-more').hide();
		
		// clear select
		$('#industry_iconselect span').text('Filter by Expertise');
		$('#industry_options div').removeClass('selectedclass');
		
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
			$('.slide-number').html((opt.currSlide + 1) + ' of ' + opt.slideCount); 
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
	
/* slider */

	$("a.slider").each( function(i) {
		var color = $(this).find('img:first').attr('class'),
				title = $(this).find('img:first').attr('alt');
		if (!title) {
			title = $(this).find('h2:first').text();
			$(this).find('img:first').attr('alt',title);
		}
		if (!color) {
			color = "orange";
		}
		$(this).find('img:first').before('<hgroup class="'+color+'-wrapper-small"><h1>'+title+'</h1></hgroup>');
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
	
	/* localScroll (capabilities page / all inline links) */
	$.localScroll.hash();
	$.localScroll({hash:true});
	
	$('.left-column').each( function() {
		$(this).find('ul li:odd').addClass('odd');
	});
	
	/* search results */
	$('#search-results ol li:odd').addClass('odd');
	
	/* leadership */
	
	$('#bios figure').find('img:first').css({'zIndex':2});
	$('#bios figure').mouseenter(
		function() {
			$(this).find('img:first').stop().animate({opacity:.0001});
		});
		
	$('#bios figure').mouseleave(
		function() {
			$(this).find('img:first').stop().animate({opacity:1});	
	});
	$('#studios figure:nth-child(3n)').addClass('last');
	
	/* modal code not used */
	
	/*$(document).keyup(function(e) {
	  if (e.keyCode == 27) { $('.modal-close a').trigger('click'); }   // esc
	});*/

/* activate first matched modal from hash */

	/*if (window.location.hash) {
		var hash = window.location.hash.substring(1);
		$('a[href*=#'+hash+']:first').trigger('click');
	}*/
	function isiPhone(){
	    return (
	        //Detect iPhone
	        (navigator.platform.indexOf("iPhone") != -1) ||
	        //Detect iPod
	        (navigator.platform.indexOf("iPod") != -1) ||
					//Detect iPad
					(navigator.platform.indexOf("iPad") != -1)
	    );
	}
	if ( isiPhone() ) {
		$('.slider hgroup, .slider hgroup h1').width('auto');
	}
	
});