$(document).ready(function(){
	$("p.byBrands a").click(function(){
		if ($(".navigation").is(':hidden')){
			$(".navigation").fadeIn();
		}
		else{$(".navigation").fadeOut();}
	});
	$("body").on("click", function(event){
		if($('.navigation').is(':animated') == false){
			if ($(event.target).parents('.navigation').length < 1){
				$(".navigation").fadeOut();
			}
		}
	});
	$(".catalogThumbs a").hover(function(){
		var href = $(this).attr('href');
		$(this).parents('.catalogBox').find('.catalogImage img').attr('src', href);
		return false;
	});
	$(".productBoxThumbs a").click(function(){
		var href = $(this).attr('href');
		$('.productBoxThumb, .productBoxThumbLast').removeClass('productBoxThumb-a');
		$(this).parent().addClass('productBoxThumb-a');
		$(this).parents('.contentBox').find('.productBoxTopLeft img').attr('src', href);
		return false;
	});
	$(".footer-column3").eq(1).addClass('footer-column3-2');
	// Tab widget
	$(".checkout_tab_content").hide();
	$(".checkoutBoxLeft ul li:first a").addClass("activeCheckoutTab").show();
	$(".checkout_tab_content:first").show();
	
	$(".checkoutBoxLeft ul li a").click(function(){
		$(".checkoutBoxLeft ul li a").removeClass("activeCheckoutTab");
		$(this).addClass("activeCheckoutTab");
		$(".checkout_tab_content").hide();
		
		var activeTab = $(this).attr("href");
		$(activeTab).fadeIn();
		return false;
	});
	$(".level-1 > div").wrapInner('<div class="new" />');
	$(window).bind("load",function(){
		var drops = $('.new');
		$(drops).each(function () {
			var el =$(this);
			var heig = el.parents('.level-1').parent().height();
			el.height(heig - 33);
		});
	});
	$("li.level-1").hover(function () {
		$(this).addClass('activeli');
	},	function () {
		$(this).removeClass('activeli');
	});
	$("li.level-0").hover(function () {
		$(this).find('.level-1:first').addClass('activeli');
	});
	
	$('.banner ul').bxSlider({
		mode: 'vertical',
		pager: true,
		controls:false,
		pause:5000,
		speed:500,
		auto:true,
		step:1
	});
	$('.bx-pager').wrap('<div class="holder" />');
	$('.productBoxRating p:first').addClass('starred');
	$('.latestProduct:nth-child(4n+1)').addClass('latestProduct-fc');
	initCustomForms();
	$('.relatedProducts ul').bxSlider({
		pause:5000,
		speed:500,
		auto:false,
		displaySlideQty:4,
		pager:false
	});
});

/**
 * jQuery bxSlider v3.0
 * http://bxslider.com
 *
 * Copyright 2010, Steven Wanderski
 * http://bxcreative.com
 *
 * Free to use and abuse under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 */
