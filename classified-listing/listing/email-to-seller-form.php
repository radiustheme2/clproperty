<?php

/**
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


?>
<form id="rtcl-contact-form" class="form-vertical">

    <div class="form-group">
        <input type="text" name="name" class="form-control" id="rtcl-contact-name"
               placeholder="<?php esc_attr_e( "Name *", "clproperty" ) ?>"
               required/>
    </div>
    <div class="form-group">
        <input type="email" name="email" class="form-control" id="rtcl-contact-email"
               placeholder="<?php esc_attr_e( "Email*", "clproperty" ) ?>"
               required/>
    </div>
    <div class="form-group">
        <input type="text" name="phone" class="form-control" id="rtcl-contact-phone"
               placeholder="<?php esc_attr_e( "Phone*", "clproperty" ) ?>"
               required/>
    </div>
    <div class="form-group">
        <textarea class="form-control" name="message" id="rtcl-contact-message" rows="5" cols="20"
                  placeholder="<?php esc_attr_e( "Message*", "clproperty" ) ?>"
                  required></textarea>
    </div>

    <div id="rtcl-contact-g-recaptcha"></div>
    <p id="rtcl-contact-message-display"></p>
    <div class="form-group">
        <button type="submit"
                class="submit-btn"><?php esc_html_e( "Send Message", "clproperty" ) ?>
        </button>
    </div>
</form>
