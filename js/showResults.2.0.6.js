/**
 * showResults - jQuery plugin
 *
 * Copyright (c) 2009 Stephen Rhoades
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.stephenrhoades.com
 *
 * @version 2.0.6
 * @update February 21st 2009
 * @author Steve Rhoades <sedonami@gmail.com>
 * @support http://www.stephenrhoades.com
 * @params array result result set to display
 * @params object options object of additional opts
 * @options[limit] - limit number of results to display
 * @options[pages] - number of pages to display
 * @options[resultTarget] - container name that references container ie]. #mycontainer, .mycontainer
 * @options[bgColors] - an Array of alternating colors, these values will be inserted into %bgcolor% on the template.
 * @todo - add multiple instance browser history
 * @todo - add multiple date types currentl supports mm/dd/YYYY
 * @todo - add a narrow by feature.
 */

(function($) {
	$.fn.showResults = function(oR, opt) {
		if(!$.isArray(oR)) {
			throw "showResults expects an array";
		}
		var o = {
			limit: 5,
			pages: 10,
			start: 0,
			resultTarget: '',
			pagesTarget: '',
			bgColors: [],
			arrows: [" Ascending"," Descending"],
			emptyMsg: 'No Results',
			callback: null
		};
		
		var s = {b:'',a: true,d:''};
		
		var p = {pg: '',n: 0,p: 0,pp: 0,np: 0,sp: 0,ep: 0,ls: 0,le: 0,tp: 0,is: 0,t: oR.length,r: oR,tpl:''};
		
		if(opt) {
			$.extend(o, opt);
		}		

		p.tp = Math.ceil(p.t/o.limit);
		p.is = o.start;

		return this.each(function() {
            var self = this;

			if(p.t == 0) {
				$(this).html(o.emptyMsg);
				return;
			}

			if(o.resultTarget == '') {
				$res = $('.results',self);
				if(!$res.length) {
					$(self).append('<div class="results"></div>');
					$res = $('.results',self);
				}
			} else {
				$res = $(o.resultTarget,self);
			}

			if(o.pagesTarget == '') {
				$pag = $(".pagination", self);
				if(!$pag.length) {
					$(self).prepend('<div class="pagination"></div>');
					$pag = $(".pagination",self);
				}
			} else {
				$pag = $(o.pagesTarget,self);
			}
			
			p.tpl = $(".template", this).html();


			var $sLinks = $(".sort", this);
			$sLinks.click(function() {
				hash = $(this).attr('href').replace(/^.*#/, '');
				$.historyLoad(hash);
			});

			self.doRes = function() {
				var bgFlag = false;
				var offset = o.start+o.limit;
				
				if(offset > p.t) {
					offset = p.t;
				}
				
				if($.isArray(o.bgColors) && o.bgColors.length == 2) {
						bgFlag = true;
				}
				
				$res.empty();
				
				for(var i = o.start; i < offset; i++) {
					var template = p.tpl;
					if(bgFlag) {
						var bgcolor = (i%2) ? o.bgColors[0] : o.bgColors[1];
						var regex = new RegExp('%bgcolor%', "g");
						template = template.replace(regex, bgcolor);
					}
					for(var k in p.r[i]) {
						var regex = new RegExp("%"+ k +"%", "g");
						template = template.replace(regex, p.r[i][k]);
					}
					$res.append(template);
				}
				
				if(typeof o.callback == "function") {
					o.callback();
				}
			}

			self.doSort = function() {
				switch(s.d) {		
					case "date":
						p.r.sort(self.sortDate);
					break;
					default:
						p.r.sort(self.sortGen);
					break;
				}
			}

			self.doPag = function() {
				var pages = new Array();
				pages = self.gPL();
				p.pg = "";
				p.n = self.gNP();
				p.p = self.gPP();
				p.pp = "";
				p.np = "";

				var url = document.location.toString().split("#")[0];
				if(p.t > o.limit) {
					var id = $(self).attr('id');					
					for(var i = 0, len = pages.length; i < len;i++) {
						if(pages[i] != undefined) {
							if( i == (o.start/o.limit)+1 ) {
								p.pg += "&nbsp;<span class='active'>"+ i +"</span>&nbsp;";
							} else {
								p.pg += "&nbsp;<a href='#"+ id +"-"+ pages[i] +"' alt='"+ pages[i] +"'>"+ i +"</a>&nbsp;";
							}
						}
					}
					
					p.pp = "&nbsp;"+ ((o.start != 0) ? "<a href='#"+ id +"-"+ p.p +"' alt='"+ p.p +"'>Prev</a>" : "<span class='active'>Prev</span>") +"&nbsp;";
					p.np = "&nbsp;"+ ((p.n < p.t && p.n != 0) ? "<a href='#"+ id +"-"+ p.n +"' alt='"+ p.n +"'>Next</a>" : "<span class='active'>Next</span>") +"&nbsp;";					
					
					$pag.empty();
					$pag.append(p.pp + p.pg + p.np);
					
					$("a", $pag).click(function(e) {				
						o.start = parseInt($(this).attr('alt'));

						hash = $(this).attr('href').replace(/^.*#/, '');
						$.historyLoad(hash);
						return false;
					});
				} else {
					$pag.empty();					
				}
			}

			self.gPL = function() {
				p.sp  = (Math.ceil((o.start / o.limit)) + 1);
				p.ep    = p.tp;
				p.ls  = Math.ceil(p.sp - (o.pages / 2));
				p.le    = Math.floor(p.sp + (o.pages / 2));
		
				if (p.le > p.tp) {
					p.ls = (p.tp - (o.pages - 1));
					p.le = p.tp;
				}
		
				if ((p.le - Math.abs(p.ls)) < (o.pages - 1)) {
					p.le = o.pages;
				}
		
				if(p.ls < 1) {
					p.ls = 1;
				}
		
				var arr = new Array();
				for (var i = p.ls; i <= p.le ; ++i) {
					if (i > p.tp) {
						break;
					}
					arr[i] = ((i - 1) * o.limit);
				}
				return arr;
			
			}

			self.gNP = function() {
				return ((o.start + o.limit) > p.t) ? 0 : (o.start + o.limit);
			}
		
			self.gPP = function() {
				return (o.start == 0) ? 0 : (o.start - o.limit);
			}

			self._history = function(hash) {
				if(hash) {
					var argv = hash.split("-");
					switch(argv[0]) {
						case "sort":
							var col = hash.split("-")[1];
							s.a = (s.a) ? (s.b != col) ? true : false : true;
							s.d = (argv[2] != "undefined") ? argv[2] : '';
							s.b = col;

							for(var i =0,len = $sLinks.length; i < len; i++) {
								var $p = $sLinks.eq(i).parent();
								var $m = $(".sort-marker", $p);
								if($sLinks[i].hash == "#"+ hash) {
									var aVal = (s.a) ? o.arrows[0] : o.arrows[1];
									if($m.length) {
										$m.html(aVal);
									} else {
										$p.prepend("<span class='sort-marker'>"+ aVal +"</span>");
									}
								} else {
									$m.remove();
								}
							}							
							self.doSort();
						break;
						default:
							var noff = parseInt(hash.split("-")[1]);
							if(isNaN(noff)) {
								return;
							}
							o.start = noff;
						break;
					}
				} else {
					o.start = p.is;
				} 

				self.doPag();
				self.doRes();								
			}

			self.sortGen = function(a,b) {			
				if(a[s.b] == b[s.b]) return 0;
				if(isNaN(a[s.b]) && isNaN(b[s.b])) return (s.a) ? ((a[s.b] < b[s.b]) ? -1 : 1) : ((a[s.b] > b[s.b]) ? -1 : 1);
				else return (s.a) ? (a[s.b] - b[s.b]) : (b[s.b] - a[s.b]);					
			}

			var dateRE = new RegExp(/^(\d{1,2})[\/\- ](\d{1,2})[\/\- ](\d{4})/);
			self.sortDate = function (a, b) {
				var tmpA = a[s.b].replace(dateRE,"$3$2$1");
				var tmpB = b[s.b].replace(dateRE,"$3$2$1");
				if(tmpA == tmpB) return 0;
				return (s.a) ? ((tmpA > tmpB) ? 1 : -1) : ((tmpA < tmpB) ? 1 : -1) ;
			}
			$.historyInit(self._history);		
		});
	}
})(jQuery);
