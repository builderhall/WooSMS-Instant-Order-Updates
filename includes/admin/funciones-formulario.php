<?php
//You still shouldn't be able to open me up
defined( 'ABSPATH' ) || exit;

global $woo_sms_settings, $wpml_activo, $mensajes;

//tab control
$tab    = 1;

//WPML
if ( $woo_sms_settings ) {    
    foreach ( $mensajes as $mensaje ) {
        if ( function_exists( 'icl_register_string' ) || ! $wpml_activo ) { //Version before 3.2
            $$mensaje		= ( $wpml_activo ) ? icl_translate( 'woo_sms', $mensaje, esc_textarea( $woo_sms_settings[ $mensaje ] ) ) : esc_textarea( $woo_sms_settings[ $mensaje ] );
        } else if ( $wpml_activo ) { //Version 3.2 or higher
            $$mensaje		= apply_filters( 'wpml_translate_single_string', esc_textarea( $woo_sms_settings[ $mensaje ] ), 'woo_sms', $mensaje );
        }
    }
} else { //Initialize variables
    foreach ( $mensajes as $mensaje ) {
        $$mensaje   = '';
    }
}

//List of SMS providers
$listado_de_proveedores = [ 
/*        "adlinks"           => "Adlinks Labs",
        "altiria"           => "Altiria",
        "bulkgate"          => "BulkGate",
        "bulksms"           => "BulkSMS",
        "clickatell"        => "Clickatell",
        "clockwork"         => "Clockwork",
        "esebun"            => "Esebun Business ( Enterprise & Developers only )",
        "isms"              => "iSMS Malaysia",
        "labsmobile"        => "LabsMobile",
        "mobtexting"        => "MobTexting",
        "moplet"            => "Moplet",
        "moreify"           => "Moreify",
        "msg91"             => "MSG91",
        "msgwow"            => "MSGWOW",
        "mvaayoo"           => "mVaayoo",
        "nexmo"             => "Nexmo",
        "plivo"             => "Plivo",
        "routee"            => "Routee",
        "sendsms"           => "sendSMS.ro",
        "sipdiscount"       => "SIP Discount",
        "smscx"             => "SMS.CX (SMS Connexion)",
        "smscountry"        => "SMS Country",
        "smsdiscount"       => "SMS Discount",*/
        "smsgateway_smshall" => "SMS Hall (Bulk SMS Single Solution)",
/*        "smslane"           => "SMS Lane ( Transactional SMS only )",
        "solutions_infini"  => "Solutions Infini",
        "springedge"        => "Spring Edge",
        "twilio"            => "Twilio",
        "twizo"             => "Twizo",
        "voipbuster"        => "VoipBuster",
        "voipbusterpro"     => "VoipBusterPro",
        "voipstunt"         => "VoipStunt",
        "waapi"             => "WhatsApp Message By WA Api",*/
];
asort( $listado_de_proveedores, SORT_NATURAL | SORT_FLAG_CASE ); //Sort providers alphabetically

