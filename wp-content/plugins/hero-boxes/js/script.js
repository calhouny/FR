/*
 * Front end javascript / jquery code if any
 */

jQuery(document).ready(function($) {
	"use strict";

	// convert images into background divs
    $('.hero-box .hero-box-wrapper img').each(function() {
		$(this).parent().prepend( 
			$('<div></div>')
			.addClass('hero-box-img')
			.css('backgroundImage', 'url(' + $(this).attr('src') + ')') 
		).end().remove();
    });
});