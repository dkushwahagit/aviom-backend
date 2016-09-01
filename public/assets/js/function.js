$(document) .ready(function() {
    var winHeight = $(window).height();	
	
		$(".loginBox") .css("min-height", (winHeight - 158) + "px");	
		$(".mainBodyBox") .css("min-height", (winHeight - 68) + "px");
		$(".rightBox .containerBox") .css("min-height", (winHeight - 252) + "px");
	
	
	
	var winWidth = $(window).width() ;
	//$(".rightBox") .css("width", (winWidth - 400) + "px");
});