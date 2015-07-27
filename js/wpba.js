jQuery(window).on("load", function(){
    jQuery('.ba-container').beforeAfter({
		animateIntro : WPBA.animated,
		showFullLinks : WPBA.showlinks,
		introPosition : WPBA.dividerposition,
		dividerColor : WPBA.dividercolor,
		enableKeyboard : WPBA.enablekeyboard,
		keypressAmount : WPBA.keyspace,
		cursor : 'e-resize',
		imagePath : WPBA.url + 'js/'
	});
});