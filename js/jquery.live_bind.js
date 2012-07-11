/* Copyright (c) 2008 DIYism (email/msn/gtalk:kexianbin@diyism.com web:http://diyism.com)
 * Licensed under GPL (http://www.opensource.org/licenses/gpl-license.php) license.
 *
 * Version: k923
 */

$.fn.is_match=function(selector) {
	selector=selector.split(/\s+/).reverse().join(' ').split(' > ').join('").parent("').split(' + ').join('").prev("').split(' ~ ').join('").prevAll("').split(/\s+/).join('").parents("');
return eval('this.filter("'+selector+'").length');
}
$.live_bind=function(selector, etype, fn)
                    {var fn_tmp=function(event) //one fn_tmp per selector para
                                {var event=event || window.event,
                                 src_ele=event.srcElement || event.target;
                                 if (!src_ele.parentNode) 
                                    {return; //for parentNode maybe replaced by other bound events
                                    }
                                 if ($(src_ele).is_match(selector))
                                    {fn.call(src_ele, event); //for support 'this'
                                    }
                                };
                     if ($.inArray(etype, ['focus', 'blur'])=='-1')
                        {$(document).bind(etype, fn_tmp);
                        }
                     else
                         {if (document.addEventListener)
                             {document.addEventListener(etype, fn_tmp, true);
                             }
                          else
                              {etype=etype=='focus'?'focusin':'focusout';
                               document.attachEvent('on'+etype, fn_tmp);
                              }
                         }
                    };