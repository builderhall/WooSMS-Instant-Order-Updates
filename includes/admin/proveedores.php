<?php
//You still shouldn't be able to open me up
defined( 'ABSPATH' ) || exit;

//Send the SMS message
function woo_sms_envia_sms( $woo_sms_settings, $telefono, $mensaje, $estado, $propietario = false ) {
    //Manage states
	switch( $estado ) {
		case "on-hold":
            $estado    = ( $propietario ) ? "mensaje_pedido" : "mensaje_recibido";
            
            break;
		case "pending":
            $estado    = "mensaje_pendiente";
            
            break;
		case "failed":
            $estado    = "mensaje_fallido";
            
            break;
		case "processing":
            $estado    = ( $propietario ) ? "mensaje_pedido" : "mensaje_procesando";
            
            break;
		case "completed":
            $estado    = "mensaje_completado";
            
            break;
		case "refunded":
            $estado    = "mensaje_devuelto";
            
            break;
		case "cancelled":
            $estado    = "mensaje_cancelado";
            
            break;
    }

    //Manage suppliers
	switch ( $woo_sms_settings[ 'servicio' ] ) {
		case "adlinks":
 			$url						= add_query_arg( [
 				'authkey'					=> $woo_sms_settings[ 'usuario_adlinks' ],
 				'mobiles'					=> $telefono,
 				'message'					=> woo_sms_codifica_el_mensaje( $mensaje ),
 				'sender'					=> $woo_sms_settings[ 'identificador_adlinks' ],
 				'route'						=> $woo_sms_settings[ 'ruta_adlinks' ],
 				'country'					=> 0,
 			], 'http://adlinks.websmsc.com/api/sendhttp.php' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "altiria":
            $url                        = add_query_arg( [
 				'cmd'                      => 'sendsms',
 				'login'                    => $woo_sms_settings[ 'usuario_altiria' ],
 				'passwd'                   => $woo_sms_settings[ 'contrasena_altiria' ],
 				'dest'                     => $telefono,
 				'msg'                      => woo_sms_codifica_el_mensaje( $mensaje ),
 			], 'http://www.altiria.net/api/http' );
            
 			$respuesta					= wp_remote_post( $url );
            
			break;
		case "bulkgate":
 			$url						= add_query_arg( [
 				'application_id'			=> $woo_sms_settings[ 'usuario_bulkgate' ],
 				'application_token'			=> $woo_sms_settings[ 'clave_bulkgate' ],
 				'number'					=> $telefono,
 				'text'						=> woo_sms_codifica_el_mensaje( $mensaje ),
 				'unicode'					=> intval( $woo_sms_settings[ 'unicode_bulkgate' ] ),
 				'sender_id'					=> 'gText',
 				'sender_id_value'			=> $woo_sms_settings[ 'identificador_bulkgate' ],
 			], 'https://portal.bulkgate.com/api/1.0/simple/transactional' );
            
 			$respuesta					= wp_remote_get( $url );
            
 			break;
		case "bulksms":
			$argumentos[ 'body' ]		= [ 
				'username' 					=> $woo_sms_settings[ 'usuario_bulksms' ],
				'password' 					=> $woo_sms_settings[ 'contrasena_bulksms' ],
				'message' 					=> $mensaje,
				'msisdn' 					=> $telefono,
				'allow_concat_text_sms'		=> 1,
                'concat_text_sms_max_parts'	=> 6,
            ];
            
			$respuesta					= wp_remote_post( "https://" . $woo_sms_settings[ 'servidor_bulksms' ] . "/eapi/submission/send_sms/2/2.0", $argumentos );
            
			break;
		case "clickatell":
 			$url						= add_query_arg( [
 				'apiKey'					=> $woo_sms_settings[ 'identificador_clickatell' ],
 				'to'						=> $telefono,
 				'content'					=> woo_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://platform.clickatell.com/messages/http/send' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "clockwork":
 			$url						= add_query_arg( [
 				'key'						=> $woo_sms_settings[ 'identificador_clockwork' ],
 				'to'						=> $telefono,
 				'content'					=> woo_sms_normaliza_mensaje( $mensaje ),
 			], 'https://api.clockworksms.com/http/send.aspx' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "esebun":
 			$url						= add_query_arg( [
 				'user'						=> $woo_sms_settings[ 'usuario_esebun' ],
 				'password'					=> $woo_sms_settings[ 'contrasena_esebun' ],
 				'sender'					=> woo_sms_codifica_el_mensaje( $woo_sms_settings[ 'identificador_esebun' ] ),
 				'SMSText'					=> woo_sms_codifica_el_mensaje( $mensaje ),
 				'GSM'						=> preg_replace( '/\+/', '', $telefono ),
 			], 'http://api.cloud.bz.esebun.com/api/v3/sendsms/plain' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "isms":
 			$url						= add_query_arg( [
 				'un'						=> $woo_sms_settings[ 'usuario_isms' ],
 				'pwd'						=> $woo_sms_settings[ 'contrasena_isms' ],
 				'dstno'						=> $telefono,
 				'msg'						=> woo_sms_codifica_el_mensaje( $mensaje ),
 				'type'						=> 2,
 				'sendid'					=> $woo_sms_settings[ 'telefono_isms' ],
 			], 'https://www.isms.com.my/isms_send.php' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "labsmobile":
 			$url						= add_query_arg( [
 				'username'					=> $woo_sms_settings[ 'usuario_labsmobile' ],
 				'password'					=> $woo_sms_settings[ 'contrasena_labsmobile' ],
 				'msisdn'					=> $telefono,
 				'message'					=> woo_sms_codifica_el_mensaje( woo_sms_normaliza_mensaje( $mensaje ) ),
 				'sender'					=> $woo_sms_settings[ 'sid_labsmobile' ],
 			], 'https://api.labsmobile.com/get/send.php' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;			
        case "mobtexting":
 			$url						= add_query_arg( [
 				'access_token'				=> $woo_sms_settings[ 'clave_mobtexting' ],
 				'to'						=> $telefono,
 				'service'					=> 'T',
 				'sender'					=> $woo_sms_settings[ 'identificador_mobtexting' ],
 				'message'					=> woo_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://portal.mobtexting.com/api/v2/sms/send' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "moplet":
            $argumentos                 = [
 				'authkey'					=> $woo_sms_settings[ 'clave_moplet' ],
 				'mobiles'					=> $telefono,
 				'message'					=> woo_sms_codifica_el_mensaje( $mensaje ),
 				'sender'					=> $woo_sms_settings[ 'identificador_moplet' ],
 				'route'						=> $woo_sms_settings[ 'ruta_moplet' ],
 				'country'					=> $woo_sms_settings[ 'servidor_moplet' ],
            ];
            //DLT
            if ( $woo_sms_settings[ 'dlt_moplet' ] ) { //Only if the value exists
 				$argumentos[ 'DLT_TE_ID' ] = $woo_sms_settings[ 'dlt_' . $estado ];
            }
            $url						= add_query_arg( $argumentos, 'http://sms.moplet.com/api/sendhttp.php' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "moreify":
 			$url						= add_query_arg( [
 				'project'					=> $woo_sms_settings[ 'proyecto_moreify' ],
 				'password'					=> $woo_sms_settings[ 'identificador_moreify' ],
 				'phonenumber'				=> $telefono,
 				'message'					=> woo_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://members.moreify.com/api/v1/sendSms' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "msg91":
            $argumentos[ 'body' ]		= [ 
                'authkey' 					=> $woo_sms_settings[ 'clave_msg91' ],
                'mobiles' 					=> $telefono,
                'message' 					=> woo_sms_codifica_el_mensaje( woo_sms_normaliza_mensaje( $mensaje ) ),
                'sender' 					=> $woo_sms_settings[ 'identificador_msg91' ],
                'route' 					=> $woo_sms_settings[ 'ruta_msg91' ],
            ];
            //DLT
            if ( $woo_sms_settings[ 'dlt_msg91' ] ) { //Only if the value exists
 				$argumentos[ 'body' ][ 'DLT_TE_ID' ] = $woo_sms_settings[ 'dlt_' . $estado ];
            }
            
			$respuesta					= wp_remote_post( "https://api.msg91.com/api/sendhttp.php", $argumentos );
            
			break;
		case "msgwow":
 			$url						= add_query_arg( [
 				'authkey'					=> $woo_sms_settings[ 'clave_msgwow' ],
 				'mobiles'					=> $telefono,
 				'message'					=> woo_sms_codifica_el_mensaje( $mensaje ),
 				'sender'					=> $woo_sms_settings[ 'identificador_msgwow' ],
 				'route'						=> $woo_sms_settings[ 'ruta_msgwow' ],
 				'country'					=> $woo_sms_settings[ 'servidor_msgwow' ],
 			], 'http://my.msgwow.com/api/sendhttp.php' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "mvaayoo":
			$argumentos[ 'body' ]		= [ 
				'user' 						=> $woo_sms_settings[ 'usuario_mvaayoo' ] . ":" . $woo_sms_settings[ 'contrasena_mvaayoo' ],
				'senderID' 					=> $woo_sms_settings[ 'identificador_mvaayoo' ],
				'receipientno' 				=> $telefono,
				'msgtxt' 					=> $mensaje,
				'dcs' 						=> 0,
				'state' 					=> 4,
            ];
            
			$respuesta					= wp_remote_post( "http://api.mVaayoo.com/mvaayooapi/MessageCompose", $argumentos );
            
			break;
		case "nexmo":
 			$url						= add_query_arg( [
 				'api_key'					=> $woo_sms_settings[ 'clave_nexmo' ],
 				'api_secret'				=> $woo_sms_settings[ 'identificador_nexmo' ],
 				'from'						=> 'NEXMO',
 				'to'						=> $telefono,
 				'text'						=> woo_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://rest.nexmo.com/sms/json' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "plivo":
			$argumentos[ 'headers' ]	= [
				'Authorization'				=> 'Basic ' . base64_encode( $woo_sms_settings[ 'usuario_plivo' ] . ":" . $woo_sms_settings[ 'clave_plivo' ] ),
				'Connection'				=> 'close',
				'Content-Type'				=> 'application/json',
			];
			$argumentos[ 'body' ]		= json_encode( [
				'src'						=> ( trim( $woo_sms_settings[ 'identificador_plivo' ] ) != '' ? $woo_sms_settings[ 'identificador_plivo' ] : $woo_sms_settings[ 'telefono' ] ),
				'dst'						=> $telefono,
				'text'						=> $mensaje,
				'type'						=> 'sms',
			] );
            
			$respuesta					= wp_remote_post( "https://api.plivo.com/v1/Account/" . $woo_sms_settings[ 'usuario_plivo' ] . "/Message/", $argumentos );
            
			break;
		case "routee":
			$argumentos[ 'headers' ] 	= [
				'Authorization'				=> 'Basic ' . base64_encode( $woo_sms_settings[ 'usuario_routee' ] . ":" . $woo_sms_settings[ 'contrasena_routee' ] ),
				'Content-Type'				=> 'application/x-www-form-urlencoded',
			];
			$argumentos[ 'body' ] 		= [
				'grant_type'				=> 'client_credentials',
			];
            
			$respuesta					= wp_remote_post( "https://auth.routee.net/oauth/token", $argumentos );
			$routee						= json_decode( $respuesta[ 'body' ] );
			
            if ( isset( $routee->access_token ) ) {
                $argumentos[ 'headers' ]	= [
                    'Authorization'				=> 'Bearer ' . $routee->access_token,
                    'Content-Type'				=> 'application/json',
                ];
                $argumentos[ 'body' ]		= json_encode( [
                    'body'						=> $mensaje,
                    'to'						=> $telefono,
                    'from'						=> $woo_sms_settings[ 'identificador_routee' ],
                ] );

                $respuesta 					= wp_remote_post( "https://connect.routee.net/sms", $argumentos );
            }
            
			break;
		case "sendsms":
            $url						= add_query_arg( [
                'action'                    => ( $woo_sms_settings[ 'gdpr_sendsms' ] == 1 ) ? 'message_send_gdpr' : 'message_send',
                'username'					=> $woo_sms_settings[ 'usuario_sendsms' ],
                'password'					=> urlencode( [ 'contrasena_sendsms' ] ),
                'to'                        => $telefono,
                'text'                      => woo_sms_codifica_el_mensaje( $mensaje ),
                'short'                     => ( $woo_sms_settings[ 'short_sendsms' ] == 1 ) ? 'true' : 'false',
            ], 'https://api.sendsms.ro/json' );
            
 			$respuesta					= wp_remote_get( $url );
            
            break;
		case "sipdiscount":
 			$url						= add_query_arg( [
 				'username'					=> $woo_sms_settings[ 'usuario_sipdiscount' ],
 				'password'					=> $woo_sms_settings[ 'contrasena_sipdiscount' ],
 				'from'						=> $woo_sms_settings[ 'telefono' ],
				'to'						=> $telefono,
 				'text'						=> woo_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://www.sipdiscount.com/myaccount/sendsms.php' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "smscx":
			$argumentos[ 'headers' ] 	= [
				'Authorization'				=> 'Basic ' . base64_encode( $woo_sms_settings[ 'usuario_smscx' ] . ":" . $woo_sms_settings[ 'contrasena_smscx' ] ),
				'Content-Type'				=> 'application/x-www-form-urlencoded',
			];
			$argumentos[ 'body' ] 		= [
				'grant_type'				=> 'client_credentials',
			];
            
			$respuesta					= wp_remote_post( "https://api.sms.cx/oauth/token", $argumentos );
			$smscx						= json_decode( $respuesta[ 'body' ] );
            
            if ( isset( $smscx->access_token ) ) {
                $pais                       = explode ( ":", get_option( 'woocommerce_default_country' ) );

                $argumentos[ 'headers' ]    = [
                    'Authorization'				=> 'Bearer ' . $smscx->access_token,
                    'Content-Type'				=> 'application/json',
                ];
                $argumentos[ 'body' ]		= json_encode( [
                    'text'						=> $mensaje,
                    'to'						=> $telefono,
                    'from'						=> $woo_sms_settings[ 'identificador_smscx' ],
                    'countryIso'                => $pais[ 0 ],
                ] );

                $respuesta 					= wp_remote_post( "https://api.sms.cx/sms", $argumentos );
            }
            
			break;
        case "smscountry":
			$argumentos[ 'body' ]		= [ 
				'User' 						=> $woo_sms_settings[ 'usuario_smscountry' ],
				'passwd' 					=> $woo_sms_settings[ 'contrasena_smscountry' ],
				'mobilenumber' 				=> $telefono,
				'sid' 						=> $woo_sms_settings[ 'sid_smscountry' ],
				'message' 					=> $mensaje,
				'mtype' 					=> "N",
				'DR' 						=> "Y",
			];
            
			$respuesta					= wp_remote_post( "https://api.smscountry.com/SMSCwebservice_bulk.aspx", $argumentos );
            
			break;
		case "smsdiscount":
 			$url						= add_query_arg( [
 				'username'					=> $woo_sms_settings[ 'usuario_smsdiscount' ],
 				'password'					=> $woo_sms_settings[ 'contrasena_smsdiscount' ],
 				'from'						=> $woo_sms_settings[ 'telefono' ],
				'to'						=> $telefono,
 				'text'						=> woo_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://www.sipdiscount.com/myaccount/sendsms.php' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "smsgateway_smshall":
			$argumentos['body'] 		= [
				'key'						=> $woo_sms_settings['clave_smsgateway_smshall'],
				'number'					=> $telefono,
				'message'					=> $mensaje,
				'devices'					=> $woo_sms_settings['identificador_smsgateway_smshall'],
				'type'						=> $woo_sms_settings['type_smshall'],
				'prioritize'				=> $woo_sms_settings['prioritize_smshall'],
			];
			$respuesta = wp_remote_post( "https://smshall.com/sms/services/send.php", $argumentos );
//			$respuesta = wp_remote_post( "{$woo_sms_settings['servidor_smsgateway_smshall']}/services/send.php", $argumentos );
			break;
		case "smslane":
			$argumentos[ 'body' ] 		= [ 
				'ApiKey' 					=> $woo_sms_settings[ 'usuario_smslane' ],
				'ClientId' 					=> $woo_sms_settings[ 'contrasena_smslane' ],
				'SenderId' 					=> $woo_sms_settings[ 'sid_smslane' ],
				'Message'					=> $mensaje,
				'MobileNumbers'				=> $telefono,
            ];
            
			$respuesta 					= wp_remote_post( "https://api.smslane.com/api/v2/SendSMS", $argumentos );
            
            break;
		case "solutions_infini":
 			$url						= add_query_arg( [
 				'workingkey'				=> $woo_sms_settings[ 'clave_solutions_infini' ],
				'to'						=> $telefono,
 				'sender'					=> $woo_sms_settings[ 'identificador_solutions_infini' ],
 				'message'					=> woo_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://alerts.sinfini.com/api/web2sms.php' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "springedge":
 			$url						= add_query_arg( [
 				'apikey'					=> $woo_sms_settings[ 'clave_springedge' ],
 				'sender'					=> $woo_sms_settings[ 'identificador_springedge' ],
				'to'						=> $telefono,
 				'message'					=> woo_sms_codifica_el_mensaje( $mensaje ),
				'format'					=> 'json',
 			], 'https://instantalerts.co/api/web/send/' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;			
		case "twilio":
			$argumentos[ 'header' ]		= "Accept-Charset: utf-8\r\n";
			$argumentos[ 'body' ]		= [ 
				'To' 						=> $telefono,
				'From' 						=> $woo_sms_settings[ 'telefono_twilio' ],
				'Body' 						=> $mensaje,
            ];
            
			$respuesta					= wp_remote_post( "https://" . $woo_sms_settings[ 'clave_twilio' ] . ":" . $woo_sms_settings[ 'identificador_twilio' ] . "@api.twilio.com/2010-04-01/Accounts/" . $woo_sms_settings[ 'clave_twilio' ] . "/Messages", $argumentos );
            
			break;
		case "twizo":
			$contenido					= json_encode( [
				'recipients'				=> [ $telefono ],
				'body'						=> $mensaje,
				'sender'					=> $woo_sms_settings[ 'identificador_twizo' ],
				'tag'						=> 'WooSMS Instant Order Updates',
			] );
			$argumentos[ 'headers' ]	= [
				'Authorization'				=> "Basic " . base64_encode( "twizo:" . $woo_sms_settings[ 'clave_twizo' ] ),
				'Accept'					=> 'application/json',
				'Content-Type'				=> 'application/json; charset=utf8',
				'Content-Length'			=> strlen( $contenido ),
				'method'					=> 'POST',
			];
			$argumentos[ 'body' ]		= $contenido;
            
			$respuesta					= wp_remote_post( "https://" . $woo_sms_settings[ 'servidor_twizo' ] . "/v1/sms/submitsimple", $argumentos );
            
			break;
		case "voipbuster":
 			$url						= add_query_arg( [
 				'username'					=> $woo_sms_settings[ 'usuario_voipbuster' ],
 				'password'					=> $woo_sms_settings[ 'contrasena_voipbuster' ],
				'from'						=> $woo_sms_settings[ 'telefono' ],
				'to'						=> $telefono,
 				'text'						=> woo_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://www.voipbuster.com/myaccount/sendsms.php' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "voipbusterpro":
 			$url						= add_query_arg( [
 				'username'					=> $woo_sms_settings[ 'usuario_voipbusterpro' ],
 				'password'					=> $woo_sms_settings[ 'contrasena_voipbusterpro' ],
				'from'						=> $woo_sms_settings[ 'telefono' ],
				'to'						=> $telefono,
 				'text'						=> woo_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://www.voipbusterpro.com/myaccount/sendsms.php' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "voipstunt":
 			$url						= add_query_arg( [
 				'username'					=> $woo_sms_settings[ 'usuario_voipstunt' ],
 				'password'					=> $woo_sms_settings[ 'contrasena_voipstunt' ],
				'from'						=> $woo_sms_settings[ 'telefono' ],
				'to'						=> $telefono,
 				'text'						=> woo_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://www.voipstunt.com/myaccount/sendsms.php' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "waapi":
 			$url						= add_query_arg( [
 				'client_id'					=> $woo_sms_settings[ 'usuario_waapi' ],
 				'instance'					=> $woo_sms_settings[ 'contrasena_waapi' ],
				'type'						=> 'text',
				'number'					=> $telefono,
 				'message'					=> woo_sms_codifica_el_mensaje( $mensaje ),
 			], $woo_sms_settings[ 'dominio_waapi' ] . "/api/send.php" );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
	}

    //Send the email with the report
	if ( isset( $woo_sms_settings[ 'debug' ] ) && $woo_sms_settings[ 'debug' ] == "1" && isset( $woo_sms_settings[ 'campo_debug' ] ) ) {
		$correo	= __( 'Mobile number:', 'woosms-instant-order-updates' ) . "\r\n" . $telefono . "\r\n\r\n";
		$correo	.= __( 'Message: ', 'woosms-instant-order-updates' ) . "\r\n" . $mensaje . "\r\n\r\n"; 
        if ( isset( $argumentos ) ) {
            $correo	.= __( 'Arguments: ', 'woosms-instant-order-updates' ) . "\r\n" . print_r( $argumentos, true );
        }
		$correo	.= __( 'Gateway answer: ', 'woosms-instant-order-updates' ) . "\r\n" . print_r( $respuesta, true );
		wp_mail( $woo_sms_settings[ 'campo_debug' ], 'WooSMS - Instant Order Updates', $correo, 'charset=UTF-8' . "\r\n" ); 
	}
}
