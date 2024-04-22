<?php

/**
 * Kivicare\Utility\Notice\Component class
 *
 * @package kivicare
 */

namespace Kivicare\Utility\Notice;

use Kivicare\Utility\Component_Interface;
use Kivicare\Utility\Templating_Component_Interface;
use function add_action;

// use function Kivicare\Utility\WP_Rig;

/**
 * Class for managing notice UI.
 *
 * Exposes template tags:
 *
 * @link https://wordpress.org/plugins/amp/
 */
class Component implements Component_Interface, Templating_Component_Interface
{
	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string
	{
		return 'notice';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize()
	{
		
	}

	public function __construct()
	{

		// sale banner
		if ( date('d/m/Y') >= '31/03/2023' && date('d/m/Y') <= '02/04/2023'){
			add_action('admin_notices',  array($this, 'iqonic_sale_banner'), 0);
		}

		add_action('wp_ajax_iqonic_dismiss_notice', array($this, 'iqonic_dismiss_notice'), 10);
		add_action('admin_enqueue_scripts', array($this, 'iqonic_notice_enqueue_admin_script'));
	}
	
	public function template_tags(): array
	{
		return array();
	}

	public function iqonic_sale_banner()
	{
		wp_enqueue_style( 'iqonic-stylesheet', get_stylesheet_uri() );
		global $current_user;
		$user_id = $current_user->ID;
			if (!get_user_meta($user_id, 'iqonic_sale_banner_announcement',true)) {  ?>
				<div class="notice iqonic-notice is-dismissible" id="iqonic_sale_banner_announcement">
				<div class="iqonic-notice-message iqonic-sale">
					<a href="javascript:void(0)" target="_blank"><img src="<?php echo get_template_directory_uri() . '/assets/images/BANNER.jpg'; ?>" alt="<?php esc_attr('sale-banner', 'kivicare' ) ?>"></a>
				</div>
					<div class="iqonic-notice-cta">
						<button class="iqonic-notice-dismiss iqonic-dismiss-welcome notice-dismiss" data-msg="iqonic_sale_banner_announcement"><span class="screen-reader-text"><?php esc_html_e('Dismiss', 'kivicare'); ?></span></button>
					</div>
				</div>
			<?php  } 
			wp_add_inline_style( 'iqonic-stylesheet', '.iqonic-notice { background: #fff; border:none; padding:0px 0px; box-shadow:none} .iqonic-notice img { max-width:100%; width:100% } .wp-core-ui .notice.is-dismissible#iqonic_sale_banner_announcement { padding:0; } .iqonic-notice a { display:grid; }' );
	}

	public function iqonic_dismiss_notice()
	{
		global $current_user;
		$user_id = $current_user->ID;
		if (!empty($_POST['action']) && $_POST['action'] == 'iqonic_dismiss_notice') {
			if ($_POST['data'] === 'iqonic_sale_banner_announcement') {
				add_user_meta($user_id, 'iqonic_sale_banner_announcement', 'true', true);
				wp_send_json_success();
			}
		}
	}

	public function iqonic_notice_enqueue_admin_script()
	{
		wp_enqueue_script('admin-custom', get_template_directory_uri() . '/assets/js/admin-custom.min.js', array('jquery'), 1.0 , true);
	}
}