//Required fields for each provider
$campos_de_proveedores      = [
	"adlinks"			=> [
 		"usuario_adlinks"                 => __( 'authentication key', 'woosms-instant-order-updates' ),
 		"ruta_adlinks"                    => __( 'route', 'woosms-instant-order-updates' ),
 		"identificador_adlinks"           => __( 'sender ID', 'woosms-instant-order-updates' ),
 	],
	"altiria"			=> [
 		"usuario_altiria"                 => __( 'username', 'woosms-instant-order-updates' ),
 		"contrasena_altiria"              => __( 'password', 'woosms-instant-order-updates' ),
 	],
	"bulkgate"			=> [
 		"usuario_bulkgate"                => __( 'application ID', 'woosms-instant-order-updates' ),
 		"clave_bulkgate"                  => __( 'authentication Token', 'woosms-instant-order-updates' ),
 		"identificador_bulkgate"          => __( 'sender ID', 'woosms-instant-order-updates' ),
 		"unicode_bulkgate"                => __( 'unicode', 'woosms-instant-order-updates' ),
    ],
	"bulksms" 			=> [ 
		"usuario_bulksms"                 => __( 'username', 'woosms-instant-order-updates' ),
		"contrasena_bulksms"              => __( 'password', 'woosms-instant-order-updates' ),
		"servidor_bulksms"                => __( 'host', 'woosms-instant-order-updates' ),
	],
	"clickatell" 		=> [ 
		"identificador_clickatell"        => __( 'key', 'woosms-instant-order-updates' ),
	],
	"clockwork" 		=> [ 
		"identificador_clockwork"         => __( 'key', 'woosms-instant-order-updates' ),
	],
	"esebun" 			=> [ 
		"usuario_esebun"                  => __( 'username', 'woosms-instant-order-updates' ),
		"contrasena_esebun"               => __( 'password', 'woosms-instant-order-updates' ),
		"identificador_esebun"            => __( 'sender ID', 'woosms-instant-order-updates' ),
	],
	"isms" 				=> [ 
		"usuario_isms"                    => __( 'username', 'woosms-instant-order-updates' ),
		"contrasena_isms"                 => __( 'password', 'woosms-instant-order-updates' ),
		"telefono_isms"                   => __( 'mobile number', 'woosms-instant-order-updates' ),
	],
	"labsmobile"		=> [
		"usuario_labsmobile"              => __( 'username', 'woosms-instant-order-updates' ),
		"contrasena_labsmobile"           => __( 'password', 'woosms-instant-order-updates' ),
		"sid_labsmobile"                  => __( 'sender ID', 'woosms-instant-order-updates' ),
	],
	"mobtexting"		=> [ 
		"clave_mobtexting"                => __( 'key', 'woosms-instant-order-updates' ),
		"identificador_mobtexting"        => __( 'sender ID', 'woosms-instant-order-updates' ),
	],
	"moplet" 			=> [ 
		"clave_moplet"                    => __( 'authentication key', 'woosms-instant-order-updates' ),
		"identificador_moplet"            => __( 'sender ID', 'woosms-instant-order-updates' ),
		"ruta_moplet"                     => __( 'route', 'woosms-instant-order-updates' ),
		"servidor_moplet"                 => __( 'host', 'woosms-instant-order-updates' ),
		"dlt_moplet"                      => __( 'template ID', 'woosms-instant-order-updates' ),
	],
	"moreify" 			=> [ 
		"proyecto_moreify"                => __( 'project', 'woosms-instant-order-updates' ),
		"identificador_moreify"           => __( 'authentication Token', 'woosms-instant-order-updates' ),
	],
	"msg91" 			=> [ 
		"clave_msg91"                     => __( 'authentication key', 'woosms-instant-order-updates' ),
		"identificador_msg91"             => __( 'sender ID', 'woosms-instant-order-updates' ),
		"ruta_msg91"                      => __( 'route', 'woosms-instant-order-updates' ),
		"dlt_msg91"                       => __( 'template ID', 'woosms-instant-order-updates' ),
    ],
	"msgwow" 			=> [ 
		"clave_msgwow"                    => __( 'key', 'woosms-instant-order-updates' ),
		"identificador_msgwow"            => __( 'sender ID', 'woosms-instant-order-updates' ),
		"ruta_msgwow"                     => __( 'route', 'woosms-instant-order-updates' ),
		"servidor_msgwow"                 => __( 'host', 'woosms-instant-order-updates' ),
	],
	"mvaayoo" 			=> [ 
		"usuario_mvaayoo"                 => __( 'username', 'woosms-instant-order-updates' ),
		"contrasena_mvaayoo"              => __( 'password', 'woosms-instant-order-updates' ),
		"identificador_mvaayoo"           => __( 'sender ID', 'woosms-instant-order-updates' ),
	],
	"nexmo" 			=> [ 
		"clave_nexmo"                     => __( 'key', 'woosms-instant-order-updates' ),
		"identificador_nexmo"             => __( 'authentication Token', 'woosms-instant-order-updates' ),
	],
	"plivo"				=> [
		"usuario_plivo"                   => __( 'authentication ID', 'woosms-instant-order-updates' ),
		"clave_plivo"                     => __( 'authentication Token', 'woosms-instant-order-updates' ),
		"identificador_plivo"             => __( 'sender ID', 'woosms-instant-order-updates' ),
	],
	"routee"			=> [ 
		"usuario_routee"                  => __( 'application ID', 'woosms-instant-order-updates' ),
		"contrasena_routee"               => __( 'application secret', 'woosms-instant-order-updates' ),
		"identificador_routee"            => __( 'sender ID', 'woosms-instant-order-updates' ),
	], 
	"sendsms"           => [ 
		"usuario_sendsms"                 => __( 'username', 'woosms-instant-order-updates' ),
		"contrasena_sendsms"              => __( 'password', 'woosms-instant-order-updates' ),
		"short_sendsms"                   => __( 'short URL', 'woosms-instant-order-updates' ),
		"gdpr_sendsms"                    => __( 'unsubscribe link', 'woosms-instant-order-updates' ),
	], 
	"sipdiscount"		=> [ 
		"usuario_sipdiscount"             => __( 'username', 'woosms-instant-order-updates' ),
		"contrasena_sipdiscount"          => __( 'password', 'woosms-instant-order-updates' ),
	], 
	"smscx"            => [ 
		"usuario_smscx"                   => __( 'application ID', 'woosms-instant-order-updates' ),
		"contrasena_smscx"                => __( 'application secret', 'woosms-instant-order-updates' ),
		"identificador_smscx"             => __( 'sender ID', 'woosms-instant-order-updates' ),
	],
	"smscountry" 		=> [ 
		"usuario_smscountry"              => __( 'username', 'woosms-instant-order-updates' ),
		"contrasena_smscountry"           => __( 'password', 'woosms-instant-order-updates' ),
		"sid_smscountry"                  => __( 'sender ID', 'woosms-instant-order-updates' ),
	],
	"smsdiscount"		=> [ 
		"usuario_smsdiscount"             => __( 'username', 'woosms-instant-order-updates' ),
		"contrasena_smsdiscount"          => __( 'password', 'woosms-instant-order-updates' ),
	],
	"smsgateway_smshall" => [
//		"servidor_smsgateway_smshall"      => __( 'server', 'woosms-instant-order-updates' ),
		"clave_smsgateway_smshall"         => __( 'API Key', 'woosms-instant-order-updates' ),
		"identificador_smsgateway_smshall" => __( 'device ID', 'woosms-instant-order-updates' ),
		"type_smshall"                     => __( 'type', 'woosms-instant-order-updates' ),
		"prioritize_smshall"               => __( 'prioritize', 'woosms-instant-order-updates' ),
	],
	"smslane" 			=> [ 
		"usuario_smslane"                 => __( 'key', 'woosms-instant-order-updates' ),
		"contrasena_smslane"              => __( 'client ID', 'woosms-instant-order-updates' ),
		"sid_smslane"                     => __( 'sender ID', 'woosms-instant-order-updates' ),
	],
	"solutions_infini" 	=> [ 
		"clave_solutions_infini"          => __( 'key', 'woosms-instant-order-updates' ),
		"identificador_solutions_infini"  => __( 'sender ID', 'woosms-instant-order-updates' ),
	],
	"springedge" 		=> [ 
		"clave_springedge"                => __( 'key', 'woosms-instant-order-updates' ),
		"identificador_springedge"        => __( 'sender ID', 'woosms-instant-order-updates' ),
	],
	"twilio" 			=> [ 
		"clave_twilio"                    => __( 'account Sid', 'woosms-instant-order-updates' ),
		"identificador_twilio"            => __( 'authentication Token', 'woosms-instant-order-updates' ),
		"telefono_twilio"                 => __( 'mobile number', 'woosms-instant-order-updates' ),
	],
	"twizo" 			=> [ 
		"clave_twizo"                     => __( 'key', 'woosms-instant-order-updates' ),
		"identificador_twizo"             => __( 'sender ID', 'woosms-instant-order-updates' ),
		"servidor_twizo"                  => __( 'host', 'woosms-instant-order-updates' ),
	],
	"voipbuster"		=> [ 
		"usuario_voipbuster"              => __( 'username', 'woosms-instant-order-updates' ),
		"contrasena_voipbuster"           => __( 'password', 'woosms-instant-order-updates' ),
	], 
	"voipbusterpro"		=> [ 
		"usuario_voipbusterpro"           => __( 'username', 'woosms-instant-order-updates' ),
		"contrasena_voipbusterpro"        => __( 'password', 'woosms-instant-order-updates' ),
	], 
	"voipstunt"			=> [ 
		"usuario_voipstunt"               => __( 'username', 'woosms-instant-order-updates' ),
		"contrasena_voipstunt"            => __( 'password', 'woosms-instant-order-updates' ),
	], 
	"waapi"             => [
		"dominio_waapi"                   => __( 'API Domain', 'woosms-instant-order-updates' ),
		"usuario_waapi"                   => __( 'client ID', 'woosms-instant-order-updates' ),
		"contrasena_waapi"                => __( 'instance ID', 'woosms-instant-order-updates' ),
	], 
];

