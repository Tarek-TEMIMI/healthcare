<?php

/**
 * Kivicare\Utility\Redux_Framework\Options\SocialMedia class
 *
 * @package kivicare
 */

namespace Kivicare\Utility\Redux_Framework\Options;
use Redux;
use Kivicare\Utility\Redux_Framework\Component;

class Mailchimp extends Component {

	public function __construct() {
		$this->set_widget_option();
	}

	protected function set_widget_option() {

		Redux::set_section($this->opt_name, array(
            'title'      => esc_html__( 'MailChimp Subscribe', 'kivicare' ),
            'id'         => 'kivicare-subscribe',
            'icon'       => 'el el-envelope',
            'fields'     => array(
        
                array(
                    'id'        => 'kivi_subscribe',
                    'type'      => 'text',
                    'title'     => esc_html__( 'Subscribe Shortcode','kivicare'),
                    'subtitle'  => esc_html__( 'Put you Mailchimp for WP Shortcode here','kivicare' ),
                ),
        
            )) 
        );
		
	}
}
