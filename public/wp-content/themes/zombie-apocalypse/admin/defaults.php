<?php

function zombie_get_option_defaults() {

// DEFAULT OPTIONS ARRAY
$zombie_defaults = array(

"zmb_side" => "Right",
"zmb_sidewidth" => 650,
"zmb_colpad" => "10px",

"zmb_fontsize" => "15px",
"zmb_fontfamily" => "Verdana",
"zmb_textalign" => "Default",
"zmb_parindent" => "0px",

"zmb_caption" => "Light Gray",
"zmb_title" => "Show",
"zmb_pagetitle" => "Show",
"zmb_categtitle" => "Show",
"zmb_hand" => "Show",
"zmb_top" => "Show",
"zmb_splash" => "Show",
"zmb_drips" => "Show",
"zmb_puddle" => "Show",
"zmb_info" => "Show",
"zmb_menu" => "Enable",
"zmb_tables" => "Disable",
"zmb_copyright" => "",

"zmb_postdate" => "Show",
"zmb_posttime" => "Hide",
"zmb_postauthor" => "Show",
"zmb_postcateg" => "Show",
"zmb_postbook" => "Show"

); 

return apply_filters( 'zombie_option_defaults', $zombie_defaults );
}

?>