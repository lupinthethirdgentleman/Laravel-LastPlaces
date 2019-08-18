/*
 * MWS Admin v2.1 - Themer JS
 * This file is part of MWS Admin, an Admin template build for sale at ThemeForest.
 * All copyright to this file is hold by Mairel Theafila <maimairel@yahoo.com> a.k.a nagaemas on ThemeForest.
 * Last Updated:
 * December 08, 2012
 *
 */
 
(function($) {
	$(document).ready(function() {
		var backgroundPattern = "images/core/bg/paper.png";
		var baseColor = "#35353a";
		var highlightColor = "#c5d52b";
		var textColor = "#c5d52b";
		var textGlowColor = {r: 197, g: 213, b: 42, a: 0.5};
	
		
		$("div#mws-themer #mws-themer-toggle").on("click", function(e) {
			var toggle = $(this);
			if($(this).hasClass("opened")) {
				toggle.parent().stop().animate({right: "0"}, "slow", function() {
					toggle.removeClass('opened');
				});
			} else {
				toggle.parent().stop().animate({right: "256"}, "slow", function() {
					toggle.addClass('opened');
				});
			}
		});
		
	
		
	});
}) (jQuery);