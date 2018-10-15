<?php
/**
 * Contains framework prototypes
 * @since Cryout Framework 0.5
 */

function cryout_get_theme_options($sub=''){
	$opts = array();
	if ( function_exists( preg_replace( '/[^a-z0-9]/i', '_', _THEME_NAME ) . '_get_theme_options') ) $opts = call_user_func( preg_replace( '/[^a-z0-9]/i', '_', _THEME_NAME) . '_get_theme_options' ); 
	if ( !empty($sub) && !empty($opts[$sub]) ) return $opts[$sub];
	                                      else return $opts;
} // cryout_get_theme_options()

function cryout_get_theme_structure($sub=''){
	$opts = array();
	if ( function_exists( preg_replace( '/[^a-z0-9]/i', '_', _THEME_NAME ) . '_get_theme_structure' ) ) $opts = call_user_func( preg_replace( '/[^a-z0-9]/i', '_', _THEME_NAME ) . '_get_theme_structure' ); 
	if ( !empty($sub) && !empty($opts[$sub]) ) return $opts[$sub];
	                                      else return $opts;
} // cryout_get_theme_structure()

function cyout_get_option($subs = array()) {
	$opts = cryout_get_theme_options();
	$returns = array();
	if ( is_array($subs) ) {
		foreach ($subs as $sub) {
			if ( isset($opts[$sub]) ) $returns[] = array($sub => $opts[$sub]);
		}
		return $returns;
	} else {
		if ( isset($opts[$subs]) ) return $opts[$subs];
	}
	return '';
} // cyout_get_option()

// cryout_gen_values() is located in admin/options.php because it is needed by the options array


function cryout_color_clean($color){
	if (strlen($color)>1): return "#".str_replace("#","",$color);
	else: return $color;
	endif;
} // cryout_color_clean()

function cryout_proto_arrsan($data){
	$filtered = array();
	foreach ($data as $key => $value):
		if (is_array($value)):
			$value = cryout_proto_arrsan($value);
		endif;
		if (is_numeric($value)): $filtered[esc_attr($key)] = esc_attr($value);
		else: $filtered[esc_attr($key)] = wp_kses($value);
		endif;
	endforeach;
	return $filtered;
} //cryout_proto_arrsan()


///////// frontend helper functions /////////

function cryout_optset($var,$val1,$val2='',$val3='',$val4=''){
	$vals = array($val1,$val2,$val3,$val4);
	if (in_array($var,$vals)): return false; else: return true; endif;
} // cryout_optset()

function cryout_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);
   if (preg_match("/^([a-f0-9]{3}|[a-f0-9]{6})$/i",$hex)):
        if(strlen($hex) == 3) {
           $r = hexdec(substr($hex,0,1).substr($hex,0,1));
           $g = hexdec(substr($hex,1,1).substr($hex,1,1));
           $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
           $r = hexdec(substr($hex,0,2));
           $g = hexdec(substr($hex,2,2));
           $b = hexdec(substr($hex,4,2));
        }
        $rgb = array($r, $g, $b);
        return implode(",", $rgb); // returns the rgb values separated by commas
   else: return "";  // input string is not a valid hex color code
   endif;
} // cryout_hex2rgb()


function cryout_hexadder($hex,$inc) {
   $hex = str_replace("#", "", $hex);
   if (preg_match("/^([a-f0-9]{3}|[a-f0-9]{6})$/i",$hex)):
        if(strlen($hex) == 3) {
           $r = hexdec(substr($hex,0,1).substr($hex,0,1));
           $g = hexdec(substr($hex,1,1).substr($hex,1,1));
           $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
           $r = hexdec(substr($hex,0,2));
           $g = hexdec(substr($hex,2,2));
           $b = hexdec(substr($hex,4,2));
        }
		
		$rgb_array = array($r,$g,$b);
		$newhex="#";
		foreach ($rgb_array as $el) {
			$el+=$inc;
			if ($el<=0) { $el='00'; } 
			elseif ($el>=255) {$el='ff';} 
			else {$el=dechex($el);}
			if(strlen($el)==1)  {$el='0'.$el;}
			$newhex.=$el;
		}
		return $newhex;
   else: return "";  // input string is not a valid hex color code
   endif;
} // cryout_hexadder()


function cryout_gfontclean( $gfont, $mode = 1 ) {
	switch ($mode) {
		case 2: // for custom styling
			return esc_attr(preg_replace('/[:&].*/','',$gfont));
		break;
		case 1: // for font enqueuing
		default: 
			return esc_attr(preg_replace( '/\s+/', '+',$gfont)); 
		break;
	} // switch
} // cryout_gfontcleanup()

// FIN! //
