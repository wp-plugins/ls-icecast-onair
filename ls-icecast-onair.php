<?php
/*
Plugin Name: LS IceCast ONAIR
Plugin URI: http://git.ladasoukup.cz/wp-icecast-onair-song-wp-plugin
Description: Shortcode to display onair song fetched from IceCast server (v2).
Version: 1.1.1
Author: Ladislav Soukup
Author URI: http://www.ladasoukup.cz/
*/

wp_register_script('ls_icecast_onair',
					plugins_url('ls-icecast-onair.js', __FILE__),
					array('jquery'),
					'1.1.0',
					true
);

add_action('wp_footer', 'ls_icecast_add2footer');
function ls_icecast_add2footer() {
	$upload_dir = wp_upload_dir(); $file = $upload_dir['baseurl'] . '/icecast_onair.txt';
	echo "<script>var ls_icecast_onair_url = '".$file."';</script>";
}

/* === shortcode === */
add_shortcode( 'icecast', 'sh_ls_icecast');
function sh_ls_icecast( $atts, $content ) {
	wp_enqueue_script('ls_icecast_onair');
	$html = '';
	$id = uniqid('icecast_onair_');
	$live = 0; if (!empty($atts['live'])) $live = $atts['live'];
	
	$html .= '<span class="icecast_onair_outer"><span class="icecast_onair_inner'; if ($live == 1) { $html .= ' icecast_onair_live'; } $html .= '" id="'.$id.'">';
	$html .= get_option('ls_icecast_ONAIRdata');
	$html .= '</span></span>';
	
	return ($html);
}

/* === admin menu === */
add_action('admin_menu', 'admitem_ls_icecast');
function admitem_ls_icecast() {
        add_menu_page( 'IceCast ONAIR', 'IceCast ONAIR', 'manage_options', 'ls_icecast_admin', 'ls_icecast_admin', plugins_url('icecast_logo.png', __FILE__), 100.25457 );
}
function ls_icecast_admin() {
	if ( current_user_can('manage_options')  !== true ) die('access dinied');
	
	if ($_GET['DO'] == 'save') {
		update_option( 'ls_icecast_url', $_POST['ls_icecast_url'] );
		update_option( 'ls_icecast_mount', $_POST['ls_icecast_mount'] );
		cron_ls_icecast__do();
	}
	
	?>
	<div class="wrap">
		<div id="icon-themes" class="icon32"><br /></div>
		<h2>IceCast ONAIR</h2>
    </div>
	<form name="frm_editor" action="?page=ls_icecast_admin&DO=save" method="POST">
		<div>
			<h3>IceCast server settings</h3>
			<table>
				<tr><td><strong>Icecast server (url): </strong></td><td><input type="text" style="width: 410px;" name="ls_icecast_url" value="<?php echo get_option('ls_icecast_url'); ?>" /></td></tr>
				<tr><td><strong>Mount name: </strong></td><td><input type="text" style="width: 410px;" name="ls_icecast_mount" value="<?php echo get_option('ls_icecast_mount'); ?>" /></td></tr>
				<tr><td><strong>Current value: </strong></td><td><?php echo get_option('ls_icecast_ONAIRdata'); ?></td></tr>
				<tr><td><strong>Last error: </strong></td><td><?php echo get_option('ls_icecast_errorlog', ''); ?></td></tr>
			</table>
		</div>
		<div>
			<div>&nbsp;<br/></div>
			<input type="submit" class="button button-primary" value="Uložit změny" />
		</div>
	</form>
	
	<?php
}

/* === CRON to update radios === */
add_filter( 'cron_schedules', 'cron_ls_icecast__intervals' );
function cron_ls_icecast__intervals( $array ) {
        $array['ls_icecast_cron'] = array(
                'interval' => 20,
                'display' => 'Every 20 seconds'
        );
        return $array;
}

add_action ( 'cron_ls_icecast', 'cron_ls_icecast__do' );
function cron_ls_icecast__do() {
	$url = get_option('ls_icecast_url') . '/xml.xsl?ts='.time();
	$mount = '/' . get_option('ls_icecast_mount'); $mount = str_replace('//', '/', $mount);
	
	$response = wp_remote_get($url);  /** echo '<pre>'; print_r($response); echo '</pre>'; /**/
	$response_code = wp_remote_retrieve_response_code( $response );
	$error = '-';
	
	if ( 200 == $response_code ) {
		$data = wp_remote_retrieve_body( $response );
		
		$data = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
		foreach ($data as $item) {
			if ($item->MOUNT == $mount) {
				// echo '<pre>'; print_r($item); echo '</pre>';
				$artist = $item->SONG->ARTIST;
				$song = $item->SONG->TITLE;
				
				$onairdata = '';
				if ($artist != '') { $onairdata .= $artist . ' - '; }
				$onairdata .= $song;
				update_option( 'ls_icecast_ONAIRdata', strip_tags($onairdata));
				
				$upload_dir = wp_upload_dir(); $file = $upload_dir['basedir'] . '/icecast_onair.txt';
				file_put_contents($file, strip_tags($onairdata));
			}
		}
	} else {
		$error = $response_code . ' - ' . $response->errors['http_request_failed'][0];
	}
	
	update_option( 'ls_icecast_errorlog', $error );
}

/* === register / deregister hooks === */
function cron_ls_icecast__init() {
        if ( !wp_next_scheduled( 'cron_ls_icecast' ) ) {
                wp_schedule_event( time(), 'ls_icecast_cron', 'cron_ls_icecast' );
        }
}
function cron_ls_icecast__clean() {
        wp_clear_scheduled_hook( 'cron_ls_icecast' );
}
register_activation_hook( __FILE__, 'cron_ls_icecast__init' );
register_deactivation_hook( __FILE__, 'cron_ls_icecast__clean' );



?>