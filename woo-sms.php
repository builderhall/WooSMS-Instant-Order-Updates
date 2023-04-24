<?php
/*
Plugin Name: WooSMS - Instant Order Updates
Version: 2.25.0.1
Plugin URI: https://builderhall.com/plugin
Description: WooSMS is the ultimate WooCommerce SMS notification plugin that enables store owners to keep their customers updated with real-time order updates through instant SMS notifications. With WooSMS, you can easily customize and configure automated SMS notifications to be sent to customers when their order status changes or when new orders are placed. Additionally, you can receive SMS notifications when new orders are placed or when customers leave notes on their orders. Stay connected with your customers, increase customer satisfaction, and improve your store's order management with WooSMS!
Author URI: https://builderhall.com
Author: Builder Hall Ltd
Requires at least: 3.8
Tested up to: 6.2
WC requires at least: 2.1
WC tested up to: 7.4

Text Domain: woosms-instant-order-updates
Domain Path: /languages

@package WooSMS - Instant order updates
@category Core
@author Builder Hall Ltd
*/

//You still shouldn't be able to open me up
defined( 'ABSPATH' ) || exit;

//We define constants
define( 'DIRECCION_woo_sms', plugin_basename( __FILE__ ) );

//APG General Features
include_once( 'includes/admin/funciones-woosms.php' );