(function(a){a.fn.bxSlider=function(b){function Z(b,c,d,e){var f=[];var g=d;var h=false;if(e=="backward"){b=a.makeArray(b);b.reverse()}while(g>0){a.each(b,function(b,d){if(g>0){if(!h){if(b==c){h=true;f.push(a(this).clone());g--}}else{f.push(a(this).clone());g--}}else{return false}})}return f}function Y(){var a=i.outerHeight()*b.displaySlideQty;return a}function X(){var a=i.outerWidth()*b.displaySlideQty;return a}function W(b,c){if(c=="left"){var d=a(".pager",h).eq(b).position().left}else if(c=="top"){var d=a(".pager",h).eq(b).position().top}return d}function V(){if(!b.infiniteLoop&&b.hideControlOnEnd){if(x==F){a(".bx-prev",h).hide()}else{a(".bx-prev",h).show()}if(x==G){a(".bx-next",h).hide()}else{a(".bx-next",h).show()}}}function U(c,e,f,g){p=a('<a href="" class="bx-start"></a>');if(c=="text"){r=e}else{r='<img src="'+e+'" />'}if(f=="text"){s=g}else{s='<img src="'+g+'" />'}if(b.autoControlsSelector){a(b.autoControlsSelector).append(p)}else{h.append('<div class="bx-auto"></div>');a(".bx-auto",h).html(p)}p.click(function(){if(b.ticker){if(a(this).hasClass("stop")){d.stopTicker()}else if(a(this).hasClass("start")){d.startTicker()}}else{if(a(this).hasClass("stop")){d.stopShow(true)}else if(a(this).hasClass("start")){d.startShow(true)}}return false})}function T(){var c=a("img",g.eq(x)).attr("title");if(c!=""){if(b.captionsSelector){a(b.captionsSelector).html(c)}else{a(".bx-captions",h).html(c)}}else{if(b.captionsSelector){a(b.captionsSelector).html(" ")}else{a(".bx-captions",h).html(" ")}}}function S(c){var e=g.length;if(b.moveSlideQty>1){if(g.length%b.moveSlideQty!=0){e=Math.ceil(g.length/b.moveSlideQty)}else{e=g.length/b.moveSlideQty}}var f="";if(b.buildPager){for(var i=0;i<e;i++){f+=b.buildPager(i,g.eq(i*b.moveSlideQty))}}else if(c=="full"){for(var i=1;i<=e;i++){f+='<a href="" class="pager-link pager-'+i+'">'+i+"</a>"}}else if(c=="short"){f='<span class="bx-pager-current">'+(b.startingSlide+1)+"</span> "+b.pagerShortSeparator+' <span class="bx-pager-total">'+g.length+"</span>"}if(b.pagerSelector){a(b.pagerSelector).append(f);n=a(b.pagerSelector)}else{var j=a('<div class="bx-pager"></div>');j.append(f);if(b.pagerLocation=="top"){h.prepend(j)}else if(b.pagerLocation=="bottom"){h.append(j)}n=a(".bx-pager",h)}n.children().click(function(){if(b.pagerType=="full"){var a=n.children().index(this);if(b.moveSlideQty>1){a*=b.moveSlideQty}d.goToSlide(a)}return false})}function R(c,e,f,g){var i=a('<a href="" class="bx-next"></a>');var j=a('<a href="" class="bx-prev"></a>');if(c=="text"){i.html(e)}else{i.html('<img src="'+e+'" />')}if(f=="text"){j.html(g)}else{j.html('<img src="'+g+'" />')}if(b.prevSelector){a(b.prevSelector).append(j)}else{h.append(j)}if(b.nextSelector){a(b.nextSelector).append(i)}else{h.append(i)}i.click(function(){d.goToNextSlide();return false});j.click(function(){d.goToPreviousSlide();return false})}function Q(c){if(b.pagerType=="full"&&b.pager){a("a",n).removeClass(b.pagerActiveClass);a("a",n).eq(c).addClass(b.pagerActiveClass)}else if(b.pagerType=="short"&&b.pager){a(".bx-pager-current",n).html(x+1)}}function P(){g.not(":eq("+x+")").fadeTo(b.speed,0).css("zIndex",98);g.eq(x).css("zIndex",99).fadeTo(b.speed,1,function(){E=false;if(jQuery.browser.msie){g.eq(x).get(0).style.removeAttribute("filter")}b.onAfterSlide(x,g.length,g.eq(x))})}function O(){e.hover(function(){if(t){d.stopTicker(false)}},function(){if(t){d.startTicker(false)}})}function N(){h.find(".bx-window").hover(function(){if(t){d.stopShow(false)}},function(){if(t){d.startShow(false)}})}function M(){if(b.startImage!=""){startContent=b.startImage;startType="image"}else{startContent=b.startText;startType="text"}if(b.stopImage!=""){stopContent=b.stopImage;stopType="image"}else{stopContent=b.stopText;stopType="text"}U(startType,startContent,stopType,stopContent)}function L(a,c,d){if(b.mode=="horizontal"){if(b.tickerDirection=="next"){e.animate({left:"-="+c+"px"},d,"linear",function(){e.css("left",a);L(a,A,b.tickerSpeed)})}else if(b.tickerDirection=="prev"){e.animate({left:"+="+c+"px"},d,"linear",function(){e.css("left",a);L(a,A,b.tickerSpeed)})}}else if(b.mode=="vertical"){if(b.tickerDirection=="next"){e.animate({top:"-="+c+"px"},d,"linear",function(){e.css("top",a);L(a,B,b.tickerSpeed)})}else if(b.tickerDirection=="prev"){e.animate({top:"+="+c+"px"},d,"linear",function(){e.css("top",a);L(a,B,b.tickerSpeed)})}}}function K(){if(b.auto){if(!b.infiniteLoop){if(b.autoDirection=="next"){o=setInterval(function(){x+=b.moveSlideQty;if(x>G){x=x%g.length}d.goToSlide(x,false)},b.pause)}else if(b.autoDirection=="prev"){o=setInterval(function(){x-=b.moveSlideQty;if(x<0){negativeOffset=x%g.length;if(negativeOffset==0){x=0}else{x=g.length+negativeOffset}}d.goToSlide(x,false)},b.pause)}}else{if(b.autoDirection=="next"){o=setInterval(function(){d.goToNextSlide(false)},b.pause)}else if(b.autoDirection=="prev"){o=setInterval(function(){d.goToPreviousSlide(false)},b.pause)}}}else if(b.ticker){b.tickerSpeed*=10;a(".pager",h).each(function(b){A+=a(this).width();B+=a(this).height()});if(b.tickerDirection=="prev"&&b.mode=="horizontal"){e.css("left","-"+(A+y)+"px")}else if(b.tickerDirection=="prev"&&b.mode=="vertical"){e.css("top","-"+(B+z)+"px")}if(b.mode=="horizontal"){C=parseInt(e.css("left"));L(C,A,b.tickerSpeed)}else if(b.mode=="vertical"){D=parseInt(e.css("top"));L(D,B,b.tickerSpeed)}if(b.tickerHover){O()}}}function J(){if(b.nextImage!=""){nextContent=b.nextImage;nextType="image"}else{nextContent=b.nextText;nextType="text"}if(b.prevImage!=""){prevContent=b.prevImage;prevType="image"}else{prevContent=b.prevText;prevType="text"}R(nextType,nextContent,prevType,prevContent)}function I(){if(b.mode=="horizontal"||b.mode=="vertical"){var c=Z(g,0,b.moveSlideQty,"backward");a.each(c,function(b){e.prepend(a(this))});var d=g.length+b.moveSlideQty-1;var f=g.length-b.displaySlideQty;var h=d-f;var i=Z(g,0,h,"forward");if(b.infiniteLoop){a.each(i,function(b){e.append(a(this))})}}}function H(){I(b.startingSlide);if(b.mode=="horizontal"){e.wrap('<div class="'+b.wrapperClass+'" style="width:'+l+'px; position:relative;"></div>').wrap('<div class="bx-window" style="position:relative; overflow:hidden; width:'+l+'px;"></div>').css({width:"999999px",position:"relative",left:"-"+y+"px"});e.children().css({width:j,"float":"left",listStyle:"none"});h=e.parent().parent();g.addClass("pager")}else if(b.mode=="vertical"){e.wrap('<div class="'+b.wrapperClass+'" style="width:'+v+'px; position:relative;"></div>').wrap('<div class="bx-window" style="width:'+v+"px; height:"+m+'px; position:relative; overflow:hidden;"></div>').css({height:"999999px",position:"relative",top:"-"+z+"px"});e.children().css({listStyle:"none",height:w});h=e.parent().parent();g.addClass("pager")}else if(b.mode=="fade"){e.wrap('<div class="'+b.wrapperClass+'" style="width:'+v+'px; position:relative;"></div>').wrap('<div class="bx-window" style="height:'+w+"px; width:"+v+'px; position:relative; overflow:hidden;"></div>');e.children().css({listStyle:"none",position:"absolute",top:0,left:0,zIndex:98});h=e.parent().parent();g.not(":eq("+x+")").fadeTo(0,0);g.eq(x).css("zIndex",99)}if(b.captions&&b.captionsSelector==null){h.append('<div class="bx-captions"></div>')}}var c={mode:"horizontal",infiniteLoop:true,hideControlOnEnd:false,controls:true,speed:500,easing:"swing",pager:false,pagerSelector:null,pagerType:"full",pagerLocation:"bottom",pagerShortSeparator:"/",pagerActiveClass:"pager-active",nextText:"next",nextImage:"",nextSelector:null,prevText:"prev",prevImage:"",prevSelector:null,captions:false,captionsSelector:null,auto:false,autoDirection:"next",autoControls:false,autoControlsSelector:null,autoStart:true,autoHover:false,autoDelay:0,pause:3e3,startText:"start",startImage:"",stopText:"stop",stopImage:"",ticker:false,tickerSpeed:5e3,tickerDirection:"next",tickerHover:false,wrapperClass:"bx-wrapper",startingSlide:0,displaySlideQty:1,moveSlideQty:1,randomStart:false,onBeforeSlide:function(){},onAfterSlide:function(){},onLastSlide:function(){},onFirstSlide:function(){},onNextSlide:function(){},onPrevSlide:function(){},buildPager:null};var b=a.extend(c,b);var d=this;var e="";var f="";var g="";var h="";var i="";var j="";var k="";var l="";var m="";var n="";var o="";var p="";var q="";var r="";var s="";var t=true;var u=false;var v=0;var w=0;var x=0;var y=0;var z=0;var A=0;var B=0;var C=0;var D=0;var E=false;var F=0;var G=g.length-1;this.goToSlide=function(a,c){if(!E){E=true;x=a;b.onBeforeSlide(x,g.length,g.eq(x));if(typeof c=="undefined"){var c=true}if(c){if(b.auto){d.stopShow(true)}}slide=a;if(slide==F){b.onFirstSlide(x,g.length,g.eq(x))}if(slide==G){b.onLastSlide(x,g.length,g.eq(x))}if(b.mode=="horizontal"){e.animate({left:"-"+W(slide,"left")+"px"},b.speed,b.easing,function(){E=false;b.onAfterSlide(x,g.length,g.eq(x))})}else if(b.mode=="vertical"){e.animate({top:"-"+W(slide,"top")+"px"},b.speed,b.easing,function(){E=false;b.onAfterSlide(x,g.length,g.eq(x))})}else if(b.mode=="fade"){P()}V();if(b.moveSlideQty>1){a=Math.floor(a/b.moveSlideQty)}Q(a);T()}};this.goToNextSlide=function(a){if(typeof a=="undefined"){var a=true}if(a){if(b.auto){d.stopShow(true)}}if(!b.infiniteLoop){if(!E){var c=false;x=x+b.moveSlideQty;if(x<=G){V();b.onNextSlide(x,g.length,g.eq(x));d.goToSlide(x)}else{x-=b.moveSlideQty}}}else{if(!E){E=true;var c=false;x=x+b.moveSlideQty;if(x>G){x=x%g.length;c=true}b.onNextSlide(x,g.length,g.eq(x));b.onBeforeSlide(x,g.length,g.eq(x));if(b.mode=="horizontal"){var f=b.moveSlideQty*k;e.animate({left:"-="+f+"px"},b.speed,b.easing,function(){E=false;if(c){e.css("left","-"+W(x,"left")+"px")}b.onAfterSlide(x,g.length,g.eq(x))})}else if(b.mode=="vertical"){var h=b.moveSlideQty*w;e.animate({top:"-="+h+"px"},b.speed,b.easing,function(){E=false;if(c){e.css("top","-"+W(x,"top")+"px")}b.onAfterSlide(x,g.length,g.eq(x))})}else if(b.mode=="fade"){P()}if(b.moveSlideQty>1){Q(Math.ceil(x/b.moveSlideQty))}else{Q(x)}T()}}};this.goToPreviousSlide=function(c){if(typeof c=="undefined"){var c=true}if(c){if(b.auto){d.stopShow(true)}}if(!b.infiniteLoop){if(!E){var f=false;x=x-b.moveSlideQty;if(x<0){x=0;if(b.hideControlOnEnd){a(".bx-prev",h).hide()}}V();b.onPrevSlide(x,g.length,g.eq(x));d.goToSlide(x)}}else{if(!E){E=true;var f=false;x=x-b.moveSlideQty;if(x<0){negativeOffset=x%g.length;if(negativeOffset==0){x=0}else{x=g.length+negativeOffset}f=true}b.onPrevSlide(x,g.length,g.eq(x));b.onBeforeSlide(x,g.length,g.eq(x));if(b.mode=="horizontal"){var i=b.moveSlideQty*k;e.animate({left:"+="+i+"px"},b.speed,b.easing,function(){E=false;if(f){e.css("left","-"+W(x,"left")+"px")}b.onAfterSlide(x,g.length,g.eq(x))})}else if(b.mode=="vertical"){var j=b.moveSlideQty*w;e.animate({top:"+="+j+"px"},b.speed,b.easing,function(){E=false;if(f){e.css("top","-"+W(x,"top")+"px")}b.onAfterSlide(x,g.length,g.eq(x))})}else if(b.mode=="fade"){P()}if(b.moveSlideQty>1){Q(Math.ceil(x/b.moveSlideQty))}else{Q(x)}T()}}};this.goToFirstSlide=function(a){if(typeof a=="undefined"){var a=true}d.goToSlide(F,a)};this.goToLastSlide=function(){if(typeof a=="undefined"){var a=true}d.goToSlide(G,a)};this.getCurrentSlide=function(){return x};this.getSlideCount=function(){return g.length};this.stopShow=function(a){clearInterval(o);if(typeof a=="undefined"){var a=true}if(a&&b.autoControls){p.html(r).removeClass("stop").addClass("start");t=false}};this.startShow=function(a){if(typeof a=="undefined"){var a=true}K();if(a&&b.autoControls){p.html(s).removeClass("start").addClass("stop");t=true}};this.stopTicker=function(a){e.stop();if(typeof a=="undefined"){var a=true}if(a&&b.ticker){p.html(r).removeClass("stop").addClass("start");t=false}};this.startTicker=function(a){if(b.mode=="horizontal"){if(b.tickerDirection=="next"){var c=parseInt(e.css("left"));var d=A+c+g.eq(0).width()}else if(b.tickerDirection=="prev"){var c=-parseInt(e.css("left"));var d=c-g.eq(0).width()}var f=d*b.tickerSpeed/A;L(C,d,f)}else if(b.mode=="vertical"){if(b.tickerDirection=="next"){var h=parseInt(e.css("top"));var d=B+h+g.eq(0).height()}else if(b.tickerDirection=="prev"){var h=-parseInt(e.css("top"));var d=h-g.eq(0).height()}var f=d*b.tickerSpeed/B;L(D,d,f);if(typeof a=="undefined"){var a=true}if(a&&b.ticker){p.html(s).removeClass("start").addClass("stop");t=true}}};this.initShow=function(){e=a(this);f=e.clone();g=e.children();h="";i=e.children(":first");j=i.width();v=0;k=i.outerWidth();w=0;l=X();m=Y();E=false;n="";x=0;y=0;z=0;o="";p="";q="";r="";s="";t=true;u=false;A=0;B=0;C=0;D=0;F=0;G=g.length-1;g.each(function(b){if(a(this).outerHeight()>w){w=a(this).outerHeight()}if(a(this).outerWidth()>v){v=a(this).outerWidth()}});if(b.randomStart){var c=Math.floor(Math.random()*g.length);x=c;y=k*(b.moveSlideQty+c);z=w*(b.moveSlideQty+c)}else{x=b.startingSlide;y=k*(b.moveSlideQty+b.startingSlide);z=w*(b.moveSlideQty+b.startingSlide)}H();if(b.pager&&!b.ticker){if(b.pagerType=="full"){S("full")}else if(b.pagerType=="short"){S("short")}}if(b.controls&&!b.ticker){J()}if(b.auto||b.ticker){if(b.autoControls){M()}if(b.autoStart){setTimeout(function(){d.startShow(true)},b.autoDelay)}else{d.stopShow(true)}if(b.autoHover&&!b.ticker){N()}}if(b.moveSlideQty>1){Q(Math.ceil(x/b.moveSlideQty))}else{Q(x)}V();if(b.captions){T()}b.onAfterSlide(x,g.length,g.eq(x))};this.destroyShow=function(){clearInterval(o);a(".bx-next, .bx-prev, .bx-pager, .bx-auto",h).remove();e.unwrap().unwrap().removeAttr("style");e.children().removeAttr("style").not(".pager").remove();g.removeClass("pager")};this.reloadShow=function(){d.destroyShow();d.initShow()};this.each(function(){if(a(this).children().length>0){d.initShow()}});return this};jQuery.fx.prototype.cur=function(){if(this.elem[this.prop]!=null&&(!this.elem.style||this.elem.style[this.prop]==null)){return this.elem[this.prop]}var a=parseFloat(jQuery.css(this.elem,this.prop));return a}})($)

