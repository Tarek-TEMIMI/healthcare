<?php
/**
 * Plugin Name: KiviCare - Google Meet Telemed AddOn
 * Plugin URI: https://iqonic.design
 * Description: KiviCare - Google Meet is an impressive AddOn of Kivicare clinic and patient management plugin (EHR).
 * Version:1.0.6
 * Author: iqonic
 * Text Domain: kc-googlemeet
 * Domain Path: /languages
 * Author URI: http://iqonic.design/
 **/
use kcGoogleMeet\baseClasses\KCGMActivate;
use kcGoogleMeet\baseClasses\KCGMDeactivate;
use kcGoogleMeet\baseClasses\KCGMBase;
defined( 'ABSPATH' ) or die( 'Something went wrong' );

// Require once the Composer Autoload
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
    require_once dirname( __FILE__ ) . '/vendor/autoload.php';
} else {
    die( 'Something went wrong' );
}

if (!defined('KIVI_CARE_GOOGLE_DIR'))
{
    define('KIVI_CARE_GOOGLE_DIR', plugin_dir_path(__FILE__));
}

if (!defined('KIVI_CARE_GOOGLE_DIR_URI'))
{
    define('KIVI_CARE_GOOGLE_DIR_URI', plugin_dir_url(__FILE__));
}

if (!defined('KIVI_CARE_GOOGLE_BASE_NAME'))
{
    define('KIVI_CARE_GOOGLE_BASE_NAME', plugin_basename(__FILE__));
}

if (!defined('KIVI_CARE_GOOGLE_PREFIX'))
{
    define('KIVI_CARE_GOOGLE_PREFIX', "kiviCare_");
}

if (!defined('KIVI_CARE_GOOGLE_VERSION'))
{
    define('KIVI_CARE_GOOGLE_VERSION', "1.0.6");
}

if(!defined('KIVI_CARE_GOOGLE_REQUIRED_PLUGIN_VERSION')) {
    define('KIVI_CARE_GOOGLE_REQUIRED_PLUGIN_VERSION', '3.0.5');
}
/**
 * The code that runs during plugin activation
 */
register_activation_hook( __FILE__, [ KCGMActivate::class, 'activate'] );

/**
 * The code that runs during plugin deactivation
 */
register_deactivation_hook( __FILE__, [KCGMDeactivate::class, 'deactivate'] );

(new KCGMBase() );

