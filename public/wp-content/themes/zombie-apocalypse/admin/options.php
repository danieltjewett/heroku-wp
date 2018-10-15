<?php

// custom function for zombie apocalypse due to different theme options array naming scheme
function cryout_get_theme_options_name(){
	return 'za_options';
}

function cryout_sanitize_tn($input){
	return preg_replace( '/[^a-z0-9-]/i', '-', $input );
}
function cryout_sanitize_tn_fn($input){
	return preg_replace( '/[^a-z0-9]/i', '_', $input );
}

// needed by the options array below
function cryout_gen_values( $from, $to, $step = 1, $attr = array() ){

	// prepend extra values
	if ( !empty($attr['pre']) && is_array($attr['pre']) )  $data = $attr['pre']; 
													  else $data = array();
	// set float precision
	if ( !empty($attr['precision']) && is_numeric($attr['precision'] ) )  $precision = $attr['precision'];
								                                     else $precision = 1;
	// set measuring unit
    if ( !empty($attr['um']) ) $um = $attr['um'];
						  else $um = '';
	
	// generate numbers
	if ($step < 1): 
		// floats
		for ($i=$from;$i<=$to;$i+=$step) {
			$data[] = number_format($i,$precision).$um;
		}
	else: 
		// integers
		for ($i=$from;$i<=$to;$i+=$step) {
			$data[] = $i.$um;
		}	
	endif;
	
	// append extra values
	if ( !empty($attr['post']) && is_array($attr['post']) )  $data = array_merge($data,$attr['post']);
	
	return $data;
} 