function initTabs(){
	var sets = document.getElementsByTagName("ul");
	for (var i = 0; i < sets.length; i++){
		if (sets[i].className.indexOf("tabset") != -1){
			var tabs = [];
			var links = sets[i].getElementsByTagName("a");
			for (var j = 0; j < links.length; j++){
				if (links[j].className.indexOf("tab") != -1){
					tabs.push(links[j]);
					links[j].tabs = tabs;
					var c = document.getElementById(links[j].href.substr(links[j].href.indexOf("#") + 1));
					if (c) if (links[j].parentNode.parentNode.className.indexOf("active") != -1) c.style.display = "block";
					else c.style.display = "none";
					links[j].onclick = function (){
						var c = document.getElementById(this.href.substr(this.href.indexOf("#") + 1));
						if (c){
							for (var i = 0; i < this.tabs.length; i++){
								var tab = document.getElementById(this.tabs[i].href.substr(this.tabs[i].href.indexOf("#") + 1));
								if (tab){
									tab.style.display = "none";
								}
								this.tabs[i].parentNode.parentNode.className = this.tabs[i].parentNode.parentNode.className.replace("active", "");
							}
							this.parentNode.parentNode.className += " active";
							c.style.display = "block";
							return false;
						}
					}
				}
			}
		}
	}
}
if (window.addEventListener)
	window.addEventListener("load", initTabs, false);