//Vendor selection field options
$opciones_de_proveedores        = [
	"type_smshall"		=> [
		"sms"					=> __( 'SMS', 'woosms-instant-order-updates' ),
		"mms"					=> __( 'MMS', 'woosms-instant-order-updates' ),
	],
	"prioritize_smshall"	=> [
		1						=> __( 'Yes', 'woosms-instant-order-updates' ),
		0						=> __( 'No', 'woosms-instant-order-updates' ),
	],
	"ruta_adlinks"		=> [
		1						=> 1, 
		4						=> 4,
	],
	"servidor_bulksms"	=> [
		"bulksms.vsms.net"		=> __( 'International', 'woosms-instant-order-updates' ), 
		"www.bulksms.co.uk"		=> __( 'UK', 'woosms-instant-order-updates' ),
		"usa.bulksms.com"		=> __( 'USA', 'woosms-instant-order-updates' ),
		"bulksms.2way.co.za"	=> __( 'South Africa', 'woosms-instant-order-updates' ),
		"bulksms.com.es"		=> __( 'Spain', 'woosms-instant-order-updates' ),
	],
	"servidor_moplet"	=> [
		"0"						=> __( 'International', 'woosms-instant-order-updates' ), 
		"1"						=> __( 'USA', 'woosms-instant-order-updates' ), 
		"91"					=> __( 'India', 'woosms-instant-order-updates' ),
	],	
	"ruta_moplet"		=> [
		1						=> 1, 
		4						=> 4,
	],
	"ruta_msg91"		=> [
		"default"				=> __( 'Default', 'woosms-instant-order-updates' ), 
		1						=> 1, 
		4						=> 4,
	],
	"ruta_msgwow"		=> [
		1						=> 1, 
		4						=> 4,
	],
	"servidor_msgwow"	=> [
		"0"						=> __( 'International', 'woosms-instant-order-updates' ), 
		"1"						=> __( 'USA', 'woosms-instant-order-updates' ), 
		"91"					=> __( 'India', 'woosms-instant-order-updates' ), 
	],	
	"servidor_twizo"	=> [
		"api-asia-01.twizo.com"	=> __( 'Singapore', 'woosms-instant-order-updates' ), 
		"api-eu-01.twizo.com"	=> __( 'Germany', 'woosms-instant-order-updates' ), 
	],
    "unicode_bulkgate"  => [
 		1                       => __( 'Yes', 'woosms-instant-order-updates' ),
 		0                       => __( 'No', 'woosms-instant-order-updates' ),
 	],
];

