<div class="informacion">
  <div class="fila">
    <div class="columna">
    <p><a href="https://builderhall.com" target="_blank" alt="builder hall logo"><img src="https://builderhall.com/assets/uploads/media-uploader/logo-blue1676564523.png" /></a></p>  
    <p><?php _e( 'We offer a range of digital solutions to help businesses succeed in the digital space. We pride ourselves on utilizing the latest technology and creative design to develop customized solutions that meet our clients unique needs.', 'woosms-instant-order-updates' ); ?></p>
    </div>
  
    <div class="columna">
      <p>
        <?php _e( 'If you need any help just contact us:', 'woosms-instant-order-updates' ); ?>
      </p>
      <p><a href="<?php echo $woo_sms['support']; ?>" target="_blank" title="<?php _e( 'Contact us ', 'woosms-instant-order-updates' ); ?>Builder Hall"><span class="genericon genericon-mail"></span></a> <a href="tel:+8801715938284"><span class="genericon genericon-phone"></span></a> <a href="https://wa.me/message/QVCA54JUBFM7D1"><span class="genericon genericon-chat"></span></a></p>
    </div>
    </div>

  <div class="fila">
    <div class="columna">
      <p>
        <?php _e( 'Documentation:', 'woosms-instant-order-updates' ); ?>
      </p>
      <p><a href="<?php echo $woo_sms['doc']; ?>" title="<?php echo $woo_sms['plugin']; ?>"><span class="genericon genericon-book"></span></a></p>
    </div>
  </div>


  <div class="fila">
    <div class="columna">
      <p>
        <?php _e( 'Follow us:', 'woosms-instant-order-updates' ); ?>
      </p>
      <p><a href="https://builderhall.com" title="<?php _e( 'Follow us on ', 'woosms-instant-order-updates' ); ?>Website" target="_blank"><span class="genericon genericon-website"></span></a> <a href="https://www.facebook.com/BuilderHallPvtLTD" title="<?php _e( 'Follow us on ', 'woosms-instant-order-updates' ); ?>Facebook" target="_blank"><span class="genericon genericon-facebook-alt"></span></a> <a href="https://twitter.com/BuilderHallLTD" title="<?php _e( 'Follow us on ', 'woosms-instant-order-updates' ); ?>Twitter" target="_blank"><span class="genericon genericon-twitter"></span></a> <a href="https://www.linkedin.com/company/builderhallprivateltd" title="<?php _e( 'Follow us on ', 'woosms-instant-order-updates' ); ?>LinkedIn" target="_blank"><span class="genericon genericon-linkedin"></span></a> <a href="https://www.youtube.com/@builderhallltd" title="<?php _e( 'Follow us on ', 'woosms-instant-order-updates' ); ?>YouTube" target="_blank"><span class="genericon genericon-youtube"></span></a></p>
    </div>
    <div class="columna">
      <p>
        <?php _e( 'Credit:', 'woosms-instant-order-updates' ); ?>
      </p>
        <p>
          <?php _e( 'Special thanks to <a href="https://artprojectgroup.es/" target="_blank">Art Project Group</a> for his original work on the WC - woosms SMS Notifications plugin.<br> Which we have built upon and added a new <abbr title="Short Message Service" lang="en">SMS</abbr> gateway (<a href="https://smshall.com/">SMS Hall</a>) to better serve our users.'); ?>
        </p>
    </div>
  </div>
  
  <div class="fila final">
    <div class="columna">
      <p> <?php echo sprintf( __( 'Please Share <br> %s:', 'woosms-instant-order-updates' ), $woo_sms['plugin'] ); ?> </p>
      <button id="share-btn"><span class="genericon genericon-share"></span></button>
          <script>
          const shareBtn = document.getElementById('share-btn');
          shareBtn.addEventListener('click', () => {
            if (navigator.share) {
              navigator.share({
                title: 'WooSMS - Instant order updates',
                text: 'Add to WooCommerce the possibility to send SMS update to your customer each time when the order status has change. Notifies the owner, if desired, when a customer place any order. You can also send customer notes',
                url: 'https://smshall.com/woosms',
              })
              .then(() => console.log('Share successful'))
              .catch((error) => console.log('Error sharing', error));
            } else {
              console.log('Share not supported');
            }
          });
        </script>
    </div>
    <div class="columna final"></div>
  </div>
</div>
