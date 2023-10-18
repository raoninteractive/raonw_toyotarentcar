var SCROLL_TOP = 0;


//모바일 체크
var mobileW = 960;
function mobileSizeFlag(){
	var wWeight = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
	var flag  = mobileW >= wWeight;

	return flag;
}

//메뉴 스크립트
var gnbSetTime;
var gnbFn = {
	init: function(t) {
		$gnb = $("#gnb"),
		$head = $("#header"),
		$btn = $(".btn_gnb"),
		SCROLL_TOP = $(window).scrollTop();


		$btn.on("click", function(){
			$(this).toggleClass("on");
			$(".gnbbox").toggle();
		});

        $gnb.find(".menu > li").on("click",function(e){


          if(mobileSizeFlag() && $(this).parent().find(".depth2").length > 0){
              var $this = $(this);

			  if ($this.find(".depth2").is(":visible")){
				  $this.removeClass("on");
			  } else {
				$this.siblings(".on").removeClass("on");
				$this.addClass("on");
			  }
          }
        }).find(">a").on("click",function(e){
			gnbFn.close();

          if(mobileSizeFlag() && $(this).parent().find(".depth2").length > 0){
			e.preventDefault();
           }
        });
	},
	open: function(t) {
		if ($("#header .modal-cover").length < 1){
			$("#header").append("<div class='modal-cover et1'>");
			$(".modal-cover.et1").animate({"opacity":1},200);
		}

		$(".modal-cover.et1").off().on("click",function(){
			gnbFn.close();
		});
	},
    close: function(s) {
		$(".btn_gnb").removeClass("on");
		$(".gnbbox").hide();

          if(!mobileSizeFlag()){

             }else{
                $("body").off("touchmove.lnb");
             }


          if(s != "switch"){
            $(".modal-cover").animate({"opacity":0},100,function(){$(this).remove(); });
          }
            openModelPopup = null;

	}
};


//탭
function tab(o,s){
  $obj  = $(o);

  $obj.each(function(){
    var $this = $(this);
    var $total = $this.find("li").length;
    var $first = s-1;
    var $prev = $first;
    var tab_id = new Array();
    var $btn = $this.find("li");
    var $start = $btn.eq($first);

    for( var i=0; i<$total; i++){
      tab_id[i] = $btn.eq(i).find("a").attr("href");
      $(tab_id[i]).css("display","none");
      $(tab_id[$first]).css("display","block");
    }

    $start.addClass("on");

   $btn.bind("click",function(){
    var $this = $(this);
    var $index = $(this).index();

    if(!$this.hasClass("link")){
          if(!$this.hasClass("on")){
           $btn.each(function(){
            $(this).removeClass("on");
           });
           $this.addClass("on");
           $(tab_id[$prev]).css("display","none");
           $(tab_id[$index]).css("display","block");
           $prev = $index;
        }
        $this.trigger("resize");

        return false;

    }
   });

  });//each
}//tab


//메인 -공지사항 :자동 슬라이딩 및 버튼 제어
function tickerTit(e,s) {
	var obj = $(e);
	var tickerHeight = obj.find('li').height();
	var ticker = function() {
		timer = setTimeout(function(){
			if (obj.find('li').length > 1 ){
				obj.find('li:first-child').animate( {marginTop: -tickerHeight}, 400, function() {
					$(this).detach().appendTo(obj).removeAttr('style');
				});
				ticker();
			}
		}, s);
	};
	ticker();

	$(window).resize(function(){
		if (!mobileSizeFlag()){
			clearTimeout(timer);
		} else {
			ticker();
		}
	});
}



