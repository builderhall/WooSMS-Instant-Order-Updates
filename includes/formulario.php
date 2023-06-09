<?php global $woo_sms_settings, $woo_sms; ?>

<div class="wrap woocommerce">
	<h1>
		<?php _e( 'WooSMS - Instant Order Updates', 'woosms-instant-order-updates' ); ?>
	</h1>
	<hr>
	<div class="cabecera"> <a href="<?php echo $woo_sms[ 'plugin_url' ]; ?>" title="<?php echo $woo_sms[ 'plugin' ]; ?>" target="_blank"><img src="<?php echo plugins_url( 'assets/images/smshall.jpg', DIRECCION_woo_sms ); ?>" class="imagen" alt="<?php echo $woo_sms[ 'plugin' ]; ?>" /></a> </div>

	<p>
		<?php _e( 'Add to WooCommerce the possibility to send <abbr title="Short Message Service" lang="en">SMS</abbr> update to your customer each time when the order status has change. <br>Notifies the owner, if desired, when a customer place any order. You can also send customer notes.'); ?>
	</p>
	<?php include( 'cuadro-informacion.php' ); ?>
	<form method="post" action="options.php">
		<?php settings_fields( 'woo_sms_settings_group' ); ?>
		<table class="form-table woosms-table">
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="woo_sms_settings[servicio]">
						<?php _e( '<abbr title="Short Message Service" lang="en">SMS</abbr> gateway:', 'woosms-instant-order-updates' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'Select your SMS gateway', 'woosms-instant-order-updates' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number">
					<select class="wc-enhanced-select servicio" id="woo_sms_settings[servicio]" name="woo_sms_settings[servicio]" tabindex="<?php echo $tab++; ?>">
						<?php woo_sms_listado_de_proveedores( $listado_de_proveedores ); ?>
					</select>
				</td>
			</tr>
			<?php woo_sms_campos_de_proveedores( $listado_de_proveedores, $campos_de_proveedores, $opciones_de_proveedores, $verificacion_de_proveedores ); ?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="woo_sms_settings[telefono]">
						<?php _e( 'Your mobile number:', 'woosms-instant-order-updates' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'The mobile number registered in your SMS gateway account and where you receive the new order SMS Notifications. You can add multiple mobile numbers separeted by | character. Example: +8801611076862|+8801715938284', 'woosms-instant-order-updates' ); ?>"></span> </label>
				</th>
				<td class="forminp forminp-number"><input type="text" id="woo_sms_settings[telefono]" name="woo_sms_settings[telefono]" size="50" value="<?php echo ( isset( $woo_sms_settings[ 'telefono' ] ) ) ? esc_attr( $woo_sms_settings[ 'telefono' ] ) : ''; ?>" tabindex="<?php echo $tab++; ?>"/>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="woo_sms_settings[notificacion]">
						<?php _e( 'New order notification:', 'woosms-instant-order-updates' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( " Check if you want to receive a SMS message when there 's a new order", 'woosms-instant-order-updates ' ); ?>"></span> 
					</label>
					</th>
        <td class="forminp forminp-number"><input id="woo_sms_settings[notificacion]" name="woo_sms_settings[notificacion]" type="checkbox" value="1" <?php echo ( isset( $woo_sms_settings[ 'notificacion' ] ) && $woo_sms_settings[ 'notificacion' ] == "1" ) ? 'checked="checked" ' : ' '; ?> tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="woo_sms_settings[internacional]">
            <?php _e( 'Send international <abbr title="Short Message Service" lang="en">SMS</abbr>?:', 'woosms-instant-order-updates' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'Check if you want to send international SMS messages', 'woosms-instant-order-updates' ); ?>"></span> </label>
				</th>
				<td class="forminp forminp-number"><input id="woo_sms_settings[internacional]" name="woo_sms_settings[internacional]" type="checkbox" value="1" <?php echo ( isset( $woo_sms_settings[ 'internacional' ] ) && $woo_sms_settings[ 'internacional' ] == "1" ) ? 'checked="checked"' : ''; ?> tabindex="
					<?php echo $tab++; ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="woo_sms_settings[envio]">
						<?php _e( 'Send <abbr title="Short Message Service" lang="en">SMS</abbr> to shipping mobile?:', 'woosms-instant-order-updates' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'Check if you want to send SMS messages to shipping mobile numbers, only if it is different from billing mobile number', 'woosms-instant-order-updates' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number"><input id="woo_sms_settings[envio]" name="woo_sms_settings[envio]" type="checkbox" value="1" <?php echo ( isset( $woo_sms_settings[ 'envio' ] ) && $woo_sms_settings[ 'envio' ] == "1" ) ? 'checked="checked"' : ''; ?> tabindex="
					<?php echo $tab++; ?>" class="envio" /></td>
			</tr>
			<tr valign="top" class="campo_envio">
				<th scope="row" class="titledesc">
					<label for="woo_sms_settings[campo_envio]">
						<?php _e( 'Shipping mobile field:', 'woosms-instant-order-updates' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'Select the shipping mobile field', 'woosms-instant-order-updates' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number">
					<select id="woo_sms_settings[campo_envio]" name="woo_sms_settings[campo_envio]" class="wc-enhanced-select" tabindex="<?php echo $tab++; ?>">
						<?php woo_sms_campos_de_envio(); ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="woo_sms_settings[productos]">
						<?php _e( 'Order Product variable full details:', 'woosms-instant-order-updates' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'Check if you want to send the SMS messages with full order product information', 'woosms-instant-order-updates' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number"><input id="woo_sms_settings[productos]" name="woo_sms_settings[productos]" type="checkbox" value="1" <?php echo ( isset( $woo_sms_settings[ 'productos' ] ) && $woo_sms_settings[ 'productos' ] == "1" ) ? 'checked="checked"' : ''; ?> tabindex="
					<?php echo $tab++; ?>" /></td>
			</tr>
			<?php if ( ! empty( $listado_de_estados ) ) : //Check for the existence of custom states ?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="woo_sms_settings[estados_personalizados]">
						<?php _e( 'Custom Order Statuses & Actions:', 'woosms-instant-order-updates' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'Select your own statuses.', 'woosms-instant-order-updates' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number">
					<select multiple="multiple" class="wc-enhanced-select multiselect estados_personalizados" id="woo_sms_settings[estados_personalizados]" name="woo_sms_settings[estados_personalizados][]" tabindex="<?php echo $tab++; ?>">
						<?php woo_sms_listado_de_estados( $listado_de_estados ); ?>
					</select>
				</td>
			</tr>
			<?php foreach ( $listado_de_estados as $nombre_de_estado => $estado_personalizado ) : ?>
			<tr valign="top" class="<?php echo $estado_personalizado; ?>">
				<!-- <?php echo $nombre_de_estado; ?> -->
				<th scope="row" class="titledesc">
					<label for="woo_sms_settings[<?php echo $estado_personalizado; ?>]">
						<?php echo sprintf( __( '%s state custom message:', 'woosms-instant-order-updates' ), $nombre_de_estado ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %prices_include_tax%, %tax_display_cart%, %display_totals_ex_tax%, %display_cart_ex_tax%, %order_date%, %modified_date%, %customer_message%, %customer_note%, %post_status%, %shop_name%, %order_product% and %note%.', 'woosms-instant-order-updates' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number"><textarea id="woo_sms_settings[<?php echo $estado_personalizado; ?>]" name="woo_sms_settings[<?php echo $estado_personalizado; ?>]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes( isset( $woo_sms_settings[$estado_personalizado] ) ? esc_textarea( $woo_sms_settings[$estado_personalizado] ) : "" ); ?></textarea>
				</td>
			</tr>
            <tr valign="top" class="mensaje_dlt <?php echo 'dlt_' . $estado_personalizado; ?>">
                <th scope="row" class="titledesc">
                    <label for="woo_sms_settings[<?php echo 'dlt_' . $estado_personalizado; ?>]">
						<?php echo sprintf( __( '%s state custom message template ID:', 'woosms-instant-order-updates' ), $nombre_de_estado ); ?>
                        <span class="woocommerce-help-tip" data-tip="'<?php _e( "Template ID for " . $nombre_de_estado ); ?>"></span>
                    </label>
                </th>
                <td class="forminp forminp-number"><input type="text" id="woo_sms_settings[<?php echo 'dlt_' . $estado_personalizado; ?>]" name="woo_sms_settings[<?php echo 'dlt_' . $estado_personalizado; ?>]" size="50" value="<?php echo ( isset( $woo_sms_settings[ 'dlt_' . $estado_personalizado ] ) ) ? esc_attr( $woo_sms_settings[ 'dlt_' . $estado_personalizado ] ) : ''; ?>" tabindex="<?php echo $tab++; ?>"/>
                </td>
            </tr>
            <?php endforeach; ?>
			<?php endif; ?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="woo_sms_settings[variables]">
						<?php _e( 'Custom variables:', 'woosms-instant-order-updates' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'You can add your own variables. Each variable must be entered onto a new line without percentage character ( % ). Example: <code>_custom_variable_name</code><br /><code>_another_variable_name</code>.', 'woosms-instant-order-updates' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number"><textarea id="woo_sms_settings[variables]" name="woo_sms_settings[variables]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes( isset( $woo_sms_settings[ 'variables' ] ) ? esc_textarea( $woo_sms_settings[ 'variables' ] ) : '' ); ?></textarea>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="woo_sms_settings[productos]">
						<?php _e( 'Send only this messages:', 'woosms-instant-order-updates' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'Select what messages do you want to send', 'woosms-instant-order-updates' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number">
					<select multiple="multiple" class="wc-enhanced-select multiselect mensajes" id="woo_sms_settings[mensajes]" name="woo_sms_settings[mensajes][]" tabindex="<?php echo $tab++; ?>">
						<?php woo_sms_listado_de_mensajes( $listado_de_mensajes ); ?>
					</select>
			</tr>
            <?php
            foreach( $mensajes as $mensaje ) {
                $campos = [
                    'mensaje_pedido',
                    'mensaje_pendiente',
                    'mensaje_fallido',
                    'mensaje_recibido'
                ];
                if ( in_array( $mensaje, $campos ) ) {
                    woo_sms_campo_de_mensaje_personalizado( $mensaje, $$mensaje, $listado_de_mensajes ); 
                }
            }
            ?> 
            <tr valign="top" class="mensaje_recibido">
 				<th scope="row" class="titledesc">
 					<label for="woo_sms_settings[retardo]">
 						<?php _e( 'Order on-hold delay (minutes)', 'woosms-instant-order-updates' ); ?>:
     					<span class="woocommerce-help-tip" data-tip="<?php _e( 'Send this message after X minutes, if the order is still on-hold, instead of sending it immediately.', 'woosms-instant-order-updates' ); ?>"/>
 					</label>
 				</th>
 				<td class="forminp forminp-number"><input type="text" id="woo_sms_settings[retardo]" name="woo_sms_settings[retardo]" size="50" value="<?php echo ( isset( $woo_sms_settings[ 'retardo' ] ) ) ? esc_attr( $woo_sms_settings[ 'retardo' ] ) : ''; ?>" tabindex="<?php echo $tab++; ?>"/>
 				</td>
 			</tr>
            <tr valign="top" class="mensaje_recibido">
				<th scope="row" class="titledesc">
					<label for="woo_sms_settings[temporizador]">
						<?php _e( 'Order on-hold timer (hours)', 'woosms-instant-order-updates' ); ?>:
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'You can timer this message every X hours. Leave blank to disable.', 'woosms-instant-order-updates' ); ?>"/>
                    </label>
                </th>
				<td class="forminp forminp-number"><input type="text" id="woo_sms_settings[temporizador]" name="woo_sms_settings[temporizador]" size="50" value="<?php echo ( isset( $woo_sms_settings[ 'temporizador' ] ) ) ? esc_attr( $woo_sms_settings[ 'temporizador' ] ) : ''; ?>" tabindex="<?php echo $tab++; ?>"/>
				</td>
			</tr>
            <?php 
            foreach( $mensajes as $mensaje ) {
                $campos = [
                    'mensaje_procesando',
                    'mensaje_completado',
                    'mensaje_devuelto',
                    'mensaje_cancelado',
                    'mensaje_nota'
                ];
                if ( in_array( $mensaje, $campos ) ) {
                    woo_sms_campo_de_mensaje_personalizado( $mensaje, $$mensaje, $listado_de_mensajes ); 
                }
            }
            ?> 
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="woo_sms_settings[debug]">
						<?php _e( 'Send debug information?:', 'woosms-instant-order-updates' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'Check if you want to receive debug information from your SMS gateway', 'woosms-instant-order-updates' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number"><input id="woo_sms_settings[debug]" name="woo_sms_settings[debug]" type="checkbox" class="debug" value="1" <?php echo ( isset( $woo_sms_settings[ 'debug' ] ) && $woo_sms_settings[ 'debug' ] == "1" ) ? 'checked="checked"' : ''; ?> tabindex="
					<?php echo $tab++; ?>" /></td>
			</tr>
			<tr valign="top" class="campo_debug">
				<th scope="row" class="titledesc">
					<label for="woo_sms_settings[campo_debug]">
						<?php _e( 'email address:', 'woosms-instant-order-updates' ); ?>
						<span class="woocommerce-help-tip" data-tip="<?php _e( 'Add an email address where you want to receive the debug information', 'woosms-instant-order-updates' ); ?>"></span>
					</label>
				</th>
				<td class="forminp forminp-number"><input type="text" id="woo_sms_settings[campo_debug]" name="woo_sms_settings[campo_debug]" size="50" value="<?php echo ( isset( $woo_sms_settings[ 'campo_debug' ] ) ) ? esc_attr( $woo_sms_settings[ 'campo_debug' ] ) : ''; ?>" tabindex="<?php echo $tab++; ?>"/>
				</td>
			</tr>
		</table>
		<p class="submit">
			<input class="button-primary" type="submit" value="<?php _e( 'Save Changes', 'woosms-instant-order-updates' ); ?>" name="submit" id="submit" tabindex="<?php echo $tab++; ?>"/>
		</p>
	</form>
</div>
<script>
	jQuery( document ).ready( function ( $ ) {
        //Change the fields depending on the SMS service provider
		$( '.servicio' ).on( 'change', function () {
			control( $( this ).val() );
		} );
		var control = function ( capa ) {
			if ( capa == '' ) {
				capa = $( '.servicio option:selected' ).val();
			}
			var proveedores = new Array();
			<?php 
			foreach( $listado_de_proveedores as $indice => $valor ) {
				echo "proveedores[ '$indice' ] = '$valor';" . PHP_EOL;
			}
			?>

			for ( var valor in proveedores ) {
				if ( valor == capa ) {
					$( '.' + capa ).show();
				} else {
					$( '.' + valor ).hide();
				}
			}

            if ( ! $( '.dlt' ).is( ':checked' ) ) {
                $( '.dlt' ).prop( "checked", false );
                $( '.mensaje_dlt' ).hide();
            }
		};
		control( $( '.servicio' ).val() );
                            
		//Change the fields based on the selected messages
		$( '.mensajes' ).on( 'change', function () {
			//control_mensajes( $( this ).val() );
			control_dlt( '.dlt' );
		} );
		var control_mensajes = function ( capa ) {
			if ( capa == '' ) {
				capa = $( '.mensajes option:selected' ).val();
			}

			var mensajes = new Array();
			<?php 
            foreach( $listado_de_mensajes as $indice => $valor ) {
                echo "mensajes[ '$indice' ] = '$valor';" . PHP_EOL; 
            }
			?>

			for ( var valor in mensajes ) {
				$( '.' + valor + ',.dlt_' + valor ).hide();
				for ( var valor_capa in capa ) {
					if ( valor == capa[ valor_capa ] || capa[ valor_capa ] == 'todos'  ) {
						$( '.' + valor ).show();
                        if ( $( '.dlt' ).is( ':checked' ) ) {
                            $( '.dlt_' + valor ).show();
                        }
					}
				}
			}
		};

		if ( typeof chosen !== 'undefined' && typeof chosen === "function" ) {
			jQuery( "select.chosen_select" ).chosen();
		}

        //Control the phone field of the submission form
		$( '.campo_envio' ).hide();
		$( '.envio' ).on( 'change', function () {
			control_envio( '.envio' );
		} );
		var control_envio = function ( capa ) {
			if ( $( capa ).is( ':checked' ) ) {
				$( '.campo_envio' ).show();
			} else {
				$( '.campo_envio' ).hide();
			}
		};
		control_envio( '.envio' );

		//Control the email field of the submission form
		$( '.campo_debug' ).hide();
		$( '.debug' ).on( 'change', function () {
			control_debug( '.debug' );
		} );
		var control_debug = function ( capa ) {
			if ( $( capa ).is( ':checked' ) ) {
				$( '.campo_debug' ).show();
			} else {
				$( '.campo_debug' ).hide();
			}
		};
		control_debug( '.debug' );

		<?php if ( ! empty( $listado_de_estados ) ) : //Check for the existence of custom states ?>
		//Change fields based on selected custom states
		$( '.estados_personalizados' ).on( 'change', function () {
			//control_personalizados( $( this ).val() );
			control_dlt( '.dlt' );
		} );
		var control_personalizados = function ( capa ) {
			var estados = new Array();
		<?php 
		foreach( $listado_de_estados as $valor ) {
			echo "estados[ '$valor' ] = '$valor';" . PHP_EOL; 
		}
		?>

			for ( var valor in estados ) {
				$( '.' + valor + ',.dlt_' + valor ).hide();
				for ( var valor_capa in capa ) {
					if ( valor == capa[ valor_capa ] ) {
						$( '.' + valor ).show();
                        if ( $( '.dlt' ).is( ':checked' ) ) {
                            $( '.dlt_' + valor ).show();
                        }
					}
				}
			}
		};
		<?php endif; ?>

        //Shows the DLT fields
		$( '.mensaje_dlt' ).hide();        
		$( '.dlt' ).on( 'change', function () {
			control_dlt( '.dlt' );
		} );
		var control_dlt = function ( capa ) {
			if ( $( capa ).is( ':checked' ) ) {
				$( '.mensaje_dlt' ).show();
			} else {
                $( '.mensaje_dlt' ).hide();
			}
            $( '.mensajes' ).each( function ( i, selected ) {
                control_mensajes( $( selected ).val() );
            } );
		<?php if ( ! empty( $listado_de_estados ) ) : //Check for the existence of custom states ?>
            $( '.estados_personalizados' ).each( function ( i, selected ) {
                control_personalizados( $( selected ).val() );
            } );
		<?php endif; ?>
		};
		control_dlt( '.dlt' );
    } );
</script>