$cryout_theme_options = array(

/************* general info ***************/

'info_sections' => array(
	'cryoutspecial-about-theme' => array(
		'title' => __( 'About', 'cryout' ) . ' ' . ucwords(_THEME_NAME),
		'desc' => '<img src=" ' . get_template_directory_uri() . '/admin/images/zombie-logo.png" >',
	),
), // info_sections

'info_settings' => array(
	'support_link_faqs' => array(
		'label' => '',
		'default' => sprintf( '<a href="https://www.cryoutcreations.eu/wordpress-themes/' . cryout_sanitize_tn( _THEME_NAME ) . '" target="_blank">%s</a>', __( 'Read the Docs', 'cryout' ) ),
		'desc' =>  '',
		'section' => 'cryoutspecial-about-theme',
	),
	'support_link_forum' => array(
		'label' => '',
		'default' => sprintf( '<a href="https://www.cryoutcreations.eu/forums/f/wordpress/' . cryout_sanitize_tn( _THEME_NAME ) . '" target="_blank">%s</a>', __( 'Browse the Forum', 'cryout' ) ),
		'desc' => '',
		'section' => 'cryoutspecial-about-theme',
	),
	'premium_support_link' => array(
		'label' => '',
		'default' => sprintf( '<a href="https://www.cryoutcreations.eu/priority-support" target="_blank">%s</a>', __( 'Priority Support', 'cryout' ) ),
		'desc' => '',
		'section' => 'cryoutspecial-about-theme',
	),
	'rating_url' => array(
		'label' => '&nbsp;',
		'default' => sprintf( '<a href="https://wordpress.org/support/view/theme-reviews/'. cryout_sanitize_tn( _THEME_NAME ) .'#new-post" target="_blank">%s</a>', sprintf( __( 'Rate %s on WordPress.org', 'cryout' ) , ucwords(_THEME_NAME) ) ),
		'desc' => '',
		'section' => 'cryoutspecial-about-theme',
	),
), // info_settings

'panel_overrides' => array(
/* 	'background' => array(
		'title' => __( 'Background', 'cryout' ),
		'desc' => __( 'Background Settings.', 'cryout' ),
		'priority' => 50,
		'section' => 'zombie_section',
		'replaces' => 'background_image',
		'type' => 'section',
	),
	'header' => array(
		'title' => __( 'Header Image', 'cryout' ),
		'desc' => __( 'Header Image Settings.', 'cryout' ),
		'priority' => 51,
		'section' => 'zombie_section',
		'replaces' => 'header_image',
		'type' => 'section',
	),
	'colours' => array(
		'title' => __( 'Colours', 'cryout' ),
		'desc' => __( 'Background colour will be overlapped by the background image if set.', 'cryout' ),
		'priority' => 52,
		'section' => 'zombie_section',
		'replaces' => 'colors',
		'type' => 'section',
	), */
	
), // panel_overrides

/************* sections *************/

'sections' => array(

	//array('id'=>'zombie_section', 'title'=>__('Settings','zombie-apocalypse'), 'callback'=>''),

), // sections

/************* fields *************/

'fields' => array(

	array('id'=>'za_layout_section', 'title'=>__('Layout','zombie-apocalypse'), 'callback'=>'', 'sid' => '' ),
	array('id'=>'za_text_section', 'title'=>__('Typography','zombie-apocalypse'), 'callback'=>'', 'sid' => '' ),
	array('id'=>'za_graphics_section', 'title'=>__('Graphics','zombie-apocalypse') , 'callback'=>'', 'sid' => '' ),
	array('id'=>'za_post_section', 'title'=>__('Post Information','zombie-apocalypse') , 'callback'=>'', 'sid' => '' ),
	
), // fields

/************* options *************/

'options' => array (
	//////////////////////////////////////////////////// Layout ////////////////////////////////////////////////////
	array(
	'id' => 'zmb_side',
		'type' => 'select',
		'label' => __('Sidemenu Position','zombie-apocalypse'),
		'values' => array( "Left", "Right", "Disable" ),
		'labels' => array( __("Left", 'zombie-apocalypse'), __("Right",'zombie-apocalypse'), __("Disable",'zombie-apocalypse') ),
		'desc' => __('Configure the side on which to display the sidebar or disable it altogether and have only one column for a presentation-like design.','zombie-apocalypse'),
	'section' => 'za_layout_section' ),
	array(
	'id' => 'zmb_sidewidth',
		'type' => 'select',
		'label' => __('Content Width','zombie-apocalypse'),
		'values' => cryout_gen_values( 500, 750, 10, array( 'um' => 'px') ),
		'desc' => __('Configure the width of the content area. The sidebar will use the remaining width out of the theme total of 940px.','zombie-apocalypse'),
	'section' => 'za_layout_section' ),
	array(
	'id' => 'zmb_colpad',
		'type' => 'select',
		'label' => __('Columns Padding','zombie-apocalypse'),
		'values' => cryout_gen_values( 0, 30, 5, array( 'um' => 'px') ),
		'desc' => __('Configure padding between content and sidebar.','zombie-apocalypse'),
	'section' => 'za_layout_section' ),
	
	
	//////////////////////////////////////////////////// Text ////////////////////////////////////////////////////

	array(
	'id' => 'zmb_fontsize',
		'type' => 'select',
		'label' => __( 'Font Size','zombie-apocalypse' ),
		'values' => cryout_gen_values( 12, 18, 1, array( 'um' => 'px') ),
		'desc' => __('Configure general font size which applies to pages, posts and comments. Buttons, headers and sidebar menus will not be affected.','zombie-apocalypse'),
	'section' => 'za_text_section' ),	
	array(
	'id' => 'zmb_fontfamily',
		'type' => 'font',
		'label' => __( 'Font Family','zombie-apocalypse' ),
		'desc' => __('Configure general font family which applies to all site text (including header, menus, sidebar).','zombie-apocalypse'),
	'section' => 'za_text_section' ),	
	array(
	'id' => 'zmb_textalign',
		'type' => 'select',
		'label' => __( 'Forced Text Alignment','zombie-apocalypse' ),
		'values' => array( 'Default' , 'Left' , 'Right' , 'Justify' , 'Center' ),
		'labels' => array( __('Default','zombie-apocalypse') , __('Left','zombie-apocalypse') , __('Right','zombie-apocalypse') , __('Justify','zombie-apocalypse') , __('Center','zombie-apocalypse') ),
		'desc' => __('Override text alignment in posts and pages.','zombie-apocalypse'),
	'section' => 'za_text_section' ),	
	array(
	'id' => 'zmb_parindent',
		'type' => 'select',
		'label' => __( 'Paragraph Indentation','zombie-apocalypse' ),
		'values' => cryout_gen_values ( 0, 20, 5, array( 'um' => 'px' ) ),
		'desc' => __('Configure paragraph text indentation.','zombie-apocalypse'),
	'section' => 'za_text_section' ),	
	
	//////////////////////////////////////////////////// Graphics ////////////////////////////////////////////////////
	
	array(
	'id' => 'zmb_caption',
		'type' => 'select',
		'label' => __( 'Caption Border','zombie-apocalypse' ),
		'values' => array( 'Light Gray' , 'Gray' , 'Bloody' , 'Light Bloody' , 'Paper' , 'Black' ),
		'labels' => array( __('Light Gray','zombie-apocalypse'), __('Gray','zombie-apocalypse'), __('Bloody','zombie-apocalypse'), __('Light Bloody','zombie-apocalypse'), __('Paper','zombie-apocalypse'), __('Black','zombie-apocalypse') ),
		'desc' => __('Configure the appearance of image captions. Images not inserted through captions are not affected.','zombie-apocalypse'),
	'section' => 'za_graphics_section' ),	
	array(
	'id' => 'zmb_title',
		'type' => 'select',
		'label' => __( 'Site Title and Description','zombie-apocalypse' ),
		'values' => array( 'Show', 'Hide' ),
		'labels' => array( __('Show','zombie-apocalypse'), __('Hide','zombie-apocalypse') ),
		'desc' => __('Configure the visibility of the site\'s title and description in he header area.','zombie-apocalypse'),
	'section' => 'za_graphics_section' ),	
	array(
	'id' => 'zmb_pagetitle',
		'type' => 'select',
		'label' => __( 'Page Titles','zombie-apocalypse' ),
		'values' => array( 'Show', 'Hide' ),
		'labels' => array( __('Show','zombie-apocalypse'), __('Hide','zombie-apocalypse') ),
		'desc' => __('Configure the visibility of (static) page titles.','zombie-apocalypse'),
	'section' => 'za_graphics_section' ),	
	array(
	'id' => 'zmb_categtitle',
		'type' => 'select',
		'label' => __( 'Category Page Titles','zombie-apocalypse' ),
		'values' => array( 'Show', 'Hide' ),
		'labels' => array( __('Show','zombie-apocalypse'), __('Hide','zombie-apocalypse') ),
		'desc' => __('Configure the visibility of category and archive pages','zombie-apocalypse'),
	'section' => 'za_graphics_section' ),	
	array(
	'id' => 'zmb_top',
		'type' => 'select',
		'label' => __( 'Header Background','zombie-apocalypse' ),
		'values' => array( 'Show', 'Hide' ),
		'labels' => array( __('Show','zombie-apocalypse'), __('Hide','zombie-apocalypse') ),
		'desc' => __('Configure the visibility of the grunge background in the header.','zombie-apocalypse'),
	'section' => 'za_graphics_section' ),	
	array(
	'id' => 'zmb_hand',
		'type' => 'select',
		'label' => __( 'Zombie Hand','zombie-apocalypse' ),
		'values' => array( 'Show', 'Hide' ),
		'labels' => array( __('Show','zombie-apocalypse'), __('Hide','zombie-apocalypse') ),
		'desc' => __('Configure the visibility of the zombie hand image in the right corner of the header.','zombie-apocalypse'),
	'section' => 'za_graphics_section' ),	
	array(
	'id' => 'zmb_splash',
		'type' => 'select',
		'label' => __( 'Blood Splash','zombie-apocalypse' ),
		'values' => array( 'Show', 'Hide' ),
		'labels' => array( __('Show','zombie-apocalypse'), __('Hide','zombie-apocalypse') ),
		'desc' => __('','zombie-apocalypse'),
	'section' => 'za_graphics_section' ),	
	array(
	'id' => 'zmb_drips',
		'type' => 'select',
		'label' => __( 'Dripping Blood','zombie-apocalypse' ),
		'values' => array( 'Show', 'Hide' ),
		'labels' => array( __('Show','zombie-apocalypse'), __('Hide','zombie-apocalypse') ),
		'desc' => __('','zombie-apocalypse'),
	'section' => 'za_graphics_section' ),	
	array(
	'id' => 'zmb_puddle',
		'type' => 'select',
		'label' => __( 'Bloody Footer','zombie-apocalypse' ),
		'values' => array( 'Show', 'Hide' ),
		'labels' => array( __('Show','zombie-apocalypse'), __('Hide','zombie-apocalypse') ),
		'desc' => __('','zombie-apocalypse'),
	'section' => 'za_graphics_section' ),	
	array(
	'id' => 'zmb_info',
		'type' => 'select',
		'label' => __( 'Bullets in Footer','zombie-apocalypse' ),
		'values' => array( 'Show', 'Hide' ),
		'labels' => array( __('Show','zombie-apocalypse'), __('Hide','zombie-apocalypse') ),
		'desc' => __('','zombie-apocalypse'),
	'section' => 'za_graphics_section' ),	
	array(
	'id' => 'zmb_menu',
		'type' => 'select',
		'label' => __( 'Main Menu Animation','zombie-apocalypse' ),
		'values' => array( 'Enable', 'Disable' ),
		'labels' => array( __('Enable','zombie-apocalypse'), __('Disable','zombie-apocalypse') ),
		'desc' => __('','zombie-apocalypse'),
	'section' => 'za_graphics_section' ),	
	array(
	'id' => 'zmb_tables',
		'type' => 'select',
		'label' => __( 'Hide Tables','zombie-apocalypse' ),
		'values' => array( 'Enable', 'Disable' ),
		'labels' => array( __('Enable','zombie-apocalypse'), __('Disable','zombie-apocalypse') ),
		'desc' => __('','zombie-apocalypse'),
	'section' => 'za_graphics_section' ),	
	array(
	'id' => 'zmb_copyright',
		'type' => 'textarea',
		'label' => __( 'Custom Footer Text','zombie-apocalypse' ),
		'desc' => __('','zombie-apocalypse'),
	'section' => 'za_graphics_section' ),	
	
	//////////////////////////////////////////////////// Post Info ////////////////////////////////////////////////////
	
	array(
	'id' => 'zmb_postdate',
		'type' => 'select',
		'label' => __( 'Post Date', 'zombie-apocalypse' ),
		'values' => array( 'Show', 'Hide' ),
		'labels' => array( __('Show','zombie-apocalypse'), __('Hide','zombie-apocalypse') ),
		'desc' => __('','zombie-apocalypse'),
	'section' => 'za_post_section' ),
	array(
	'id' => 'zmb_posttime',
		'type' => 'select',
		'label' => __( 'Post Time', 'zombie-apocalypse' ),
		'values' => array( 'Show', 'Hide' ),
		'labels' => array( __('Show','zombie-apocalypse'), __('Hide','zombie-apocalypse') ),
		'desc' => __('','zombie-apocalypse'),
	'section' => 'za_post_section' ),
	array(
	'id' => 'zmb_postauthor',
		'type' => 'select',
		'label' => __( 'Post Author', 'zombie-apocalypse' ),
		'values' => array( 'Show', 'Hide' ),
		'labels' => array( __('Show','zombie-apocalypse'), __('Hide','zombie-apocalypse') ),
		'desc' => __('','zombie-apocalypse'),
	'section' => 'za_post_section' ),
	array(
	'id' => 'zmb_postcateg',
		'type' => 'select',
		'label' => __( 'Post Category', 'zombie-apocalypse' ),
		'values' => array( 'Show', 'Hide' ),
		'labels' => array( __('Show','zombie-apocalypse'), __('Hide','zombie-apocalypse') ),
		'desc' => __('','zombie-apocalypse'),
	'section' => 'za_post_section' ),
	array(
	'id' => 'zmb_postbook',
		'type' => 'select',
		'label' => __( 'Post Permalink', 'zombie-apocalypse' ),
		'values' => array( 'Show', 'Hide' ),
		'labels' => array( __('Show','zombie-apocalypse'), __('Hide','zombie-apocalypse') ),
		'desc' => __('','zombie-apocalypse'),
	'section' => 'za_post_section' ),

), // options

/*** fonts ***/
'fonts' => array(

	'Sans-Serif' => array("Segoe UI, Arial, sans-serif",
					 "Verdana, Geneva, sans-serif " ,
					 "Geneva, sans-serif ",
					 "Helvetica Neue, Arial, Helvetica, sans-serif",
					 "Helvetica, sans-serif" ,
					 "Century Gothic, AppleGothic, sans-serif",
				     "Futura, Century Gothic, AppleGothic, sans-serif",
					 "Calibri, Arian, sans-serif",
				     "Myriad Pro, Myriad,Arial, sans-serif",
					 "Trebuchet MS, Arial, Helvetica, sans-serif" ,
					 "Gill Sans, Calibri, Trebuchet MS, sans-serif",
					 "Impact, Haettenschweiler, Arial Narrow Bold, sans-serif ",
					 "Tahoma, Geneva, sans-serif" ,
					 "Arial, Helvetica, sans-serif" ,
					 "Arial Black, Gadget, sans-serif",
					 "Lucida Sans Unicode, Lucida Grande, sans-serif "),

	'Serif' => array("Georgia, Times New Roman, Times, serif" ,
					  "Times New Roman, Times, serif",
					  "Cambria, Georgia, Times, Times New Roman, serif",
					  "Palatino Linotype, Book Antiqua, Palatino, serif",
					  "Book Antiqua, Palatino, serif",
					  "Palatino, serif",
				      "Baskerville, Times New Roman, Times, serif",
 					  "Bodoni MT, serif",
					  "Copperplate Light, Copperplate Gothic Light, serif",
					  "Garamond, Times New Roman, Times, serif"),

	'MonoSpace' => array( "Courier New, Courier, monospace" ,
					  "Lucida Console, Monaco, monospace",
					  "Consolas, Lucida Console, Monaco, monospace",
					  "Monaco, monospace"),

	'Cursive' => array(  "Lucida Casual, Comic Sans MS , cursive",
				      "Brush Script MT, Phyllis, Lucida Handwriting, cursive",
					  "Phyllis, Lucida Handwriting, cursive",
					  "Lucida Handwriting, cursive",
					  "Comic Sans MS, cursive")
	), // fonts

); // $cryout_theme_options

// FIN