//verification fields
$verificacion_de_proveedores    = [
    "short_sendsms",
    "gdpr_sendsms",
    "dlt_moplet",
    "dlt_msg91",
];

//Order status list
$listado_de_estados				= wc_get_order_statuses();
$listado_de_estados_temporal	= [];
$estados_originales				= [ 
	'pending',
	'failed',
	'on-hold',
	'processing',
	'completed',
	'refunded',
	'cancelled',
];
foreach ( $listado_de_estados as $clave => $estado ) {
	$nombre_de_estado = str_replace( "wc-", "", $clave );
	if ( ! in_array( $nombre_de_estado, $estados_originales ) ) {
		$listado_de_estados_temporal[ $estado ] = $nombre_de_estado;
	}
}
$listado_de_estados = $listado_de_estados_temporal;

//List of custom messages
$listado_de_mensajes = [
	'todos'					=> __( 'All messages', 'woosms-instant-order-updates' ),
	'mensaje_pedido'		=> __( 'Owner custom message', 'woosms-instant-order-updates' ),
	'mensaje_pendiente'		=> __( 'Order pending custom message', 'woosms-instant-order-updates' ),
	'mensaje_fallido'		=> __( 'Order failed custom message', 'woosms-instant-order-updates' ),
	'mensaje_recibido'		=> __( 'Order on-hold custom message', 'woosms-instant-order-updates' ),
	'mensaje_procesando'	=> __( 'Order processing custom message', 'woosms-instant-order-updates' ),
	'mensaje_completado'	=> __( 'Order completed custom message', 'woosms-instant-order-updates' ),
	'mensaje_devuelto'		=> __( 'Order refunded custom message', 'woosms-instant-order-updates' ),
	'mensaje_cancelado'		=> __( 'Order cancelled custom message', 'woosms-instant-order-updates' ),
	'mensaje_nota'			=> __( 'Notes custom message', 'woosms-instant-order-updates' ),
];