//Is WooCommerce active?
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_network_only_plugin( 'woocommerce/woocommerce.php' ) ) {
	//We load necessary functions
	include_once( 'includes/admin/funciones.php' );

	//We check if WPML is installed and active
	$wpml_activo = function_exists( 'icl_object_id' );
    
    //Messages
    $mensajes   = [
        'propietario'   => 'mensaje_pedido',
        'pending'       => 'mensaje_pendiente',
        'failed'        => 'mensaje_fallido',
        'on-hold'       => 'mensaje_recibido',
        'processing'    => 'mensaje_procesando',
        'completed'     => 'mensaje_completado',
        'refunded'      => 'mensaje_devuelto',
        'cancelled'     => 'mensaje_cancelado',
        'nota'          => 'mensaje_nota',
    ];

	//Update the translations of SMS messages
	function woosms_registra_wpml( $woo_sms_settings ) {
		global $wpml_activo, $mensajes;
        
		//We log messages in WPML
        foreach ( $mensajes as $mensaje ) {
            if ( $wpml_activo && function_exists( 'icl_register_string' ) ) { //Version before 3.2
                icl_register_string( 'woo_sms', $mensaje, esc_textarea( $woo_sms_settings[ $mensaje ] ) );
            } else if ( $wpml_activo ) { //Version 3.2 or higher
                do_action( 'wpml_register_single_string', 'woo_sms', $mensaje, esc_textarea( $woo_sms_settings[ $mensaje ] ) );
            }
        }
	}
	
	//We initialize the translations and providers
	function woo_sms_inicializacion() {
		global $woo_sms_settings, $wpml_activo;

        if ( $wpml_activo ) {
		  woosms_registra_wpml( $woo_sms_settings );
        }
	}
	add_action( 'init', 'woo_sms_inicializacion' );

	//Paint the configuration form
	function woo_sms_tab() {
		include( 'includes/admin/funciones-formulario.php' );
		include( 'includes/formulario.php' );
	}

	//Add in the menu to WooCommerce
	function woo_sms_admin_menu() {
		add_submenu_page( 'woocommerce', __( 'WooSMS - Notifications', 'woosms-instant-order-updates' ),  __( 'WooSMS - Notifications', 'woosms-instant-order-updates' ) , 'manage_woocommerce', 'woo_sms', 'woo_sms_tab' );
	}
	add_action( 'admin_menu', 'woo_sms_admin_menu', 15 );

	//Load WooCommerce scripts and CSS
	function woo_sms_screen_id( $woocommerce_screen_ids ) {
		$woocommerce_screen_ids[] = 'woocommerce_page_woo_sms';

		return $woocommerce_screen_ids;
	}
	add_filter( 'woocommerce_screen_ids', 'woo_sms_screen_id' );

	//Record the options
	function woo_sms_registra_opciones() {
		global $woo_sms_settings;
	
		register_setting( 'woo_sms_settings_group', 'woo_sms_settings', 'woo_sms_update' );
		$woo_sms_settings = get_option( 'woo_sms_settings' );
	}
	add_action( 'admin_init', 'woo_sms_registra_opciones' );
	
	function woo_sms_update( $woo_sms_settings ) {
        woosms_registra_wpml( $woo_sms_settings );
		
		return $woo_sms_settings;
	}

	//Process the SMS
	function woo_sms_procesa_estados( $numero_de_pedido, $temporizador = false ) {
		global $woo_sms_settings, $wpml_activo, $mensajes;
		
		$pedido   = new WC_Order( $numero_de_pedido );
		$estado   = is_callable( [ $pedido, 'get_status' ] ) ? $pedido->get_status() : $pedido->status;

		//We check if the message has to be sent or not
		if ( isset( $woo_sms_settings[ 'mensajes' ] ) ) {
			if ( $estado == 'on-hold' && ! array_intersect( [ "todos", "mensaje_pedido", "mensaje_recibido" ], $woo_sms_settings[ 'mensajes' ] ) ) {
				return;
			} else if ( $estado == 'pending' && ! array_intersect( [ "todos", "mensaje_pendiente" ], $woo_sms_settings[ 'mensajes' ] ) ) {
				return;
			} else if ( $estado == 'failed' && ! array_intersect( [ "todos", "mensaje_fallido" ], $woo_sms_settings[ 'mensajes' ] ) ) {
				return;
			} else if ( $estado == 'processing' && ! array_intersect( [ "todos", "mensaje_pedido", "mensaje_procesando" ], $woo_sms_settings[ 'mensajes' ] ) ) {
				return;
			} else if ( $estado == 'completed' && ! array_intersect( [ "todos", "mensaje_completado" ], $woo_sms_settings[ 'mensajes' ] ) ) {
				return;
			} else if ( $estado == 'refunded' && ! array_intersect( [ "todos", "mensaje_devuelto" ], $woo_sms_settings[ 'mensajes' ] ) ) {
				return;
			} else if ( $estado == 'cancelled' && ! array_intersect( [ "todos", "mensaje_cancelado" ], $woo_sms_settings[ 'mensajes' ] ) ) {
				return;
			}
		} else {
			return;
		}

        //Allow other plugins to prevent the SMS from being sent
		if ( ! apply_filters( 'woo_sms_send_message', true, $pedido ) ) {
			return;
		}

		//Collect data from the billing form
		$billing_country		= is_callable( [ $pedido, 'get_billing_country' ] ) ? $pedido->get_billing_country() : $pedido->billing_country;
		$billing_phone			= is_callable( [ $pedido, 'get_billing_phone' ] ) ? $pedido->get_billing_phone() : $pedido->billing_phone;
		$shipping_country		= is_callable( [ $pedido, 'get_shipping_country' ] ) ? $pedido->get_shipping_country() : $pedido->shipping_country;
		$campo_envio			= esc_attr( get_post_meta( $numero_de_pedido, $woo_sms_settings[ 'campo_envio' ], true ) );
		$telefono				= woo_sms_procesa_el_telefono( $pedido, $billing_phone, esc_attr( $woo_sms_settings[ 'servicio' ] ) );
		$telefono_envio			= woo_sms_procesa_el_telefono( $pedido, $campo_envio, esc_attr( $woo_sms_settings[ 'servicio' ] ), false, true );
		$enviar_envio			= ( ! empty( $telefono_envio ) && $telefono != $telefono_envio && isset( $woo_sms_settings[ 'envio' ] ) && $woo_sms_settings[ 'envio' ] == 1 ) ? true : false;
		$internacional			= ( isset( $billing_country ) && ( WC()->countries->get_base_country() != $billing_country ) ) ? true : false;
		$internacional_envio	= ( isset( $shipping_country ) && ( WC()->countries->get_base_country() != $shipping_country ) ) ? true : false;
        
		//owner phone
		if ( strpos( $woo_sms_settings[ 'telefono' ], "|" ) ) { //there is more than one
			$administradores = explode( "|", esc_attr( $woo_sms_settings[ 'telefono' ] ) );
			foreach ( $administradores as $administrador ) {
				$telefono_propietario[]	= woo_sms_procesa_el_telefono( $pedido, $administrador, esc_attr( $woo_sms_settings[ 'servicio' ] ), true );
			}
		} else {
			$telefono_propietario = woo_sms_procesa_el_telefono( $pedido, esc_attr( $woo_sms_settings[ 'telefono' ] ), esc_attr( $woo_sms_settings[ 'servicio' ] ), true );	
		}

        //Generates the variables with the custom texts
        foreach ( $mensajes as $mensaje ) {
            if ( function_exists( 'icl_register_string' ) || ! $wpml_activo ) { //WPML version prior to 3.2 or not installed
                $$mensaje   = ( $wpml_activo ) ? icl_translate( 'woo_sms', $mensaje, esc_textarea( $woo_sms_settings[ $mensaje ] ) ) : esc_textarea( $woo_sms_settings[ $mensaje ] );
            } else if ( $wpml_activo ) { //WPML version 3.2 or higher
                $$mensaje   = apply_filters( 'wpml_translate_single_string', esc_textarea( $woo_sms_settings[ $mensaje ] ), 'woo_sms', $mensaje );
            }
        }
        unset( $mensaje ); //Avoid empty message with the timer
		
		//We load SMS providers
		include_once( 'includes/admin/proveedores.php' );
        
		//Send the SMS
        $variables  = esc_textarea( $woo_sms_settings[ 'variables' ] );
        
        //SMS message
        if ( $estado == 'on-hold' ) { //back order
            //Message to the owner(s)
            if ( !! array_intersect( [ "todos", "mensaje_pedido" ], $woo_sms_settings[ 'mensajes' ] ) && isset( $woo_sms_settings[ 'notificacion' ] ) && $woo_sms_settings[ 'notificacion' ] == 1 && ! $temporizador ) { //Avoid sending on timer
                if ( ! is_array( $telefono_propietario ) ) {
                    woo_sms_envia_sms( $woo_sms_settings, $telefono_propietario, woo_sms_procesa_variables( $mensaje_pedido, $pedido, $variables ), $estado, true ); //Message to the owner
                } else {
                    foreach ( $telefono_propietario as $administrador ) {
                        woo_sms_envia_sms( $woo_sms_settings, $administrador, woo_sms_procesa_variables( $mensaje_pedido, $pedido, $variables ), $estado, true ); //Message to owners
                    }
                }
            }
            //Message to the client
            if ( !! array_intersect( [ "todos", $mensajes[ $estado ] ], $woo_sms_settings[ 'mensajes' ] ) ) {
                //Clear the timer for orders received
                wp_clear_scheduled_hook( 'woo_sms_ejecuta_el_temporizador' );

                //Delay for orders received
                if ( isset( $woo_sms_settings[ 'retardo' ] ) && $woo_sms_settings[ 'retardo' ] > 0 && ( ! intval( get_post_meta( $numero_de_pedido, 'woo_sms_retardo_enviado', true ) ) == 1 ) ) {
                    wp_schedule_single_event( time() + ( absint( $woo_sms_settings[ 'retardo' ] ) * 60 ), 'woo_sms_ejecuta_el_retraso', [ $numero_de_pedido ] );
                    update_post_meta( $numero_de_pedido, 'woo_sms_retardo_enviado', -1 );
                } else { //Normal delivery
                    $mensaje = woo_sms_procesa_variables( ${ $mensajes[ $estado ] }, $pedido, $variables ); //Message to the client
                }

                //Timer for orders received
                if ( isset( $woo_sms_settings[ 'temporizador' ] ) && $woo_sms_settings[ 'temporizador' ] > 0 ) {
                    wp_schedule_single_event( time() + ( absint( $woo_sms_settings[ 'temporizador' ] ) * 60 * 60 ), 'woo_sms_ejecuta_el_temporizador' );
                }
            }            
        } else if ( $estado == 'processing' ) { //order processing
            //Message to the owner(s)
            if ( !! array_intersect( [ "todos", "mensaje_pedido" ], $woo_sms_settings[ 'mensajes' ] ) && isset( $woo_sms_settings[ 'notificacion' ] ) && $woo_sms_settings[ 'notificacion' ] == 1 ) {
                if ( ! is_array( $telefono_propietario ) ) {
                    woo_sms_envia_sms( $woo_sms_settings, $telefono_propietario, woo_sms_procesa_variables( $mensaje_pedido, $pedido, $variables ), $estado, true ); //Message to the owner
                } else {
                    foreach ( $telefono_propietario as $administrador ) {
                        woo_sms_envia_sms( $woo_sms_settings, $administrador, woo_sms_procesa_variables( $mensaje_pedido, $pedido, $variables ), $estado, true ); //Message to owners
                    }
                }
            }
            //Message to the client
            if ( !! array_intersect( [ "todos", $mensajes[ $estado ] ], $woo_sms_settings[ 'mensajes' ] ) ) {
                $mensaje = woo_sms_procesa_variables( ${ $mensajes[ $estado ] }, $pedido, $variables );
            }            
        } else if ( $estado != 'on-hold' && $estado != 'processing' ) { //the rest of the states
            if ( !! array_intersect( [ "todos", $mensajes[ $estado ] ], $woo_sms_settings[ 'mensajes' ] ) ) {
                $mensaje = woo_sms_procesa_variables( ${ $mensajes[ $estado ] }, $pedido, $variables );
            } else {
                $mensaje = woo_sms_procesa_variables( $woo_sms_settings[ $estado ], $pedido, $variables );            
            }
        }

        //The SMS message is sent if it has not been sent yet
		if ( isset( $mensaje ) && ( ! $internacional || ( isset( $woo_sms_settings[ 'internacional' ] ) && $woo_sms_settings[ 'internacional' ] == 1 ) ) ) {
			if ( ! is_array( $telefono ) ) {
				woo_sms_envia_sms( $woo_sms_settings, $telefono, $mensaje, $estado ); //Message for billing phone
			} else {
				foreach ( $telefono as $cliente ) {
					woo_sms_envia_sms( $woo_sms_settings, $cliente, $mensaje, $estado ); //Message for received phones
				}
			}
			if ( $enviar_envio ) {
				woo_sms_envia_sms( $woo_sms_settings, $telefono_envio, $mensaje, $estado ); //Message for sending phone
			}
		}
	}
	add_action( 'woocommerce_order_status_changed', 'woo_sms_procesa_estados', 10 ); //Works when the order changes status
	
    //Delay
 	function woo_sms_retardo( $numero_de_pedido ) {
 		global $woo_sms_settings;
        
 		if ( $pedido = wc_get_order( intval( $numero_de_pedido ) ) ) {
 			$retraso_enviado    = get_post_meta( $numero_de_pedido, 'woo_sms_retardo_enviado', true );
 			$estado             = is_callable( [ $pedido, 'get_status' ] ) ? $pedido->get_status() : $pedido->status;
 			if ( intval( $retraso_enviado ) == -1 ) { //We only ship if you have not changed status
 				update_post_meta( $numero_de_pedido, 'woo_sms_retardo_enviado', 1 );		 			
                if ( $estado == 'on-hold' ) {
                    woo_sms_procesa_estados( $numero_de_pedido );		 				
                    $retraso_enviado    = get_post_meta( $numero_de_pedido, 'woo_sms_retardo_enviado', true );
                    if ( intval( $retraso_enviado ) == -1 ) {
                        update_post_meta( $numero_de_pedido, 'woo_sms_retardo_enviado', 1 );
                        woo_sms_procesa_estados( $numero_de_pedido );
                    }
                }
            }
        }
    }
 	add_action( 'woo_sms_ejecuta_el_retraso', 'woo_sms_retardo' );
    
	//timer
	function woo_sms_temporizador() {
		global $woo_sms_settings;
		
		$pedidos = wc_get_orders( [
			'limit'			=> -1,
			'date_created'	=> '<' . ( time() - ( absint( $woo_sms_settings[ 'temporizador' ] ) * 60 * 60 ) - 1 ),
			'status'		=> 'on-hold',
		] );

		if ( $pedidos ) {
			foreach ( $pedidos as $pedido ) {
				woo_sms_procesa_estados( is_callable( [ $pedido, 'get_id' ] ) ? $pedido->get_id() : $pedido->id, true );
			}
		}
	}
	add_action( 'woo_sms_ejecuta_el_temporizador', 'woo_sms_temporizador' );

	//Send customer notes by SMS
	function woo_sms_procesa_notas( $datos ) {
		global $woo_sms_settings, $wpml_activo;
		
		//We check if the message has to be sent
		if ( isset( $woo_sms_settings[ 'mensajes' ] ) && ! array_intersect( [ "todos", "mensaje_nota" ], $woo_sms_settings[ 'mensajes' ] ) ) {
			return;
		}
	
		//Order
		$numero_de_pedido		= $datos[ 'order_id' ];
		$pedido					= new WC_Order( $numero_de_pedido );
		//Collect data from the billing form
		$billing_country		= is_callable( [ $pedido, 'get_billing_country' ] ) ? $pedido->get_billing_country() : $pedido->billing_country;
		$billing_phone			= is_callable( [ $pedido, 'get_billing_phone' ] ) ? $pedido->get_billing_phone() : $pedido->billing_phone;
		$shipping_country		= is_callable( [ $pedido, 'get_shipping_country' ] ) ? $pedido->get_shipping_country() : $pedido->shipping_country;	
		$campo_envio			= get_post_meta( $numero_de_pedido, esc_attr( $woo_sms_settings[ 'campo_envio' ] ), true );
		$telefono				= woo_sms_procesa_el_telefono( $pedido, $billing_phone, esc_attr( $woo_sms_settings[ 'servicio' ] ) );
		$telefono_envio			= woo_sms_procesa_el_telefono( $pedido, $campo_envio, esc_attr( $woo_sms_settings[ 'servicio' ] ), false, true );
		$enviar_envio			= ( $telefono != $telefono_envio && isset( $woo_sms_settings[ 'envio' ] ) && $woo_sms_settings[ 'envio' ] == 1 ) ? true : false;
		$internacional			= ( $billing_country && ( WC()->countries->get_base_country() != $billing_country ) ) ? true : false;
		$internacional_envio	= ( $shipping_country && ( WC()->countries->get_base_country() != $shipping_country ) ) ? true : false;

        //Generate the variable with the custom text
		if ( function_exists( 'icl_register_string' ) || ! $wpml_activo ) { //WPML version prior to 3.2 or not installed
			$mensaje_nota		= ( $wpml_activo ) ? icl_translate( 'woo_sms', 'mensaje_nota', esc_textarea( $woo_sms_settings[ 'mensaje_nota' ] ) ) : esc_textarea( $woo_sms_settings[ 'mensaje_nota' ] );
		} else if ( $wpml_activo ) { //WPML version 3.2 or higher
			$mensaje_nota		= apply_filters( 'wpml_translate_single_string', esc_textarea( $woo_sms_settings[ 'mensaje_nota' ] ), 'woo_sms', 'mensaje_nota' );
		}
		
		//We load SMS providers
		include_once( 'includes/admin/proveedores.php' );
        
		//Send the SMS
		if ( ! $internacional || ( isset( $woo_sms_settings[ 'internacional' ] ) && $woo_sms_settings[ 'internacional' ] == 1 ) ) {
            $variables  = esc_textarea( $woo_sms_settings[ 'variables' ] );
			if ( ! is_array( $telefono ) ) {
				woo_sms_envia_sms( $woo_sms_settings, $telefono, woo_sms_procesa_variables( $mensaje_nota, $pedido, $variables, wptexturize( $datos[ 'customer_note' ] ) ), 'mensaje_nota' ); //Message for billing phone
			} else {
				foreach ( $telefono as $cliente ) {
					woo_sms_envia_sms( $woo_sms_settings, $cliente, woo_sms_procesa_variables( $mensaje_nota, $pedido, $variables, wptexturize( $datos[ 'customer_note' ] ) ), 'mensaje_nota' ); //Message for received phones
				}
			}
			if ( $enviar_envio ) {
				woo_sms_envia_sms( $woo_sms_settings, $telefono_envio, woo_sms_procesa_variables( $mensaje_nota, $pedido, $variables, wptexturize( $datos[ 'customer_note' ] ) ), 'mensaje_nota' ); //Message for sending phone
			}
		}
	}
	add_action( 'woocommerce_new_customer_note', 'woo_sms_procesa_notas', 10 );
} else {
	add_action( 'admin_notices', 'woo_sms_requiere_wc' );
}

//Displays the WooCommerce activation message and deactivates the plugin
function woo_sms_requiere_wc() {
	global $woo_sms;
		
	echo '<div class="notice notice-error is-dismissible" id="woosms-instant-order-updates"><h3>' . $woo_sms[ 'plugin' ] . '</h3><h4>' . __( "This plugin require WooCommerce active to run!", 'woosms-instant-order-updates' ) . '</h4></div>';
	deactivate_plugins( DIRECCION_woo_sms );
}

//Remove all traces of the plugin when uninstalling it
function woo_sms_desinstalar() {
	delete_option( 'woo_sms_settings' );
	delete_transient( 'woo_sms_plugin' );
}
register_uninstall_hook( __FILE__, 'woo_sms_desinstalar' );
