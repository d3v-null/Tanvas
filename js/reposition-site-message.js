/*hello*/
;(function($, window, document, undefined)
{
	$(document).ready(function(){
		var banner = $("p.demo_store");
		var wrapper = $("div.off-canvas-wrap");
        // console.log(banner);
        // console.log(wrapper);
		if(banner.length > 0 && wrapper.length > 0){
			banner.insertBefore(wrapper);
		}
	});
}(jQuery));
