<?php
//You still shouldn't be able to open me up
defined( 'ABSPATH' ) || exit;

//define the variables
$woo_sms = [ 	
	'plugin' 		=> 'WooSMS - Instant Order Updates', 
	'plugin_uri' 	=> 'woosms-instant-order-updates', 
	'doc' 			=> 'https://smshall.com/woosms',
	'soporte' 		=> 'https://builderhall.com/contact',
	'plugin_url' 	=> 'https://builderhall.com/plugin', 
	'ajustes' 		=> 'admin.php?page=woo_sms', 
	'company_url'	=> 'https://builderhall.com',
	'company_name'	=> 'Builder Hall Ltd',
	'puntuacion' 	=> 'https://builderhall.com/' 
];

//Load the language
function woo_sms_inicia_idioma() {
    load_plugin_textdomain( 'woosms-instant-order-updates', null, dirname( DIRECCION_woo_sms ) . '/languages' );
}
add_action( 'plugins_loaded', 'woo_sms_inicia_idioma' );

//Load plugin settings
$woo_sms_settings = get_option( 'woo_sms_settings' );

//custom extra links
function woo_sms_enlaces( $enlaces, $archivo ) {
	global $woo_sms;

	if ( $archivo == DIRECCION_woo_sms ) {
		#$enlaces[] = '<a href="' . $woo_sms[ 'donacion' ] . '" target="_blank" title="' . __( 'Make a donation by ', 'woosms-instant-order-updates' ) . 'A"><span class="genericon genericon-cart"></span></a>';

		$enlaces[] = '<a href="https://smshall.com/woosms/">Doc</a>';

		//$enlaces[] = '<a href="https://www.facebook.com/BuilderHallPvtLTD" title="' . __( 'Follow us on ', 'woosms-instant-order-updates' ) . 'Facebook" target="_blank"><span class="genericon genericon-facebook-alt"></span></a> <a href="https://twitter.com/BuilderHallLTD" title="' . __( 'Follow us on ', 'woosms-instant-order-updates' ) . 'Twitter" target="_blank"><span class="genericon genericon-twitter"></span></a> <a href="https://www.linkedin.com/company/builderhallprivateltd" title="' . __( 'Follow us on ', 'woosms-instant-order-updates' ) . 'LinkedIn" target="_blank"><span class="genericon genericon-linkedin"></span></a> <a href="mailto:contact@builderhall.com" title="' . __( 'Contact with us by ', 'woosms-instant-order-updates' ) . 'e-mail"><span class="genericon genericon-mail"></span></a>';

		//$enlaces[] = '<a href="https://builderhall.com/plugin title="' . __( 'More plugins ', 'woosms-instant-order-updates' ) . 'Builder Hall" target="_blank"> <span class="genericon genericons-neue-website"></span></a>';

		#$enlaces[] = '<a href="mailto:contact@builderhall.com" title="' . __( 'Contact with us by ', 'woosms-instant-order-updates' ) . 'e-mail"><span class="genericon genericon-mail"></span></a>';
		
		#<a href="https://wa.me/message/QVCA54JUBFM7D1" title="' . __( 'Contact with us by ', 'woosms-instant-order-updates' ) . 'WhatsApp"><span class="genericon genericon-whatsapp"></span></a>
		#$enlaces[] = woo_sms_plugin( $woo_sms[ 'plugin_uri' ] );
	}

	return $enlaces;
}
add_filter( 'plugin_row_meta', 'woo_sms_enlaces', 10, 2 );

//Add the settings button
function woo_sms_enlace_de_ajustes( $enlaces ) { 
	global $woo_sms;

	$enlaces_de_ajustes = [ 
		'<a href="' . $woo_sms[ 'ajustes' ] . '" title="' . __( 'Settings of ', 'woosms-instant-order-updates' ) . $woo_sms[ 'plugin' ] .'">' . __( 'Settings', 'woosms-instant-order-updates' ) . '</a>',

		
		#'<a href="' . $woo_sms[ 'soporte' ] . '" title="' . __( 'Support of ', 'woosms-instant-order-updates' ) . $woo_sms[ 'plugin' ] .'">' . __( 'Support', 'woosms-instant-order-updates' ) . '</a>' 
	];
	foreach( $enlaces_de_ajustes as $enlace_de_ajustes )	{
		array_unshift( $enlaces, $enlace_de_ajustes );
	}

	return $enlaces; 
}
$plugin = DIRECCION_woo_sms; 
add_filter( "plugin_action_links_$plugin", 'woo_sms_enlace_de_ajustes' );

//Gets all information about the plugin
function woo_sms_plugin( $nombre ) {
	global $woo_sms;
	
	$respuesta	= get_transient( 'woo_sms_plugin' );
	if ( false === $respuesta ) {
		$respuesta = wp_remote_get( 'https://api.wordpress.org/plugins/info/1.2/?action=plugin_information&request[slug]=' . $nombre  );
		set_transient( 'woo_sms_plugin', $respuesta, 24 * HOUR_IN_SECONDS );
	}
	if ( ! is_wp_error( $respuesta ) ) {
		$plugin = json_decode( wp_remote_retrieve_body( $respuesta ) );
	} else {
	   return '<a title="' . sprintf( __( 'Please, rate %s:', 'woosms-instant-order-updates' ), $woo_sms[ 'plugin' ] ) . '" href="' . $woo_sms[ 'puntuacion' ] . '?rate=5#postform" class="estrellas">' . __( 'Unknown rating', 'woosms-instant-order-updates' ) . '</a>';
	}


// Plugin Page Rating star
    $rating = [
	   'rating'		=> $plugin->rating,
	   'type'		=> 'percent',
	   'number'		=> $plugin->num_ratings,
	];
	ob_start();
	wp_star_rating( $rating );
	$estrellas = ob_get_contents();
	ob_end_clean();

	return '<a title="' . sprintf( __( 'Please, rate %s:', 'woosms-instant-order-updates' ), $woo_sms[ 'plugin' ] ) . '" href="' . $woo_sms[ 'puntuacion' ] . '?rate=5#postform" class="estrellas">' . $estrellas . '</a>';
}

//Style sheet
function woo_sms_estilo() {
	if ( strpos( $_SERVER[ 'REQUEST_URI' ], 'woo_sms' ) !== false || strpos( $_SERVER[ 'REQUEST_URI' ], 'plugins.php' ) !== false ) {
		wp_register_style( 'woo_sms_hoja_de_estilo', plugins_url( 'assets/css/style.css', DIRECCION_woo_sms ) ); //Load the style sheet
		wp_enqueue_style( 'woo_sms_hoja_de_estilo' ); //Load the style sheet
	}
}
add_action( 'admin_enqueue_scripts', 'woo_sms_estilo' );