/*
Paint the select field with the list of providers
*/
function woo_sms_listado_de_proveedores( $listado_de_proveedores ) {
	global $woo_sms_settings;
	
	foreach ( $listado_de_proveedores as $valor => $proveedor ) {
		$chequea = ( isset( $woo_sms_settings[ 'servicio' ] ) && $woo_sms_settings[ 'servicio' ] == $valor ) ? ' selected="selected"' : '';
		echo '<option value="' . esc_attr( $valor ) . '"' . $chequea . '>' . $proveedor . '</option>' . PHP_EOL;
	}
}

/*
Paint the fields of the suppliers
*/
function woo_sms_campos_de_proveedores( $listado_de_proveedores, $campos_de_proveedores, $opciones_de_proveedores, $verificacion_de_proveedores ) {
	global $woo_sms_settings, $tab;
	
	foreach ( $listado_de_proveedores as $valor => $proveedor ) {
		foreach ( $campos_de_proveedores[$valor] as $valor_campo => $campo ) {
			if ( array_key_exists( $valor_campo, $opciones_de_proveedores ) ) { //Campo select
				echo '
  <tr valign="top" class="' . $valor . '"><!-- ' . $proveedor . ' -->
	<th scope="row" class="titledesc"> <label for="woo_sms_settings[' . $valor_campo . ']">' .ucfirst( $campo ) . ':' . '
	  <span class="woocommerce-help-tip" data-tip="' . sprintf( __( 'The %s for your account in %s', 'woosms-instant-order-updates' ), $campo, $proveedor ) . '"></span></label></th>
	<td class="forminp forminp-number"><select class="wc-enhanced-select" id="woo_sms_settings[' . $valor_campo . ']" name="woo_sms_settings[' . $valor_campo . ']" tabindex="' . $tab++ . '">
				';
				foreach ( $opciones_de_proveedores[$valor_campo] as $valor_opcion => $opcion ) {
					$chequea = ( isset( $woo_sms_settings[$valor_campo] ) && $woo_sms_settings[$valor_campo] == $valor_opcion ) ? ' selected="selected"' : '';
					echo '<option value="' . esc_attr( $valor_opcion ) . '"' . $chequea . '>' . $opcion . '</option>' . PHP_EOL;
				}
				echo '          </select></td>
  </tr>
				';
			} elseif ( in_array( $valor_campo, $verificacion_de_proveedores ) ) { //Campo checkbox
                $dlt        = ( strpos( $valor_campo, "dlt_" ) !== false ) ? ' class="dlt"' : '';
                $chequea    = ( isset( $woo_sms_settings[$valor_campo] ) && $woo_sms_settings[$valor_campo] == 1 ) ? ' checked="checked"' : '';
				echo '
  <tr valign="top" class="' . $valor . '"><!-- ' . $proveedor . ' -->
	<th scope="row" class="titledesc"> <label for="woo_sms_settings[' . $valor_campo . ']">' . ucfirst( $campo ) . ':' . '
	  <span class="woocommerce-help-tip" data-tip="' . sprintf( __( 'The %s for your account in %s', 'woosms-instant-order-updates' ), $campo, $proveedor ) . '"></span></label></th>
	<td class="forminp forminp-number"><input type="checkbox"' . $dlt . ' id="woo_sms_settings[' . $valor_campo . ']" name="woo_sms_settings[' . $valor_campo . ']" value="1"' . $chequea . ' tabindex="' . $tab++ . '" ></td>
  </tr>
				';
            } else { //Campo input
				echo '
  <tr valign="top" class="' . $valor . '"><!-- ' . $proveedor . ' -->
	<th scope="row" class="titledesc"> <label for="woo_sms_settings[' . $valor_campo . ']">' . ucfirst( $campo ) . ':' . '
	  <span class="woocommerce-help-tip" data-tip="' . sprintf( __( 'The %s for your account in %s', 'woosms-instant-order-updates' ), $campo, $proveedor ) . '"></span></label></th>
	<td class="forminp forminp-number"><input type="text" id="woo_sms_settings[' . $valor_campo . ']" name="woo_sms_settings[' . $valor_campo . ']" size="50" value="' . ( isset( $woo_sms_settings[$valor_campo] ) ? esc_attr( $woo_sms_settings[$valor_campo] ) : '' ) . '" tabindex="' . $tab++ . '" /></td>
  </tr>
				';
			}
		}
	}
}

