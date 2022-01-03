<?php
/*
	Plugin Name: Partytown
	Plugin URI: https://github.com/builderio/partytown
	Description: Load your JS in service worker automatically
	Author: Daniele Scasciafratte
	Version: 0.0.1
	Author URI: http://codeat.co/
*/

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

// Enqueue Partytown on page as first JS
function enqueue_partytown() {
	?>
<script>
	window.partytown = {lib:"<?php echo plugins_url( 'wp-partytown/assets/', plugin_dir_path( __FILE__ ) ) ?>"};
</script>
<script src="<?php plugins_url( 'wp-partytown/assets/', plugin_dir_path( __FILE__ ) ) ?>/partytown.js"></script>
	<?php
}
add_action('wp_print_scripts', 'enqueue_partytown', -1);

function add_partytown_on_type( $tag, $handle, $src ) {
	$url = parse_url($src);
	$this_host = parse_url( get_site_url() );
	// If it isn't a third party library ignore
	if ( $this_host['host'] === $url['host'] ) {
		return $tag;
	}

	// We need to exclude internal libraries and use partytown only for third party
	if ( false === stripos( $tag, 'partytown' ) ) {
		$tag = str_replace(' src', ' script="text/partytown" src', $tag);
		$tag = str_replace(' type=\'text/javascript\'', '', $tag);
	}

	return $tag;
}
add_filter('script_loader_tag', 'add_partytown_on_type', 10, 3);
