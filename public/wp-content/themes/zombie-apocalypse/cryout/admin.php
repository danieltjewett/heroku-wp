<?php
/**
 * Contains dashboard generic functions
 * @since Cryout Framework 0.5
 */
 
// generic functions
 
function cryout_export_options(){

    if (ob_get_contents()) ob_clean();

	/* Check authorisation */
	$authorised = true;
	// Check nonce
	if ( ! wp_verify_nonce( $_POST[_THEME_NAME.'-export'], _THEME_NAME.'-export' ) ) {
		$authorised = false;
	}
	// Check permissions
	if ( ! current_user_can( 'edit_theme_options' ) ){
		$authorised = false;
	}

	if ( $authorised) {
        
        date_default_timezone_set('UTC');
        $name = _THEME_NAME.'settings-'.preg_replace("/[^a-z0-9-_]/i",'',preg_replace("/https?\:\/\//","",get_option('siteurl'))).'-'.date('Ymd-His').'.txt';
		$data = cryout_get_theme_options();
		$data = json_encode( $data );
		$size = strlen( $data );

		header( 'Content-Type: text/plain' );
		header( 'Content-Disposition: attachment; filename="'.$name.'"' );
		header( "Content-Transfer-Encoding: binary" );
		header( 'Accept-Ranges: bytes' );

		/* The three lines below basically make the download non-cacheable */
		header( "Cache-control: private" );
		header( 'Pragma: private' );
		header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );

		header( "Content-Length: " . $size);
		print( $data );
}
die();
} // cryout_export_options()

/**
 * This file manages the theme settings uploading and import operations.
 * Uses the theme page to create a new form for uplaoding the settings
 * Uses WP_Filesystem
*/
function cryout_import_form(){

    $bytes = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
    $size = size_format( $bytes );
    $upload_dir = wp_upload_dir();
    if ( ! empty( $upload_dir['error'] ) ) :
        ?><div class="error"><p><?php _e('Before you can upload your import file, you will need to fix the following error:', 'cryout'); ?></p>
            <p><strong><?php echo $upload_dir['error']; ?></strong></p></div><?php
    else :
    ?>
	
    <div class="wrap">
		<div style="width:400px;display:block;margin-left:30px;">
        <div id="icon-tools" class="icon32"><br></div>
        <h2><?php echo __( 'Import Theme Options', 'cryout' );?></h2>
        <form enctype="multipart/form-data" id="import-upload-form" method="post" action="">
        	<p><?php _e('Hi! This is where you import the theme settings.<i> Please remember that this is still an experimental feature.</i>', 'cryout'); ?></p>
            <p>
                <label for="upload"><strong><?php _e('Just choose a file from your computer:', 'cryout'); ?> </strong><i>(<?php echo _THEME_NAME; ?>-settings.txt)</i></label>
		       <input type="file" id="upload" name="import" size="25"  />
				<span style="font-size:10px;">(<?php  printf( __( 'Maximum size: %s', 'cryout' ), $size ); ?> )</span>
                <input type="hidden" name="action" value="save" />
                <input type="hidden" name="max_file_size" value="<?php echo $bytes; ?>" />
                <?php wp_nonce_field(_THEME_NAME.'-import', _THEME_NAME.'-import'); ?>
                <input type="hidden" name="cryout_import_confirmed" value="true" />
            </p>
            <input type="submit" class="button" value="<?php _e('And import!', 'cryout'); ?>" />
        </form>
	</div>
    </div> <!-- end wrap -->
    <?php
    endif;
} // cryout_import_form()

