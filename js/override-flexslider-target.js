;(function($, window, document, undefined)
{
	$(document).ready(function(){
		if(flexslider_target_params.hasOwnProperty('target_overrides')){
			target_overrides = flexslider_target_params.target_overrides;
			// console.log(target_overrides);
			for (var slideID in target_overrides) {
				if (target_overrides.hasOwnProperty(slideID)) {
					var targetValue = target_overrides[slideID];
					var slideAnchors = $("[id^='slide-" + slideID + "'] a");
					// console.log(slideAnchors);
					slideAnchors.attr("target", targetValue);
				}
			}
		}

	});
}(jQuery));
