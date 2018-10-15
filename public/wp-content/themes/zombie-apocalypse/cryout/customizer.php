<?php
/**
 * Contains methods for hooking into WP Customizer.
 * @since Cryout Framework 0.5
 */

///////// SANITIZERS
function cryout_customizer_sanitize_blank(){
	// dummy function that does nothing, since the sanitized add_section 
	// calling it does not add any user-editable field
} // cryout_customizer_sanitize_blank()

function cryout_customizer_sanitize_number($input){
	return ( is_numeric( $input ) ) ? $input : intval( $input );
} // cryout_customizer_sanitize_number()

function cryout_customizer_sanitize_checkbox($input){
    if ( intval( $input ) == 1 ) return 1;
                            else return 0;
} // cryout_customizer_sanitize_checkbox()

function cryout_customizer_sanitize_url($input){
	return esc_url_raw( $input );	
} // cryout_customizer_sanitize_url()

function cryout_customizer_sanitize_color($input){
	if ( preg_match('/#?([0-9a-f]{6}|[0-9a-f]{3})/i', $input, $ms) ):
		return '#' . $ms[1];;	
	else:
		return '';
	endif;
} // cryout_customizer_sanitize_color()

function cryout_customizer_sanitize_generic($input){
	return wp_kses_post( $input );	
} // cryout_customizer_sanitize_generic()


///////// CUSTOM CUSTOMIZERS
function cryout_customizer_extras($wp_customize){

	class Cryout_Customize_Link_Control extends WP_Customize_Control {
			public $type = 'link';
			public function render_content() { 
				if ( !empty( $this->description ) ) { ?>
					<li class="customize-section-description-container">
						<div class="description customize-section-description">
						    <?php echo esc_attr( $this->description ); ?>
						</div>
					</li>
				<?php
				}
				echo '<a href="' . esc_url( $this->value() ) . '" target="_blank">' . esc_attr( $this->label ) .'</a>';
			}
	} // class Cryout_Customize_Link_Control
	
	class Cryout_Customize_About_Control extends WP_Customize_Control {
			public $type = 'about';
			public function render_content() {
					if ( ! empty( $this->label ) ) { ?>
                        <span class="customize-control-title"><?php echo esc_attr( $this->label ) ?></span>
					<?php }
					if ( ! empty( $this->description ) ) { ?>
                        <span class="description customize-control-description cryout-nomove"><?php echo wp_kses_post( $this->description ) ?></span>
                    <?php } ?>
					<span class="customize-control-content customize-cryoutspecial-about-link"><?php echo wp_kses_post( $this->value() ) ?></span>
			<?php
			}
	} // class Cryout_Customize_About_Control

	class Cryout_Customize_Hint_Control extends WP_Customize_Control {
			public $type = 'hint';
			public function render_content() {
					if ( ! empty( $this->label ) ) { ?>
                        <span class="customize-control-title customize-cryoutcontrol-hint"><?php echo esc_attr( $this->label ) ?></span>
					<?php }
					if ( ! empty( $this->description ) ) { ?>
                        <span class="description customize-control-description cryout-nomove customize-cryoutcontrol-hint-desc"><?php echo $this->description ?></span>
                    <?php } ?>
					<span class="customize-control-content customize-cryoutcontrol-hint-value"><?php echo wp_kses_post( $this->value() ) ?></span>
			<?php
			}
	} // class Cryout_Customize_Hint_Control
	
	class Cryout_Customize_Notice_Control extends WP_Customize_Control {
			public $type = 'notice';
			public function render_content() {
					if ( ! empty( $this->label ) ) { ?>
                        <span class="customize-control-title customize-cryoutcontrol-notice customize-cryoutcontrol-notice-<?php echo $this->input_attrs['class'] ?>"><?php echo esc_attr( $this->label ) ?></span>
					<?php }
					if ( ! empty( $this->description ) ) { ?>
                        <span class="description customize-control-description cryout-nomove customize-cryoutcontrol-notice-desc customize-cryoutcontrol-notice-<?php echo $this->input_attrs['class'] ?>-desc"><?php echo $this->description ?></span>
                    <?php } ?>
					<span class="customize-control-content customize-cryoutcontrol-notice-value customize-cryoutcontrol-notice-<?php echo $this->input_attrs['class'] ?>-value"><?php echo wp_kses_post( $this->value() ) ?></span>
			<?php
			}
	} // class Cryout_Customize_Notice_Control

	class Cryout_Customize_Blank_Control extends WP_Customize_Control {
			public $type = 'blank';
			public function render_content() {
				echo '&nbsp;';
			}
	} // class Cryout_Customize_Blank_Control
	
	class Cryout_Customize_Font_Control extends WP_Customize_Control {
			public $type = 'font';
			private $fonts = array();
			public function render_content() {
				$this->fonts = cryout_get_theme_structure('fonts');
			    ?>
                <label>
                    <?php if ( ! empty( $this->label ) ) : ?>
                        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                    <?php endif;
                    if ( ! empty( $this->description ) ) : ?>
                        <span class="description customize-control-description"><?php echo $this->description; ?></span>
                    <?php endif; ?>
 
                    <select <?php $this->link(); ?>>
                        <?php
						foreach ( $this->fonts as $fgroup => $fsubs ): ?>
							<optgroup label='<?php echo $fgroup; ?>'>
							<?php foreach($fsubs as $item):
								$item_show = explode(',',$item); ?>
								<option style='font-family:<?php echo $item; ?>;' value='<?php echo $item; ?>' <?php selected( $this->value(), $item ); ?>>
									<?php echo $item_show[0]; ?>
								</option>
							<?php endforeach; // fsubs ?>
							</optgroup>
						<?php endforeach; // $this->fonts ?>
                    </select>
                </label>
                <?php
			} // render_content()
		
} // class Cryout_Customize_Font_Control

} // cryout_customizer_extras()