/*
Paint the submission form fields
*/
function woo_sms_campos_de_envio() {
	global $woo_sms_settings;

	$pais					= new WC_Countries();
	$campos					= $pais->get_address_fields( $pais->get_base_country(), 'shipping_' ); //Campos ordinarios
	$campos_personalizados	= apply_filters( 'woocommerce_checkout_fields', [] );
	if ( isset( $campos_personalizados[ 'shipping' ] ) ) {
		$campos += $campos_personalizados[ 'shipping' ];
	}
	foreach ( $campos as $valor => $campo ) {
		$chequea = ( isset( $woo_sms_settings[ 'campo_envio' ] ) && $woo_sms_settings[ 'campo_envio' ] == $valor ) ? ' selected="selected"' : '';
		if ( isset( $campo[ 'label' ] ) ) {
			echo '<option value="' . esc_attr( $valor ) . '"' . $chequea . '>' . $campo[ 'label' ] . '</option>' . PHP_EOL;
		}
	}
}

/*
Paint the select field with the list of order statuses
*/
function woo_sms_listado_de_estados( $listado_de_estados ) {
	global $woo_sms_settings;

	foreach( $listado_de_estados as $nombre_de_estado => $estado ) {
		$chequea = '';
		if ( isset( $woo_sms_settings[ 'estados_personalizados' ] ) ) {
			foreach ( $woo_sms_settings[ 'estados_personalizados' ] as $estado_personalizado ) {
				if ( $estado_personalizado == $estado ) {
					$chequea = ' selected="selected"';
				}
			}
		}
		echo '<option value="' . esc_attr( $estado ) . '"' . $chequea . '>' . $nombre_de_estado . '</option>' . PHP_EOL;
	}
}

