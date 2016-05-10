$(function(){
	function init(){
		$(".leftMenu_item .leftMenu_con ul").each(function(index){
			var $this = $(this);
			var item_con = $(".leftMenu_con:eq(" + index + ")");
			var item_conH = item_con.height();
			if (item_conH > 60) {
				$this.closest(".leftMenu_item").find("h2").show();
				$this.closest(".leftMenu_item").find(".li_dest").css({"overflow": "hidden" });
				//$this.closest(".leftMenu_item").find(".li_dest").css({ "height": "60px", "overflow": "hidden" });
			}
		})
	}
	init()
	$(".leftMenu_item .leftMenu_tit").click(function(){
		$(this).next().toggleClass("hide");
		$(this).children(".icon_menu").toggleClass("open");
		init()
	})
	$(".leftMenu_con h2").click(function(){
		var ul = $(this).prev().height();
		if(ul<=60){
			$(this).children().addClass("current").html("收起");
			$(this).closest(".leftMenu_con").find("ul").css({ "height": "auto", "overflow": "auto" });
		} else {
			$(this).children().removeClass("current").html("查看更多");
			$(this).closest(".leftMenu_con").find("ul").css({ "height": "60px", "overflow": "hidden" });
		}
	})

	$('.value0').spinner({ value:1, min:0});
	$('.value1').spinner({ value:1, min:0});
	$(".img-thumbs").slide({ mainCell:"ul",prevCell:".prev",nextCell:".next",effect:"left",vis:4,scroll:4,autoPage:true,pnLoop:false});
	$(".img-thumbs li").click(function(){
		var _this = $(this);
		if(_this.hasClass("active")) return;
		_this.addClass("active").siblings().removeClass("active");
		var src = _this.find("img").attr("src");
		$("#bigImg img").attr("src",src);
	})
	/* 行程详情跟随导航开始 */
	var pkg = $(".pkg-detail-tab");
	var tab_top = pkg.position().top;
	var tab_left = pkg.position().left;
	var under = $(".under-tab").outerHeight(true)+$(".under-tab").position().top;
	$(window).on("scroll",function(){
        var scTop = $(window).scrollTop();
        if(scTop > tab_top && scTop<under){
            $(".pkg-detail-tab").css({'position':'fixed','top':0,'left':tab_left});
        } else {
			 $(".pkg-detail-tab").css({'position':'static'});
		}
		$(".under-tab .detail_content:not(':first')").each(function(i,v){
            if(scTop >= $(v).position().top-70){
               $(".pkg-detail-tab").find("li").eq(i).addClass("current").siblings("li").removeClass("current");
            }
        })
	})
	$(".pkg-detail-tab a").click(function(){
		var scroll = $(this).attr("scroll");
		var val = $(scroll).position().top-50;
		$("html, body").stop().animate({ scrollTop: val }, 500);
	})
	var trip = $("#js_route_days");
	var trip_top = pkg.position().top;
	var trip_left = pkg.position().left;
	var trip_Box = $("#trip_days").outerHeight(true)+$("#trip_days").offset().top-$("#pdt_features").outerHeight(true)-$("#js_route_days").outerHeight(true);
	$(window).on("scroll",function(){
        var tripTop = $(window).scrollTop();
        if(tripTop > trip_top+$("#pdt_features").outerHeight(true) && tripTop<trip_Box){
            trip.css({'position':'fixed','top':55,'left':trip_left+25});
        } else {
			trip.removeAttr("style");
		}
		$("#trip_days .tripdays").each(function(i,v){
            if(tripTop >= $(v).offset().top-60){	//80
               trip.find("a").eq(i).addClass("current").siblings("a").removeClass("current");
            }
        })
	})
	$("#js_route_days a").click(function(){
		var scroll = $(this).attr("scroll");
		var val = $(scroll).offset().top-40;
		$("html, body").stop().animate({ scrollTop: val }, 500);
	})
	/* 行程详情跟随导航结束 */
})