else if (window.attachEvent && !window.opera)
	window.attachEvent("onload", initTabs);

// custom forms init
function initCustomForms() {
	$('select').customSelect();
}
// custom selects module
$.fn.customSelect = function(_options) {
	var _options = $.extend({
		selectStructure: '<div class="selectArea"><span class="left"></span><span class="center"></span><a href="#" class="selectButton"></a><div class="disabled"></div></div>',
		hideOnMouseOut: false,
		copyClass: true,
		selectText: '.center',
		selectBtn: '.selectButton',
		selectDisabled: '.disabled',
		optStructure: '<div class="optionsDivVisible"><div class="select-top"></div><div class="select-center"><ul></ul></div><div class="select-bottom"></div></div>',
		optList: 'ul'
	}, _options);
	return this.each(function() {
		var select = $(this);
		if(!select.hasClass('outtaHere')) {
			if(select.is(':visible')) {
				var hideOnMouseOut = _options.hideOnMouseOut;
				var copyClass = _options.copyClass;
				var replaced = $(_options.selectStructure);
				var selectText = replaced.find(_options.selectText);
				var selectBtn = replaced.find(_options.selectBtn);
				var selectDisabled = replaced.find(_options.selectDisabled).hide();
				var optHolder = $(_options.optStructure);
				var optList = optHolder.find(_options.optList);
				if(copyClass) optHolder.addClass('drop-'+select.attr('class'));
				replaced.get(0).className += ' ' + select.get(0).className

				if(select.attr('disabled')) selectDisabled.show();
				select.find('option').each(function(){
					var selOpt = $(this);
					var _opt = $('<li><a href="#">' + selOpt.html() + '</a></li>');
					if(selOpt.attr('selected')) {
						selectText.html(selOpt.html());
						_opt.addClass('selected');
					}
					_opt.children('a').click(function() {
						optList.find('li').removeClass('selected');
						select.find('option').removeAttr('selected');
						$(this).parent().addClass('selected');
						selOpt.attr('selected', 'selected');
						selectText.html(selOpt.html());
						select.change();
						optHolder.hide();
						return false;
					});
					optList.append(_opt);
				});
				replaced.width(select.outerWidth());
				replaced.insertBefore(select);
				optHolder.css({
					width: select.outerWidth(),
					display: 'none',
					position: 'absolute'
				});
				$(document.body).append(optHolder);

				var optTimer;
				replaced.hover(function() {
					if(optTimer) clearTimeout(optTimer);
				}, function() {
					if(hideOnMouseOut) {
						optTimer = setTimeout(function() {
							optHolder.hide();
						}, 200);
					}
				});
				optHolder.hover(function(){
					if(optTimer) clearTimeout(optTimer);
				}, function() {
					if(hideOnMouseOut) {
						optTimer = setTimeout(function() {
							optHolder.hide();
						}, 200);
					}
				});
				selectBtn.click(function() {
					if(optHolder.is(':visible')) {
						optHolder.hide();
					}
					else{
						if(_activeDrop) _activeDrop.hide();
						optHolder.children('ul').css({height:'auto', overflow:'hidden'});
						optHolder.css({
							top: replaced.offset().top + replaced.outerHeight(),
							left: replaced.offset().left,
							display: 'block'
						});
						if(optHolder.children('ul').height() > 200) optHolder.children('ul').css({height:200, overflow:'auto'});
						_activeDrop = optHolder;
					}
					return false;
				});
				select.addClass('outtaHere');
			}
		}
	});
}

// event handler on DOM ready
var _activeDrop;
$(function(){
	$('body').click(hideOptionsClick)
	$(window).resize(hideOptions)
});
function hideOptions() {
	if(_activeDrop && _activeDrop.length) {
		_activeDrop.hide();
		_activeDrop = null;
	}
}
function hideOptionsClick(e) {
	if(_activeDrop && _activeDrop.length) {
		var f = false;
		$(e.target).parents().each(function(){
			if(this == _activeDrop) f=true;
		});
		if(!f) {
			_activeDrop.hide();
			_activeDrop = null;
		}
	}
}