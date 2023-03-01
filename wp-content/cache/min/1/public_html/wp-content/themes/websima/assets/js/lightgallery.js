/*! lightgallery - v1.6.7 - 2018-02-11
* http://sachinchoolur.github.io/lightGallery/
* Copyright (c) 2018 Sachin N; Licensed GPLv3 */
(function(root,factory){if(typeof define==='function'&&define.amd){define(['jquery'],function(a0){return(factory(a0))})}else if(typeof exports==='object'){module.exports=factory(require('jquery'))}else{factory(root.jQuery)}}(this,function($){(function(){'use strict';var defaults={mode:'lg-slide',cssEasing:'ease',easing:'linear',speed:600,height:'100%',width:'100%',addClass:'',startClass:'lg-start-zoom',backdropDuration:150,hideBarsDelay:6000,useLeft:!1,closable:!0,loop:!0,escKey:!0,keyPress:!0,controls:!0,slideEndAnimatoin:!0,hideControlOnEnd:!1,mousewheel:!0,getCaptionFromTitleOrAlt:!0,appendSubHtmlTo:'.lg-sub-html',subHtmlSelectorRelative:!1,preload:1,showAfterLoad:!0,selector:'',selectWithin:'',nextHtml:'',prevHtml:'',index:!1,iframeMaxWidth:'100%',download:!0,counter:!0,appendCounterTo:'.lg-toolbar',swipeThreshold:50,enableSwipe:!0,enableDrag:!0,dynamic:!1,dynamicEl:[],galleryId:1};function Plugin(element,options){this.el=element;this.$el=$(element);this.s=$.extend({},defaults,options);if(this.s.dynamic&&this.s.dynamicEl!=='undefined'&&this.s.dynamicEl.constructor===Array&&!this.s.dynamicEl.length){throw('When using dynamic mode, you must also define dynamicEl as an Array.')}
this.modules={};this.lGalleryOn=!1;this.lgBusy=!1;this.hideBartimeout=!1;this.isTouch=('ontouchstart' in document.documentElement);if(this.s.slideEndAnimatoin){this.s.hideControlOnEnd=!1}
if(this.s.dynamic){this.$items=this.s.dynamicEl}else{if(this.s.selector==='this'){this.$items=this.$el}else if(this.s.selector!==''){if(this.s.selectWithin){this.$items=$(this.s.selectWithin).find(this.s.selector)}else{this.$items=this.$el.find($(this.s.selector))}}else{this.$items=this.$el.children()}}
this.$slide='';this.$outer='';this.init();return this}
Plugin.prototype.init=function(){var _this=this;if(_this.s.preload>_this.$items.length){_this.s.preload=_this.$items.length}
var _hash=window.location.hash;if(_hash.indexOf('lg='+this.s.galleryId)>0){_this.index=parseInt(_hash.split('&slide=')[1],10);$('body').addClass('lg-from-hash');if(!$('body').hasClass('lg-on')){setTimeout(function(){_this.build(_this.index)});$('body').addClass('lg-on')}}
if(_this.s.dynamic){_this.$el.trigger('onBeforeOpen.lg');_this.index=_this.s.index||0;if(!$('body').hasClass('lg-on')){setTimeout(function(){_this.build(_this.index);$('body').addClass('lg-on')})}}else{_this.$items.on('click.lgcustom',function(event){try{event.preventDefault();event.preventDefault()}catch(er){event.returnValue=!1}
_this.$el.trigger('onBeforeOpen.lg');_this.index=_this.s.index||_this.$items.index(this);if(!$('body').hasClass('lg-on')){_this.build(_this.index);$('body').addClass('lg-on')}})}};Plugin.prototype.build=function(index){var _this=this;_this.structure();$.each($.fn.lightGallery.modules,function(key){_this.modules[key]=new $.fn.lightGallery.modules[key](_this.el)});_this.slide(index,!1,!1,!1);if(_this.s.keyPress){_this.keyPress()}
if(_this.$items.length>1){_this.arrow();setTimeout(function(){_this.enableDrag();_this.enableSwipe()},50);if(_this.s.mousewheel){_this.mousewheel()}}else{_this.$slide.on('click.lg',function(){_this.$el.trigger('onSlideClick.lg')})}
_this.counter();_this.closeGallery();_this.$el.trigger('onAfterOpen.lg');_this.$outer.on('mousemove.lg click.lg touchstart.lg',function(){_this.$outer.removeClass('lg-hide-items');clearTimeout(_this.hideBartimeout);_this.hideBartimeout=setTimeout(function(){_this.$outer.addClass('lg-hide-items')},_this.s.hideBarsDelay)});_this.$outer.trigger('mousemove.lg')};Plugin.prototype.structure=function(){var list='';var controls='';var i=0;var subHtmlCont='';var template;var _this=this;$('body').append('<div class="lg-backdrop"></div>');$('.lg-backdrop').css('transition-duration',this.s.backdropDuration+'ms');for(i=0;i<this.$items.length;i++){list+='<div class="lg-item"></div>'}
if(this.s.controls&&this.$items.length>1){controls='<div class="lg-actions">'+'<button class="lg-prev lg-icon">'+this.s.prevHtml+'</button>'+'<button class="lg-next lg-icon">'+this.s.nextHtml+'</button>'+'</div>'}
if(this.s.appendSubHtmlTo==='.lg-sub-html'){subHtmlCont='<div class="lg-sub-html"></div>'}
template='<div class="lg-outer '+this.s.addClass+' '+this.s.startClass+'">'+'<div class="lg" style="width:'+this.s.width+'; height:'+this.s.height+'">'+'<div class="lg-inner">'+list+'</div>'+'<div class="lg-toolbar lg-group">'+'<span class="lg-close lg-icon"></span>'+'</div>'+controls+subHtmlCont+'</div>'+'</div>';$('body').append(template);this.$outer=$('.lg-outer');this.$slide=this.$outer.find('.lg-item');if(this.s.useLeft){this.$outer.addClass('lg-use-left');this.s.mode='lg-slide'}else{this.$outer.addClass('lg-use-css3')}
_this.setTop();$(window).on('resize.lg orientationchange.lg',function(){setTimeout(function(){_this.setTop()},100)});this.$slide.eq(this.index).addClass('lg-current');if(this.doCss()){this.$outer.addClass('lg-css3')}else{this.$outer.addClass('lg-css');this.s.speed=0}
this.$outer.addClass(this.s.mode);if(this.s.enableDrag&&this.$items.length>1){this.$outer.addClass('lg-grab')}
if(this.s.showAfterLoad){this.$outer.addClass('lg-show-after-load')}
if(this.doCss()){var $inner=this.$outer.find('.lg-inner');$inner.css('transition-timing-function',this.s.cssEasing);$inner.css('transition-duration',this.s.speed+'ms')}
setTimeout(function(){$('.lg-backdrop').addClass('in')});setTimeout(function(){_this.$outer.addClass('lg-visible')},this.s.backdropDuration);if(this.s.download){this.$outer.find('.lg-toolbar').append('<a id="lg-download" target="_blank" download class="lg-download lg-icon"></a>')}
this.prevScrollTop=$(window).scrollTop()};Plugin.prototype.setTop=function(){if(this.s.height!=='100%'){var wH=$(window).height();var top=(wH-parseInt(this.s.height,10))/2;var $lGallery=this.$outer.find('.lg');if(wH>=parseInt(this.s.height,10)){$lGallery.css('top',top+'px')}else{$lGallery.css('top','0px')}}};Plugin.prototype.doCss=function(){var support=function(){var transition=['transition','MozTransition','WebkitTransition','OTransition','msTransition','KhtmlTransition'];var root=document.documentElement;var i=0;for(i=0;i<transition.length;i++){if(transition[i]in root.style){return!0}}};if(support()){return!0}
return!1};Plugin.prototype.isVideo=function(src,index){var html;if(this.s.dynamic){html=this.s.dynamicEl[index].html}else{html=this.$items.eq(index).attr('data-html')}
if(!src){if(html){return{html5:!0}}else{console.error('lightGallery :- data-src is not pvovided on slide item '+(index+1)+'. Please make sure the selector property is properly configured. More info - http://sachinchoolur.github.io/lightGallery/demos/html-markup.html');return!1}}
var youtube=src.match(/\/\/(?:www\.)?youtu(?:\.be|be\.com)\/(?:watch\?v=|embed\/)?([a-z0-9\-\_\%]+)/i);var vimeo=src.match(/\/\/(?:www\.)?vimeo.com\/([0-9a-z\-_]+)/i);var dailymotion=src.match(/\/\/(?:www\.)?dai.ly\/([0-9a-z\-_]+)/i);var vk=src.match(/\/\/(?:www\.)?(?:vk\.com|vkontakte\.ru)\/(?:video_ext\.php\?)(.*)/i);if(youtube){return{youtube:youtube}}else if(vimeo){return{vimeo:vimeo}}else if(dailymotion){return{dailymotion:dailymotion}}else if(vk){return{vk:vk}}};Plugin.prototype.counter=function(){if(this.s.counter){$(this.s.appendCounterTo).append('<div id="lg-counter"><span id="lg-counter-current">'+(parseInt(this.index,10)+1)+'</span> / <span id="lg-counter-all">'+this.$items.length+'</span></div>')}};Plugin.prototype.addHtml=function(index){var subHtml=null;var subHtmlUrl;var $currentEle;if(this.s.dynamic){if(this.s.dynamicEl[index].subHtmlUrl){subHtmlUrl=this.s.dynamicEl[index].subHtmlUrl}else{subHtml=this.s.dynamicEl[index].subHtml}}else{$currentEle=this.$items.eq(index);if($currentEle.attr('data-sub-html-url')){subHtmlUrl=$currentEle.attr('data-sub-html-url')}else{subHtml=$currentEle.attr('data-sub-html');if(this.s.getCaptionFromTitleOrAlt&&!subHtml){subHtml=$currentEle.attr('title')||$currentEle.find('img').first().attr('alt')}}}
if(!subHtmlUrl){if(typeof subHtml!=='undefined'&&subHtml!==null){var fL=subHtml.substring(0,1);if(fL==='.'||fL==='#'){if(this.s.subHtmlSelectorRelative&&!this.s.dynamic){subHtml=$currentEle.find(subHtml).html()}else{subHtml=$(subHtml).html()}}}else{subHtml=''}}
if(this.s.appendSubHtmlTo==='.lg-sub-html'){if(subHtmlUrl){this.$outer.find(this.s.appendSubHtmlTo).load(subHtmlUrl)}else{this.$outer.find(this.s.appendSubHtmlTo).html(subHtml)}}else{if(subHtmlUrl){this.$slide.eq(index).load(subHtmlUrl)}else{this.$slide.eq(index).append(subHtml)}}
if(typeof subHtml!=='undefined'&&subHtml!==null){if(subHtml===''){this.$outer.find(this.s.appendSubHtmlTo).addClass('lg-empty-html')}else{this.$outer.find(this.s.appendSubHtmlTo).removeClass('lg-empty-html')}}
this.$el.trigger('onAfterAppendSubHtml.lg',[index])};Plugin.prototype.preload=function(index){var i=1;var j=1;for(i=1;i<=this.s.preload;i++){if(i>=this.$items.length-index){break}
this.loadContent(index+i,!1,0)}
for(j=1;j<=this.s.preload;j++){if(index-j<0){break}
this.loadContent(index-j,!1,0)}};Plugin.prototype.loadContent=function(index,rec,delay){var _this=this;var _hasPoster=!1;var _$img;var _src;var _poster;var _srcset;var _sizes;var _html;var getResponsiveSrc=function(srcItms){var rsWidth=[];var rsSrc=[];for(var i=0;i<srcItms.length;i++){var __src=srcItms[i].split(' ');if(__src[0]===''){__src.splice(0,1)}
rsSrc.push(__src[0]);rsWidth.push(__src[1])}
var wWidth=$(window).width();for(var j=0;j<rsWidth.length;j++){if(parseInt(rsWidth[j],10)>wWidth){_src=rsSrc[j];break}}};if(_this.s.dynamic){if(_this.s.dynamicEl[index].poster){_hasPoster=!0;_poster=_this.s.dynamicEl[index].poster}
_html=_this.s.dynamicEl[index].html;_src=_this.s.dynamicEl[index].src;if(_this.s.dynamicEl[index].responsive){var srcDyItms=_this.s.dynamicEl[index].responsive.split(',');getResponsiveSrc(srcDyItms)}
_srcset=_this.s.dynamicEl[index].srcset;_sizes=_this.s.dynamicEl[index].sizes}else{if(_this.$items.eq(index).attr('data-poster')){_hasPoster=!0;_poster=_this.$items.eq(index).attr('data-poster')}
_html=_this.$items.eq(index).attr('data-html');_src=_this.$items.eq(index).attr('href')||_this.$items.eq(index).attr('data-src');if(_this.$items.eq(index).attr('data-responsive')){var srcItms=_this.$items.eq(index).attr('data-responsive').split(',');getResponsiveSrc(srcItms)}
_srcset=_this.$items.eq(index).attr('data-srcset');_sizes=_this.$items.eq(index).attr('data-sizes')}
var iframe=!1;if(_this.s.dynamic){if(_this.s.dynamicEl[index].iframe){iframe=!0}}else{if(_this.$items.eq(index).attr('data-iframe')==='true'){iframe=!0}}
var _isVideo=_this.isVideo(_src,index);if(!_this.$slide.eq(index).hasClass('lg-loaded')){if(iframe){_this.$slide.eq(index).prepend('<div class="lg-video-cont lg-has-iframe" style="max-width:'+_this.s.iframeMaxWidth+'"><div class="lg-video"><iframe class="lg-object" frameborder="0" src="'+_src+'"  allowfullscreen="true"></iframe></div></div>')}else if(_hasPoster){var videoClass='';if(_isVideo&&_isVideo.youtube){videoClass='lg-has-youtube'}else if(_isVideo&&_isVideo.vimeo){videoClass='lg-has-vimeo'}else{videoClass='lg-has-html5'}
_this.$slide.eq(index).prepend('<div class="lg-video-cont '+videoClass+' "><div class="lg-video"><span class="lg-video-play"></span><img class="lg-object lg-has-poster" src="'+_poster+'" /></div></div>')}else if(_isVideo){_this.$slide.eq(index).prepend('<div class="lg-video-cont "><div class="lg-video"></div></div>');_this.$el.trigger('hasVideo.lg',[index,_src,_html])}else{_this.$slide.eq(index).prepend('<div class="lg-img-wrap"><img class="lg-object lg-image" src="'+_src+'" /></div>')}
_this.$el.trigger('onAferAppendSlide.lg',[index]);_$img=_this.$slide.eq(index).find('.lg-object');if(_sizes){_$img.attr('sizes',_sizes)}
if(_srcset){_$img.attr('srcset',_srcset);try{picturefill({elements:[_$img[0]]})}catch(e){console.warn('lightGallery :- If you want srcset to be supported for older browser please include picturefil version 2 javascript library in your document.')}}
if(this.s.appendSubHtmlTo!=='.lg-sub-html'){_this.addHtml(index)}
_this.$slide.eq(index).addClass('lg-loaded')}
_this.$slide.eq(index).find('.lg-object').on('load.lg error.lg',function(){var _speed=0;if(delay&&!$('body').hasClass('lg-from-hash')){_speed=delay}
setTimeout(function(){_this.$slide.eq(index).addClass('lg-complete');_this.$el.trigger('onSlideItemLoad.lg',[index,delay||0])},_speed)});if(_isVideo&&_isVideo.html5&&!_hasPoster){_this.$slide.eq(index).addClass('lg-complete')}
if(rec===!0){if(!_this.$slide.eq(index).hasClass('lg-complete')){_this.$slide.eq(index).find('.lg-object').on('load.lg error.lg',function(){_this.preload(index)})}else{_this.preload(index)}}};Plugin.prototype.slide=function(index,fromTouch,fromThumb,direction){var _prevIndex=this.$outer.find('.lg-current').index();var _this=this;if(_this.lGalleryOn&&(_prevIndex===index)){return}
var _length=this.$slide.length;var _time=_this.lGalleryOn?this.s.speed:0;if(!_this.lgBusy){if(this.s.download){var _src;if(_this.s.dynamic){_src=_this.s.dynamicEl[index].downloadUrl!==!1&&(_this.s.dynamicEl[index].downloadUrl||_this.s.dynamicEl[index].src)}else{_src=_this.$items.eq(index).attr('data-download-url')!=='false'&&(_this.$items.eq(index).attr('data-download-url')||_this.$items.eq(index).attr('href')||_this.$items.eq(index).attr('data-src'))}
if(_src){$('#lg-download').attr('href',_src);_this.$outer.removeClass('lg-hide-download')}else{_this.$outer.addClass('lg-hide-download')}}
this.$el.trigger('onBeforeSlide.lg',[_prevIndex,index,fromTouch,fromThumb]);_this.lgBusy=!0;clearTimeout(_this.hideBartimeout);if(this.s.appendSubHtmlTo==='.lg-sub-html'){setTimeout(function(){_this.addHtml(index)},_time)}
this.arrowDisable(index);if(!direction){if(index<_prevIndex){direction='prev'}else if(index>_prevIndex){direction='next'}}
if(!fromTouch){_this.$outer.addClass('lg-no-trans');this.$slide.removeClass('lg-prev-slide lg-next-slide');if(direction==='prev'){this.$slide.eq(index).addClass('lg-prev-slide');this.$slide.eq(_prevIndex).addClass('lg-next-slide')}else{this.$slide.eq(index).addClass('lg-next-slide');this.$slide.eq(_prevIndex).addClass('lg-prev-slide')}
setTimeout(function(){_this.$slide.removeClass('lg-current');_this.$slide.eq(index).addClass('lg-current');_this.$outer.removeClass('lg-no-trans')},50)}else{this.$slide.removeClass('lg-prev-slide lg-current lg-next-slide');var touchPrev;var touchNext;if(_length>2){touchPrev=index-1;touchNext=index+1;if((index===0)&&(_prevIndex===_length-1)){touchNext=0;touchPrev=_length-1}else if((index===_length-1)&&(_prevIndex===0)){touchNext=0;touchPrev=_length-1}}else{touchPrev=0;touchNext=1}
if(direction==='prev'){_this.$slide.eq(touchNext).addClass('lg-next-slide')}else{_this.$slide.eq(touchPrev).addClass('lg-prev-slide')}
_this.$slide.eq(index).addClass('lg-current')}
if(_this.lGalleryOn){setTimeout(function(){_this.loadContent(index,!0,0)},this.s.speed+50);setTimeout(function(){_this.lgBusy=!1;_this.$el.trigger('onAfterSlide.lg',[_prevIndex,index,fromTouch,fromThumb])},this.s.speed)}else{_this.loadContent(index,!0,_this.s.backdropDuration);_this.lgBusy=!1;_this.$el.trigger('onAfterSlide.lg',[_prevIndex,index,fromTouch,fromThumb])}
_this.lGalleryOn=!0;if(this.s.counter){$('#lg-counter-current').text(index+1)}}
_this.index=index};Plugin.prototype.goToNextSlide=function(fromTouch){var _this=this;var _loop=_this.s.loop;if(fromTouch&&_this.$slide.length<3){_loop=!1}
if(!_this.lgBusy){if((_this.index+1)<_this.$slide.length){_this.index++;_this.$el.trigger('onBeforeNextSlide.lg',[_this.index]);_this.slide(_this.index,fromTouch,!1,'next')}else{if(_loop){_this.index=0;_this.$el.trigger('onBeforeNextSlide.lg',[_this.index]);_this.slide(_this.index,fromTouch,!1,'next')}else if(_this.s.slideEndAnimatoin&&!fromTouch){_this.$outer.addClass('lg-right-end');setTimeout(function(){_this.$outer.removeClass('lg-right-end')},400)}}}};Plugin.prototype.goToPrevSlide=function(fromTouch){var _this=this;var _loop=_this.s.loop;if(fromTouch&&_this.$slide.length<3){_loop=!1}
if(!_this.lgBusy){if(_this.index>0){_this.index--;_this.$el.trigger('onBeforePrevSlide.lg',[_this.index,fromTouch]);_this.slide(_this.index,fromTouch,!1,'prev')}else{if(_loop){_this.index=_this.$items.length-1;_this.$el.trigger('onBeforePrevSlide.lg',[_this.index,fromTouch]);_this.slide(_this.index,fromTouch,!1,'prev')}else if(_this.s.slideEndAnimatoin&&!fromTouch){_this.$outer.addClass('lg-left-end');setTimeout(function(){_this.$outer.removeClass('lg-left-end')},400)}}}};Plugin.prototype.keyPress=function(){var _this=this;if(this.$items.length>1){$(window).on('keyup.lg',function(e){if(_this.$items.length>1){if(e.keyCode===37){e.preventDefault();_this.goToPrevSlide()}
if(e.keyCode===39){e.preventDefault();_this.goToNextSlide()}}})}
$(window).on('keydown.lg',function(e){if(_this.s.escKey===!0&&e.keyCode===27){e.preventDefault();if(!_this.$outer.hasClass('lg-thumb-open')){_this.destroy()}else{_this.$outer.removeClass('lg-thumb-open')}}})};Plugin.prototype.arrow=function(){var _this=this;this.$outer.find('.lg-prev').on('click.lg',function(){_this.goToPrevSlide()});this.$outer.find('.lg-next').on('click.lg',function(){_this.goToNextSlide()})};Plugin.prototype.arrowDisable=function(index){if(!this.s.loop&&this.s.hideControlOnEnd){if((index+1)<this.$slide.length){this.$outer.find('.lg-next').removeAttr('disabled').removeClass('disabled')}else{this.$outer.find('.lg-next').attr('disabled','disabled').addClass('disabled')}
if(index>0){this.$outer.find('.lg-prev').removeAttr('disabled').removeClass('disabled')}else{this.$outer.find('.lg-prev').attr('disabled','disabled').addClass('disabled')}}};Plugin.prototype.setTranslate=function($el,xValue,yValue){if(this.s.useLeft){$el.css('left',xValue)}else{$el.css({transform:'translate3d('+(xValue)+'px, '+yValue+'px, 0px)'})}};Plugin.prototype.touchMove=function(startCoords,endCoords){var distance=endCoords-startCoords;if(Math.abs(distance)>15){this.$outer.addClass('lg-dragging');this.setTranslate(this.$slide.eq(this.index),distance,0);this.setTranslate($('.lg-prev-slide'),-this.$slide.eq(this.index).width()+distance,0);this.setTranslate($('.lg-next-slide'),this.$slide.eq(this.index).width()+distance,0)}};Plugin.prototype.touchEnd=function(distance){var _this=this;if(_this.s.mode!=='lg-slide'){_this.$outer.addClass('lg-slide')}
this.$slide.not('.lg-current, .lg-prev-slide, .lg-next-slide').css('opacity','0');setTimeout(function(){_this.$outer.removeClass('lg-dragging');if((distance<0)&&(Math.abs(distance)>_this.s.swipeThreshold)){_this.goToNextSlide(!0)}else if((distance>0)&&(Math.abs(distance)>_this.s.swipeThreshold)){_this.goToPrevSlide(!0)}else if(Math.abs(distance)<5){_this.$el.trigger('onSlideClick.lg')}
_this.$slide.removeAttr('style')});setTimeout(function(){if(!_this.$outer.hasClass('lg-dragging')&&_this.s.mode!=='lg-slide'){_this.$outer.removeClass('lg-slide')}},_this.s.speed+100)};Plugin.prototype.enableSwipe=function(){var _this=this;var startCoords=0;var endCoords=0;var isMoved=!1;if(_this.s.enableSwipe&&_this.doCss()){_this.$slide.on('touchstart.lg',function(e){if(!_this.$outer.hasClass('lg-zoomed')&&!_this.lgBusy){e.preventDefault();_this.manageSwipeClass();startCoords=e.originalEvent.targetTouches[0].pageX}});_this.$slide.on('touchmove.lg',function(e){if(!_this.$outer.hasClass('lg-zoomed')){e.preventDefault();endCoords=e.originalEvent.targetTouches[0].pageX;_this.touchMove(startCoords,endCoords);isMoved=!0}});_this.$slide.on('touchend.lg',function(){if(!_this.$outer.hasClass('lg-zoomed')){if(isMoved){isMoved=!1;_this.touchEnd(endCoords-startCoords)}else{_this.$el.trigger('onSlideClick.lg')}}})}};Plugin.prototype.enableDrag=function(){var _this=this;var startCoords=0;var endCoords=0;var isDraging=!1;var isMoved=!1;if(_this.s.enableDrag&&_this.doCss()){_this.$outer.on('mousedown.lg',function(e){if(!_this.$outer.hasClass('lg-zoomed')&&!_this.lgBusy&&!$(e.target).text()){e.preventDefault();_this.manageSwipeClass();startCoords=e.pageX;isDraging=!0;_this.$outer.scrollLeft+=1;_this.$outer.scrollLeft-=1;_this.$outer.removeClass('lg-grab').addClass('lg-grabbing');_this.$el.trigger('onDragstart.lg')}});$(window).on('mousemove.lg',function(e){if(isDraging){isMoved=!0;endCoords=e.pageX;_this.touchMove(startCoords,endCoords);_this.$el.trigger('onDragmove.lg')}});$(window).on('mouseup.lg',function(e){if(isMoved){isMoved=!1;_this.touchEnd(endCoords-startCoords);_this.$el.trigger('onDragend.lg')}else if($(e.target).hasClass('lg-object')||$(e.target).hasClass('lg-video-play')){_this.$el.trigger('onSlideClick.lg')}
if(isDraging){isDraging=!1;_this.$outer.removeClass('lg-grabbing').addClass('lg-grab')}})}};Plugin.prototype.manageSwipeClass=function(){var _touchNext=this.index+1;var _touchPrev=this.index-1;if(this.s.loop&&this.$slide.length>2){if(this.index===0){_touchPrev=this.$slide.length-1}else if(this.index===this.$slide.length-1){_touchNext=0}}
this.$slide.removeClass('lg-next-slide lg-prev-slide');if(_touchPrev>-1){this.$slide.eq(_touchPrev).addClass('lg-prev-slide')}
this.$slide.eq(_touchNext).addClass('lg-next-slide')};Plugin.prototype.mousewheel=function(){var _this=this;_this.$outer.on('mousewheel.lg',function(e){if(!e.deltaY){return}
if(e.deltaY>0){_this.goToPrevSlide()}else{_this.goToNextSlide()}
e.preventDefault()})};Plugin.prototype.closeGallery=function(){var _this=this;var mousedown=!1;this.$outer.find('.lg-close').on('click.lg',function(){_this.destroy()});if(_this.s.closable){_this.$outer.on('mousedown.lg',function(e){if($(e.target).is('.lg-outer')||$(e.target).is('.lg-item ')||$(e.target).is('.lg-img-wrap')){mousedown=!0}else{mousedown=!1}});_this.$outer.on('mousemove.lg',function(){mousedown=!1});_this.$outer.on('mouseup.lg',function(e){if($(e.target).is('.lg-outer')||$(e.target).is('.lg-item ')||$(e.target).is('.lg-img-wrap')&&mousedown){if(!_this.$outer.hasClass('lg-dragging')){_this.destroy()}}})}};Plugin.prototype.destroy=function(d){var _this=this;if(!d){_this.$el.trigger('onBeforeClose.lg');$(window).scrollTop(_this.prevScrollTop)}
if(d){if(!_this.s.dynamic){this.$items.off('click.lg click.lgcustom')}
$.removeData(_this.el,'lightGallery')}
this.$el.off('.lg.tm');$.each($.fn.lightGallery.modules,function(key){if(_this.modules[key]){_this.modules[key].destroy()}});this.lGalleryOn=!1;clearTimeout(_this.hideBartimeout);this.hideBartimeout=!1;$(window).off('.lg');$('body').removeClass('lg-on lg-from-hash');if(_this.$outer){_this.$outer.removeClass('lg-visible')}
$('.lg-backdrop').removeClass('in');setTimeout(function(){if(_this.$outer){_this.$outer.remove()}
$('.lg-backdrop').remove();if(!d){_this.$el.trigger('onCloseAfter.lg')}},_this.s.backdropDuration+50)};$.fn.lightGallery=function(options){return this.each(function(){if(!$.data(this,'lightGallery')){$.data(this,'lightGallery',new Plugin(this,options))}else{try{$(this).data('lightGallery').init()}catch(err){console.error('lightGallery has not initiated properly')}}})};$.fn.lightGallery.modules={}})()}));
/*! lg-thumbnail - v1.1.0 - 2017-08-08
* http://sachinchoolur.github.io/lightGallery
* Copyright (c) 2017 Sachin N; Licensed GPLv3 */
(function(root,factory){if(typeof define==='function'&&define.amd){define(['jquery'],function(a0){return(factory(a0))})}else if(typeof exports==='object'){module.exports=factory(require('jquery'))}else{factory(jQuery)}}(this,function($){(function(){'use strict';var defaults={thumbnail:!0,animateThumb:!0,currentPagerPosition:'middle',thumbWidth:100,thumbHeight:'80px',thumbContHeight:100,thumbMargin:5,exThumbImage:!1,showThumbByDefault:!0,toogleThumb:!0,pullCaptionUp:!0,enableThumbDrag:!0,enableThumbSwipe:!0,swipeThreshold:50,loadYoutubeThumbnail:!0,youtubeThumbSize:1,loadVimeoThumbnail:!0,vimeoThumbSize:'thumbnail_small',loadDailymotionThumbnail:!0};var Thumbnail=function(element){this.core=$(element).data('lightGallery');this.core.s=$.extend({},defaults,this.core.s);this.$el=$(element);this.$thumbOuter=null;this.thumbOuterWidth=0;this.thumbTotalWidth=(this.core.$items.length*(this.core.s.thumbWidth+this.core.s.thumbMargin));this.thumbIndex=this.core.index;if(this.core.s.animateThumb){this.core.s.thumbHeight='100%'}
this.left=0;this.init();return this};Thumbnail.prototype.init=function(){var _this=this;if(this.core.s.thumbnail&&this.core.$items.length>1){if(this.core.s.showThumbByDefault){setTimeout(function(){_this.core.$outer.addClass('lg-thumb-open')},700)}
if(this.core.s.pullCaptionUp){this.core.$outer.addClass('lg-pull-caption-up')}
this.build();if(this.core.s.animateThumb&&this.core.doCss()){if(this.core.s.enableThumbDrag){this.enableThumbDrag()}
if(this.core.s.enableThumbSwipe){this.enableThumbSwipe()}
this.thumbClickable=!1}else{this.thumbClickable=!0}
this.toogle();this.thumbkeyPress()}};Thumbnail.prototype.build=function(){var _this=this;var thumbList='';var vimeoErrorThumbSize='';var $thumb;var html='<div class="lg-thumb-outer">'+'<div class="lg-thumb lg-group">'+'</div>'+'</div>';switch(this.core.s.vimeoThumbSize){case 'thumbnail_large':vimeoErrorThumbSize='640';break;case 'thumbnail_medium':vimeoErrorThumbSize='200x150';break;case 'thumbnail_small':vimeoErrorThumbSize='100x75'}
_this.core.$outer.addClass('lg-has-thumb');_this.core.$outer.find('.lg').append(html);_this.$thumbOuter=_this.core.$outer.find('.lg-thumb-outer');_this.thumbOuterWidth=_this.$thumbOuter.width();if(_this.core.s.animateThumb){_this.core.$outer.find('.lg-thumb').css({width:_this.thumbTotalWidth+'px',position:'relative'})}
if(this.core.s.animateThumb){_this.$thumbOuter.css('height',_this.core.s.thumbContHeight+'px')}
function getThumb(src,thumb,index){var isVideo=_this.core.isVideo(src,index)||{};var thumbImg;var vimeoId='';if(isVideo.youtube||isVideo.vimeo||isVideo.dailymotion){if(isVideo.youtube){if(_this.core.s.loadYoutubeThumbnail){thumbImg='//img.youtube.com/vi/'+isVideo.youtube[1]+'/'+_this.core.s.youtubeThumbSize+'.jpg'}else{thumbImg=thumb}}else if(isVideo.vimeo){if(_this.core.s.loadVimeoThumbnail){thumbImg='//i.vimeocdn.com/video/error_'+vimeoErrorThumbSize+'.jpg';vimeoId=isVideo.vimeo[1]}else{thumbImg=thumb}}else if(isVideo.dailymotion){if(_this.core.s.loadDailymotionThumbnail){thumbImg='//www.dailymotion.com/thumbnail/video/'+isVideo.dailymotion[1]}else{thumbImg=thumb}}}else{thumbImg=thumb}
thumbList+='<div data-vimeo-id="'+vimeoId+'" class="lg-thumb-item" style="width:'+_this.core.s.thumbWidth+'px; height: '+_this.core.s.thumbHeight+'; margin-right: '+_this.core.s.thumbMargin+'px"><img src="'+thumbImg+'" /></div>';vimeoId=''}
if(_this.core.s.dynamic){for(var i=0;i<_this.core.s.dynamicEl.length;i++){getThumb(_this.core.s.dynamicEl[i].src,_this.core.s.dynamicEl[i].thumb,i)}}else{_this.core.$items.each(function(i){if(!_this.core.s.exThumbImage){getThumb($(this).attr('href')||$(this).attr('data-src'),$(this).find('img').attr('src'),i)}else{getThumb($(this).attr('href')||$(this).attr('data-src'),$(this).attr(_this.core.s.exThumbImage),i)}})}
_this.core.$outer.find('.lg-thumb').html(thumbList);$thumb=_this.core.$outer.find('.lg-thumb-item');$thumb.each(function(){var $this=$(this);var vimeoVideoId=$this.attr('data-vimeo-id');if(vimeoVideoId){$.getJSON('//www.vimeo.com/api/v2/video/'+vimeoVideoId+'.json?callback=?',{format:'json'},function(data){$this.find('img').attr('src',data[0][_this.core.s.vimeoThumbSize])})}});$thumb.eq(_this.core.index).addClass('active');_this.core.$el.on('onBeforeSlide.lg.tm',function(){$thumb.removeClass('active');$thumb.eq(_this.core.index).addClass('active')});$thumb.on('click.lg touchend.lg',function(){var _$this=$(this);setTimeout(function(){if((_this.thumbClickable&&!_this.core.lgBusy)||!_this.core.doCss()){_this.core.index=_$this.index();_this.core.slide(_this.core.index,!1,!0,!1)}},50)});_this.core.$el.on('onBeforeSlide.lg.tm',function(){_this.animateThumb(_this.core.index)});$(window).on('resize.lg.thumb orientationchange.lg.thumb',function(){setTimeout(function(){_this.animateThumb(_this.core.index);_this.thumbOuterWidth=_this.$thumbOuter.width()},200)})};Thumbnail.prototype.setTranslate=function(value){this.core.$outer.find('.lg-thumb').css({transform:'translate3d(-'+(value)+'px, 0px, 0px)'})};Thumbnail.prototype.animateThumb=function(index){var $thumb=this.core.$outer.find('.lg-thumb');if(this.core.s.animateThumb){var position;switch(this.core.s.currentPagerPosition){case 'left':position=0;break;case 'middle':position=(this.thumbOuterWidth/2)-(this.core.s.thumbWidth/2);break;case 'right':position=this.thumbOuterWidth-this.core.s.thumbWidth}
this.left=((this.core.s.thumbWidth+this.core.s.thumbMargin)*index-1)-position;if(this.left>(this.thumbTotalWidth-this.thumbOuterWidth)){this.left=this.thumbTotalWidth-this.thumbOuterWidth}
if(this.left<0){this.left=0}
if(this.core.lGalleryOn){if(!$thumb.hasClass('on')){this.core.$outer.find('.lg-thumb').css('transition-duration',this.core.s.speed+'ms')}
if(!this.core.doCss()){$thumb.animate({left:-this.left+'px'},this.core.s.speed)}}else{if(!this.core.doCss()){$thumb.css('left',-this.left+'px')}}
this.setTranslate(this.left)}};Thumbnail.prototype.enableThumbDrag=function(){var _this=this;var startCoords=0;var endCoords=0;var isDraging=!1;var isMoved=!1;var tempLeft=0;_this.$thumbOuter.addClass('lg-grab');_this.core.$outer.find('.lg-thumb').on('mousedown.lg.thumb',function(e){if(_this.thumbTotalWidth>_this.thumbOuterWidth){e.preventDefault();startCoords=e.pageX;isDraging=!0;_this.core.$outer.scrollLeft+=1;_this.core.$outer.scrollLeft-=1;_this.thumbClickable=!1;_this.$thumbOuter.removeClass('lg-grab').addClass('lg-grabbing')}});$(window).on('mousemove.lg.thumb',function(e){if(isDraging){tempLeft=_this.left;isMoved=!0;endCoords=e.pageX;_this.$thumbOuter.addClass('lg-dragging');tempLeft=tempLeft-(endCoords-startCoords);if(tempLeft>(_this.thumbTotalWidth-_this.thumbOuterWidth)){tempLeft=_this.thumbTotalWidth-_this.thumbOuterWidth}
if(tempLeft<0){tempLeft=0}
_this.setTranslate(tempLeft)}});$(window).on('mouseup.lg.thumb',function(){if(isMoved){isMoved=!1;_this.$thumbOuter.removeClass('lg-dragging');_this.left=tempLeft;if(Math.abs(endCoords-startCoords)<_this.core.s.swipeThreshold){_this.thumbClickable=!0}}else{_this.thumbClickable=!0}
if(isDraging){isDraging=!1;_this.$thumbOuter.removeClass('lg-grabbing').addClass('lg-grab')}})};Thumbnail.prototype.enableThumbSwipe=function(){var _this=this;var startCoords=0;var endCoords=0;var isMoved=!1;var tempLeft=0;_this.core.$outer.find('.lg-thumb').on('touchstart.lg',function(e){if(_this.thumbTotalWidth>_this.thumbOuterWidth){e.preventDefault();startCoords=e.originalEvent.targetTouches[0].pageX;_this.thumbClickable=!1}});_this.core.$outer.find('.lg-thumb').on('touchmove.lg',function(e){if(_this.thumbTotalWidth>_this.thumbOuterWidth){e.preventDefault();endCoords=e.originalEvent.targetTouches[0].pageX;isMoved=!0;_this.$thumbOuter.addClass('lg-dragging');tempLeft=_this.left;tempLeft=tempLeft-(endCoords-startCoords);if(tempLeft>(_this.thumbTotalWidth-_this.thumbOuterWidth)){tempLeft=_this.thumbTotalWidth-_this.thumbOuterWidth}
if(tempLeft<0){tempLeft=0}
_this.setTranslate(tempLeft)}});_this.core.$outer.find('.lg-thumb').on('touchend.lg',function(){if(_this.thumbTotalWidth>_this.thumbOuterWidth){if(isMoved){isMoved=!1;_this.$thumbOuter.removeClass('lg-dragging');if(Math.abs(endCoords-startCoords)<_this.core.s.swipeThreshold){_this.thumbClickable=!0}
_this.left=tempLeft}else{_this.thumbClickable=!0}}else{_this.thumbClickable=!0}})};Thumbnail.prototype.toogle=function(){var _this=this;if(_this.core.s.toogleThumb){_this.core.$outer.addClass('lg-can-toggle');_this.$thumbOuter.append('<span class="lg-toogle-thumb lg-icon"></span>');_this.core.$outer.find('.lg-toogle-thumb').on('click.lg',function(){_this.core.$outer.toggleClass('lg-thumb-open')})}};Thumbnail.prototype.thumbkeyPress=function(){var _this=this;$(window).on('keydown.lg.thumb',function(e){if(e.keyCode===38){e.preventDefault();_this.core.$outer.addClass('lg-thumb-open')}else if(e.keyCode===40){e.preventDefault();_this.core.$outer.removeClass('lg-thumb-open')}})};Thumbnail.prototype.destroy=function(){if(this.core.s.thumbnail&&this.core.$items.length>1){$(window).off('resize.lg.thumb orientationchange.lg.thumb keydown.lg.thumb');this.$thumbOuter.remove();this.core.$outer.removeClass('lg-has-thumb')}};$.fn.lightGallery.modules.Thumbnail=Thumbnail})()}));
/*! lg-fullscreen - v1.0.1 - 2016-09-30
* http://sachinchoolur.github.io/lightGallery
* Copyright (c) 2016 Sachin N; Licensed GPLv3 */
(function(root,factory){if(typeof define==='function'&&define.amd){define(['jquery'],function(a0){return(factory(a0))})}else if(typeof exports==='object'){module.exports=factory(require('jquery'))}else{factory(jQuery)}}(this,function($){(function(){'use strict';var defaults={fullScreen:!0};var Fullscreen=function(element){this.core=$(element).data('lightGallery');this.$el=$(element);this.core.s=$.extend({},defaults,this.core.s);this.init();return this};Fullscreen.prototype.init=function(){var fullScreen='';if(this.core.s.fullScreen){if(!document.fullscreenEnabled&&!document.webkitFullscreenEnabled&&!document.mozFullScreenEnabled&&!document.msFullscreenEnabled){return}else{fullScreen='<span class="lg-fullscreen lg-icon"></span>';this.core.$outer.find('.lg-toolbar').append(fullScreen);this.fullScreen()}}};Fullscreen.prototype.requestFullscreen=function(){var el=document.documentElement;if(el.requestFullscreen){el.requestFullscreen()}else if(el.msRequestFullscreen){el.msRequestFullscreen()}else if(el.mozRequestFullScreen){el.mozRequestFullScreen()}else if(el.webkitRequestFullscreen){el.webkitRequestFullscreen()}};Fullscreen.prototype.exitFullscreen=function(){if(document.exitFullscreen){document.exitFullscreen()}else if(document.msExitFullscreen){document.msExitFullscreen()}else if(document.mozCancelFullScreen){document.mozCancelFullScreen()}else if(document.webkitExitFullscreen){document.webkitExitFullscreen()}};Fullscreen.prototype.fullScreen=function(){var _this=this;$(document).on('fullscreenchange.lg webkitfullscreenchange.lg mozfullscreenchange.lg MSFullscreenChange.lg',function(){_this.core.$outer.toggleClass('lg-fullscreen-on')});this.core.$outer.find('.lg-fullscreen').on('click.lg',function(){if(!document.fullscreenElement&&!document.mozFullScreenElement&&!document.webkitFullscreenElement&&!document.msFullscreenElement){_this.requestFullscreen()}else{_this.exitFullscreen()}})};Fullscreen.prototype.destroy=function(){this.exitFullscreen();$(document).off('fullscreenchange.lg webkitfullscreenchange.lg mozfullscreenchange.lg MSFullscreenChange.lg')};$.fn.lightGallery.modules.fullscreen=Fullscreen})()}))