$(function () {
	gnbFn.init(); //메뉴

	//메인 공지사항
	if (mobileSizeFlag()){
		tickerTit("#ticker",3000);
	}

	//말줄임
	if ($('.t-dot').length > 0)	{
	 $('.t-dot').dotdotdot({
		  ellipsis: '...',//말줄임 뭘로 할지
		  watch : true, //윈도우 창에따라서 업데이트 할건지, 윈도우가 리사이즈될 때 업데이트할 건지
		  wrap : 'letter',//word(단어단위), letter(글 단위), children(자식단위) 자르기
		  tolerance : 0 //글이 넘치면 얼만큼 height 늘릴건지
	  });
	}


	if ($("input.calender").length > 0){
		//달력
		$.datepicker.regional['ko'] = {
			closeText: '닫기',
			prevText: '이전달',
			nextText: '다음달',
			currentText: '오늘',
			monthNames: ['.01','.02','.03','.04','.05','.06','.07','.08','.09','.10','.11','.12'],
			monthNamesShort: ['01월','02월','03월','04월','05월','06월','07월','08월','09월','10월','11월','12월'],
			dayNames: ['일','월','화','수','목','금','토'],
			dayNamesShort: ['일','월','화','수','목','금','토'],
			dayNamesMin: ['일','월','화','수','목','금','토'],
			weekHeader: 'Wk',
			dateFormat: 'yy-mm-dd',
			firstDay: 0,
			isRTL: false,
			showMonthAfterYear: true,
			//showButtonPanel:true,
			showOn: "both",
			buttonImage: "/images/common/ico_calender.png",
			closeText:'취소',
			onClose: function () {
				if ($(window.event.srcElement).hasClass('ui-datepicker-close')) {
					$(this).val('');
				}
			}
		};
		$.datepicker.setDefaults($.datepicker.regional['ko']);


		//달력
		$('.datepicker_first').datepicker({
			dateFormat: "yy-mm-dd",
			showOtherMonths: true,
			showOn: "both",
			buttonImage: "/images/common/ico_calender.png",
			onClose: function(selectedDate) {
				$(".datepicker_last").datepicker("option", "minDate", selectedDate);
			}
		});
		$('.datepicker_last').datepicker({
			dateFormat: "yy-mm-dd",
			showOtherMonths: true,
			showOn: "both",
			buttonImage: "/images/common/ico_calender.png",
			onClose: function(selectedDate) {
				$(".datepicker_first").datepicker("option", "maxDate", selectedDate);
			}
		});

		$('.datepicker').datepicker({
			dateFormat: "yy-mm-dd",
			showOn: "both",
			buttonImage: "/images/common/ico_calender.png"
		});
	}

	// 테이블 내용 있을때에 클릭시 바로 보이게
	$(document).on("click", ".tr_q", function(){
		if ($(this).next('.tr_a').is(':hidden')){
			$('.tr_a').hide();
			$(this).next('.tr_a').show();
		} else {
			$('.tr_a').hide();
		}
	});

	//파일검색
	$(document).on('change', ('.filebox>.upload-hidden'), function(){
		var $target  = $(this);
		var $targetimg = $target.siblings('.upload-name').find("img");
		var $targetbtn = $target.closest('.filebox').next('.filebox_txt').find('.btn_file_del');
		var fileType = $target.attr('upload-type');
		var fileSize = $target.attr('upload-size');
		var fileExt  = $target.attr('upload-ext');
		if (!fileType) fileType = "file";
		if (!fileSize) fileSize = 100;
		if (fileType == "img" && !fileExt) {
			fileExt = "gif, png, jpg, jpeg";
		} else {
			if (!fileExt) fileExt = "ai, psd, mp3, mp4, avi, wmv, wav, htm, html, gif, png, jpg, jpeg, txt, csv, xml, odt, hwp, hwps, ppt, pptx, xls, xlsx, doc, docx, zip, alz, 7z, tar, tgz, rar, pdf";
		}

		if(window.FileReader){
			var filePath = $target.val();
			var fileName = $target[0].files[0].name;
		} else {
			var filePath = $target.val();
			var fileName = $target.val().split('/').pop().split('\\').pop();
		}

		//파일명 공백체크
		if (fileName && /\s/gi.test(fileName)) {
			alert("파일명에 공백이 들어가있습니다.\n파일명에 공백을 제거 후 다시 등록해주세요.");
			fileReset($target);
			return false;
		}
		if (fileName > 100) {
			alert("파일명은 한글50자 영문,숫자100자를 초과할 수 없습니다.\n파일명을 변경 후 다시 등록해주세요.");
			fileReset($target);
			return false;
		}


		//파일확장자
		var extArr  = fileExt.replace(/\s/gi,'').split(',');
		var thisExt = fileName.split('.').pop().toLowerCase();

		if(thisExt && $.inArray(thisExt, extArr) == -1) {
			if (fileType == "img") {
				alert(fileExt +' 파일만 업로드 할수 있습니다.');
			} else {
				alert('업로드를 지원하지 않는 확장자입니다.\n파일 확장자를 다시 확인 해주시거나 업로드 할 파일을 압축 후\n압축파일형식(zip, alz, 7z, tar, tgz, rar) 으로 등록해주세요.');
			}

			fileReset($target);
			return false;
		}


		//image 파일만
		if (fileType == "img" || 'gif, jpg, jpeg'.indexOf(thisExt) > -1) {
			if (!$(this)[0].files[0].type.match(/image\//)) {
				fileReset($target);
				return false;
			}

			var fileReader = new FileReader();
			fileReader.onload = function(e){
				var src = e.target.result;
				$targetimg.attr("src",src).show();
			}
			fileReader.readAsDataURL($(this)[0].files[0]);
		}

		$target.closest('.filebox').next('.filebox_txt').find('.upload-name').html(fileName);

		$target.closest('.filebox').next('.filebox_txt').show();
		$targetbtn.show();
		$targetbtn.on("click", function(){
			$targetbtn.hide();
			fileReset($target);
		});
	});

});

//첨부파일박스삭제
function fileReset($target) {
	if ($target != $(this)){
		$target = $($target);
	}

	$target.closest('.filebox').next('.filebox_txt').find('.upload-name').html("");
	$target.siblings('.upload-name').find("img").attr("src","");

	if (navigator.appName.indexOf("Explorer") > -1) {
		$target.replaceWith( $target.clone(true) );
	} else {
		$target.val("");
	}

	return false;
}


//메뉴
function tabMove(t){
  var $this = $(t);
  var $header = $("#header");
  var h = $header.height() + 20;
  $this.url = $this.attr("href");

  $("html,body").stop().animate({scrollTop:$($this.url).offset().top - h },300);
}


/*
 *	jQuery dotdotdot 1.6.1
 *
 *	Copyright (c) 2013 Fred Heusschen
 *	www.frebsite.nl
 *
 *	Plugin website:
 *	dotdotdot.frebsite.nl
 *
 *	Dual licensed under the MIT and GPL licenses.
 *	http://en.wikipedia.org/wiki/MIT_License
 *	http://en.wikipedia.org/wiki/GNU_General_Public_License
 */

!function(a){function c(a,b,c){var d=a.children(),e=!1;a.empty();for(var g=0,h=d.length;h>g;g++){var i=d.eq(g);if(a.append(i),c&&a.append(c),f(a,b)){i.remove(),e=!0;break}c&&c.detach()}return e}function d(b,c,g,h,i){var j=b.contents(),k=!1;b.empty();for(var l="table, thead, tbody, tfoot, tr, col, colgroup, object, embed, param, ol, ul, dl, blockquote, select, optgroup, option, textarea, script, style",m=0,n=j.length;n>m&&!k;m++){var o=j[m],p=a(o);"undefined"!=typeof o&&(b.append(p),i&&b[b.is(l)?"after":"append"](i),3==o.nodeType?f(g,h)&&(k=e(p,c,g,h,i)):k=d(p,c,g,h,i),k||i&&i.detach())}return k}function e(a,b,c,d,h){var k=!1,l=a[0];if("undefined"==typeof l)return!1;for(var m=j(l),n=-1!==m.indexOf(" ")?" ":"\u3000",o="letter"==d.wrap?"":n,p=m.split(o),q=-1,r=-1,s=0,t=p.length-1;t>=s&&(0!=s||0!=t);){var u=Math.floor((s+t)/2);if(u==r)break;r=u,i(l,p.slice(0,r+1).join(o)+d.ellipsis),f(c,d)?t=r:(q=r,s=r),t==s&&0==t&&d.fallbackToLetter&&(o="",p=p[0].split(o),q=-1,r=-1,s=0,t=p.length-1)}if(-1==q||1==p.length&&0==p[0].length){var v=a.parent();a.remove();var w=h?h.length:0;if(v.contents().size()>w){var x=v.contents().eq(-1-w);k=e(x,b,c,d,h)}else{var y=v.prev(),l=y.contents().eq(-1)[0];if("undefined"!=typeof l){var m=g(j(l),d);i(l,m),h&&y.append(h),v.remove(),k=!0}}}else m=g(p.slice(0,q+1).join(o),d),k=!0,i(l,m);return k}function f(a,b){return a.innerHeight()>b.maxHeight}function g(b,c){for(;a.inArray(b.slice(-1),c.lastCharacter.remove)>-1;)b=b.slice(0,-1);return a.inArray(b.slice(-1),c.lastCharacter.noEllipsis)<0&&(b+=c.ellipsis),b}function h(a){return{width:a.innerWidth(),height:a.innerHeight()}}function i(a,b){a.innerText?a.innerText=b:a.nodeValue?a.nodeValue=b:a.textContent&&(a.textContent=b)}function j(a){return a.innerText?a.innerText:a.nodeValue?a.nodeValue:a.textContent?a.textContent:""}function k(b,c){return"undefined"==typeof b?!1:b?"string"==typeof b?(b=a(b,c),b.length?b:!1):"object"==typeof b?"undefined"==typeof b.jquery?!1:b:!1:!1}function l(a){for(var b=a.innerHeight(),c=["paddingTop","paddingBottom"],d=0,e=c.length;e>d;d++){var f=parseInt(a.css(c[d]),10);isNaN(f)&&(f=0),b-=f}return b}function m(a,b){return a?(b="string"==typeof b?"dotdotdot: "+b:["dotdotdot:",b],"undefined"!=typeof window.console&&"undefined"!=typeof window.console.log&&window.console.log(b),!1):!1}if(!a.fn.dotdotdot){a.fn.dotdotdot=function(e){if(0==this.length)return e&&e.debug===!1||m(!0,'No element found for "'+this.selector+'".'),this;if(this.length>1)return this.each(function(){a(this).dotdotdot(e)});var g=this;g.data("dotdotdot")&&g.trigger("destroy.dot"),g.data("dotdotdot-style",g.attr("style")),g.css("word-wrap","break-word"),"nowrap"===g.css("white-space")&&g.css("white-space","normal"),g.bind_events=function(){return g.bind("update.dot",function(b,e){b.preventDefault(),b.stopPropagation(),j.maxHeight="number"==typeof j.height?j.height:l(g),j.maxHeight+=j.tolerance,"undefined"!=typeof e&&(("string"==typeof e||e instanceof HTMLElement)&&(e=a("<div />").append(e).contents()),e instanceof a&&(i=e)),q=g.wrapInner('<div class="dotdotdot" />').children(),q.empty().append(i.clone(!0)).css({height:"auto",width:"auto",border:"none",padding:0,margin:0});var h=!1,k=!1;return n.afterElement&&(h=n.afterElement.clone(!0),n.afterElement.remove()),f(q,j)&&(k="children"==j.wrap?c(q,j,h):d(q,g,q,j,h)),q.replaceWith(q.contents()),q=null,a.isFunction(j.callback)&&j.callback.call(g[0],k,i),n.isTruncated=k,k}).bind("isTruncated.dot",function(a,b){return a.preventDefault(),a.stopPropagation(),"function"==typeof b&&b.call(g[0],n.isTruncated),n.isTruncated}).bind("originalContent.dot",function(a,b){return a.preventDefault(),a.stopPropagation(),"function"==typeof b&&b.call(g[0],i),i}).bind("destroy.dot",function(a){a.preventDefault(),a.stopPropagation(),g.unwatch().unbind_events().empty().append(i).attr("style",g.data("dotdotdot-style")).data("dotdotdot",!1)}),g},g.unbind_events=function(){return g.unbind(".dot"),g},g.watch=function(){if(g.unwatch(),"window"==j.watch){var b=a(window),c=b.width(),d=b.height();b.bind("resize.dot"+n.dotId,function(){c==b.width()&&d==b.height()&&j.windowResizeFix||(c=b.width(),d=b.height(),p&&clearInterval(p),p=setTimeout(function(){g.trigger("update.dot")},10))})}else o=h(g),p=setInterval(function(){var a=h(g);(o.width!=a.width||o.height!=a.height)&&(g.trigger("update.dot"),o=h(g))},100);return g},g.unwatch=function(){return a(window).unbind("resize.dot"+n.dotId),p&&clearInterval(p),g};var i=g.contents(),j=a.extend(!0,{},a.fn.dotdotdot.defaults,e),n={},o={},p=null,q=null;return j.lastCharacter.remove instanceof Array||(j.lastCharacter.remove=a.fn.dotdotdot.defaultArrays.lastCharacter.remove),j.lastCharacter.noEllipsis instanceof Array||(j.lastCharacter.noEllipsis=a.fn.dotdotdot.defaultArrays.lastCharacter.noEllipsis),n.afterElement=k(j.after,g),n.isTruncated=!1,n.dotId=b++,g.data("dotdotdot",!0).bind_events().trigger("update.dot"),j.watch&&g.watch(),g},a.fn.dotdotdot.defaults={ellipsis:"... ",wrap:"word",fallbackToLetter:!0,lastCharacter:{},tolerance:0,callback:null,after:null,height:null,watch:!1,windowResizeFix:!0,debug:!1},a.fn.dotdotdot.defaultArrays={lastCharacter:{remove:[" ","\u3000",",",";",".","!","?"],noEllipsis:[]}};var b=1,n=a.fn.html;a.fn.html=function(a){return"undefined"!=typeof a?this.data("dotdotdot")&&"function"!=typeof a?this.trigger("update",[a]):n.call(this,a):n.call(this)};var o=a.fn.text;a.fn.text=function(b){if("undefined"!=typeof b){if(this.data("dotdotdot")){var c=a("<div />");return c.text(b),b=c.html(),c.remove(),this.trigger("update",[b])}return o.call(this,b)}return o.call(this)}}}(jQuery);

