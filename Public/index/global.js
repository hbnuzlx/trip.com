$(function(){
	$(".search-box .search-select").hover(
		function(){$(this).children(".tn_search_bar").show()},
		function(){$(this).children(".tn_search_bar").hide()}
	)
	$(".tn_search_bar .type_s").click(function(){
		var txt = $(this).text();
		var val = $(this).attr("sid");
		$(this).parent().siblings("span").text(txt);
		$(this).parent().hide();
		$("#search_id").val(val);
	})
})