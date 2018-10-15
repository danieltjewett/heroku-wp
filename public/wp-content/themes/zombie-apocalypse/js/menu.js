jQuery(document).ready(function() { 

	/* Standard menu touch support for tablets */
	var custom_event = ('ontouchstart' in window) ? 'touchstart' : 'click'; // check touch support 
	var ios = /iPhone|iPad|iPod/i.test(navigator.userAgent);
		jQuery('#access ul.menu > li a').on('click', function(e){
			var $link_id = jQuery(this).attr('href');
			if (jQuery(this).parent().data('clicked') == $link_id) { // second touch 
				jQuery(this).parent().data('clicked', null);
			}
			else { // first touch 
				if (custom_event != 'click' && !ios && (jQuery(this).parent().children('.sub-menu').length >0)) {e.preventDefault();}
				jQuery(this).parent().data('clicked', $link_id);
			}
		}); 
 
	/* Menu animation */
	jQuery("#access ul ul").css({display: "none"}); /* Opera Fix */
	jQuery("#access > .menu-header > ul.menu ul li > a:not(:only-child)").attr("aria-haspopup","true");/* IE10 mobile Fix */

	jQuery("#access ul ul").css({display: "none"}); // Opera Fix 
	jQuery("#access li").hover(function(){ 
		jQuery(this).find('ul:first').css({visibility: "visible",display: "none"}).show(250); 
	},function(){ 
		jQuery(this).find('ul:first').css({visibility: "hidden"}); 
	}); 
}); 

 