class Cryout_Customizer {
	
	public function __construct () {

	} // __construct()

	public static function register( $wp_customize ) {	
		global $cryout_theme_options;
		global $cryout_theme_defaults;
		
		// override built-in wordpress customizer panels, if set
		if (!empty($cryout_theme_options['panel_overrides']))
		foreach ($cryout_theme_options['panel_overrides'] as $poid => $pover):
		
			if (empty($pover['priority2'])) $pover['priority2'] = 60; // failsafe
			switch ($pover['type']):
				case 'section': // move built-in setting to theme panel
					$wp_customize->get_section( $pover['replaces'] )->panel = $pover['section'];	
					break;
				case 'panel':
				default: // add custom panel to replace built-in panel
					$wp_customize->add_panel( $poid, array(
						'priority'       => $pover['priority'],
						'title'          => ucwords(_THEME_NAME). ' '. $pover['title'],
						'description'    => $pover['desc'],
					) );
					$wp_customize->get_section( $pover['replaces'] )->panel = $poid;
					break;
			endswitch;
				
			$wp_customize->get_section( $pover['replaces'] )->priority = $pover['priority2'];			
		endforeach; 

		// add about theme panel and sections
		if (!empty($cryout_theme_options['info_sections'])):
		/* $wp_customize->add_panel( 'about', array(
			'priority'       => 10,
			'title'          => __( 'About', 'cryout' ). ' ' . ucwords(_THEME_NAME),
			'description'    => ucwords(_THEME_NAME) . __( ' by ', 'cryout' ) . 'Cryout Creations',
		) ); */
		$section_priority = 10;
		
		foreach ($cryout_theme_options['info_sections'] as $iid=>$info):
			$wp_customize->add_section( $iid, array(
				'title'          => $info['title'],
				'description'          => $info['desc'],
				'priority'       => $section_priority++,
				//'panel'  => 'about',
			) );
		endforeach;
		endif; //!empty
		
		foreach ($cryout_theme_options['info_settings'] as $iid => $info):
			$wp_customize->add_setting( $iid, array(
				'default'        => $info['default'],
				'capability'     => 'edit_theme_options',
				'sanitize_callback' => 'cryout_customizer_sanitize_blank'
			) );
			$wp_customize->add_control( new Cryout_Customize_About_Control( $wp_customize, $iid, array(
				'label'   	 => $info['label'],
				'description' => $info['desc'],
				'section' 	 => $info['section'],
				'default' 	 => $info['default'],
				'settings'   => $iid,
				'priority'   => 10,
			) ) );				
		endforeach;		
		// end about panel
		
		// add custom theme options panels
		$priority = 53;
		foreach ($cryout_theme_options['sections'] as $section):
		
			$wp_customize->add_panel( $section['id'], array(
			  'title' => ucwords(_THEME_NAME) . ' ' . $section['title'],
			  'description' => __( '', 'cryout' ), 
			  'priority' => $priority++, 
			) );
			
		endforeach; 
		
		// add custom theme options sections, settings and empty placeholder control
		$section_priority = 10;
		foreach ($cryout_theme_options['fields'] as $field):
		
			$wp_customize->add_section( $field['id'], array(
				'title'          => $field['title'],
				'description'    => __( '', 'cryout' ),
				'priority'       => $section_priority++,
				'panel'  		 => $field['sid'],
			) );
			
			$wp_customize->add_setting( 'placeholder_'.$section_priority, array(
				'default'        => '',
				'capability'     => 'edit_theme_options',
				'sanitize_callback' => 'cryout_customizer_sanitize_blank'
			) );	
			
			$wp_customize->add_control( new Cryout_Customize_Blank_Control( $wp_customize, 'placeholder_'.$section_priority, array(
				'section' => $field['id'],
				'settings'   => 'placeholder_'.$section_priority,
				'priority'   => 10,
			) ) );		

		endforeach;
		// end option panels
		
		// add custom theme option controls, based on option type 
		foreach ($cryout_theme_options['options'] as $opt):
			switch ($opt['type']): // sanitize function callback
				
				case 'number': case 'range':
					$sanitize_callback = 'cryout_customizer_sanitize_number';
				break;
				
				case 'checkbox':
					$sanitize_callback = 'cryout_customizer_sanitize_checkbox';
				break;
				
				case 'url': 
					$sanitize_callback = 'cryout_customizer_sanitize_url';
				break;
				
				case 'color':
					$sanitize_callback = 'cryout_customizer_sanitize_color';
				break;
				
				case 'blank':
					$sanitize_callback = 'cryout_customizer_sanitize_blank';
				break;
				
				case 'text': case 'tel': case 'email': case 'search:': case 'time': case 'date': case 'datetime': case 'week':				
				case 'textarea':
				default: 
					$sanitize_callback = 'cryout_customizer_sanitize_generic';
				break;
				
			endswitch;
			
			// guess theme options variable name
			if (function_exists('cryout_get_theme_options_name')) {
				$theme_options_array = cryout_get_theme_options_name();
			} else {
				$theme_options_array = _THEME_NAME . '_settings';
			};
			$opid = $theme_options_array . '[' . $opt['id'] . ']'; 

			// add settings
			$wp_customize->add_setting( $opid, array(
				'type'			 => 'option',
				'default'        => $cryout_theme_defaults[$opt['id']],
				'capability'     => 'edit_theme_options',
				'sanitize_callback' => $sanitize_callback,
			) );
			
			// cycle through and add appropriate control types
			switch ($opt['type']): // control selector
				case 'text': 
				case 'number':	
				case 'url': case 'tel': case 'email': case 'search:': case 'time': case 'date': case 'datetime': case 'week':				
				case 'textarea':
				case 'checkbox':
					$wp_customize->add_control( $opid, array(
						'label'		=> $opt['label'],
						'description'	=> $opt['desc'],
						'section'	=> $opt['section'],
						'settings'	=> $opid,
						'input_attrs' => (!empty($opt['input_attrs'])?$opt['input_attrs']:array()),
						'type'		=> $opt['type'],
						'priority'	=> (isset($opt['priority'])?$opt['priority']:2),
					) );
					break;
				case 'select':
					if (empty($opt['labels'])) $opt['labels'] = $opt['values'];
					$wp_customize->add_control( $opid, array(
						'label'		=> $opt['label'],
						'description'	=> $opt['desc'],
						'section'	=> $opt['section'],
						'settings'	=> $opid,
						'type'		=> $opt['type'],
						'priority'	=> (isset($opt['priority'])?$opt['priority']:2),
						'choices'	=> array_combine($opt['values'],$opt['labels']),
					) );
					break;
				case 'range': 
					$wp_customize->add_control( $opid, array(
						'label'		=> $opt['label'],
						'description'	=> $opt['desc'],
						'section'	=> $opt['section'],
						'settings'	=> $opid,
						'type'		=> $opt['type'],
						'priority'	=> (isset($opt['priority'])?$opt['priority']:2),
						'input_attrs' => array( 'min' => $opt['min'], 'max' => $opt['max'], 'step' => (isset($opt['step'])?$opt['step']:10) ),
					) );					
					break; 
				case 'color':
					$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $opid, array(
						'label' 	=> $opt['label'],
						'description'	=> $opt['desc'],
						'section'	=> $opt['section'],
						'priority'	=> (isset($opt['priority'])?$opt['priority']:2),
					) ) );				
					break;
				case 'font':
					$wp_customize->add_control( new Cryout_Customize_Font_Control( $wp_customize, $opid, array(
						'label' 	=> $opt['label'],
						'description'	=> $opt['desc'],
						'section'	=> $opt['section'],
						'priority'	=> (isset($opt['priority'])?$opt['priority']:2),
					) ) );				
					break;
				case 'media-image':
					$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $opid, array(
						'label' 	=> $opt['label'],
						'description'	=> $opt['desc'],
						'section'	=> $opt['section'],
						'mime_type'	=> 'image',
						'priority'	=> (isset($opt['priority'])?$opt['priority']:2),
					) ) );
					break;
				case 'media':
					$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $opid, array(
						'label' 	=> $opt['label'],
						'description'	=> $opt['desc'],
						'section'	=> $opt['section'],
						'priority'	=> (isset($opt['priority'])?$opt['priority']:2),
					) ) );
					break;
				case 'blank':
				default: 
					$wp_customize->add_control( new Cryout_Customize_Blank_Control( $wp_customize, $opid, array(
						'label' 	=> $opt['label'],
						'description'	=> $opt['desc'],
						'section'	=> $opt['section'],
						'settings'	=> $opid,
						'priority'	=> (isset($opt['priority'])?$opt['priority']:2),
					) ) );	
					break;
			endswitch; 
		endforeach; 		
		// end option fields
		
	} // register()
 
} // class Cryout_Customizer

function cryout_customizer_enqueue_scripts() {
	wp_enqueue_style( 'cryout-customizer-css', get_template_directory_uri() . '/cryout/css/customizer.css', array(), null );
	wp_enqueue_script( 'cryout-customizer-js', get_template_directory_uri() . '/cryout/js/customizer.js', array( 'jquery' ), false, true );
}
add_action('customize_controls_enqueue_scripts', 'cryout_customizer_enqueue_scripts');

// FIN! 