/**
 * This actual import of the options from the file to the settings array.
*/
function cryout_import_file() {

	$theme_settings = cryout_get_theme_options();

    /* Check authorisation */
    $authorised = true;
    // Check nonce
    if (!wp_verify_nonce($_POST[_THEME_NAME.'-import'], _THEME_NAME.'-import')) {$authorised = false;}
    // Check permissions
    if (!current_user_can('edit_theme_options')){ $authorised = false; }

    // If the user is authorised, import the theme's options to the database
    if ($authorised) {?>
        <?php
        // make sure there is an import file uploaded
        if ( (isset($_FILES["import"]["size"]) &&  ($_FILES["import"]["size"] > 0) ) ) {

			$form_fields = array('import');
			$method = '';

			$url = wp_nonce_url('themes.php?page='._THEME_NAME.'-page', _THEME_NAME.'-import');

			// Get file writing credentials
			if (false === ($creds = request_filesystem_credentials($url, $method, false, false, $form_fields) ) ) {
				return true;
			}

			if ( ! WP_Filesystem($creds) ) {
				// our credentials were no good, ask the user for them again
				request_filesystem_credentials($url, $method, true, false, $form_fields);
				return true;
			}

			// Write the file if credentials are good
			$upload_dir = wp_upload_dir();
			$filename = trailingslashit($upload_dir['path'])._THEME_NAME.'s.txt';

			// by this point, the $wp_filesystem global should be working, so let's use it to create a file
			global $wp_filesystem;
			if ( ! $wp_filesystem->move($_FILES['import']['tmp_name'], $filename, true) ) {
				echo 'Error saving file!';
				return;
			}

			$file = $_FILES['import'];

			if ($file['type'] == 'text/plain') {
				$data = $wp_filesystem->get_contents($filename);
				// try to read the file
				if ($data !== FALSE){
					$settings = json_decode($data, true);
					// try to read the settings array
					if (isset($settings[_THEME_NAME.'_db'])){ ?>
        <div class="wrap">
        <div id="icon-tools" class="icon32"><br></div>
        <h2><?php echo __( 'Import Theme Options ', 'cryout' );?></h2> <?php
						$settings = array_merge($theme_settings, $settings);
						update_option(_THEME_NAME.'_settings', $settings);
						echo '<div class="updated fade"><p>'. __('Great! The options have been imported!', 'cryout').'<br />';
						echo '<a href="themes.php?page='._THEME_NAME.'-page">'.__('Go back to the theme settings page and check them out!', 'cryout').'<a></p></div>';
					}
					else { // else: try to read the settings array
						echo '<div class="error"><p><strong>'.__('Oops, there\'s a small problem.', 'cryout').'</strong><br />';
						echo __('The uploaded file does not contain valid theme options. Make sure the file is exported from the theme options page.', 'cryout').'</p></div>';
						cryout_import_form();
					}
				}
				else { // else: try to read the file
					echo '<div class="error"><p><strong>'.__('Oops, there\'s a small problem.', 'cryout').'</strong><br />';
					echo __('The uploaded file could not be read.', 'cryout').'</p></div>';
					cryout_import_form();
				}
			}
			else { // else: make sure the file uploaded was a plain text file
				echo '<div class="error"><p><strong>'.__('Oops, there\'s a small problem.', 'cryout').'</strong><br />';
				echo __('The uploaded file is not supported. Make sure the file was exported from the theme settings page and that it is a text file.', 'cryout').'</p></div>';
				cryout_import_form();
			}

			// Delete the file after we're done
			$wp_filesystem->delete($filename);

        }
        else { // else: make sure there is an import file uploaded
            echo '<div class="error"><p>'.__( 'Oops! The file is empty or there was no file. This error could also be caused by uploads being disabled in your php.ini or by post_max_size being defined as smaller than upload_max_filesize in php.ini.', 'cryout' ).'</p></div>';
			cryout_import_form();
        }
        echo '</div> <!-- end wrap -->';
    }
    else {
        wp_die(__('ERROR: You are not authorised to perform that operation', 'cryout'));
    }
} // cryout_import_file()

// Truncate function for use in the Admin RSS feed
function cryout_truncate_words($string,$words=20, $ellipsis=' ...') {
 $new = preg_replace('/((\w+\W+\'*){'.($words-1).'}(\w+))(.*)/', '${1}', $string);
 return $new.$ellipsis;
}

add_action('wp_ajax_feed_action', 'cryout_fetch_feed');
function cryout_fetch_feed() {
	
	$theme_news = fetch_feed( array( 'http://www.cryoutcreations.eu/cat/'._THEME_NAME.'/feed/') );
	$maxitems = 0;
	if ( ! is_wp_error( $theme_news ) ) {
			$maxitems = $theme_news->get_item_quantity( 10 );
			$news_items = $theme_news->get_items( 0, $maxitems );
	}
	?>
         <ul class="news-list">
            <?php if ( $maxitems == 0 ) : echo '<li>' . __( 'No news items.', 'cryout' ) . '</li>'; else :
						foreach( $news_items as $news_item ) : ?>
                    	<li>
                        	<a class="news-header" target="_blank" href='<?php echo esc_url( $news_item->get_permalink() ); ?>'><?php echo esc_html( $news_item->get_title() ); ?></a>
							<span class="news-item-date"><?php _e('Posted on','cryout'); echo $news_item->get_date(' j F Y'); ?></span>
							<a class="news-more" target="_blank" href='<?php echo esc_url( $news_item->get_permalink() ); ?>'><?php _e('Read the full post','cryout');?> &#8594;</a>
                        </li>
						<?php endforeach; 
				endif; ?>
          </ul>
<?php 
die();
} // cryout_fetch_feed()