/*
Paint the select field with the list of custom messages
*/
function woo_sms_listado_de_mensajes( $listado_de_mensajes ) {
	global $woo_sms_settings;
	
	$chequeado = false;
	foreach ( $listado_de_mensajes as $valor => $mensaje ) {
		if ( isset( $woo_sms_settings[ 'mensajes' ] ) && in_array( $valor, $woo_sms_settings[ 'mensajes' ] ) ) {
			$chequea	= ' selected="selected"';
			$chequeado	= true;
		} else {
			$chequea	= '';
		}
		$texto = ( ! isset( $woo_sms_settings[ 'mensajes' ] ) && $valor == 'todos' && ! $chequeado ) ? ' selected="selected"' : '';
		echo '<option value="' . esc_attr( $valor ) . '"' . $chequea . $texto . '>' . $mensaje . '</option>' . PHP_EOL;
	}
}

/*
Paint the message fields
*/
function woo_sms_campo_de_mensaje_personalizado( $campo, $campo_cliente, $listado_de_mensajes ) {
    global $tab, $woo_sms_settings;
    
    //List of custom messages
    $listado_de_mensajes_personalizados = [
        'mensaje_pedido'		=> __( 'Order No. %s received on ', 'woosms-instant-order-updates' ),
        'mensaje_pendiente'		=> __( 'Thank you for shopping with us! Your order No. %s is now: ', 'woosms-instant-order-updates' ),
        'mensaje_fallido'		=> __( 'Thank you for shopping with us! Your order No. %s is now: ', 'woosms-instant-order-updates' ),
        'mensaje_recibido'		=> __( 'Your order No. %s is received on %s. Thank you for shopping with us!', 'woosms-instant-order-updates' ),
        'mensaje_procesando'	=> __( 'Thank you for shopping with us! Your order No. %s is now: ', 'woosms-instant-order-updates' ),
        'mensaje_completado'	=> __( 'Thank you for shopping with us! Your order No. %s is now: ', 'woosms-instant-order-updates' ),
        'mensaje_devuelto'		=> __( 'Thank you for shopping with us! Your order No. %s is now: ', 'woosms-instant-order-updates' ),
        'mensaje_cancelado'		=> __( 'Thank you for shopping with us! Your order No. %s is now: ', 'woosms-instant-order-updates' ),
        'mensaje_nota'			=> __( 'A note has just been added to your order No. %s: ', 'woosms-instant-order-updates' ),
    ];

    //List of custom texts
    $listado_de_textos_personalizados = [
        'mensaje_pendiente'		=> __( 'Pending', 'woosms-instant-order-updates' ),
        'mensaje_fallido'		=> __( 'Failed', 'woosms-instant-order-updates' ),
        'mensaje_procesando'	=> __( 'Processing', 'woosms-instant-order-updates' ),
        'mensaje_completado'	=> __( 'Completed', 'woosms-instant-order-updates' ),
        'mensaje_devuelto'		=> __( 'Refunded', 'woosms-instant-order-updates' ),
        'mensaje_cancelado'		=> __( 'Cancelled', 'woosms-instant-order-updates' ),
    ];

    if ( $campo == 'mensaje_pedido'  ) {
        $texto  = stripcslashes( ! empty( $campo_cliente ) ? $campo_cliente : sprintf( __( $listado_de_mensajes_personalizados[ $campo ], 'woosms-instant-order-updates' ), "%id%" ) . "%shop_name%" . "." );
    } elseif ( $campo == 'mensaje_recibido'  ) {
        $texto  = stripcslashes( ! empty( $campo_cliente ) ? $campo_cliente : sprintf( __( $listado_de_mensajes_personalizados[ $campo ], 'woosms-instant-order-updates' ), "%id%", "%shop_name%" ) );
    } elseif ( $campo == 'mensaje_nota'  ) {
        $texto  = stripcslashes( ! empty( $campo_cliente ) ? $campo_cliente : sprintf( __( $listado_de_mensajes_personalizados[ $campo ], 'woosms-instant-order-updates' ), "%id%" ) . "%note%" );
    } else {
        $texto  = stripcslashes( ! empty( $campo_cliente ) ? $campo_cliente : sprintf( __( $listado_de_mensajes_personalizados[ $campo ], 'woosms-instant-order-updates' ), "%id%" ) . __( $listado_de_textos_personalizados[ $campo ], 'woosms-instant-order-updates' ) . "." );
    }
    
    //List of custom messages - DLT
    $listado_de_mensajes_dlt = [
        'mensaje_pedido'		=> __( 'Owner custom message template ID', 'woosms-instant-order-updates' ),
        'mensaje_pendiente'		=> __( 'Order pending custom message template ID', 'woosms-instant-order-updates' ),
        'mensaje_fallido'		=> __( 'Order failed custom message template ID', 'woosms-instant-order-updates' ),
        'mensaje_recibido'		=> __( 'Order on-hold custom message template ID', 'woosms-instant-order-updates' ),
        'mensaje_procesando'	=> __( 'Order processing custom message template ID', 'woosms-instant-order-updates' ),
        'mensaje_completado'	=> __( 'Order completed custom message template ID', 'woosms-instant-order-updates' ),
        'mensaje_devuelto'		=> __( 'Order refunded custom message template ID', 'woosms-instant-order-updates' ),
        'mensaje_cancelado'		=> __( 'Order cancelled custom message template ID', 'woosms-instant-order-updates' ),
        'mensaje_nota'			=> __( 'Notes custom message template ID', 'woosms-instant-order-updates' ),
    ];
    
    $texto_dlt  = ( isset( $woo_sms_settings[ 'dlt_' . $campo ] ) ) ? $woo_sms_settings[ 'dlt_' . $campo ] : '';
    
    echo '
        <tr valign="top" class="' . $campo . '">
            <th scope="row" class="titledesc">
                <label for="woo_sms_settings[' . $campo . ']">
                    ' . __( $listado_de_mensajes[ $campo ], 'woosms-instant-order-updates' ) .':
                    <span class="woocommerce-help-tip" data-tip="'. __( "You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %prices_include_tax%, %tax_display_cart%, %display_totals_ex_tax%, %display_cart_ex_tax%, %order_date%, %modified_date%, %customer_message%, %customer_note%, %post_status%, %shop_name%, %order_product% and %note%.", "woosms-instant-order-updates" ) . '"></span>
                </label>
            </th>
            <td class="forminp forminp-number"><textarea id="woo_sms_settings[' . $campo . ']" name="woo_sms_settings[' . $campo . ']" cols="50" rows="5" tabindex="' . $tab++ . '">' . esc_textarea( $texto ) . '</textarea>
            </td>
        </tr>
        <tr valign="top" class="mensaje_dlt dlt_' . $campo . '">
            <th scope="row" class="titledesc">
                <label for="woo_sms_settings[dlt_' . $campo . ']">
                    ' . __( $listado_de_mensajes_dlt[ $campo ], 'woosms-instant-order-updates' ) .':
                    <span class="woocommerce-help-tip" data-tip="'. __( "Template ID for " . $listado_de_mensajes[ $campo ] ) . '"></span>
                </label>
            </th>
            <td class="forminp forminp-number"><input type="text" id="woo_sms_settings[dlt_' . $campo . ']" name="woo_sms_settings[dlt_' . $campo . ']" size="50" value="' . esc_attr( $texto_dlt ) . '" tabindex="' . $tab++ . '"/>
            </td>
        </tr>';
}
